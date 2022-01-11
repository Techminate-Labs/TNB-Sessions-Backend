<?php

namespace App\Services\Event;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Event\SessionValidation;

//Models
use App\Models\Account;
use App\Models\Session;
use App\Models\SessionUser;

class SessionUserServices extends BaseServices{
    private $sessionUserModel = SessionUser::class;

    public function createSessionUser($sessionId, $userId){
        $this->baseRI->storeInDB(
            $this->sessionUserModel,
            [
                'session_id'=> $sessionId,
                'user_id'=>$userId,
            ]
        );
    }

    public function enrollSession($request){
        $userId = auth()->user()->id;
        $sessionId = $request->session_id;
        $sessionUserExit = SessionUser::where('session_id',$sessionId)->where('user_id',$userId)->first();
        
        if($sessionUserExit){
            return response(["message"=>'you are already enrolled to the session.'],200);
        }

        $session = Session::where('id',$sessionId)->first();
        $instAccount = Account::where('user_id',2)->first();
        $stdAccount = Account::where('user_id',$userId)->first();
        $fee = (int)$session->fee;

        if($stdAccount && $instAccount){
            $stdAccBal = $stdAccount->balance;
            $instAccBal = $instAccount->balance;
            if($stdAccBal >= $fee){
                $stdAccount->balance = $stdAccBal - $fee;
                $instAccount->balance = $instAccBal + $fee;
                $stdAccount->save();
                $instAccount->save();
                $this->createSessionUser($sessionId, $userId);
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
}