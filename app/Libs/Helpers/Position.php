<?php
namespace App\Libs\Helpers;

use Wevelope\Wevelope\Misc\Position as ParentObject;

class Position
{
    public static function makePositionSequential($rows)
    {
        $pos = new ParentObject($rows);

        return $pos->getSorted();
    }

    public static function makePositionSequentialNonObject($rows)
    {
        $pos = new ParentObject($rows);

        return $pos->getSorted();
    }
}