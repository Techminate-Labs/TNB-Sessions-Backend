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

        if ($request->has('amount')){
            $amount = (int)$request->amount;
            $withdrawable_balance = $recipientAcc->balance - 2;
            $new_balance = $withdrawable_balance - $amount;

            if($amount == 0){
                return response(["message"=>'Can not withdraw 0 amount']);
            }
            elseif($amount > $withdrawable_balance){
                return response(["message"=>'you can withdraw maximum '.$withdrawable_balance.' tnbc']);
            }
            else{
                $withdraw_exist = Withdraw::where("user_id", $recipentId)->Where("status","pending")->first();
                if(!$withdraw_exist){
                    $withdraw = $this->baseRI->storeInDB(
                        $this->withdrawModel,
                        [
                            'user_id'=>$recipentId,
                            'account_number'=>$recipientAcc->account_number,
                            'account_balance'=>$recipientAcc->balance,
                            'withdrawable_balance'=>$withdrawable_balance,
                            'withdraw_amount'=>$amount,
                            'status'=>'pending',
                            'new_balance'=>$new_balance
                        ]
                    );
                }else{
                    $withdraw_exist->withdraw_amount = $amount;
                    $withdraw_exist->new_balance = $withdrawable_balance - $amount;
                    $withdraw_exist->save();
                }
                //make an api call to send tnbc service
                return response([
                    "message"=>'Withdraw amount '.$amount.' would be transfered to your account soon.'
                ]);
            }
        }else{
            return "Insert withdraw amount";
        }
    }

    public function updateWithdrawStatus($request, $id){
        $recipentId = auth()->user()->id;
        $recipientAcc = Account::where('user_id', $recipentId)->first();
        $withdraw_req = $this->baseRI->findById($this->$withdrawModel, $id);
        if($withdraw_req){
            if($request->status == "completed"){
                $recipientAcc->balance = $new_balance;
                $recipientAcc->save();
                $withdraw_req->status = "completed";
                $withdraw_req->save();
            }else{
                $withdraw_req->status = $request->status;
                $withdraw_req->save();
            }
            return response([
                "message"=>'Successful'
            ]);
        }else{
            return response([
                "message"=>'Try again'
            ]);
        }
        
    }
}