<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\DateConversions;

class Forecast extends Attribute
{

    use DateConversions;

    protected $date;
    protected $summary;
    protected $icon;
    protected $humidity;
    protected $pressure;
    protected $dew_point;
    protected $uv_index;
    protected $temperature;
    protected $wind;
    protected $windchill;
    protected $precipitation;

    protected $timestamps = [
        'date',
    ];


    public function getDayAttribute() : string
    {
        return $this->dateToRelative($this->date);
    }

}
