<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\System\ActivityLogServices;

class ActivityLogController extends Controller
{
    private $activityLogServices;

    public function __construct(ActivityLogServices $activityLogServices){
        $this->services = $activityLogServices;
    }

    public function logList(Request $request)
    {
        return $this->services->logList($request);
    }
}
