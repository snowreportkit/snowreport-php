<?php namespace SnowReportKit\SnowReportKit\Attribute;

use Carbon\Carbon;

class Report extends Attribute
{

    protected $time;
    protected $snow;
    protected $wind;
    protected $temperature;
    protected $humidity;
    protected $message;
    protected $icon;
    protected $custom;

    protected $container = [
        'snow',
        'wind',
        'temperature',
    ];

    protected $required = [
        'time',
        'snow',
        'wind',
        'temperature',
    ];

    protected $timestamps = [
        'time',
    ];


    public function getTimeJsAttribute() : int
    {
        return (int) Carbon::parse($this->time)->valueOf();
    }

}
