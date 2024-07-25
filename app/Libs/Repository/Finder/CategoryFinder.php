<?php

namespace App\Libs\Repository\Finder;

use DB;

class CategoryFinder extends AbstractFinder
{
    private $only;
    private $excludeNames; // Many
    private $isDisabled;
    private $withTrashed;
    private $groupBy;

    public function __construct()
    {
        parent::__construct();
        $this->query = DB::table('category');

        $this->query->select(
            'category.*'
        );
    }

    public function setDisabled($isDisabled)
    {
        $this->isDisabled = $isDisabled;
    }

    // If null = all page
    public function setOnly($q)
    {
        $this->only = $q;
    }

    public function setExcludeByNames($excludeNames)
    {
        $this->excludeNames = $excludeNames;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function setGroup($group)
    {
        $this->groupBy = $group;
    }

    public function setParentId($parentId)
    {
        if(!empty($parentId)) {
            $this->query->where('category.category_id', $parentId);
        } else {
            $this->query->whereNull('category.category_id');
        }
    }

    public function setChildOnly($childOnly)
    {
        if (!empty($childOnly) && $childOnly == 1) {
            $this->query->whereNotNull('category.category_id');
        }
    }

    public function setWithTrashed($withTrashed)
    {
        if (!empty($withTrashed)) {
            $this->withTrashed = $withTrashed;
        }
    }

    public function orderBy($columnName, $orderBy)
    {
        switch($columnName) {
            case 'label':
                $this->query->orderBy('category.label', $orderBy);
                break;
            case 'level':
                $this->query->orderBy('meta_level.value', $orderBy);
                break;
        }
    }

    public function setKeyword($keyword)
    {
        if(!empty($keyword)) {
            $list = explode(' ', $keyword);
            $list = array_map('trim', $list);

            $this->query->where(function($query) use ($list) {
                foreach($list as $x) {
                    $pattern = '%' . $x . '%';
                    $query->orWhere('category.label', 'like', $pattern);
                }
            });
        }
    }

    private function whereGroupBy()
    {
        $arr = explode(",", $this->groupBy);

        $this->query->whereIn('category.group_by', $arr);
    }

    private function whereOnly() {
        $q = $this->only;
        if($q != null) {
            $this->query->where('category.name', $q);
        }
    }

    private function whereExcludeNames() {
        $q = explode(',', $this->excludeNames);
        if($q != null) {
            $this->query->whereNotIn('category.name', $q);
        }
    }

    private function whereDisabled() {
        if (isset($this->isDisabled)) {
            $this->query->where('category.disabled', $this->isDisabled);
        }
    }

    private function whereWithTrashed()
    {
        if (empty($this->withTrashed)) {
            $this->query->whereNull('category.deleted_at');
        }
    }

    private function whereExcludeCategoryByHakAkses()
    {
        if(!$this->accessControl->hasAccess('customer_full_access')){
            $groupBy = explode(",", $this->groupBy);
            $trx = array_search('trx', $groupBy);

            if($groupBy[$trx] == 'trx'){
                $this->query->whereNotIn('category.name', ['income']);
            }
        }
    }

    public function doQuery()
    {
        $this->whereGroupBy();
        $this->whereWithTrashed();
        $this->whereExcludeNames();
        $this->whereOnly();
        // $this->whereExcludeCategoryByHakAkses();

        $this->query->whereNull('category.deleted_at');
        // $this->query->orderBy('category.label', 'asc');

        // var_dump($this->query->toSql()); die;
    }
}