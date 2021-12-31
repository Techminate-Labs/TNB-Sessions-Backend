<?php

namespace App\Services\Validation\Event;

class SessionValidation{
    public static function validate1($request){
        return $request->validate([
            'event_id'=>'required',
            'title'=>'required|string',
            'date'=>'required|string',
            'start'=>'required|string',
            'end'=>'required|string',
            'meeting_link'=>'required|string',
            'password'=>'required|string',
        ]);
    }
}