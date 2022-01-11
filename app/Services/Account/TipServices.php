<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Account\DepositValidation;

//Models
use App\Models\Account;

class TipServices extends BaseServices{
    public function sendTip($request){
        //request validation
        //amount would be transfered from sender to recipient account
        //check if sender and recipient has account number
        //check if sender has enough balance in account
        //total wallet balance, total fee collected,
        //Direction (incoming, outgoing)

        $recipientID = $request->user_id;
        $amount = (int)$request->amount;
        $senderId = auth()->user()->id;

        $senderAcc = Account::where('user_id', $senderId)->first();
        $recipientAcc = Account::where('user_id', $recipientID)->first();

        if($senderAcc && $recipientAcc){
            $senderbalance = $senderAcc->balance;
            $recipientbalance = $recipientAcc->balance;

            if($senderbalance >= $amount){
                $recipientbalance = $recipientbalance + $amount;
                $recipientAcc->balance = $recipientbalance;
                $recipientAcc->save();

                $senderbalance = $senderbalance - $amount;
                $senderAcc->balance = $senderbalance;
                $senderAcc->save();
                return [
                    'recipientID' => $recipientID,
                    'senderId' => $senderId,
                    'amount' => $amount,
                    'senderbalance' => $senderbalance,
                    'recipientbalance' => $recipientbalance
                ];
            }else{
                return response(["message"=>'insufficient balance.'],200);
            }
        }else{
            return 'account not found';
        }
    }
}