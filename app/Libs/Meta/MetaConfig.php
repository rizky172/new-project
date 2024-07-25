<?php

namespace App\Libs\Meta;

use Illuminate\Support\Collection;

class MetaConfig
{
    private $list;

    public function __construct($list = [])
    {
        $this->list = $list;
    }

    public function getList()
    {
        return $this->list;
    }

    public function setList($list)
    {
        $this->list = $list;
    }

    public function getMetaList()
    {
        $list = [];
        foreach($this->list as $x)
            $list[] = [
                'key' => $x,
                'value' => null
            ];

        return $list;
    }

    public function transform($metas = [])
    {
        $list = [];
        foreach ($this->list as $x)
            $list[$x] = null;

        if (!empty($metas)) {
            $tempList = [];
            foreach ($metas as $x)
                $tempList[$x['key']] = $x['value'];

            $list = array_merge($list, $tempList);
        }

        return $list;
    }
}
