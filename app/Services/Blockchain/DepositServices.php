<?php

namespace App\Services\Blockchain;
use Illuminate\Support\Facades\Http;
//Services
use App\Services\BaseServices;

//Models
use App\Models\Deposit;

class DepositServices extends BaseServices{

    public function registerPK($request){
        $account_number = $request->account_number;
        $verification_code = 'TNBS_'.date('md').'_'.date('is').mt_rand(10,100);
        return[
            'account_number' => $account_number,
            'verification_code' => $verification_code
        ];
    }

    public function fetchUrl($url)
    {
        // $currency = 'BDT';
        $req_url = 'https://open.er-api.com/v6/latest/'.$currency;
        $response_json = file_get_contents($req_url);
        if(false !== $response_json) {
            try {
                $response = json_decode($response_json);
                if('success' === $response->result) {
                    return $response->rates->USD;
                }
            }
            catch(Exception $e) {
                return [];
            }
        }
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

    public function checkDeposits(){
        /**
         * Fetch bank transactions from bank
         * Insert new deposits into database
         */
        $app_pk = '8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74';
        $protocol = 'http';
        $bank = '20.98.98.0';
        $next_url = $protocol.'://'.$bank.'/bank_transactions?recipient='.$app_pk.'&ordering=-block__created_date';
        $fetch = Http::get($url);
    }
}