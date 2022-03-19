<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;

//Models
use App\Models\Account;

//Utilities
use App\Utilities\HttpUtilities;

use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class WithdrawServices extends BaseServices{

    public function withdraw($request){
        //encode hexadecimal
        //signing
        //check if sender has enough balance in account
        //total wallet balance
        //display withdrawable amount

        $recipentId = auth()->user()->id;
        $recipientAcc = Account::where('user_id', $recipentId)->first();
        $payment_account_number = $recipientAcc->account_number;
        
        //bank configuration
        // $bank_ip = '54.183.16.194';
        $bank_ip = '20.98.98.0';
        $bank_protocol = 'http';
        $bank_config_url = $bank_protocol.'://'.$bank_ip.'/config?format=json';
        $bank_config = HttpUtilities::fetchUrl($bank_config_url);
        
        $balance_lock_url = $bank_config->primary_validator->protocol.'://'.$bank_config->primary_validator->ip_address.':'.$bank_config->primary_validator->port.'/accounts'.'/'.$payment_account_number.'/balance_lock?format=json';
        $balance_lock = HttpUtilities::fetchUrl($balance_lock_url)->balance_lock;

        # Check if the signing key is initialized. If not, request the user to initialize by sending a tnbc.
        if (!$balance_lock){
            $message =  "Signing key not initialized. Please send a tnbc to the corresponding account number to initialize";
            return $message;
        }

        if ($request->has('amount')){
            $amount = (int)$request->amount;
            $withdrawable_balance = $recipientAcc->balance - 2;
            if($amount == 0){
                return response(["message"=>'Can not withdraw 0 amount']);
            }
            elseif($amount > $withdrawable_balance){
                return response(["message"=>'you can withdraw maximum '.$withdrawable_balance.' tnbc']);
            }
            else{
                return response(["message"=>'Withdraw amount '.$amount.' would be transfered to your account soon.']);
            }
        }else{
            return "Insert withdraw amount";
        }
    }

    public function generate_block($balance_lock, $transactions, $signing_key){
        //
    }

        
    public function is_valid_key($key){
        # Check if signing key is valid hexadecimal
       
        # Check if the length of the key is 64
    }
    

    public function send_tnbc($bank_ip, $signing_key, $destination_account_number, $amount, $memo){
      
        $payment_account_number = '';
        $recipentId = auth()->user()->id;
        $recipientAcc = Account::where('user_id', $recipentId)->first();
        $payment_account_number = $recipientAcc->account_number;
        
        //bank configuration
        // $bank_ip = '54.183.16.194';
        $bank_ip = '20.98.98.0';
        $bank_protocol = 'http';
        $bank_config_url = $bank_protocol.'://'.$bank_ip.'/config?format=json';
        $bank_config = HttpUtilities::fetchUrl($bank_config_url);
        
        $balance_lock_url = $bank_config->primary_validator->protocol.'://'.$bank_config->primary_validator->ip_address.':'.$bank_config->primary_validator->port.'/accounts'.'/'.$payment_account_number.'/balance_lock?format=json';
        $balance_lock = HttpUtilities::fetchUrl($balance_lock_url)->balance_lock;

        if (!$balance_lock){
            $message =  "Signing key not initialized. Please send a tnbc to the corresponding account number to initialize";
            return $message;
        }

        $txs = [
            [
                'amount'=> $amount,
                'memo'=> $memo,
                'recipient'=> $recipientAcc->account_number,
            ],
            [
                'amount'=> (int)$bank_config->default_transaction_fee,
                'fee'=> 'BANK',
                'recipient'=> $bank_config->account_number
            ],
            [
                'amount'=> (int)$bank_config->primary_validator->default_transaction_fee,
                'fee'=> 'PRIMARY_VALIDATOR',
                'recipient'=> $bank_config->primary_validator->account_number
            ]
        ];

        $data = generate_block($balance_lock, $txs, $nacl_signing_key);

        $headers = [
            'Connection'=> 'keep-alive',
            'Accept'=> 'application/json, text/plain, */*',
            'User-Agent'=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) TNBAccountManager/1.0.0-alpha.43 Chrome/83.0.4103.122 Electron/9.4.0 Safari/537.36',
            'Content-Type'=> 'application/json',
        ];

        $response = HttpUtilities::fetchUrl('http://'.$bank_ip.'/blocks', $headers, $data);

        if ($response->status_code == 201){
            $success = True;
            $message = $response->json();
        }
        else{
            $message = $response;
        }
    }

}