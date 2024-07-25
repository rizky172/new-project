<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Libs\Repository\Finder\CategoryFinder;
use App\Libs\Repository\Category;
use App\Libs\Helpers\ResourceUrl;
use App\Libs\ImportExport\CoaExport;
use App\Libs\ImportExport\CoaImport;

use App\Models\Category as Model;

class ApiCategoryController extends ApiController
{
    public function index(Request $request)
    {
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->getAccessControl());

        if($request->keyword)
            $finder->setKeyword($request->keyword);

        if (isset($request->is_disabled))
            $finder->setDisabled($request->is_disabled);

        if (isset($request->group_by))
            $finder->setGroup($request->group_by);

        if (isset($request->only))
            $finder->setOnly($request->only);

        if (isset($request->exclude_names))
            $finder->setExcludeByNames($request->exclude_names);

        if (isset($request->parent_id))
            $finder->setParentId($request->parent_id);
        else
            $finder->setParentId(null);

        if ($request->page)
            $finder->setPage($request->page);

        $paginator = $finder->get();
        $data = [];
        foreach ($paginator as $x) {
            $data[] = [
                'id' => (int) $x->id,
                'category_id' => !empty($x->category_id) ? (int) $x->category_id : null,
                'name' => $x->name,
                'label' => $x->label,
                'notes' => $x->notes,
                'group_by' => $x->group_by
            ];
        }

        $this->jsonResponse->setData($data);
        $this->jsonResponse->setMeta($this->jsonResponse->getPaginatorConfig($paginator));

        return $this->jsonResponse->getResponse();
    }

    public function store(Request $request)
    {
        $row = Model::findOrNew($request->id);
        if(isset($request->category_id))
            $row->category_id = $request->category_id;
        $row->label = $request->label;
        $row->name = $request->name;
        $row->notes = $request->notes;
        $row->group_by = $request->group_by;

        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());

        if ((isset($request->_delete_detail) ? !empty($request->_delete_detail) : false)) {
            foreach ($request->_delete_detail as $x) {
                $repo->deleteDetail($x);
            }
        }

        if ((isset($request->detail) ? !empty($request->detail) : false)) {
            foreach ($request->detail as $x) {
                $x = (array) $x;

                $id = empty($x['id']) ? null : $x['id'];

                $repo->addDetail($id, $x['label'], $x['notes'], $x['name'], $x['group_by']);
            }
        }

        $repo->save();

        $this->jsonResponse->setMessage('Kategori telah berhasil disimpan.');
        $this->jsonResponse->setData($row->id);

        return $this->jsonResponse->getResponse();
    }

    public function show($id)
    {
        $row = $this->getModel($id);
        $repo = new Category($row);

        $this->jsonResponse->setData($repo->toArray());

        return $this->jsonResponse->getResponse();
    }

    public function destroy($id)
    {
        $row = $this->getModel($id);

        $repo = new Category($row);
        $repo->setAccessControl($this->getAccessControl());
        $repo->delete();

        $this->jsonResponse->setMessage('Kategori telah berhasil dihapus.');

        return $this->jsonResponse->getResponse();
    }

    // Get item category for nodes only
    public function item()
    {
        $this->jsonResponse->setData(Category::getItemCategory());

        return $this->jsonResponse->getResponse();
    }

    private function getModel($id)
    {
        $row = Model::find($id);
        if(empty($row)){
            throw new NotFoundHttpException('Kategori tidak ditemukan.');
        }

        return $row;
    }

    public function export(Request $request)
    {
        $export = new CoaExport(); // make categoryexport libs
        $export->setAccessControl($this->getAccessControl());

        if($request->keyword)
            $export->setKeyword($request->keyword);

        $export->run();
        $filename = $export->getFilename();

        // Generate URl and pass to json
        $this->jsonResponse->setData(['url' => ResourceUrl::tmp($filename)]);
        return $this->jsonResponse->getResponse();
    }

    public function import(Request $request)
    {
        $import = new CoaImport($request->file('file'));
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
