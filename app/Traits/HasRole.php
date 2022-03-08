<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait HasRole
{

    public function assignRole($role){

        switch (gettype($role)){


            case 'integer':
                $this->update(['role_id'=>$role]);
                break;

            case 'string':
                break;

            case 'object':
                $this->roles()->associate($role);
                break;


        }


    }

    public function roles()
    {

        return $this->belongsToMany(Role::class);
        return $this->hasMany(Role::class,'id','role_id');
    }



}
