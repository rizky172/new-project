<?php

namespace App\Libs\Meta;

class CategoryMetaConfig extends MetaConfig
{
    const COA_ID = 'coa_id';
    const COA_ID_DEBIT = 'coa_id_debit';
    const COA_ID_CREDIT = 'coa_id_credit';
    const ITEM_ID = 'item_id';
    const LEVEL = 'level';

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
            self::COA_ID,
            self::COA_ID_DEBIT,
            self::COA_ID_CREDIT,
            self::ITEM_ID,
            self::LEVEL
        ];
    }
}
