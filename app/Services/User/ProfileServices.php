<?php

namespace App\Services\User;

//Hash
use Illuminate\Support\Facades\Hash;

//Rules
use App\Rules\MatchOldPassword;

//Model
use App\Models\Profile;

//Services
use App\Services\BaseServices;

//Format
use App\Format\UserFormat;

//Utilities
use App\Utilities\FileUtilities;

class ProfileServices extends BaseServices{

    public static $imagePath = 'images/profile';
    public static $explode_at = "profile/";
    private $profileModel = Profile::class;

    public function userProfileGetById($id){
        $profile = $this->baseRI->findById($this->profileModel, $id);
        if($profile){
            return $profile;
        }else{
            return response(["failed"=>'Profile not found'],404);
        }
    }

    public function userProfileCreate($request){
        $this->logCreate($request);
        $request->validate([
            'mobile'=>'required',
            'present_address'=>'required|min:3',
            'identity_number'=>'required|numeric',
            'user_id'=>'required',
        ]);

        $data = $request->all();
        $url  = url('');
        
        //image upload
        $profileImage = FileUtilities::fileUpload($request, $url, self::$imagePath, self::$explode_at, false, false);
        $data['image'] = $profileImage;

        $profile = $this->baseRI->storeInDB($this->profileModel, $data);
        return response($profile,201);
    }

    public function userProfileUpdate($request, $id){
        $this->logCreate($request);
        $profile = $this->baseRI->findById($this->profileModel, $id);
        if($profile){
            $request->validate([
                'mobile'=>'required',
                'present_address'=>'required|min:3',
                'identity_number'=>'required|numeric',
                'user_id'=>'required',
            ]);
            $data = $request->all();
            $exImagePath = $profile->image;
            $url  = url('');
            
            //image upload
            $profileImage = FileUtilities::fileUpload($request, $url, self::$imagePath, self::$explode_at, $exImagePath, true);
            $data['image'] = $profileImage;

            $profile->update($data);
            return response($profile,201);
        }else{
            return response(["failed"=>'Profile not found'],404);
        }
    }

    public function userProfileDelete($id){
        $this->logCreate($request);
        $profile = $this->baseRI->findById($this->profileModel, $id);
        if($profile){
            $exImagePath = $profile->image;
            FileUtilities::removeExistingFile(self::$imagePath, $exImagePath, self::$explode_at);
            $profile->delete();
            return response(["done"=>'Profile Deleted Successfully'],200);
        }else{
            return response(["failed"=>'Profile not found'],404);
        }
    }

    public function profileSettingPhotoUpdate($request){
        $this->logCreate($request);
        $user = $this->authUser();
        $profile = $user->profile;
        if($profile){
            $data = $request->all();
            $exImagePath = $profile->image;
            $url  = url('');
            
            //image upload
            $profileImage = FileUtilities::fileUpload($request, $url, self::$imagePath, self::$explode_at, $exImagePath, true);
            
            $profile->image = $profileImage;
            $profile->save();

            return response(["image"=>$profileImage],201);
        }else{
            return response(["failed"=>'Profile not found'],404);
        }
    }

    public function profileSettingPasswordUpdate($request){
        $this->logCreate($request);
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $this->authUser()->update(['password'=> Hash::make($request->new_password)]);
        // auth()->user()->tokens()->delete();
        return response(["done"=>'Password changed successfully'],200);
    }
}