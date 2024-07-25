<?php
namespace App\Libs\Repository;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Libs\Repository\AbstractRepository;
use App\Libs\Meta\MetaManager;
use App\Libs\Meta\CategoryMetaConfig;

use App\Models\Category as Model;
use App\Models\Meta;

class Category extends AbstractRepository
{
    protected $details = [];

    private $metaConfig;

    private $coaId;
    private $itemId;
    private $level;

    public function __construct(Model $model)
    {
        parent::__construct($model);

        $this->metaConfig = new CategoryMetaConfig();
    }

    public function addDetail($id, $label, $notes, $name, $groupBy)
    {
        $this->details[] = [
            'id' => $id,
            'label' => $label,
            'notes' => $notes,
            'name' => $name,
            'group_by' => $groupBy
        ];
    }

    public function deleteDetail($id)
    {
        Model::where('id', $id)->delete();
    }

    public function setCoaId($coaId)
    {
        $this->coaId = $coaId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    /*
     * Would find child and delete it first
     *
     * @param int Parent ID
     */
    public static function deleteChildOf($id)
    {
        $listId = [];
        $list = Model::where('category_id', $id)->get();

        if($list->isNotEmpty()) {
            foreach($list as $x) {
                // Delete all child
                self::deleteChildOf($x->id);
                // Then delete the id
                Model::where('id', $x->id)->forceDelete();
            }
        }
    }

    public function beforeDelete($permanent = null)
    {
        if (!empty($permanent)) {
            $fields = [];

            $rules = [];

            $messages = [];

            $validator = Validator::make($fields, $rules, $messages);
            self::validOrThrow($validator);
        }
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl(sprintf('category_%s_delete', $this->model->group_by));

        parent::delete('permanent');

        // Delete all child of $model
        self::deleteChildOf($this->model->id);

        $itemId = Meta::where([
            'table_name' => 'category',
            'key' => 'item_id',
            'fk_id' => $this->model->id
        ])->delete();

        $level = Meta::where([
            'table_name' => 'category',
            'key' => 'level',
            'fk_id' => $this->model->id
        ])->delete();

        // $this->model->forceDelete();
    }

    public function validate()
    {
        $this->validateCategory();

        if (!empty($this->details))
            $this->validateDetail();
    }

    public function validateDetail()
    {
        $fields = [
            'details' => $this->details
        ];

        $rules = [
            'details' => 'required',
            'details.*.label' => 'required',
            'details.*.notes' => 'nullable'
        ];
    }

    public function validateCategory()
    {
        $accepted = [];

        if (!in_array($this->model->group_by, $accepted))
            throw new \Exception("Kategori tidak ditemukan.");

        $this->model->name = self::generateName($this->model->label);

        // Validation
        $fields = [
            'label' => $this->model->label,
            'name' => $this->model->name,
        ];

        $rules = [
            'label' => [
                Rule::unique('category')->where(function ($query) {
                    return $query->where('name', $this->model->name)
                    ->where('id', '!=', $this->model->id)
                    ->where('group_by', $this->model->group_by);
                }),
            ]
        ];


        $validator = Validator::make($fields, $rules);
        self::validOrThrow($validator);
    }

    public function save()
    {
        $this->filterByAccessControl(sprintf('category_%s_create', $this->model->group_by));

        $this->validate();

        $this->model->save();

        if (!empty($this->details)) {
            $this->saveDetails();
        }

        // if($this->model->group_by == 'customer_type')
        //     $this->saveMeta();
    }

    public function saveDetails()
    {
        foreach ($this->details as $key => $value) {
            $detail = Model::findOrNew($value['id']);
            $detail->category_id = $this->model->id;
            $detail->label = $value['label'];
            $detail->notes = $value['notes'];
            $detail->name = self::generateName($value['label']);
            $detail->group_by = $value['group_by'];
            $detail->save();
        }
    }

    private function saveMeta()
    {
        $list = $this->model->meta;
        $fkId = $this->model->id;
        $tableName = $this->model->getTable();

        $metaManager = new MetaManager($list, $fkId, $tableName);
        $metaManager->addDetail(CategoryMetaConfig::COA_ID, $this->coaId);
        $metaManager->addDetail(CategoryMetaConfig::ITEM_ID, $this->itemId);
        $metaManager->addDetail(CategoryMetaConfig::LEVEL, $this->level);
        $metaManager->saveAllAndDelete();
    }

    public function toArray()
    {
        $data = $this->model->toArray();

        $details = [];

        foreach ($this->model->details as $key => $value) {
            $details[] = [
                'id' => $value['id'],
                'category_id' => $value['category_id'],
                'label' => $value['label'],
                'name' => $value['name'],
                'notes' => $value['notes'],
                'group_by' => $value['group_by']
            ];
        }

        $metas = $this->model->meta;
        $metaList = $this->metaConfig->transform($metas->toArray());
        $data = array_merge($data, $metaList);

        $data['detail'] = $details;

        return $data;
    }

    public static function generateName($name)
    {
        return str_replace([' ', '(', ')', '/'], '-', strtolower($name));
    }

    /* Little bit different with other category, because item category
     * using tree data structure.
     *
     * @return Array Return all NODE only
     */
    public static function getItemCategory()
    {
        $list = Model::where('group_by', 'item')->get();

        // All nodes
        $nodes = [];
        // Get all root
        $root = $list->where('category_id', null);
        foreach($root as $x) {
            $nodes = array_merge($nodes, self::findNodeCategory($list, $x, $x['label']));
        }

        // var_dump($nodes, 'test'); die;

        return $nodes;
    }

    private static function findNodeCategory($list, $parentNode, $lastLabel = null)
    {
        // All nodes for this parent would be store here
        $nodes = [];

        // Get all child parent node
        $childs = $list->where('category_id', $parentNode->id);
        if(!$childs->isEmpty()) {
            // Get into child
            foreach($childs as $x) {
                $newLabel = $lastLabel . ' > ' . $x['label'];
                // Store when no more nodes
                // This mean all this child is nodes.
                $nodes = array_merge($nodes, self::findNodeCategory($list, $x, $newLabel));
            }
        } else {
            // Mean this node is last
            $temp = $parentNode->toArray();
            $temp['label'] = $lastLabel;

            $nodes[] = $temp;
        }

        return $nodes;
    }
}
