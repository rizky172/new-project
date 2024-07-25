<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Libs\Repository\Finder\UserFinder;
use App\Libs\Repository\User;

use App\Models\User as Model;


class ApiUserController extends ApiController
{
    public function index(Request $request)
    {
        $finder = new UserFinder();
        $finder->setAccessControl($this->getAccessControl());

        if(isset($request->order_by) && !empty($request->order_by))
            $finder->orderBy($request->order_by['column'], $request->order_by['ordered']);

        if($request->page)
            $finder->setPage($request->page);

        if (isset($request->deleted)) {
            $finder->setOnlyTrashed($request->deleted);
        }

        // Search by keyword
        if($request->keyword)
            $finder->setKeyword($request->keyword);

        $paginator = $finder->get();

        $data = [];
        foreach($paginator as $x){
            $data[] = [
                'id' => $x->id,
                'email' => $x->email,
                'name' => $x->name,
                'username' => $x->username,
            ];
        }
        $this->jsonResponse->setData($data);
        $this->jsonResponse->setMeta($this->jsonResponse->getPaginatorConfig($paginator));

        return $this->jsonResponse->getResponse();
    }

    public function store(Request $request)
    {
        $admin = Model::findOrNew($request->id);
        $admin->person_id = $request->person_id;
        $admin->username = $request->username;
        $admin->name = $request->name;
        $admin->email = $request->email;

        $repo = new User($admin);
        $repo->setAccessControl($this->getAccessControl());

        // Add permission group
        if (isset($request->permission_group_id) &&
            !empty($request->permission_group_id)) {
            $repo->setPermissionGroupId($request->permission_group_id);
        }
        
        // Update password
        if(!empty($request->password) && !empty($request->retype_password)){
            $repo->setPassword($request->password, $request->retype_password);
        }

        $repo->save();

        $this->jsonResponse->setMessage('Admin telah berhasil tersimpan.');
        $this->jsonResponse->setData($admin->id);

        return $this->jsonResponse->getResponse();
    }

    public function show($id){
        $row = $this->getUser($id);
        $repo = new User($row);
        $repo->setAccessControl($this->getAccessControl());

        $this->jsonResponse->setData($repo->toArray());

        return $this->jsonResponse->getResponse();
    }

    public function destroy($id, $permanent = null)
    {
        $row = Model::withTrashed()
                        ->find($id);

        $repo = new User($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete($permanent);

        $this->jsonResponse->setMessage('Admin telah berhasil dihapus.');

        return $this->jsonResponse->getResponse();

    }

    public function profile()
    {
        $row = $this->getAccessControl()->getUser();

        $repo = new User($row);

        $this->jsonResponse->setData($repo->toArray());
        return $this->jsonResponse->getResponse();
    }

    private function getUser($id){
        $row = Model::find($id);
        if(empty($row)){
            throw new NotFoundHttpException('Admin tidak ditemukan');
        }

        return $row;
    }

    public function restore($id)
    {
        $row = Model::withTrashed()
            ->find($id);

        $repo = new User($row);
        $repo->restore();

        $this->jsonResponse->setMessage('Admin berhasil dikembalikan');

        return $this->jsonResponse->getResponse();
    }
}
