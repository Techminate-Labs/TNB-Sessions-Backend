<?php

namespace App\Services;

//Interface
use App\Contracts\BaseRepositoryInterface;
use App\Contracts\FilterRepositoryInterface;

//Models
use App\Models\ActivityLog;
use App\Models\User;

class BaseServices{
    
    private $baseRepositoryInterface;
    private $filterRepositoryInterface;
    public $logModel = ActivityLog::class;
    public $userModel = User::class;

    public function __construct(
        BaseRepositoryInterface $baseRepositoryInterface,
        FilterRepositoryInterface $filterRepositoryInterface
    ){
        $this->baseRI = $baseRepositoryInterface;
        $this->filterRI = $filterRepositoryInterface;
    }

    public function logCreate($request)
    {
        $url = $request->fullURL();
        $splitUrl = explode('api/',$url);
        $activity = $splitUrl[1];
        
    	$method = $request->method();
    	$ip = $request->ip();
    	$agent = $request->header('user-agent');
    	$user_id = auth()->check() ? auth()->user()->id : 0;

        $this->baseRI->storeInDB(
            $this->logModel,
            [
                'subject' => $activity,
                'url' => $url,
                'method' => $method,
                'ip' => $ip,
                'agent' => $agent,
                'user_id' => $user_id
            ]
        );
    }

    public function getUserName($id){
        if($id == 0){
            return 'Unknown';
        }else{
            $user = $this->baseRI->findById($this->userModel, $id);
            return $user->name;
        }
    }
    
    public function authUser()
    {
        return $this->baseRI->findById($this->userModel, auth()->user()->id);
    }
}