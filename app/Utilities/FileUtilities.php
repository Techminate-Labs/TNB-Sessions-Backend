<?php
namespace App\Utilities;
use Illuminate\Support\Facades\File;

class FileUtilities{
    public static function fileUpload($request, $url, $imagePath, $explode_at, $exImagePath, $is_update){
        if($request->hasFile('image')){
            $validated = $request->validate([
                'image'=>'required|mimes:jpeg,jpg,png',
            ]);
            $image = $request->file('image');
            
            $imgName = 'img'.time(). '.' .$image->getClientOriginalExtension();
            File::isDirectory($imagePath) or File::makeDirectory($imagePath, 0777, true, true);
            
            $image->move(public_path(env('REL_PUB_FOLD').$imagePath),$imgName);
            
            $fileLocation = $url. '/' .$imagePath . '/' . $imgName;
            $profileImage = $fileLocation;

            //remove existing image
            if($is_update){
                self::removeExistingFile($imagePath, $exImagePath, $explode_at);
            } 
        return $profileImage;
        }else{
            if($is_update){
                return $profileImage = $exImagePath;
            }
            else{
                return $profileImage = $url. '/' .$imagePath . '/' . 'default.jpg';
            }
        }
    }

    public static function imageUpload($attributeName, $request, $url, $imagePath, $explode_at, $exImagePath, $is_update){
        if($request->hasFile($attributeName)){
            $validated = $request->validate([
                $attributeName=>'required|mimes:jpeg,jpg,png',
            ]);
            $image = $request->file($attributeName);
            
            $imgName = 'img'.time().rand(10,1000).'.'.$image->getClientOriginalExtension();
            File::isDirectory($imagePath) or File::makeDirectory($imagePath, 0777, true, true);
            
            $image->move(public_path(env('REL_PUB_FOLD').$imagePath),$imgName);
            
            $fileLocation = $url. '/' .$imagePath . '/' . $imgName;
            $profileImage = $fileLocation;

            //remove existing image
            if($is_update){
                self::removeExistingFile($imagePath, $exImagePath, $explode_at);
            } 
        return $profileImage;
        }else{
            if($is_update){
                return $profileImage = $exImagePath;
            }
            else{
                return $profileImage = $url. '/' .$imagePath . '/' . 'default.jpg';
            }
        }
    }

    public static function removeExistingFile($imagePath, $exImagePath, $explode_at){
        $splitImg = explode($explode_at,$exImagePath);
        $storageImg = $splitImg[1];
        if($storageImg !== "default.jpg" && $storageImg !== "default.png"){
            $image_path = public_path(env('REL_PUB_FOLD').$imagePath)."/".$storageImg;  
            if(File::exists($image_path)) {
                File::delete($image_path);
            }
        }
    }
}