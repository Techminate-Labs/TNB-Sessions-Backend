<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Account\DepositValidation;

//Models
use App\Models\Deposit;
use App\Models\Tempregister;
use App\Models\Account;
use App\Models\Scantracker;

//Utilities
use App\Utilities\HttpUtilities;

class DepositServices extends BaseServices{

    private  $depositModel = Deposit::class;
    private  $registerModel = Tempregister::class;
    private  $accountModel = Account::class;
    private  $scanTrackerModel = Scantracker::class;

    public function deleteConfirmedDeposits($scanTracker){
        // where time less than scanTracker
        // where is_confirmed is true
        $oldDeposits = Deposit::orderBy('id', 'DESC')
        ->where('created_at', '<', $scanTracker->last_scanned)
        ->where('is_confirmed', 1)
        ->get();
        foreach($oldDeposits as $deposit){
            $deposit->delete();
        }
    }

    public function saveDeposit($bankTransaction){
        $deposit = $this->baseRI->storeInDB(
            $this->depositModel,
            [
                'transaction_id'=> $bankTransaction->id,
                'amount'=>$bankTransaction->amount,
                'block_id'=>$bankTransaction->block->id,
                'confirmation_checks' => 0,
                'is_confirmed'=> false,
                'memo'=>$bankTransaction->memo,
                'sender'=>$bankTransaction->block->sender,
            ]
        );
    }

    public function storeDeposits(){
        /**
         * Fetch bank transactions from bank
         * Insert new deposits into database
         */
        $scanTracker = Scantracker::orderBy('id', 'DESC')->first();
        $protocol = 'http';
        // $app_pk = '9c22ee4f664e7167f9a67f2a882240de6e34ee61a01af7ce8995ad74958b81e8';
        // $bank = '54.183.16.194';

        $app_pk = '8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74';
        $bank = '20.98.98.0';
        $next_url = $protocol.'://'.$bank.'/bank_transactions?recipient='.$app_pk.'&ordering=-block__created_date';
        
        while($next_url) {
            $data = HttpUtilities::fetchUrl($next_url);
            $bankTransactions = $data->results;
            $next_url = $data->next;

            foreach($bankTransactions as $bankTransaction){
                if($scanTracker){
                    $lastScanned = strtotime($scanTracker->last_scanned);
                    $transactionTime = strtotime($bankTransaction->block->created_date);
                    $transactionExist = Deposit::where('transaction_id',$bankTransaction->id)->first();
                    if($transactionTime < $lastScanned){
                        print("breaking the loop \n");
                        break;
                    }elseif($transactionExist){
                        print("transaction exits \n");
                        continue; 
                    }else{
                        print("added new deposit \n");
                        $this->saveDeposit($bankTransaction);
                    }
                }else{
                    $this->saveDeposit($bankTransaction);
                }
            }
            
            $lastDepositId = Deposit::orderBy('id', 'DESC')->first();
            
            if($scanTracker){
                $scanTracker->last_scanned = $lastDepositId->created_at;
                $scanTracker->save();
                $this->deleteConfirmedDeposits($scanTracker);
                return response(["message"=>"data pulled"],201);
            }else{
                $scanTracker = $this->baseRI->storeInDB(
                    $this->scanTrackerModel,
                    [
                        'last_scanned'=> $lastDepositId->created_at
                    ]
                );
                $this->deleteConfirmedDeposits($scanTracker);
                return response(["message"=>"data pulled"],201);
            }
        }
    }

    public function businessLogics($deposit){
        /**
         * Update confirmation status of deposit
         * Increase users balance or create new user if they don't already exist
        */
        $deposit->is_confirmed = true;
        $deposit->save();

        //check register model
        $requestRegistration = Tempregister::where('account_number',$deposit->sender)->where('verification_code', $deposit->memo)->first();
        if($requestRegistration){
            //create new account
            $account = $this->baseRI->storeInDB(
                $this->accountModel,
                [
                    'user_id' => auth()->user()->id,
                    'account_number' => $requestRegistration->account_number,
                    'balance' => 0
                ]
            );
            if($account){
                $requestRegistration->delete();
            }
        }else{
            $account = Account::where('account_number',$deposit->sender)->first();
            if($account){
                $account->balance = $account->balance + $deposit->amount;
                $account->save();
            }
        }
    }

    public function checkConfirmations(){
        /**
         * Check bank for confirmation status
         * Query unconfirmed deposits from database
         */
        $maxConfirmationChecks = 20;
        $protocol = 'http';
        // $bank = '54.183.16.194';
        $bank = '20.98.98.0';
        
        $unconfirmedDeposits = Deposit::where('is_confirmed',0)->where('confirmation_checks', '<', $maxConfirmationChecks)->get();
        foreach($unconfirmedDeposits as $deposit){
            // $blockId = $deposit->block_id;
            // $url = $protocol.'://'.$bank.'/confirmation_blocks?block='.$blockId;
            // $data = HttpUtilities::fetchUrl($url);
            // $confirmation = $data->count;
            $confirmation = 1;
            if($confirmation){
                $this->businessLogics($deposit);
                return "ok";
            }else{
                $deposit->confirmation_checks +=1;
                $deposit->save();
                return "waiting for confirmation";
            }
        }
        // return $unconfirmedDeposits;
        // return $blockId;
    }

    public function pullBlockchain(){
        /**
        * Poll blockchain for new transactions/deposits sent to the account
        * Only accept confirmed transactions
        */
        $this->storeDeposits();
        $this->checkConfirmations();
    }
}