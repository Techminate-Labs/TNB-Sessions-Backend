<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Account\WithdrawServices;

class WithdrawController extends Controller
{
    private $withdrawServices;

    public function __construct(WithdrawServices $withdrawServices){
        $this->services = $withdrawServices;
    }

    public function withdraw(Request $request){
        return $this->services->withdraw($request);
    }

    public function checkConfirmations(){
        return "ok";
    }
}
