<?php

namespace App\Services\Event;

use Illuminate\Support\Str;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\EventValidation;

//Models
use App\Models\Event;

class EventServices extends BaseServices{
    
    private $eventModel = Event::class;

    public function list($request){
        $countObj = 'session';
        $prop1 = 'title';
        if ($request->has('q')){
            $event = $this->filterRI->filterBy1PropWithCount($this->eventModel, $request->q, $request->limit, $countObj, $prop1);
        }else{
            $event = $this->baseRI->listwithCount($this->eventModel, $request->limit, $countObj);
        }
        return $event;
    }

    public function getById($id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        if($event){
            return $event;
        }else{
            return response(["message"=>'event not found'],404);
        }
    }

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

    public function update($request, $id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        // $user_id = auth()->user()->id;
        if($event){
            $fields = EventValidation::validate1($request);
            $data = [
                'user_id'=> 1,
                'title'=>$fields['title'],
                'start'=>$fields['start'],
                'end' => $request->end,
                'duration'=>$fields['duration'],
                'media'=>$fields['media'],
                'pay_type'=>$fields['pay_type'],
            ];
            $event->update($data);
            return response($event,201);
        }else{
            return response(["message"=>'event not found'],404);
        }
    }

    public function delete($id){
        $event = $this->baseRI->findById($this->eventModel, $id);
        if($event){
            $event->delete();
            return response(["message"=>'event deleted successfully'],200);
        }else{
            return response(["message"=>'event not found'],404);
        }
    }
}