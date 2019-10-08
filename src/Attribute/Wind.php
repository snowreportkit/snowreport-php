<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\WindFormulas;

class Wind extends Attribute
{

    use WindFormulas;

    protected $direction;
    protected $speed_mph;
    protected $speed_kph;
    protected $gusts_mph;
    protected $gusts_kph;


    protected function getSpeedMphAttribute() : int
    {
        if ($this->speed_mph === null && $this->speed_kph === null) {
            return null;
        }

        return $this->speed_mph ?? $this->kphToMph($this->speed_kph);
    }


    protected function getGustsMphAttribute() : int
    {
        if ($this->gusts_mph === null && $this->gusts_kph === null) {
            return null;
        }

        return $this->gusts_mph ?? $this->kphToMph($this->gusts_kph);
    }


    protected function getSpeedKphAttribute() : int
    {
        if ($this->speed_mph === null && $this->speed_kph === null) {
            return null;
        }

        return $this->speed_kph ?? $this->mphToKph($this->speed_mph);
    }


    protected function getGustsKphAttribute() : int
    {
        if ($this->gusts_mph === null && $this->gusts_kph === null) {
            return null;
        }

        return $this->gusts_kph ?? $this->mphToKph($this->gusts_mph);
    }


    protected function getCardinalAttribute() : ?string
    {
        if ($this->direction === null) {
            return null;
        }

        return $this->directionToCardinal($this->direction);
    }

}
