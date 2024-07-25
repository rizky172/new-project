<?php

namespace App\Libs\Helpers;

class MetaHelper
{
    public static function toArray($list)
    {
        if (empty($list))
            return [];
        else {
            $temp = [];
            foreach($list as $x) {
                $temp[$x['key']] = $x['value'];
            }

            return $temp;
        }
    }
}
