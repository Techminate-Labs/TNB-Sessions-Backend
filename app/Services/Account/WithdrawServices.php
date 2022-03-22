<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;

//Models
use App\Models\Account;
use App\Models\Withdraw;

//Utilities
use App\Utilities\HttpUtilities;

class WithdrawServices extends BaseServices{

    private  $accountModel = Account::class;
    private  $withdrawModel = Withdraw::class;

    public function withdraw($request){
        $recipentId = auth()->user()->id;
        $recipientAcc = Account::where('user_id', $recipentId)->first();

        // $bank_ip = '54.183.16.194';
        $bank_ip = '20.98.98.0';
        $bank_protocol = 'http';
        $bank_config_url = $bank_protocol.'://'.$bank_ip.'/config?format=json';
        $bank_config = HttpUtilities::fetchUrl($bank_config_url);
        
        $balance_lock_url = $bank_config->primary_validator->protocol.'://'.$bank_config->primary_validator->ip_address.':'.$bank_config->primary_validator->port.'/accounts'.'/'.$recipientAcc->account_number.'/balance_lock?format=json';
        $balance_lock = HttpUtilities::fetchUrl($balance_lock_url)->balance_lock;

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
                $withdraw_exist = Withdraw::where("user_id", $recipentId)->Where("status","pending")->first();
                if(! $withdraw_exist){
                    $withdraw = $this->baseRI->storeInDB(
                        $this->withdrawModel,
                        [
                            'user_id'=>$recipentId,
                            'account_number'=>$recipientAcc->account_number,
                            'account_balance'=>$recipientAcc->balance,
                            'withdrawable_balance'=>$withdrawable_balance,
                            'withdraw_amount'=>$amount,
                            'status'=>'pending',
                            'new_balance'=>$withdrawable_balance - $amount
                        ]
                    );
                }else{
                    $withdraw_exist->withdraw_amount = $amount;
                    $withdraw_exist->new_balance = $withdrawable_balance - $amount;
                    $withdraw_exist->save();
                    return $withdraw_exist;
                }
                return response([
                    "message"=>'Withdraw amount '.$amount.' would be transfered to your account soon.',
                    "info"=>$withdraw
                ]);
            }
        }else{
            return "Insert withdraw amount";
        }
    }
}