<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\LengthFormulas;
use SnowReportKit\SnowReportKit\Traits\TemperatureFormulas;
use SnowReportKit\SnowReportKit\Traits\WindFormulas;

class Reading extends Attribute
{

    use WindFormulas, TemperatureFormulas, LengthFormulas;

    protected $time;
    protected $humidity;
    protected $water_in;
    protected $water_cm;
    protected $temperature_f;
    protected $temperature_c;
    protected $wind_mph;
    protected $gust_mph;
    protected $wind_kph;
    protected $gust_kph;
    protected $wind_direction;
    protected $cardinal;
    protected $new_snow_in;
    protected $settled_snow_in;
    protected $new_snow_cm;
    protected $settled_snow_cm;

    protected $timestamps = [
        'time',
    ];

    protected $required = [
        'time',
    ];


    protected function getNewSnowInAttribute() : ?float
    {
        if ($this->new_snow_in === null && $this->new_snow_cm === null) {
            return null;
        }

        return $this->new_snow_in ?? $this->cmToIn($this->new_snow_cm);
    }


    protected function getNewSnowCmAttribute() : ?int
    {
        if ($this->new_snow_in === null && $this->new_snow_cm === null) {
            return null;
        }

        return $this->new_snow_cm ?? $this->inToCm($this->new_snow_in);
    }


    protected function getSettledSnowInAttribute() : ?float
    {
        if ($this->settled_snow_in === null && $this->settled_snow_cm === null) {
            return null;
        }

        return $this->settled_snow_in ?? $this->cmToIn($this->settled_snow_cm);
    }


    protected function getSettledSnowCmAttribute() : ?int
    {
        if ($this->settled_snow_in === null && $this->settled_snow_cm === null) {
            return null;
        }

        return $this->settled_snow_cm ?? $this->inToCm($this->settled_snow_in);
    }


    protected function getWaterInAttribute() : ?float
    {
        if ($this->water_in === null && $this->water_cm === null) {
            return null;
        }

        return $this->water_in ?? $this->cmToIn($this->water_cm);
    }


    protected function getWaterCmAttribute() : ?int
    {
        if ($this->water_in === null && $this->water_cm === null) {
            return null;
        }

        return $this->water_cm ?? $this->inToCm($this->water_in);
    }


    protected function getTemperatureFAttribute() : ?int
    {
        if ($this->temperature_c === null && $this->temperature_f === null) {
            return null;
        }

        return $this->temperature_f ?? $this->cToF($this->temperature_c);
    }


    protected function getTemperatureCAttribute() : ?int
    {
        if ($this->temperature_c === null && $this->temperature_f === null) {
            return null;
        }

        return $this->temperature_c ?? $this->fToC($this->temperature_f);
    }


    protected function getWindMphAttribute() : ?int
    {
        if ($this->wind_mph === null && $this->wind_kph === null) {
            return null;
        }

        return $this->wind_mph ?? $this->kphToMph($this->wind_kph);
    }


    protected function getGustMphAttribute() : ?int
    {
        if ($this->gust_mph === null && $this->gust_kph === null) {
            return null;
        }

        return $this->gust_mph ?? $this->kphToMph($this->gust_kph);
    }


    protected function getWindKphAttribute() : ?int
    {
        if ($this->wind_mph === null && $this->wind_kph === null) {
            return null;
        }

        return $this->wind_kph ?? $this->mphToKph($this->wind_mph);
    }


    protected function getGustKphAttribute() : ?int
    {
        if ($this->gust_mph === null && $this->gust_kph === null) {
            return null;
        }

        return $this->gust_kph ?? $this->mphToKph($this->gust_mph);
    }


    protected function getCardinalAttribute() : ?string
    {
        if ($this->wind_direction === null) {
            return null;
        }

        return $this->directionToCardinal($this->wind_direction);
    }


    protected function getWindchillFAttribute() : ?int
    {
        if ($this->gust_mph < 5 || $this->temperature_f === null) {
            return null;
        }

        return $this->windchill($this->gust_mph, $this->temperature_f);
    }


    protected function getWindchillCAttribute() : ?int
    {
        if ( ! $f = $this->getWindchillFAttribute()) {
            return null;
        }

        return $this->fToC($f);
    }

}
