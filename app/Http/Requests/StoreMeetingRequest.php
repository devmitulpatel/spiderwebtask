<?php

namespace App\Http\Requests;

use App\Repositories\Zoom\Enum\MeetingType;
use App\Repositories\Zoom\Enum\RecurrenceType;
use App\Repositories\Zoom\Meeting;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           //'type'=>''
            'topic'=>['required'],
            'date'=>['required'],
            'duration_in_hr'=>['required'],
            'duration_in_min'=>['required','numeric','min:9'],
            'recurrence_type'=>['required','numeric'],
            'recurrence_end_times'=>[],//['required'],
            'recurrence_end_date_time'=>[],//['required'],
            'recurrence_repeat_interval'=>['required'],
            'recurrence_monthly_week'=>[],//['required'],
            'recurrence_monthly_week_day'=>[],//['required'],
            'recurrence_weekly_days'=>[],
            'selectedUser'=>[]
        ];
    }

    public function presist()
    {
        $validated=$this->validated();
        $duration=$validated['duration_in_min'];
        if($validated['duration_in_hr']>0)$duration=$duration+($validated['duration_in_hr']*60);
        $input=[
            'date'=>Carbon::parse($validated['date']),
            //'agenda'=>$validated[''],
            'duration'=>$duration,
            'topic'=>$validated['topic'],
            'type'=> enum(MeetingType::RecurringMeetingFixedTime),
            'recurrence_type'=>$validated['recurrence_type']+1,
           // 'recurrence_end_date_time'=>format_for_zoom(Carbon::parse($validated['recurrence_end_date_time'])),
            //'recurrence_end_times'=>4,
            'recurrence_repeat_interval'=>$validated['recurrence_repeat_interval'],
            'recurrence_weekly_days'=>$validated['recurrence_weekly_days'],
//            'recurrence_monthly_week'=>enum(WeekHelper::FirstWeek),
//            'recurrence_monthly_week_day'=>enum(WeekDays::Sunday),

        ];


        if(array_key_exists('recurrence_end_date_time',$validated)&& $validated['recurrence_end_date_time']!=null){
            $input['recurrence_end_date_time']=Carbon::parse($validated['recurrence_end_date_time']);
        }
        if(array_key_exists('recurrence_end_times',$validated) && $validated['recurrence_end_times']!=null){
            $input['recurrence_end_times']=$validated['recurrence_end_times'];
        }
        //dd($input);

        return (new Meeting)->createMeet($input,$validated['selectedUser']);

    }
}
