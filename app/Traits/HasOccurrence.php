<?php

namespace App\Traits;


use App\Models\Occurrence;
use Illuminate\Support\Facades\Hash;

/**
 * @method static createMeeting()
 */

trait HasOccurrence
{


    public function occurrence(){
        return $this->belongsToMany(Occurrence::class);
    }

    public function scopeCreateMeeting($query,$data=[]){



        $tableData=[
            'uuid'=>$data->uuid,
            'zoom_meet_id'=>$data->id,
            'host_id'=>$data->host_id,
            'host_email'=>$data->host_email,
            'topic'=>$data->topic,
            'type'=>$data->type,
            'status'=>$data->status,
            'start_url'=>$data->start_url,
            'join_url'=>$data->join_url,
            'password'=>Hash::make($data->password),
            'encrypted_password'=>$data->encrypted_password,
            'h323_password'=>$data->h323_password,
        ];

        $occurrence= json_decode(json_encode($data->occurrences),true);

        $meeting=$query->create($tableData);

        $occurrences=$meeting->occurrence()->createMany($occurrence);

        return $query;

    }

}
