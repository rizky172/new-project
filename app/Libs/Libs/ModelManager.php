<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs;

// Manage and use existing model if any
class ModelManager extends AbstractObjectManager
{
    public function deleteUnusedObject()
    {
        $list = $this->getList();
        foreach ($list as $x)
            $x->delete();
    }
}
