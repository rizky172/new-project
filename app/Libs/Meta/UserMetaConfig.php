<?php

namespace App\Libs\Meta;

class UserMetaConfig extends MetaConfig
{
    const PERMISSION_GROUP_ID = 'permission_group_id';

    public function __construct($list = [])
    {
        if(empty($list)) {
            $list = $this->generateList();
        }

        parent::__construct($list);
    }

    public static function getIgnoreKeys()
    {
        return [
            'media'
        ];
    }

    private function generateList()
    {
        return [
            self::PERMISSION_GROUP_ID,
        ];
    }
}
