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
    

    public function send_tnbc($bank_ip, $signing_key, $destination_account_number, $amount, $memo){
        //check if $signing_key, $destination_account_number is valid

        //decode account number from public key
        $payment_account_number = '';
        //bank configuration
        // http://54.183.16.194/config?format=json
        // $bank = '54.183.16.194';
        // $bank = '20.98.98.0';
        $protocol = 'http';
        $bank = $bank_ip;
        $bank_config_url = $protocol.'://'.$bank_ip.'/config?format=json';
        $bank_config = HttpUtilities::fetchUrl($bank_config_url);

        $balance_lock_url = $bank_config->primary_validator->protocol.'://'.$bank_config->primary_validator->ip_address.':'.$bank_config->primary_validator->port || 0.'/accounts/'.$payment_account_number.'/balance_lock?format=json';
        // $balance_lock = requests.get(balance_lock_url).json()['balance_lock']
    }

}