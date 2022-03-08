<?php

namespace App\Models;

use App\Traits\HasInvitation;
use App\Traits\HasOccurrence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Meeting extends Model
{
    protected  $fillable=[
        'uuid',
        'zoom_meet_id',
        'host_id',
        'host_email',
        'topic',
        'type',
        'status',
        'start_url',
        'join_url',
        'password',
        'encrypted_password',
        'h323_password'
    ];


    use HasFactory,HasOccurrence,HasInvitation;
}
