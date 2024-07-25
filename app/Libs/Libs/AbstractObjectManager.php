<?php

namespace App\Libs\Libs;

use Illuminate\Support\Collection;

/**
 * Managing list of object. If empty it would create new one
 */
abstract class AbstractObjectManager
{
    private $list;
    private $className;

    /**
     *
     * @param Collection $list
     * @param string $className. Ex: 'App\JournalEntry', or JournalEntry::class
     */
    public function __construct(Collection $list, $className = null)
    {
        $this->list = $list;
        $this->className = $className;
    }

    // ==== GETTER & SETTER ====
    public function getList()
    {
        return $this->list;
    }

    public function setList($list)
    {
        $this->list = $list;
    }
    // ==== GETTER & SETTER ====

    /**
     * Get object if exist in $list, if not it would create new one using getNewObject() method
     * @return Object
     */
    public function getObject()
    {
        $model = $this->list->shift();

        if (empty($model))
            $model = $this->getNewObject();

        return $model;
    }

    /**
     * Create new object
     *
     * @return Object
     */
    public function getNewObject()
    {
        $className = $this->className;

        return new $className;
    }

    abstract public function deleteUnusedObject();
}
