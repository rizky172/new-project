<?php

namespace App\Libs\Libs;

use Illuminate\Support\Collection;

/**
 * Help to manipulate tree structure
 */
class TreeManipulator
{
    private $list = [];
    private $newList = [];

    private $keyIndex;
    private $parentKeyIndex;
    private $position = 1;

    /*
     * @param array $list
     * @param string Primary key index. Idealy unique
     * @param string Parent key index. Key to point to primary key
     */
    public function __construct($list, $keyIndex = 'id', $parentKeyIndex = 'parent_id')
    {
        $this->list = $list;
        $this->newList = $list;
        $this->keyIndex = $keyIndex;
        $this->parentKeyIndex = $parentKeyIndex;
    }

    public function getNewList()
    {
        return $this->newList;
    }

    /*
     * Generate level index for $this->newList
     *
     * @parent int Array index
     * @parant int Level default
     */
    private function generateLevel($index, $level = 0)
    {
        // Get correct row
        $row = $this->newList[$index];

        foreach($this->newList as $key => $value) {
            if($value[$this->parentKeyIndex] == $row[$this->keyIndex]) {
                $this->generateLevel($key, $level + 1);
            }
        }

        $this->newList[$index]['level'] = $level;
    }

    /*
     * Generate total all child of $INDEX
     *
     * @param int Array index
     */
    private function getTotal($index)
    {
        // Get correct row
        $row = $this->newList[$index];
        $cNewList = collect($this->newList);

        $sum = 0;
        foreach($this->newList as $key => $value) {
            // Find child
            if($value[$this->parentKeyIndex] == $row[$this->keyIndex]) {
                $checkChild = $cNewList->where($this->parentKeyIndex, $value[$this->keyIndex])->all(); 
                $total = 0;
                if (!empty($checkChild)) {
                    $total += $this->getTotal($key);
                } else {
                    $total += $value['amount'];
                }
                
                $sum += $total;
                $this->newList[$key]['total'] = $total;
            }
        }

        return $sum;
    }

    // Generate list that contain level and total
    public function generate()
    {
        $cNewList = collect($this->newList);
        // Do for root first
        foreach($this->newList as $key => $value) {
            // Find all parent key index that has NULL value
            if($value[$this->parentKeyIndex] === null) {
                // Generate level
                $this->generateLevel($key, 0);
                
                $checkChild = $cNewList->where($this->parentKeyIndex, $value[$this->keyIndex])->all();
                $this->newList[$key]['total'] = !empty($checkChild) ? $this->getTotal($key) : $value['amount'];
            }
        }
    }

    //generate position for sorting
    public function generatePosition()
    {
        // Do for root first
        foreach($this->newList as $key => $value) {
            // Find all parent key index that has NULL value
            if($value[$this->parentKeyIndex] === null) {
                // Generate position
                $this->newList[$key]['position'] = $this->position;
                $this->position++;

                $this->getPosition($key);
            }
        }

        // var_dump($this->getNewList());
        // die;
    }

    private function getPosition($index)
    {
        // Get correct row
        $row = $this->newList[$index];

        foreach($this->newList as $key => $value) {
            if($value[$this->parentKeyIndex] == $row[$this->keyIndex]) {
                $this->newList[$index]['position'] = $this->position;
                $this->position++;

                $this->getPosition($key);
            }
        }

        $this->newList[$index]['position'] = $this->position;
        $this->position++;
    }
}
