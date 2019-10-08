<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Info extends Attribute
{

    protected $name;
    protected $website;
    protected $address;
    protected $phone;
    protected $open;
    protected $acres;
    protected $acres_open;
    protected $hours;
    protected $season;
    protected $messages;
    protected $base_elevation;
    protected $top_elevation;
    protected $max_vertical;
    protected $lift_served_vertical;

    protected $required = [
        'name',
    ];

    protected $container = [
        'messages',
    ];

}
