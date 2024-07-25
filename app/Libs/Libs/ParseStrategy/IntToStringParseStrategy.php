<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs\ParseStrategy;

/**
 * Parse from date object excel into string Y-m-d
 */
class IntToStringParseStrategy implements IParseStrategy
{
    public function parse($field)
    {
        $datetime = $field;
        // Parse datetime format from excel to php date time object
        // Basically datetime object in excel is just number or integer
        if(is_numeric($field)) {
            $datetime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($field);
            $datetime = $datetime->format('Y-m-d');
        }

        return $datetime;
    }
}
