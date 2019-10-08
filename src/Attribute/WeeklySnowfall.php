<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\DateConversions;
use SnowReportKit\SnowReportKit\Traits\LengthFormulas;

class WeeklySnowfall extends Attribute
{

    use LengthFormulas, DateConversions;

    protected $date;
    protected $day;
    protected $inches;
    protected $centimeters;

    protected $required = [
        'date',
    ];


    protected function getDayAttribute() : string
    {
        return $this->dateToRelative($this->date);
    }


    protected function getInchesAttribute() : ?float
    {
        if ($this->inches === null && $this->centimeters === null) {
            return null;
        }

        return $this->inches ?? $this->cmToIn($this->centimeters);
    }


    protected function getCentimetersAttribute() : ?int
    {
        if ($this->inches === null && $this->centimeters === null) {
            return null;
        }

        return $this->centimeters ?? $this->inToCm($this->inches);
    }


}
