<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Libs\Repository\Config;

class ApiConfigController extends ApiController
{
    public function index()
    {
        $this->jsonResponse->setData(Config::toArray());
        return $this->jsonResponse->getResponse();
    }

    public function store(Request $request)
    {
        $logo = [];

        if ($request->file('files')) {
            $file = $request->file('files');

            if(!empty($file['logo']))
                $logo = $file['logo'];
        };

        $request = json_decode($request->data);

        $repo = new Config();

        if (!empty($logo))
            $repo->addLogo($logo);

        if ($request->_delete_logo)
            foreach ($request->_delete_logo as $x)
                $repo->addDeleteLogo($x);

        foreach(Config::ALLOWED_KEY as $x) {
            $repo->update($x, $request->$x);
        }

        $repo->save();

        $this->jsonResponse->setMessage('Pengaturan berhasil disimpan.');
        return $this->jsonResponse->getResponse();
    }
}
