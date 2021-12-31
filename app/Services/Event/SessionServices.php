<?php

namespace App\Services\Event;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\SessionValidation;

//Models
use App\Models\Session;

class SessionServices extends BaseServices{
    
    private $SessionModel = Session::class;

    public function list($request){
        $prop1 = 'title';
        if ($request->has('q')){
            $session = $this->filterRI->filterBy1PropPaginated($this->sessionModel, $request->q, $request->limit, $prop1);
        }else{
            $session = $this->baseRI->listWithPagination($this->sessionModel, $request->limit);
        }
        return $session;
    }

    public function getById($id){
        $session = $this->baseRI->findById($this->sessionModel, $id);
        if($session){
            return $session;
        }else{
            return response(["message"=>'session not found'],404);
        }
    }

    public function create($request){
        $fields = SessionValidation::validate1($request);
        // $user_id = auth()->user()->id;
        $session = $this->baseRI->storeInDB(
            $this->sessionModel,
            [
                'event_id'=> 1,
                'title'=>$fields['title'],
                'date'=>$fields['date'],
                'start'=>$fields['start'],
                'end' => $fields['end'],
                'meeting_link'=>$fields['meeting_link'],
                'password'=>$fields['password'],
                'fee'=>$fields['fee'],
            ]
        );

        if($session){
            return response($session,201);
        }else{
            return response(["message"=>'server error'],500);
        }
    }

    public function update($request, $id){
        $session = $this->baseRI->findById($this->sessionModel, $id);
        // $user_id = auth()->user()->id;
        if($session){
            $fields = SessionValidation::validate1($request);
            $data = [
                'event_id'=> 1,
                'title'=>$fields['title'],
                'date'=>$fields['date'],
                'start'=>$fields['start'],
                'end' => $fields['end'],
                'meeting_link'=>$fields['meeting_link'],
                'password'=>$fields['password'],
                'fee'=>$fields['fee'],
            ];
            $session->update($data);
            return response($session,201);
        }else{
            return response(["message"=>'session not found'],404);
        }
    }

    public function delete($id){
        $session = $this->baseRI->findById($this->sessionModel, $id);
        if($session){
            $session->delete();
            return response(["message"=>'session deleted successfully'],200);
        }else{
            return response(["message"=>'session not found'],404);
        }
    }
}