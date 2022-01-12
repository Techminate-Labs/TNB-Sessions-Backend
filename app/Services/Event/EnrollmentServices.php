<?php

namespace App\Services\Event;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\SessionValidation;

//Models
use App\Models\User;
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
                // $user = User::find($userId);
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

    public function enrolledSessions($request){
        $userId = auth()->user()->id;
        $sessionId = $request->session_id;
        $user = User::find($userId);
        $sessions = $user->session;
        
        $events = [];
        foreach ($sessions as $session){
            $event = Event::where('id',$session->event_id)->first();
            if(isset($events[$session->event_id])){
                continue;
            }else{
                array_push($events, $event);
            } 
        }
        return $events;
    }
}