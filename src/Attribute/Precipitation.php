<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\LengthFormulas;

class Precipitation extends Attribute
{

    use LengthFormulas;

    protected $type;
    protected $probability;
    protected $inches;
    protected $centimeters;

    protected $enum = [
        'type' => ['rain', 'snow', 'sleet', 'clear'],
    ];


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
