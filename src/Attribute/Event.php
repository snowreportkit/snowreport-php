<?php namespace SnowReportKit\SnowReportKit\Attribute;

use Carbon\Carbon;

class Event extends Attribute
{

    protected $date;
    protected $summary;
    protected $description;
    protected $url;
    protected $thumbnail;

    protected $required = [
        'date',
    ];

    protected $timestamps = [
        'date',
    ];


    public function getDateJsAttribute() : int
    {
        return (int) Carbon::parse($this->date)->valueOf();
    }

}
