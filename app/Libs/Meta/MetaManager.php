<?php

namespace App\Libs\Meta;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

use App\Models\Meta;

class MetaManager
{
    private $detail = [];
    private $list;

    private $fkId;
    private $tableName;

    public function __construct(
        Collection $list, $fkId, $tableName)
    {
        $this->list = $list;
        $this->fkId = $fkId;
        $this->tableName = $tableName;
    }

    // ==== GETTER & SETTER ====
    public function getFkId()
    {
        return $this->fkId;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setFkId($fkId)
    {
        $this->fkId = $fkId;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getList()
    {
        return $this->list;
    }

    public function setList($list)
    {
        $this->list = $list;
    }
    // ==== GETTER & SETTER ====

    public function addDetail($key, $value, $createdAt = null)
    {
        $this->detail[] = [
            'key' => $key,
            'value' => $value,
            'created_at' => $createdAt
        ];
    }

    public function saveAll()
    {
        foreach($this->detail as $x) {
            if ($x['value'] !== null) {
                $model = $this->getModel();

                $model->fk_id = $this->fkId;
                $model->table_name = $this->tableName;
                $model->key = $x['key'];
                $model->value = $x['value'];

                if (!empty($x['created_at']))
                    $model->created_at = $x['created_at'];

                $model->save();
            }
        }
    }

    public function saveAllAndDelete()
    {
        $this->saveAll();
        $this->deleteUnusedMeta();
    }

    public function getModel()
    {
        $model = $this->list->shift();

        if(empty($model))
            $model = $this->getNewModel();

        return $model;
    }

    public function getNewModel()
    {
        return new Meta;
    }

    public function deleteUnusedMeta()
    {
        $listId = $this->list->pluck('id');
        Meta::whereIn('id', $listId)->delete();
    }
}
