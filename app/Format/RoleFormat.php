<?php

namespace App\Format;

class RoleFormat{
    public static function formatRoleList($role){
        return[
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions,
            'created_at'=>$role->created_at->diffForHumans(),
            'updated_at'=>$role->updated_at->diffForHumans()
        ];
    }
}