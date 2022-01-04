<?php

namespace App\Http\Controllers\Blockchain;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Blockchain\DepositServices;

class DepositController extends Controller
{
    private $depositServices;

    public function __construct(DepositServices $depositServices){
        $this->services = $depositServices;
    }

    public function registerPK(Request $request){
        return $this->services->registerPK($request);
    }
}
