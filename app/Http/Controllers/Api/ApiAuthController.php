<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Hash;
use DB;

use App\Models\User as UserModel;
use App\Models\Meta as MetaModel;
use App\Models\Category;

use App\Libs\LibKernel;
use App\Libs\Repository\User;

class ApiAuthController extends ApiController
{
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $ip = $request->ip();
        $token = LibKernel::login($username, $password, $ip);

        $this->jsonResponse->setData($token);
        $this->jsonResponse->setMessage("Login berhasil.");

        return $this->jsonResponse->getResponse();
    }

    public function register(Request $request)
    {
        $req = $request->all();
        $req['ip'] = $request->ip();

        $result = LibKernel::register($req);

        $this->jsonResponse->setData($result);
        $this->jsonResponse->setMessage('Register successfully.');

        return $this->jsonResponse->getResponse();
    }

    public function logout(Request $request){
        $ip = $request->ip();
        $result = LibKernel::logout($ip);

        $this->jsonResponse->setMessage('Logout successfully.');
        // $this->jsonResponse->setData($result['id']);

        return $this->jsonResponse->getResponse();
    }

    public function forgotPassword(Request $request){
        $email = $request->email;
        $result = LibKernel::forgotPassword($email);

        // $this->jsonResponse->setData($result);
        $this->jsonResponse->setMessage('Password reset link has been sent if the email is registered in our database.');

        return $this->jsonResponse->getResponse();
    }

    public function resetPassword(Request $request){
        $req = $request->all();
        $req['ip'] = $request->ip();

        $result = LibKernel::resetPassword($req);

        $this->jsonResponse->setData($result);
        $this->jsonResponse->setMessage('Reset password successfully.');

        return $this->jsonResponse->getResponse();
    }

    public function testAuth()
    {
        die('get in');
    }
}

