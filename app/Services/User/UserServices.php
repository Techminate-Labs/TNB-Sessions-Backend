<?php

namespace App\Services\User;

//Services
use App\Services\BaseServices;

//Format
use App\Format\UserFormat;

class UserServices extends BaseServices{
    public function userList($request){
        if ($request->has('q')){
            $users = $this->filterRI->filterBy2PropPaginated($this->userModel, $request->q, $request->limit, 'name', 'email');
        }else{
            $users = $this->baseRI->listWithPagination($this->userModel, $request->limit);
        }
        if($users){
            return $users->through(function($user){
                return UserFormat::formatList($user);
            });
        }else{
            return response(["message"=>'User not found'],404);
        }
    }

    public function userProfileView($id){
        $user = $this->baseRI->findById($this->userModel, $id);
        if($user){
            return UserFormat::formatUserProfile($user);
        }else{
            return response(["message"=>'User not found'],404);
        }
    }

    public function userGetById($id){
        $user = $this->baseRI->findById($this->userModel, $id);
        if($user){
            return UserFormat::formatList($user);
        }else{
            return response(["message"=>'User not found'],404);
        }
    }

    public function userUpdate($request, $id){
        $user = $this->baseRI->findById($this->userModel, $id);
        if($user){
            $data = $request->all();
            if($user->email==$data['email']){
                $request->validate([
                    'name'=>'required',
                    'email'=>'required|string|email|max:255',
                    'role_id'=>'required'
                ]);
            }
            else{
                $request->validate([
                    'name'=>'required',
                    'email'=>'required|string|email|max:255|unique:users',
                    'role_id'=>'required',
                ]);
            }
            $user->update($data);
            return response($user,201);
        }else{
            return response(["message"=>'User not found'],404);
        }
    }

    public function userDelete($id){
        $user = $this->baseRI->findById($this->userModel, $id);
        if($user){
            $user->delete();
            return response()->json(["message"=>'Delete successfull !'],200);
        }else{
            return response(["message"=>'User not found'],404);
        }
    }
}
