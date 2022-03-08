<?php

namespace App\Repositories\Zoom;

use App\Repositories\Zoom\Enum\MeetingType;
use App\Repositories\Zoom\Enum\WeekHelper;
use App\Repositories\Zoom\Enum\RecurrenceType;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Meeting
{


    private mixed $baseUrl;
    private string $apiVersion;
    private string $apiLevel1;
    private string $api_username;
    private string $api_password;
    private string $bearerToken;


    public function __construct()
    {
        $this->baseUrl = env('ZOOM_API_ENDPOINT');
        $this->apiVersion = 'v2';
        $this->apiLevel1 = 'users';
        $this->api_username = env('ZOOM_API_KEY_ID');
        $this->api_password = env('ZOOM_API_KEY_SECRET');
        $this->bearerToken = env('ZOOM_API_JWT_TOKEN');

    }




    public function createMeet($input,$users=[]){



//        $data=[
//            'agenda' => $input['agenda'],
//            'default_password' => false,
//            'duration' => $input['duration'],
//            'password' => 'string',
//            'pre_schedule' => false,
//            'recurrence' => [
//                'end_date_time' => '2019-08-24T14:15:22Z',
//                'end_times' => 1,
//                'monthly_day' => 1,
//                'monthly_week' => -1,
//                'monthly_week_day' => 1,
//                'repeat_interval' => 0,
//                'type' => 1,
//                'weekly_days' => '1',
//            ],
//            'schedule_for' => 'string',
//            'settings' => [
//                'additional_data_center_regions' => [
//                    0 => 'string',
//                ],
//                'allow_multiple_devices' => true,
//                'alternative_hosts' => 'string',
//                'alternative_hosts_email_notification' => true,
//                'approval_type' => 0,
//                'approved_or_denied_countries_or_regions' => [
//                    'approved_list' => [
//                        0 => 'string',
//                    ],
//                    'denied_list' => [
//                        0 => 'string',
//                    ],
//                    'enable' => true,
//                    'method' => 'approve',
//                ],
//                'audio' => 'both',
//                'authentication_domains' => 'string',
//                'authentication_exception' => [
//                    0 => [
//                        'email' => 'user@example.com',
//                        'name' => 'string',
//                    ],
//                ],
//                'authentication_option' => 'string',
//                'auto_recording' => 'local',
//                'breakout_room' => [
//                    'enable' => true,
//                    'rooms' => [
//                        0 => [
//                            'name' => 'string',
//                            'participants' => [
//                                0 => 'string',
//                            ],
//                        ],
//                    ],
//                ],
//                'calendar_type' => 1,
//                'close_registration' => false,
//                'cn_meeting' => false,
//                'contact_email' => 'string',
//                'contact_name' => 'string',
//                'email_notification' => true,
//                'encryption_type' => 'enhanced_encryption',
//                'focus_mode' => true,
//                'global_dial_in_countries' => [
//                    0 => 'string',
//                ],
//                'host_video' => true,
//                'in_meeting' => false,
//                'jbh_time' => 0,
//                'join_before_host' => false,
//                'language_interpretation' => [
//                    'enable' => true,
//                    'interpreters' => [
//                        0 => [
//                            'email' => 'user@example.com',
//                            'languages' => 'string',
//                        ],
//                    ],
//                ],
//                'meeting_authentication' => true,
//                'meeting_invitees' => [
//                    0 => [
//                        'email' => 'user@example.com',
//                    ],
//                ],
//                'mute_upon_entry' => false,
//                'participant_video' => true,
//                'private_meeting' => true,
//                'registrants_confirmation_email' => true,
//                'registrants_email_notification' => true,
//                'registration_type' => 1,
//                'show_share_button' => true,
//                'use_pmi' => false,
//                'waiting_room' => true,
//                'watermark' => false,
//            ],
//            'start_time' => '2019-08-24T14:15:22Z',
//            'template_id' => 'string',
//            'timezone' => 'string',
//            'topic' => 'string',
//
//            'type' => 1,
//        ];


        $data=[
            "topic" => $input['topic'],
            "type" => $input['type'],
            "start_time" => $input['date']->format('Y-m-d\TH:i:s.000\Z'),
            "duration" => $input['duration'],                       // 30 minutes
            "password" => "123456",
            'timezone'=>config('app.timezone'),
            'recurrence'=>$this->processRecurrence($input)
        ];


       // dd($data);
        $url=$this->makeUrl(['me','meetings']);

        $response=$this->client($url,$data);
       // dd($response->object());
       // dd($response->body());
        $meeting=\App\Models\Meeting::createMeeting($response->object());
        $meeting->first()->users()->sync($users);
        return redirect()->route('form');

    }

    private function processRecurrence($input):array
    {

        $recurrence =[];

        if($input['type']==enum(MeetingType::RecurringMeetingFixedTime) || $input['type']==enum(MeetingType::RecurringMeetingNoFixedTime)){

            $enom=RecurrenceType::class;
            $recurrence['type']=$input['recurrence_type'];
            switch($input['recurrence_type']){
                case $enom::Daily->value:

                    break;

                case $enom::Weekly->value:
                    if(array_key_exists('recurrence_weekly_days',$input))
                        $recurrence['weekly_days']=(is_array($input['recurrence_weekly_days']))
                            ?implode(',',$input['recurrence_weekly_days'])
                            :$input['recurrence_weekly_days'];
                    break;

                case $enom::Monthly->value:
                    if(array_key_exists('recurrence_monthly_week',$input))
                        $recurrence['monthly_week']=$input['recurrence_monthly_week'];
                    if(array_key_exists('recurrence_monthly_week_day',$input))
                        $recurrence['monthly_week_day']=$input['recurrence_monthly_week_day'];
                    break;
            }


            if(array_key_exists('recurrence_end_date_time',$input))$recurrence['end_date_time']=$input['recurrence_end_date_time'];
            if(array_key_exists('recurrence_end_times',$input) )$recurrence['end_times']=$input['recurrence_end_times'];

            //dd($recurrence);
            if(array_key_exists('recurrence_repeat_interval',$input)) {
                $recurrence['repeat_interval'] =(int) $input['recurrence_repeat_interval'];

                switch($input['recurrence_type']){
                    case $enom::Daily->value:
                        if($recurrence['repeat_interval']>90)throw new Exception('Repeat Interval should be less than 90 Days');
                        break;

                    case $enom::Weekly->value:
                        if($recurrence['repeat_interval']>12)throw new Exception('Repeat Interval should be less than 13 Weeks');
                        break;

                    case $enom::Monthly->value:
                        if($recurrence['repeat_interval']>3)throw new Exception('Repeat Interval should be less than 3 Months');
                        break;
                }

            }
        }

        //dd($recurrence);
        return $recurrence;

    }

    private function makeUrl($url='')
    {

        if (is_array($url))$url=implode('/',$url);

        return implode('/', [$this->baseUrl,$this->apiVersion, $this->apiLevel1,$url]);
    }

    private function client($url, $data = [], $type = 'post'): Response
    {
        return Http::contentType("application/json")->withToken($this->bearerToken)->$type($url, $data);
    }

    private function setApiLevel($val = '', $level = 1)
    {
        $base = 'apiLevel';
        $final = implode('', [$base, $level]);
        $this->$final = $val;
    }


}
