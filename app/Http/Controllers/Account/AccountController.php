<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Account\RegisterServices;

class AccountController extends Controller
{
    private $registerServices;

    public function __construct(RegisterServices $registerServices){
        $this->services = $registerServices;
    }

    public function registerPK(Request $request){
        return $this->services->registerPK($request);
    }
}