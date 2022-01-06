<?php

namespace App\Services\Validation\Account;

class AccountValidation{
    public static function validate1($request){
        return $request->validate([
            'account_number'=>'required|string',
        ]);
    }

    public static function validate2($pk){
        if (strlen($pk) !== 64){
            return false;
        }elseif(! hex2bin($pk)){
            return false;
        }else{
            return true;
        }
    }
}