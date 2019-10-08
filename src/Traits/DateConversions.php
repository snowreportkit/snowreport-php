<?php namespace SnowReportKit\SnowReportKit\Traits;

use Carbon\Carbon;

trait DateConversions
{

    protected function dateToRelative($date) : string
    {
        $date = Carbon::parse($date);
        $day  = $date->englishDayOfWeek;

        if ($date->isToday()) {
            $day = 'Today';
        }

        if ($date->isTomorrow()) {
            $day = 'Tomorrow';
        }

        if ($date->isYesterday()) {
            $day = 'Yesterday';
        }

        return $day;
    }

}
