<?php

namespace App\Services\System;

//Service
use App\Services\BaseServices;

class ActivityLogServices extends BaseServices{

    private function formatLog($log){
        return[
            'id' => $log->id,
            'date' => $log->created_at->diffForHumans(),
            'user' => $this->getUserName($log->user_id),
            'method' => $log->method,
            'url' => $log->url,
            'ip' => $log->ip,
            'agent' => $log->agent,
            'subject' => $log->subject,
        ];
    }

    public function logList($request)
    {
        $logs = $this->baseRI->listWithPagination($this->logModel, $request->limit);
        if($logs){
            return $logs->through(function($log){
                return $this->formatLog($log);
            });
        }else{
            return response(["message"=>'Data not found'],404);
        }
    }
}