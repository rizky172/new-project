<?php
namespace App\Libs\Helpers;

use Wevelope\Wevelope\Misc\FullPath as ParentObject;

class FullPath extends ParentObject
{
    public function __construct($root = null, $list = [], $filename = null, $ds = DIRECTORY_SEPARATOR)
    {
        parent::__construct($root ?? public_path(), $list, $filename, $ds);
    }

    public static function tmp($filename = null)
    {
        $list = ['tmp'];
        $fullpath = new self(null, $list, $filename);

        return $fullpath->getPath();
    }

    public static function config($filename = null)
    {
        $list = ['uploads', 'config'];
        $fullpath = new self(null, $list, $filename);

        return $fullpath->getPath();
    }
}