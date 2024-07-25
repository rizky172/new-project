<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Auth;

use App\Libs\Repository\AccessControl;
use App\Libs\LibKernel;
use App\WevelopeLibs\JsonResponse;
use App\Models\User;

class ApiController extends Controller
{
    protected $jsonResponse;

    public function __construct(Request $request)
    {
        $this->jsonResponse = new JsonResponse();

        LibKernel::smptFromDb();
    }

    public function getAccessControl()
    {
        $user = Auth::user();
        if(!empty($user))
            return new AccessControl($user);

        return null;
    }

    public function filterByAccessControl($access)
    {
        if ($this->getAccessControl()) {
            if(!$this->getAccessControl()->hasAccess($access)) {
                WeAccessControl::throwUnauthorizedException();
            }
        }
    }
}
