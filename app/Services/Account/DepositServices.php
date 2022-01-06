<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Account\DepositValidation;

//Models
use App\Models\Deposit;

class DepositServices extends BaseServices{

    private  $depositModel = Deposit::class;

    public function fetchUrl($url)
    {
        $response_json = file_get_contents($url);
        if(false !== $response_json) {
            try {
                $response = json_decode($response_json);
                if(http_response_code(200)) {
                    return $response;
                }
            }
            catch(Exception $e) {
                return [];
            }
        }
    }

    public function checkDeposits(){
        /**
         * Fetch bank transactions from bank
         * Insert new deposits into database
         */
        $app_pk = '8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74';
        $protocol = 'http';
        $bank = '20.98.98.0';
        $next_url = $protocol.'://'.$bank.'/bank_transactions?recipient='.$app_pk.'&ordering=-block__created_date';
        
        while($next_url) {
            $data = $this->fetchUrl($next_url);
            $bankTransactions = $data->results;
            $next_url = $data->next;
            // return $bankTransactions;

            foreach($bankTransactions as $bankTransaction){
                $deposit = $this->baseRI->storeInDB(
                    $this->depositModel,
                    [
                        'transaction_id'=> $bankTransaction->id,
                        'amount'=>$bankTransaction->amount,
                        'block_id'=>$bankTransaction->block->id,
                        'confimation_checks' => 0,
                        'account_confirmed'=>False,
                        'memo'=>$bankTransaction->memo,
                        'sender'=>$bankTransaction->block->sender,
                    ]
                );
            }
            
        }
    }

    public function depositCreate(){
        return $this->checkDeposits();
    }

    public function checkConfirmations(){
        /**
         * Check bank for confirmation status
         * Query unconfirmed deposits from database
         * http://54.183.16.194/bank_transactions?recipient=a5dbcded3501291743e0cb4c6a186afa2c87a54f4a876c620dbd68385cba80d0&ordering=-block__created_date
         * http://54.183.16.194/bank_transactions?recipient=8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74&ordering=-block__created_date
         */
        $app_pk = '8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74';
        $protocol = 'http';
        $bank = '20.98.98.0';
        $url = $protocol.'://'.$bank.'/bank_transactions?recipient='.$app_pk.'&ordering=-block__created_date';
        $fetch = Http::get($url);
    }
}