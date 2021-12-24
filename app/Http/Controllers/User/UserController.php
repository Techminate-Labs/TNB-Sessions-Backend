<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\User\UserServices;

class UserController extends Controller
{
    private $userServices;

    public function __construct(UserServices $userServices){
        $this->services = $userServices;
    }

    public function userList(Request $request)
    {
        return $this->services->userList($request);
    }

    public function userProfileView($id)
    {
        return $this->services->userProfileView($id);
    }

    public function userGetById($id)
    {
        return $this->services->userGetById($id);
    }

    public function userUpdate(Request $request, $id)
    {
        return $this->services->userUpdate($request, $id);
    }

    public function userDelete($id)
    {
        return $this->services->userDelete($id);
    }
}
