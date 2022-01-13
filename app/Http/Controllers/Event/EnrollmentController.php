<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Event\EnrollmentServices;

class EnrollmentController extends Controller
{
    private $enrollmentServices;

    public function __construct(EnrollmentServices $enrollmentServices){
        $this->services = $enrollmentServices;
    }

    public function enrollToSession(Request $request)
    {
        return $this->services->enrollToSession($request);
    }

    public function enrolledEvents(Request $request)
    {
        return $this->services->enrolledEvents($request);
    }

    public function enrolledSessions(Request $request)
    {
        return $this->services->enrolledSessions($request);
    }

    
}
