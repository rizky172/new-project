<?php

namespace App\Libs\Repository;

use Illuminate\Support\Facades\DB;

use App\Person as Model;
use App\User as UserModel;
use App\Config;

class Profile extends AbstractRepository
{
    private $password;
    private $retype_password;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function save()
    {
        $this->filterByAccessControl('profile_create');

        DB::transaction(function () {
            $this->savePerson();
            $this->saveUser();
        });
    }

    private function savePerson()
    {
        $customerRepo = new Customer($this->model);

        $customerRepo->setUser($this->username, $this->password, $this->retype_password);

        $customerRepo->save();
    }

    private function saveUser()
    {
        $person = $this->model;

        $user = UserModel::firstOrNew(['person_id' => $person->id]);
        $user->name = $person->name;
        $user->email = $person->email;
        $user->username = $this->username;

        $userRepo = new User($user);

        if ($this->password)
            $userRepo->setPassword($this->password, $this->retype_password);

        if ($user->id) {
            $userRepo->setPermissionGroupId($userRepo->getPermissionGroupId());
        }
        else {
            $customerPermissionId = Config::where('key', 'customer_permission_group_id')->first()->value ?? null;
            $userRepo->setPermissionGroupId([$customerPermissionId]);
        }

        $userRepo->save();
    }

    public function toArray()
    {
        $this->filterByAccessControl('profile_read');

        $user = UserModel::where('person_id', $this->model->id)->first();

        $data = $this->model->toArray();
        $data['username'] = $user->username;

        return $data;
    }

    public function delete($permanent = null)
    {
        $this->filterByAccessControl('profile_delete');

        parent::delete($permanent);
    }

    protected function beforeDelete($permanent = null)
    {
        if ($permanent) {
            $user = UserModel::where('person_id', $this->model->id)->first();
            $user->delete();
        }
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password, $retype_password)
    {
        $this->password = $password;
        $this->retype_password = $retype_password;
    }
}
