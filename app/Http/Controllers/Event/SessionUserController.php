<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\Event\SessionUserServices;

class SessionUserController extends Controller
{
    private $sessionUserServices;

    public function __construct(SessionUserServices $sessionUserServices){
        $this->services = $sessionUserServices;
    }

    public function enrollSession(Request $request)
    {
        return $this->services->enrollSession($request);
    }
}
