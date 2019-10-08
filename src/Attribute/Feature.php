<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Feature extends Attribute
{

    protected $open;
    protected $type;
    protected $difficulty;
    protected $details;

    protected $strict_type = [
        'open'       => 'boolean',
        'type'       => 'string',
        'difficulty' => 'string',
        'details'    => 'string',
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
