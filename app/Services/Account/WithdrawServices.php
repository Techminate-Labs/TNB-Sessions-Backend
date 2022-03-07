<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;

//Models
use App\Models\Account;

class WithdrawServices extends BaseServices{
    public function temp($request){
        //encode hexadecimal
        //signing
        //check if sender and recipient has account number
        //check if sender has enough balance in account
        //total wallet balance, total fee collected,
        //Direction (incoming, outgoing)

        $recipientID = $request->user_id;
        $amount = (int)$request->amount;
        $senderId = auth()->user()->id;

        $senderAcc = Account::where('user_id', $senderId)->first();
        $recipientAcc = Account::where('user_id', $recipientID)->first();
    }

    public function generate_block($balance_lock, $transactions, $signing_key){
        //
    }

        
    public function is_valid_key($key){
        # Check if signing key is valid hexadecimal
       
        # Check if the length of the key is 64
    }
    

}