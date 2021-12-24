<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\User\RoleServices;

class RoleController extends Controller
{
    private $roleServices;

    public function __construct(RoleServices $roleServices){
        $this->services = $roleServices;
    }

    public function roleList(Request $request)
    {
        return $this->services->roleList($request);
    }

    public function roleGetById(Request $request, $id)
    {
        return $this->services->roleGetById($request, $id);
    }
   
    public function roleCreate(Request $request)
    {
        return $this->services->roleCreate($request);
    }

    public function roleUpdate(Request $request, $id)
    {
        return $this->services->roleUpdate($request, $id);
    }
   
    public function roleDelete(Request $request, $id)
    {
        return $this->services->roleDelete($request, $id);
    }
}
