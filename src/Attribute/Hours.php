<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Hours extends Attribute
{

    protected $open_time;
    protected $close_time;
    protected $night_open_time;

    protected $required = [
        'open_time',
        'close_time',
    ];

}
