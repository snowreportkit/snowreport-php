<?php namespace SnowReportKit\SnowReportKit\Attribute;

use Carbon\Carbon;

class Camera extends Attribute
{

    protected $url;
    protected $area;
    protected $live;
    protected $featured;
    protected $updated_at;

    protected $required = [
        'url',
    ];

    protected $timestamps = [
        'updated_at',
    ];


    protected function getAgeAttribute() : int
    {
        return Carbon::now()->diffInSeconds($this->updated_at);
    }


    protected function getUpdatedAtJsAttribute() : int
    {
        return (int) Carbon::parse($this->updated_at)->valueOf();
    }

}
