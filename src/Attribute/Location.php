<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Location extends Attribute
{

    protected $lat;
    protected $lon;
    protected $elevation;

    protected $required = [
        'lat',
        'lon',
    ];

}
