<?php

namespace App\Libs\Repository\Logger;

use Wevelope\Wevelope\Parser\IdToStringParser;

use App\Category;

class ExampleLogger extends AbstractLogger
{
    public function setup()
    {
        $list = [
            [ 'person_id' => 1, 'value' => 'Satu' ]
        ];
        $parser = new IdToStringParser($list);
        $this->addParser('id', $parser);

        // Rename array
        $this->updateRenameIndexArray([
            'person_id' => 'Person ID'
        ]);
    }

    public function getOnUpdatedLog()
    {
        $lines = [];

        $lines[] = $this->generateLogMessage();

        $detail = $this->generateDetailMessages();
        if(!empty($detail)) {
            $lines[] = 'Detail:';
            $lines = array_merge($lines, $detail);
        }

        return implode('<br>', $lines);
    }
}
