<?php

namespace App\Services\User;

//Services
use App\Services\BaseServices;

//Format
use App\Format\RoleFormat;

//Models
use App\Models\Role;

class RoleServices extends BaseServices{
    private $roleModel = Role::class;

    public function roleList($request){
        $this->logCreate($request);
        if ($request->has('q')){
            $roles = $this->filterRI->filterBy1PropPaginated($this->roleModel, $request->q, $request->limit, 'name');
        }else{
            $roles = $this->baseRI->listWithPagination($this->roleModel, $request->limit);
        }
        if($roles){
            return $roles->through(function($role){
                return RoleFormat::formatRoleList($role);
            });
        }else{
            return response(["message"=>'Role not found'],404);
        }
    }

    public function roleGetById($request, $id){
        $this->logCreate($request);
        $role = $this->baseRI->findById($this->roleModel, $id);
        if($role){
            return $role;
        }else{
            return response(["message"=>'Role not found'],404);
        }
    }

    public function roleCreate($request){
        $this->logCreate($request);
        $request->validate([
            'name'=>'required',
            'permissions'=>'required'
        ]);
        $data = $request->all();

        $role = $this->baseRI->storeInDB($this->roleModel, $data);
        return response($role,201);
    }

    public function roleUpdate($request, $id){
        $this->logCreate($request);
        $request->validate([
            'name'=>'required',
            'permissions'=>'required'
        ]);
        $role = $this->baseRI->findById($this->roleModel, $id);
        if($role){
            $data = $request->all();
            $role->update($data);
            return response($role,201);
        }else{
            return response(["message"=>'Role not found'],404);
        }
    }

    public function roleDelete($request, $id){
        $this->logCreate($request);
        $role = $this->baseRI->findById($this->roleModel, $id);
        if($role){
            $role->delete();
            return response(["message"=>'Delete Successfull'],200);
        }else{
            return response(["message"=>'Role not found'],404);
        }
    }

}
