<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs;

/**
 * Description of ReorderTreeList
 *
 * @author blackhat
 */
class ReorderTreeList
{
    private $list = [];
    private $newList = [];

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function reorderList($id = null)
    {
        $list = collect($this->list);
        $row = $list->first();

        if($id !== null){
            $row = $list->where('id', $id)->first();
        }

        $parent = $list->where('id', $row['parent_id'])->first();

        if(!empty($parent)){
            self::reorderList($parent['id']);
        } else {
            $this->newList[] = $row;
            $deleteRow = array_splice($this->list, array_search($row['id'], $list->pluck('id')->all()), 1);
        }
    }

    public function getOrdered()
    {
        while(!empty($this->list)){
            self::reorderList();
        }
        return $this->newList;
    }
}
