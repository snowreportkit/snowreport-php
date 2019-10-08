<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Road extends Attribute
{

    protected $open;
    protected $requirements;
    protected $message;

    protected $required = [
        'open',
    ];

}
