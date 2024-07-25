<?php
namespace App\Libs\Repository\Finder;

use App\Models\User as Model;

class UserFinder extends AbstractFinder
{
    public function __construct()
    {
        parent::__construct();
        $this->query = Model::select('*');
    }

    public function setOnlyTrashed($isDeleted)
    {
        if ($isDeleted == 1){
            $this->query->onlyTrashed();
        }
    }

    public function orderBy($columnName, $orderBy)
    {
        switch($columnName) {
            case 'username':
                $this->query->orderBy('user.username', $orderBy);
                break;

            case 'name':
                $this->query->orderBy('user.name', $orderBy);
                break;
        }
    }

    public function whereKeyword()
    {
        $keyword = $this->keyword;

        if(!empty($keyword)) {
            $this->query->where(function($query) use ($keyword) {
                $pattern = '%' . $keyword . '%';
                $query->orWhere('user.name', 'like', $pattern)
                    ->orWhere('user.email', 'like', $pattern)
                    ->orWhere('user.username', 'like', $pattern);
            });
        }
    }

    private function wherePersonNull()
    {
        $this->query->whereNull('user.person_id');
    }

    private function filterByRole()
    {
        if (!$this->accessControl->hasAccess('admin_full_access')) {
            $this->accessControl->hasPerson();
            $person = $this->accessControl->getUser()->person;

            if (!empty($person)) {
                $this->query->where('user.person_id', $person->id);
            }
        }
    }

    public function doQuery()
    {
        $this->filterByAccessControl('admin_read');

        $this->whereKeyword();
        // $this->wherePersonNull();

        $this->filterByRole();
    }
}
