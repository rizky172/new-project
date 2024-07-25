<?php
namespace App\Libs\Repository;

use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\WevelopeLibs\Helper\DateFormat;
use App\Libs\Repository\AbstractRepository;

use Illuminate\Support\Facades\Log;

use App\Libs\Repository\Category;

use App\Models\Category as Model;
use App\Models\Meta;

class PermissionGroup extends Category
{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function addDetailPermission($id)
    {
        $this->details[] = $id;
    }

    public function beforeDelete($permanent = null)
    {
        if (!empty($permanent)) {
            $fields = [
                'meta_user_permission_group_id' => $this->model->id,
            ];

            $rules = [
                'meta_user_permission_group_id' => ['required', Rule::unique('meta', 'value')->where(function ($query) {
                                                                                                    $query->where('table_name', 'user')
                                                                                                    ->where('key', 'permission_group_id');
                                                                                                })],
            ];

            $messages = [
                'meta_user_permission_group_id.unique' => 'Hak Akses sedang digunakan oleh admin.',
            ];

            $validator = Validator::make($fields, $rules, $messages);
            self::validOrThrow($validator);

            $this->clearDetail();
        }
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('access_delete');

        $this->beforeDelete('permanent');

        Model::where('category_id', $this->model->id)
                ->delete();

        $this->model->delete();
    }

    public function validate()
    {
        // Validation
        $fields = [
            'name' => $this->model->name,
        ];

        $rules = [
            'name' => [
                'required',
                'max:45',
                Rule::unique('category')->where(function ($query) {
                    return $query->where('name', $this->model->name)
                    ->where('id', '!=', $this->model->id)
                    ->where('group_by', $this->model->group_by);
                })
            ]
        ];

        $validator = Validator::make($fields, $rules);
        self::validOrThrow($validator);
    }

    public function save()
    {
        // $this->validate();

        $this->model->save();

        $this->saveDetails();
    }

    public function saveDetails()
    {
        // Get all permission
        $list = Meta::where([
            'fk_id' => $this->model->id,
            'table_name' => 'category',
            'key' => 'permission_id'
        ])->get();

        // Overwrite with new permission id
        foreach ($this->details as $x) {
            // Create blank meta
            $permission = new Meta;

            // If not empty, get one of them(overwrite)
            if ($list->isNotEmpty())
                $permission = $list->shift();

            // Fill with correct information
            $permission->fk_id = $this->model->id;
            $permission->table_name = 'category';
            $permission->key = 'permission_id';
            $permission->value = $x;
            $permission->save();
        }

        // We don't need any list any more
        // If list is still not empty, then we could delete it,
        // because all we need has been saved in foreach block
        if($list->isNotEmpty()) {
            $permission = Meta::whereIn('id', $list->pluck('id'))->delete();
        }
    }

    public function toArray()
    {
        $data = $this->model->toArray();

        $details = [];
        $permissions = self::getPermission($this->model->id);

        foreach ($permissions as $key => $value) {
            
            $dataPermission = Model::where('id', $value['value'])->first();

            $singlePermisson = [
                'id' => $value['value'],
                'name' => $value['name'],
                'notes' => $value['notes'],
                'isChecked' => 1,
                'group_by' => $value['group_by']
            ];

            if($dataPermission) {
                $singlePermisson['name'] = $dataPermission['name'];
                $singlePermisson['notes'] = $dataPermission['notes'];
                $singlePermisson['group_by'] = $dataPermission['group_by'];
            }

            $details[] = $singlePermisson;
        }

        $data['detail'] = $details;

        return $data;
    }

    public function clearDetail() 
    {
        Meta::where('fk_id', $this->model->id)
            ->where('table_name', 'category')
            ->where('key', 'permission_id')->delete();
    }
   
    public static function getPermission($id)
    {
        $list = Meta::where('table_name', 'category')->where('fk_id', $id)->get();

        return $list;
    }

}
