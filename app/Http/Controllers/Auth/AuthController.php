<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Auth\AuthServices;

class AuthController extends Controller
{
    private $authServices;

    public function __construct(AuthServices $authServices){
        $this->services = $authServices;
    }

    public function register(Request $request) {
        return $this->services->register($request);
    }

    public function login(Request $request) {
        return $this->services->login($request);
    }

    public function logout(Request $request) {
        return $this->services->logout();
    }
}
