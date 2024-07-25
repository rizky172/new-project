<?php

namespace App\Libs\Repository\Logger;

use Wevelope\Wevelope\Logger\WevelopeLogger as ParentObject;
use Wevelope\Wevelope\Parser;

use App\Models\User;
use App\Models\Category;

class AbstractLogger extends ParentObject
{
    const USER_MESSAGE_PATTERN = '<span>Dimodifikasi: <strong>:name:</strong>';

    private $logMessagePattern = self::USER_MESSAGE_PATTERN . '. Dari: <strong>:original_data:</strong>, ' . 'Ke: <strong>:modified_data:</strong></span>';
    private $onAddMessagePattern = ':index:. Tambah, <strong>:data:</strong>';
    private $onModifiedMessagePattern = ':index:. Modifikasi <strong>:name:</strong>, dari: <strong>:original_data:</strong>, Ke: <strong>:modified_data:</strong>';
    private $onDeleteMessagePattern = ':index:. Hapus, <strong>:data:</strong>';

    private $key = [];

    public function __construct(User $user)
    {
        // Set user name that would print in log
        $username = null;
        if(!empty($user))
            $username = $user->name;

        parent::__construct($username);

        $this->setLogMessagePattern($this->logMessagePattern);
        $this->setOnAddMessage($this->onAddMessagePattern);
        $this->setOnModifiedMessage($this->onModifiedMessagePattern);
        $this->setOnDeleteMessage($this->onDeleteMessagePattern);

        // Setup ID parser
        $this->setup();
    }

    public function setup()
    {
        // ====== MAPPING FROM DATETIME TO STRING =======
        $parser = new Parser\DateTimeToStringParser('Y-m-d');

        // Parse all index 'created' into string 'Y-m-d' IF it has \DateTime object
        $this->addParser('created', $parser);
        $this->addParser('registered', $parser);
        $this->addParser('date_from', $parser);
        $this->addParser('date_to', $parser);
        // ====== MAPPING FROM DATETIME TO STRING(END) =======

        // ====== MAPPING FROM ID TO STRING =======
        // Map category_id to string
        $categories = Category::all();
        $mapper = $categories->map(function($x) {
            return ['id' => $x['id'], 'value' => $x['label']];
        });
        $parser = new Parser\IdToStringParser($mapper->toArray());

        // $this->addParser('status_id', $parser);

        // ====== MAPPING FROM ID TO STRING(END) =======

        // ====== REMOVE ARRAY BY INDEX =======
        $removedIndex = $this->getRemovedIndex();
        // ====== REMOVE ARRAY BY INDEX(END) =======

        // ====== RENAMING ARRAY INDEX =======
        $this->setRenameIndexArray([
            'ref_no' => 'No. Ref',
            'name' => 'Nama',
        ]);
        // ====== RENAMING ARRAY INDEX(END) =======
    }

    /*
     * Backward compatible with LogMakerV2
     */
    public function setHeader($original, $modified)
    {
        $this->setData(self::ORIGIN, $original);
        $this->setData(self::MODIFIED, $modified);
    }

    /*
     * Backward compatible with LogMakerV2
     */
    public function addDetailAsArray($key = null, $original, $modified)
    {
        if (!in_array($key, $this->key))
            $this->key[] = $key;

        // var_dump($original, $modified);
        if(!empty($original))
            foreach($original as $x)
                $this->addDetail(self::ORIGIN, $x, $key);

        if(!empty($modified))
            foreach($modified as $x)
                $this->addDetail(self::MODIFIED, $x, $key);
    }

    public function clearDetail() {}

    public function generateDetail()
    {
        $detail = [];
        //group
        foreach($this->key as $g) {
            $messages = [];
            $counter = 1;

            $messages = $this->generateDetailMessages($g);

            if (!empty($messages))
                array_unshift($messages, sprintf("%s:", ucfirst($g)));

            $detail = array_merge($detail, $messages);
        }

        return $detail;
    }

    private function generateUser()
    {
        return str_replace(':name:', $this->getName(), self::USER_MESSAGE_PATTERN);
    }

    /*
     * Backward compatible with LogMakerV2
     */
    public function getOnUpdatedLog()
    {
        $lines = [];

        $header = $this->generateLogMessage();
        if (!empty($header))
            $lines[] = $header;

        $detail = $this->generateDetail();
        if (!empty($detail))
            $lines[] = implode('<br>', $detail);

        if (!empty($lines) && empty($header))
            array_unshift($lines, $this->generateUser());

        $temp = implode('<br>', $lines);

        return $temp;
    }
}