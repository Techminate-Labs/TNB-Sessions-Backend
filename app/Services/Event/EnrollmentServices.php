<?php

namespace App\Services\Event;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\SessionValidation;

//Models
use App\Models\Account;
use App\Models\Session;
use App\Models\Event;

class EnrollmentServices extends BaseServices{

    public function createEnrollment($sessionId, $userId){
        DB::table('session_user')->insert([
            'session_id'=> $sessionId,
            'user_id'=>$userId,
        ]);
    }

    public function enrollToSession($request){
        $userId = auth()->user()->id;
        $sessionId = $request->session_id;
        
        $sessionUserExit = DB::table('session_user')->where('session_id',$sessionId)->where('user_id',$userId)->first();
        
        if($sessionUserExit){
            return response(["message"=>'you are already enrolled to the session.'],200);
        }
        $instAccount = Account::where('user_id',2)->first();
        $stdAccount = Account::where('user_id',$userId)->first();
        $session = Session::where('id',$sessionId)->first();
        $fee = (int)$session->fee;

        if($stdAccount && $instAccount){
            $stdAccBal = $stdAccount->balance;
            $instAccBal = $instAccount->balance;
            if($stdAccBal >= $fee){
                $stdAccount->balance = $stdAccBal - $fee;
                $instAccount->balance = $instAccBal + $fee;
                $stdAccount->save();
                $instAccount->save();
                // $user = $this->authUser();
                // $user->session()->attach([$sessionId]);
                $this->createEnrollment($sessionId, $userId);
                $response = [
                    "message"=>'enrollment has been completed successfully',
                    'session' => $session->title,
                    'fee' => $fee
                ];
                return response($response,201);
            }else{
                return response(["message"=>'insufficient balance. Please deposit and try again.'],200);
            }
        }else{
            return response(["message"=>'account not found.'],200);
        }
    }

    public function enrolledEvents($request){
        $user = $this->authUser();
        $sessions = $user->session;
        $events = [];
        foreach ($sessions as $session){
            if(!isset($events[$session->event_id])){
                $event = Event::where('id',$session->event_id)->first();
                array_push($events, $event);
            }else{
                continue;
            } 
        }
        // return $events;
        return array_unique($events);
    }

    public function enrolledSessions($request){
        $eventId = $request->event_id;
        $user = $this->authUser();
        $sessions = $user->session;
        $eventSessions = [];
        foreach ($sessions as $session){
            if($session->event_id != $eventId){
                continue;
            }else{
                array_push($eventSessions, $session);
            } 
        }
        return $eventSessions;
    }
}