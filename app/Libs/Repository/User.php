<?php
namespace App\Libs\Repository;

use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Arrayable;

use App\WevelopeLibs\Helper\DateFormat;

use App\Libs\Repository\AbstractRepository;
use App\Libs\Repository\Customer;
use App\Libs\Meta\MetaManager;
use App\Libs\Meta\UserMetaConfig;

use App\Models\User as Model;
use App\Models\Person;
use App\Models\Meta;

class User extends AbstractRepository implements Arrayable
{
    private $password;
    private $confirmPassword;

    private $permissionGroupId = [];

    public function __construct(Model $model)
    {
        parent::__construct($model);

        $metas = $this->model->metaIgnoreKey('media')->get();
        if (!empty($metas))
            $this->originalMeta = $metas->toArray();

        $this->metaConfig = new UserMetaConfig();

        $this->password = null;
        $this->confirmPassword = null;
    }

    public function save()
    {
        if($this->getAccessControl()){
            $this->filterByAccessControl('admin_create');

            $this->validateByRole();
        }

        $this->generateData();
        $this->validate();

        if(!empty($this->password))
            $this->model->password = Hash::make($this->password);

        $this->model->save();
        $this->saveMeta();
    }

    public function toArray()
    {
        if($this->getAccessControl()){
            $this->filterByAccessControl('admin_read');

            $this->validateByRole();
        }

        $accessControl = new AccessControl($this->model);
        $data = $this->model->toArray();

        $permissionGroups = $this->model->permissionGroups;

        $data['permission_group_id'] = empty($permissionGroups ) ? null : $permissionGroups->pluck('value')->all();
        $data['permission'] = $accessControl->getPermissions()->pluck('name');
        $data['permission_group'] = $accessControl->getPermissionGroups()->sortBy('name')->pluck('name');

        return $data;
    }

    public function delete($permanent = null)
    {
        if($this->getAccessControl()){
            $this->filterByAccessControl('admin_delete');

            $this->validateByRole();
        }

        if(empty($permanent)) {
            $this->model->delete();
        } else {
            $this->model->forceDelete();
        }
    }

    public function setPassword($password, $confirmPassword)
    {
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    public function setPermissionGroupId($permissionGroupId)
    {
        $this->permissionGroupId = $permissionGroupId;
    }

    public function validate()
    {
        $fields = [
            'id' => $this->model->id,
            'person_id' => $this->model->person_id,
            'name' => $this->model->name,
            'username' => $this->model->username,
            'email' => $this->model->email,
            'password' => $this->password,
            'confirm_password' => $this->confirmPassword
        ];

        $rules = [
            'id' => 'nullable',
            'person_id' => 'nullable|unique:user,person_id,' . $this->model->id,
            'name' => 'required',
            'username' => 'required|max:16|unique:user,username,' . $this->model->id,
            'email'=> 'required|email|unique:user,email,' . $this->model->id,
            'password' => 'nullable|required_without:id|min:5',
            'confirm_password' => 'same:password'
        ];

        $validator = Validator::make($fields, $rules);
        self::validOrThrow($validator);
    }

    private function generateData()
    {
        if (empty($this->model->ref_no))
            $this->model->ref_no = $this->generateRefNo(2, 'U', null);
    }

    private function saveMeta()
    {
        $list = $this->model->meta;
        $fkId = $this->model->id;
        $tableName = $this->model->getTable();
        $metaManager = new MetaManager($list, $fkId, $tableName);

        $permissionGroupIds = $this->permissionGroupId;
        if (!empty($permissionGroupIds))
            foreach ($permissionGroupIds as $permissionGroupId)
                $metaManager->addDetail(UserMetaConfig::PERMISSION_GROUP_ID, $permissionGroupId);

        $metaManager->saveAllAndDelete();
    }

    public function getPermissionGroupId()
    {
        $permissionGroupId = $this->model->permissionGroups->pluck('value')->toArray();

        return $permissionGroupId;
    }

    private function validateByRole()
    {
        if($this->getAccessControl()){
            if (!$this->accessControl->hasAccess('admin_full_access')) {
                $this->accessControl->hasPerson();

                $personId = $this->model->person_id;
                $this->accessControl->hasAccessPerson($personId);
            }
        }
    }

    // Restore model
    public function restore()
    {
        $this->model->restore();
    }
}
