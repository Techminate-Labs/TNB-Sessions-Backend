<?php

namespace App\Services\System;
use Illuminate\Support\Facades\Http;
//Service
use App\Services\BaseServices;

//Utilities
use App\Utilities\FileUtilities;

//Models
use App\Models\Configuration;

class ConfigServices extends BaseServices{
    public static $imagePath = 'images/logo';
    public static $explode_at = "logo/";
    private $configModel = Configuration::class;
    public function config()
    {
        $id = 1;
        $configuration = $this->baseRI->findById($this->configModel, $id);
        return $configuration;
    }

    public function configUpdate($request)
    {
        $this->logCreate($request);
        $id = 1;
        $configuration = $this->config();
        if($configuration){
            $fields = $request->validate([
                'app_name'=>'required|string',
            ]);
            $data = $request->all();
            $url  = url('');
            $exAppLogoPath = $configuration->app_logo;
            $appLogo = 'app_logo';
            //image upload
            $appLogo = FileUtilities::imageUpload($appLogo, $request, $url, self::$imagePath, self::$explode_at, $exAppLogoPath, true);
            $data['app_logo'] = $appLogo;
            $configuration->update($data);
            return response($configuration,200);
        }else{
            return response(["failed"=>'Configuration not found'],404);
        }
    }
}