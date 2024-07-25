<?php

namespace App\Http\Controllers\Api;

use Auth;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\WevelopeLibs\Helper\DateFormat;
use App\Libs\Repository\Finder\LogFinder;
use Illuminate\Support\Str;

class ApiLogController extends ApiController
{
    public function index(Request $request)
    {
        $finder = new LogFinder();
        if(isset($request->order_by) && !empty($request->order_by)) {
            $finder->orderBy($request->order_by['column'], $request->order_by['ordered']);
        }

        if($request->page)
            $finder->setPage($request->page);

        // // Search by keyword
        if($request->keyword)
            $finder->setKeyword($request->keyword);

        if($request->fk_id)
            $finder->setFkId($request->fk_id);

        if($request->table_name)
            $finder->setTableName($request->table_name);

        if($request->table_name == 'log'){
            $finder->joinTable('category');
            $finder->whereGroupBy('log');
        }

        if($request->date_from){
            $finder->whereDateFrom(new \DateTime($request->date_from));
        }

        if($request->date_to){
            $finder->whereDateTo(new \DateTime($request->date_to));
        }

        if($request->limit)
            $finder->setLimit($request->limit);


        $paginator = $finder->get();

        $data = [];
        foreach($paginator as $x){
            $created = new \DateTime($x->created);

            $data[] = [
                'id' => $x->id,
                'fk_id' => $x->fk_id,
                'table_name' => $x->table_name,
                'notes' => $x->notes,
                'created' => $created->format('d F Y h:i:s')
            ];
        }
        // { index: 0, title: 'New Message', msg: 'Are your going to meet me tonight?', icon: 'MessageSquareIcon', time: this.randomDate({sec: 10}), category: 'primary' },
        $this->jsonResponse->setData($data);
        $this->jsonResponse->setMeta($this->jsonResponse->getPaginatorConfig($paginator));

        return $this->jsonResponse->getResponse();
    }
}
