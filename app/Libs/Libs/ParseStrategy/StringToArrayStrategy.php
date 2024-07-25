<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs\ParseStrategy;

/**
 * Parse from string into string Y-m-d
 */
class StringToArrayStrategy implements IParseStrategy
{
    private $delimiter;

    public function __construct($delimiter = ',')
    {
        $this->delimiter = $delimiter;
    }

    public function parse($field)
    {
        $tempList = [];
        $trimmedTempList = [];
        if(!empty($field)) {
            $tempList = explode($this->delimiter, $field);

            foreach ($tempList as $list) {
                $trimmedTempList[] = trim($list);
            }
        }

        return $trimmedTempList;
    }
}
