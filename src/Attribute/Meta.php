<?php namespace SnowReportKit\SnowReportKit\Attribute;

use Carbon\Carbon;

class Meta extends Attribute
{

    protected $version;

    protected $timestamps = [
        'updated_at',
    ];


    public function getUpdatedAtAttribute() : Carbon
    {
        return Carbon::now();
    }

}
