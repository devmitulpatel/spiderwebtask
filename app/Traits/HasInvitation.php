<?php

namespace App\Traits;

use App\Models\Meeting;
use App\Models\Role;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

trait HasInvitation
{

    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function invites()
    {
        return $this->belongsToMany(Meeting::class);
        return $this->hasMany(Role::class,'id','role_id');
    }





}
