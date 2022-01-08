<?php
namespace App\Utilities;

class HttpUtilities{
    
    public static function fetchUrl($url){
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
}