<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Season extends Attribute
{

    protected $open;
    protected $close;

    protected $required = [
        'open',
        'close',
    ];

    protected $dates = [
        'open',
        'close',
    ];

}
