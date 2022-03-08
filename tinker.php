<?php

use App\Models\Role;
use App\Models\User;
use App\Repositories\Zoom\Enum\MeetingType;
use App\Repositories\Zoom\Enum\WeekHelper;
use App\Repositories\Zoom\Enum\RecurrenceType;
use App\Repositories\Zoom\Enum\WeekDays;


enum(WeekDays::Sunday);
//dd(now()->format('Y-m-d\TH:i:s.000\Z'));

$input=[
    'date'=>now()->addDay(1),
    'agenda'=>'Description',
    'duration'=>30,
    'topic'=>'Meeting Topic',
    'type'=> enum(MeetingType::RecurringMeetingFixedTime),
//    'recurrence'=> [
//        'type'=> enum(RecurrenceType::Daily),
//        'end_date_time'=>format_for_zoom(now()->addDays(15)),
//    ],
    'recurrence_type'=>enum(RecurrenceType::Weekly),
   // 'recurrence_end_date_time'=>format_for_zoom(now()->addDays(3)),
    'recurrence_end_times'=>4,
    'recurrence_repeat_interval'=>3,
  //  'recurrence_weekly_days'=>[enum(WeekDays::Sunday),enum(WeekDays::Monday)],
    'recurrence_monthly_week'=>enum(WeekHelper::FirstWeek),
    'recurrence_monthly_week_day'=>enum(WeekDays::Sunday),



];
dd((new App\Repositories\Zoom\Meeting)->createMeet($input));

$out=[
    "topic" => "Meeting Topic",
  "type" => 8,
  "start_time" => "2022-03-09T12:57:31.000Z",
  "duration" => 30,
  "password" => "123456",
  "timezone" => "Asia/Kolkata",
  "recurrence" =>  [
    "type" => 2,
    "end_times" => 4,
    "repeat_interval" => 3,
  ]
];


$user=User::class;

$TestUser=$user::with(['roles'])->get()->first();
dd($TestUser->roles);
$TestUser->update([
    'role_id'=>Role::whereKey(2)->first()
]);
//$TestUser->role_id=Role::whereKey(2)->first();
//$TestUser->save();
dd('here');
