<?php
namespace App\Libs\Repository\Finder;

use Illuminate\Pagination\LengthAwarePaginator;
use DB;

use App\Models\LogM;

class LogFinder extends AbstractFinder
{
    private $limit;

    private $tableName;
    private $joinTableName;
    private $fkId;

    public function __construct()
    {
        parent::__construct();
        $this->query = LogM::select([
            'log.id',
            'log.fk_id',
            'log.table_name',
            'log.created',
            'log.notes'
        ]);
    }
    public function orderBy($columnName, $orderBy)
    {
        switch($columnName) {
            case 'created':
                $this->query->orderBy('log.created', $orderBy);
                break;
        }
    }

    public function setLimit($limit) 
    {
        $this->limit = $limit;
    }

    public function whereKeyword()
    {
        $keyword = $this->keyword;

        if(!empty($keyword)) {
            $list = explode(' ', $keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('log.notes', 'like', $pattern);
                }
            });
        }
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function whereTableName() 
    {
        $tableName = $this->tableName;

        if(!empty($tableName)) {
            $this->query->where('log.table_name', $tableName);
        }
    }

    public function setFkId($fkId)
    {
        $this->fkId = $fkId;
    }

    public function whereFkId() 
    {
        $fkId = $this->fkId;

        if(!empty($fkId)) {
            $this->query->where('log.fk_id', $fkId);
        }
    }

    public function joinTable($joinTableName){
        $this->joinTableName =  $joinTableName;
        if(!empty($joinTableName)) {
            $this->query->join($joinTableName, $this->tableName.'.fk_id','=',$joinTableName.'.id');
        }
    }

    public function whereGroupBy($whereGroupBy){
        if(!empty($whereGroupBy)) {
            $this->query->where($this->joinTableName.'.group_by', $whereGroupBy);
        }
    }

    public function whereDateFrom($date_from)
    {
        if(!empty($date_from)) {
            $this->query->whereDate('created', '>=', $date_from);
        }
    }

    public function whereDateTo($date_to)
    {
        if(!empty($date_to)) {
            $this->query->whereDate('created', '<=', $date_to);
        }
    }

    public function doQuery()
    {
        $this->whereFkId();
        $this->whereTableName();
        $this->whereKeyword();
        $this->orderBy('created', 'DESC');
    }
}
