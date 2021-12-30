<?php

namespace App\Services\Validation\Event;

class EventValidation{
    public static function validate1($request){
        return $request->validate([
            'title'=>'required|string',
            'start'=>'required|string',
            'duration'=>'required|string',
            'media'=>'required|string',
            'pay_type'=>'required|string',
        ]);
    }
}