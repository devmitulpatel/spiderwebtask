<?php
namespace App\Repositories\Zoom\Enum;

enum MeetingType:int{
   case InstantMeeting =1;
   case ScheduledMeeting =2;
   case RecurringMeetingNoFixedTime =3;
   case RecurringMeetingFixedTime =8;

}
