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
class StringToLowerStrategy implements IParseStrategy
{
    public function parse($field)
    {
        return strtolower($field);
    }
}
