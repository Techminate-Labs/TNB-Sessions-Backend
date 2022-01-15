<?php

namespace App\Services\Event;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\EventValidation;

//Models
use App\Models\Event;

class ReviewServices extends BaseServices{

    public function create($request){
        $fields = EventValidation::validate1($request);
        // $user_id = auth()->user()->id;
        $event = $this->baseRI->storeInDB(
            $this->eventModel,
            [
                'user_id'=> 1,
                'title'=>$fields['title'],
                'start'=>$fields['start'],
                'end' => $request->end,
                'duration'=>$fields['duration'],
                'media'=>$fields['media'],
                'pay_type'=>$fields['pay_type'],
            ]
        );

        if($event){
            return response($event,201);
        }else{
            return response(["message"=>'server error'],500);
        }
    }
}