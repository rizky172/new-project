<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiCategoryController;

use App\Libs\Helpers\ResourceUrl;
use App\Libs\ImportExport\HakAksesExport;
use App\Libs\ImportExport\HakAksesImport;

use App\Libs\Repository\Finder\CategoryFinder;
use App\Libs\Repository\PermissionGroup;

use App\Models\Category as Model;

class ApiPermissionController extends ApiCategoryController
{

    public function store(Request $request)
    {
        $row = Model::findOrNew($request->id);
        $groupBy = 'permission_group';

        if(isset($request->category_id))
            $row->category_id = $request->category_id;
        $row->label = $request->name;
        $row->name = $request->name;
        $row->group_by = $groupBy;

        $repo = new PermissionGroup($row);

        if ((isset($request->permission_id))) {
            foreach ($request->permission_id as $x) {
                $repo->addDetailPermission($x);
            }
        }

        $repo->save();

        $this->jsonResponse->setMessage('Hak Akses telah berhasil disimpan.');
        $this->jsonResponse->setData($row->id);

        return $this->jsonResponse->getResponse();
    }

    public function show($id)
    {
        $row = $this->getModel($id);
        $repo = new PermissionGroup($row);

        $this->jsonResponse->setData($repo->toArray());

        return $this->jsonResponse->getResponse();
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);

        $repo = new PermissionGroup($row);
        $repo->setAccessControl($this->getAccessControl());

        $repo->delete();

        $this->jsonResponse->setMessage('Hak Akses berhasil dihapus.');

        return $this->jsonResponse->getResponse();
    }

    public function permission($id)
    {
        $this->jsonResponse->setData(PermissionGroup::getPermission($id));

        return $this->jsonResponse->getResponse();
    }

    private function getModel($id)
    {
        $row = Model::find($id);
        if(empty($row)){
            throw new NotFoundHttpException('Hak Akses tidak ditemukan');
        }

        return $row;
    }

    public function export(Request $request)
    {
        $export = new HakAksesExport(); // make categoryexport libs
        $export->setAccessControl($this->getAccessControl());

        $export->run();
        $filename = $export->getFilename();

        // Generate URl and pass to json
        $this->jsonResponse->setData(['url' => ResourceUrl::tmp($filename)]);
        return $this->jsonResponse->getResponse();
    }

    public function import(Request $request)
    {
        $import = new HakAksesImport($request->file('file'));
        $import->setAccessControl($this->getAccessControl());
        $import->run();

        $message = $import->getErrorMessage();
        if(empty($message)) {
            $message = 'Import berhasil.';
        }

        $this->jsonResponse->setMessage($message);
        return $this->jsonResponse->getResponse();
    }
}
