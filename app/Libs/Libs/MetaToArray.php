<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs;

class MetaToArray implements Illuminate\Contracts\Support\Arrayable
{
    private $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function toArray()
    {
        $temp = [];
        foreach ($this->list as $key => $value) {
            $temp[$key] = $value;
        }

        return $temp;
    }
}
