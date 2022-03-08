<?php
namespace App\Repositories\Zoom\Enum;

enum RecurrenceType:int{
   case Daily =1;
   case Weekly =2;
   case Monthly =3;
}
