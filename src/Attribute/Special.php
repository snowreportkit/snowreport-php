<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Special extends Attribute
{

    protected $open;
    protected $difficulty;
    protected $area;

    protected $strict_type = [
        'open'       => 'boolean',
        'difficulty' => 'string',
        'area'       => 'string',
    ];

    protected $enum = [
        'difficulty' => [
            SRK::XS,
            SRK::S,
            SRK::M,
            SRK::L,
            SRK::XL,
            SRK::XXL,
        ],
    ];

}
