<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Database\Eloquent\Model as ParentObject;
use DB;

abstract class Model extends ParentObject
{
    const RULE_MAPPING = [
        [
            'rule' => 'max',
            'sql_data_type' => 'varchar',
            'with_length' => true
        ],
        [
            'rule' => 'integer',
            'sql_data_type' => 'int',
            'with_length' => false
        ]
    ];

    public function getRules()
    {
        $dbName = env('DB_DATABASE');
        $tableName = $this->table;

        if(empty($dbName) || empty($tableName)) {
            throw new \Exception('Cannot create rules. Databse Name or table name is EMPTY!');
        }

        // column_key = UNI mean unique
        $sql = <<<EOT
SELECT  column_name as column_name,
    data_type as data_type,
    character_maximum_length as character_maximum_length,
    is_nullable as is_nullable,
    column_key as column_key
FROM information_schema.columns
WHERE table_name = '%s' AND TABLE_SCHEMA = '%s'
EOT;
        // Using MYSQL command to get length of type
        $list = DB::select(sprintf($sql, $tableName, $dbName));

        /*
         * First Validation
         * NULL or NOT NULL
         *
         * Second Validation
         * varchar => size:length
         * int => integer
         *
         * Third validation
         * unique or not
         */

        $rules = [];
        $rulesMap = collect(self::RULE_MAPPING);
        foreach($list as $x) {
            $tempRules = [];
            // Ignore ID column. No need to validate because Auto
            if($x->column_name != 'id') {
                // First validation rule
                // NULL or NOT NULL
                if($x->is_nullable == 'NO')
                    $tempRules[] = 'required';
                else
                    $tempRules[] = 'nullable';

                // Second validation rule
                // Mapping based on column data type and length
                $found = $rulesMap->firstWhere('sql_data_type', $x->data_type);
                if(!empty($found)) {
                    // Create rule:length(if any)
                    $tempRules[] = $found['rule'] .
                            ($found['with_length'] ? (':' . $x->character_maximum_length) : null);
                }

                // Third validation rule
                // Unique column
                if($x->column_key == 'UNI') {
                    // Create unique:table_name,column,id
                    $tempRules[] = sprintf('unique:%s,%s,%s', $tableName, $x->column_name, $this->id);
                }
            }

            // Make on rule for a column
            if(!empty($tempRules))
                $rules[$x->column_name] = $tempRules;
        }

        return $rules;
    }

    public function save(array $options = [])
    {
        $rules = $this->getRules();
        if(!empty($rules)) {
            $validator = Validator::make($this->toArray(), $this->getRules());

            // Equivalent with self::validOrThrow($validator);
            // Validate the input and return correct response
            if ($validator->fails()) {
                throw new ValidationException($validator);
                // throw new \Exception($validator->errors()->first());
            }
        }

        if(isset($this->ref_no) && !empty($this->ref_no)) {
            // Auto strtoupper for ref_no
            $this->ref_no = strtoupper($this->ref_no);

            // Convert all space with _
            $this->ref_no = str_replace(' ', '_', $this->ref_no);
        }

        parent::save();
    }

    public function meta()
    {
        // var_dump('meta model', $this->table); die;
        return $this->hasMany(Meta::class, 'fk_id')
                ->where('table_name', $this->table);
    }

    public function media()
    {
        return $this->meta()
                ->where('key', 'media');
    }

    public function metaIgnoreKey($key = null)
    {
        if(!empty($key))
            return $this->metaIgnoreKeys([$key]);
        else
            return $this->metaIgnoreKeys();
    }

    public function metaIgnoreKeys($keys = [])
    {
        return $this->meta()->whereNotIn('key', $keys);
    }
}
