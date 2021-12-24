<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Service
use App\Services\User\ProfileServices;

class ProfileController extends Controller
{
    private $profileServices;

    public function __construct(ProfileServices $profileServices){
        $this->service = $profileServices;
    }

    public function userProfileCreate(Request $request)
    {
        return $this->service->userProfileCreate($request);
    }

    public function userProfileUpdate(Request $request, $id)
    {
        return $this->service->userProfileUpdate($request, $id);
    }

    public function userProfileDelete($id)
    {
        return $this->service->userProfileDelete($id);
    }

    public function userProfileGetById($id){
        return $this->service->userProfileGetById($id);
    }

    public function profileSettingPhotoUpdate(Request $request)
    {
        return $this->service->profileSettingPhotoUpdate($request);
    }

    public function profileSettingPasswordUpdate(Request $request)
    {
        return $this->service->profileSettingPasswordUpdate($request);
    }
}
