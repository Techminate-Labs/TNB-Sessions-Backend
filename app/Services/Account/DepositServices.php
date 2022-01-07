<?php

namespace App\Services\Account;
use Illuminate\Support\Facades\Http;

//Services
use App\Services\BaseServices;
use App\Services\Validation\Account\DepositValidation;

//Models
use App\Models\Deposit;
use App\Models\Tempregister;

class DepositServices extends BaseServices{

    /**
     * http://54.183.16.194/bank_transactions?recipient=a5dbcded3501291743e0cb4c6a186afa2c87a54f4a876c620dbd68385cba80d0&ordering=-block__created_date
     * http://54.183.16.194/bank_transactions?recipient=8c44cb32b7b0394fe7c6a8c1778d19d095063249b734b226b28d9fb2115dbc74&ordering=-block__created_date
     * http://54.183.16.194/confirmation_blocks?block=aa5c09a7-c573-4dd1-b06b-b123eb5880ff
     * http://54.183.16.194/confirmation_blocks?block=&block__signature=a44d171d9f0ba0f6d0c0c489b9a17d24dd38734a4428fdf85ea08e1ca821086dae6601d20c3a0bcfc94e73b3f77092026d179391752702dd76adf38c50b8cb06
     */
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

            foreach($bankTransactions as $bankTransaction){
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
            return response(["message"=>'ok'],201);
        }
    }

    public function depositCreate(){
        return $this->checkDeposits();
    }

    public function handleDepositConfirmation($deposit){
        /**
         * Update confirmation status of deposit
         * Increase users balance or create new user if they don't already exist
        */
        $deposit->is_confirmed = true;
        $deposit->save();

        //check register model
        $registation = Tempregister::where('account_number',$deposit->sender)->where('verification_code', $deposit->memp)->first();
        //create account
        if($registation) {
           return 'create new account';
        }else{
            return 'update account information';
        }
        //update account
    }

    public function increaseConfirmationCheck($deposit){
        /**
         * Increment the number of confirmation checks for the given deposit
        */
        $deposit->confirmation_checks +=1;
        $deposit->save();
    }

    public function checkConfirmations(){
        /**
         * Check bank for confirmation status
         * Query unconfirmed deposits from database
         */
        $maxConfirmationChecks = 15;
        $protocol = 'http';
        $bank = '54.183.16.194';
        
        $unconfirmedDeposits = Deposit::where('is_confirmed',0)->where('confirmation_checks', '<', $maxConfirmationChecks)->get();
        foreach($unconfirmedDeposits as $deposit){
            $blockId = $deposit->block_id;
            $url = $protocol.'://'.$bank.'/confirmation_blocks?block='.$blockId;
            $data = $this->fetchUrl($url);
            $confirmation = $data->count;
            if($confirmation){
                #businesss logics 
                $this->handleDepositConfirmation($deposit);
            }else{
                $this->increaseConfirmationCheck($deposit);
            }
        }
        // return $unconfirmedDeposits;
        // return $blockId;
    }

    public function pullBlockchain(){
        /**
        * Poll blockchain for new transactions/deposits sent to the bot account
        * Only accept confirmed transactions
        */
        $this->checkDeposits();
        $this->checkConfirmations();
    }
}