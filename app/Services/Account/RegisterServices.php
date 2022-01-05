<?php

namespace App\Services\Account;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Account\AccountValidation;

//Models
use App\Models\Tempregister;

class RegisterServices extends BaseServices{

    private $registerModel = Tempregister::class;
    // a5dbcded3501291743e0cb4c6a186afa2c87a54f4a876c620dbd68385cba80d0
    // 8b9706ccfcaa58b20208a7121f869d9429d63cd90c4b6a380ce91f2b3132b05a
    // 8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74

    public function registerPK($request){
        //check required
        $fields = AccountValidation::validate1($request);
        $account_number = $fields['account_number'];
        $agentPk = '8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74';
        //check valid address
        $validatePK = AccountValidation::validate2($account_number);
        if (!$validatePK){
            return response(["message"=>'account number is invalid']);
        }
        $userId = auth()->user()->id;
        //check if user already has an registered account
        //
        //check if user is already on registraton process
        $userExist = $this->filterRI->filterBy1PropFirst(Tempregister::class, $userId, 'user_id');
        if($userExist) {
            $response = [
                'message'=> 'Registration on process',
                'account_number'=>$account_number,
                'agent_account'=>$agentPk,
                'memo' => $userExist->verification_code
            ];
            //check if request pk is already registered
            if($account_number === $userExist->account_number){
                return response($response,201);
            }else{
                //update existing account
                $userExist->account_number = $account_number;
                $userExist->save();
                return response($response,201);
            }
        }else{
            //create new account
            $verification_code = 'TNBS_'.date('md').'_'.date('is').mt_rand(10,100);
            $register = $this->baseRI->storeInDB(
                $this->registerModel,
                [
                    'user_id' => $userId,
                    'account_number' => $account_number,
                    'verification_code' => $verification_code
                ]
            );

            if($register){
                $response = [
                    'message'=> 'Registration on process',
                    'account_number'=>$account_number,
                    'agent_account'=>$agentPk,
                    'memo' => $verification_code
                ];
                return response($response,201);
            }else{
                return response(["failed"=>'Server Error'],500);
            }
        }
    }
}