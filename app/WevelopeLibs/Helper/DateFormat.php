<?php
namespace App\WevelopeLibs\Helper;

use Wevelope\Wevelope\Helper\DateFormatHelper as ParentObject;

class DateFormat extends ParentObject
{
    // @return 03.11.2022 11:41
    public static function default(\DateTime $datetime)
    {
        $return = null;

        if(!empty($datetime))
            $return = $datetime->format('d.m.Y H:i');

        return $return;
    }
}

