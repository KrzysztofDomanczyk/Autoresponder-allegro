<?php
    // require_once "EmailGetter.php";
    // require_once "EmailSender.php";
    // require 'Carbon-2.24.0/autoload.php';
    require 'Carbon-1.38.4/autoload.php';


use Carbon\Carbon;
use Carbon\CarbonInterval;

class TimeChecker
{
    public $_date;
    private $_workHourStart;
    private $_workHourEnd;

    public function __construct()
    {
        $this->_date = Carbon::now()->day(4)->setTime(11, 36, 0); 
        $this->_workHourStart = 8;
        $this->_workHourEnd = 16;
        // $this->_date = Carbon::now();
    }

    public function isTimeToRespond()
    {
        if ($this->_date->isWeekday()) {
                return $this->isWorkHours(); 
        } 

        return true;
    }

    public function isWorkHours() 
    {
        $time = $this->_date;
        
        $morning = Carbon::create($time->year, $time->month, $time->day, $this->_workHourStart, 0, 0); 
        $evening = Carbon::create($time->year, $time->month, $time->day, $this->_workHourEnd, 0, 0);

        return $time->between($morning, $evening, true);
    }   

    public function isTimeToClearSentEmails()
    {
        if ($this->_date->isWeekday()) {
            
            $time = $this->_date;

            $start = Carbon::create($time->year, $time->month, $time->day, 12, 0, 0);
            $end = Carbon::create($time->year, $time->month, $time->day, 12, 20, 0); 

            return $time->between($start, $end, true);
        }
            return false;
    }
    
    public function isPreviousCycle($date)
    {
        // $dateSentEmail = Carbon::create(2019, 9, 20, 16, 15, 16);
        // $now           = Carbon::create(2019, 9, 20, 17, 15, 16);
  
        $now = $this->_date;
        $dateSentEmail = Carbon::parse($date);

        $begin = $now->day-1;
        $end = $now->day;

        if ($dateSentEmail->day === $now->day) {
            
            $begin++;
            $end++;

            $start = Carbon::create($now->year, $now->month, $begin, $this->_workHourEnd, 0, 0); 
            $end   = Carbon::create($now->year, $now->month, $end, $this->_workHourStart, 0, 0);
            
            return !$dateSentEmail->between($start, $end, true);
        }

        return true;
    }
}
?>