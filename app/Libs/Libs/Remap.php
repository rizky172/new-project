<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Libs\Libs;

use Illuminate\Support\Facades\Validator;

use App\Libs\Repository\AbstractRepository;
use App\Libs\Libs\ParseStrategy\IParseStrategy;

/**
 * Description of JamsostekCalculator
 *
 * @author blackhat
 */
class Remap
{
    private $newHeaders;
    private $list;
    private $parseStrategies;

    /*
     * @params array New header name
     * @params array List data
     */
    public function __construct($newHeaders, $list)
    {
        $this->newHeaders = $newHeaders;
        $this->list = $list;
        $this->parseStrategies = collect([]);
    }

    public function addParseStrategy($key, IParseStrategy $parseStrategy)
    {
        $this->parseStrategies->push(['key' => $key, 'parse_strategy' => $parseStrategy]);
    }

    public function validateBasic()
    {
        $fields = [
            'new_headers' => $this->newHeaders,
            'list' => $this->list
        ];

        $rules = [
            'new_headers' => 'required|array',
            'list' => 'required|array'
        ];

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);
    }

    public function validateCountColumnsMustSame()
    {
        $first = $this->list[0];

        $fields = [
            'new_headers' => count($this->newHeaders),
        ];

        $rules = [
            'new_headers' => 'in:' . count($first),
        ];

        $messages = [
            'new_headers.in' => 'Jumlah kolom tidak valid',
        ];

        $validator = Validator::make($fields, $rules, $messages);
        AbstractRepository::validOrThrow($validator);
    }

    public function validate()
    {
        $this->validateBasic();
        $this->validateCountColumnsMustSame();
    }

    // Get new list
    public function getNewList()
    {
        $this->validate();

        // Must be in order
        $columns = $this->newHeaders;
        $list = $this->list;

        $newList = [];
        foreach($list as $x) {
            // Mapping to columns
            $temp = [];
            $c = 0;
            foreach($x as $y) {
                // Trim all space
                $y = trim($y); // Normal trim
                $y = preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','', $y); // Trim for all special space

                $parseStrategy = $this->parseStrategies
                    ->firstWhere('key', $columns[$c]);
                if(!empty($parseStrategy))
                    $y = $parseStrategy['parse_strategy']->parse($y);

                $temp[$columns[$c]] = $y;
                $c++;
            }

            $newList[] = $temp;
        }

        return $newList;
    }
}
