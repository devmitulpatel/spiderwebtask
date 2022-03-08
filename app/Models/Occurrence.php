<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    use HasFactory;


    protected $fillable=[
        'occurrence_id',
        'start_time',
        'duration',
        'status'

    ];

    public function setStartTimeAttribute($value){
        $this->attributes['start_time']=Carbon::parse($value);

    }

}
