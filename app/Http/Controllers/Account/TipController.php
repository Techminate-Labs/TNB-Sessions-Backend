<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Account\TipServices;

class TipController extends Controller
{
    private $tipServices;

    public function __construct(TipServices $tipServices){
        $this->services = $tipServices;
    }

    public function sendTip(Request $request){
        return $this->services->sendTip($request);
    }
}
