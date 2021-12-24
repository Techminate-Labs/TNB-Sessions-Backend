<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\System\ConfigServices;

class ConfigurationController extends Controller
{
    private $configServices;

    public function __construct(ConfigServices $configServices){
        $this->services = $configServices;
    }

    public function config()
    {
        return $this->services->config();
    }

    public function configUpdate(Request $request)
    {
        return $this->services->configUpdate($request);
    }

    public function convCur(Request $request)
    {
        return $this->services->convCur($request);
    }
}
