<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Park extends Attribute
{

    protected $difficulty;
    protected $groomed;
    protected $open;
    protected $night_operation;
    protected $area;
    protected $featured;
    protected $features;

    protected $enum = [
        'groomed' => [
            SRK::AM,
            SRK::PM,
            SRK::UNGROOMED,
        ],
        'difficulty' => [
            SRK::XS,
            SRK::S,
            SRK::M,
            SRK::L,
            SRK::XL,
            SRK::XXL,
        ],
    ];

    protected $container = [
        'features',
    ];

    protected $strict_type = [
        'open'            => 'boolean',
        'night_operation' => 'boolean',
        'featured'        => 'boolean',
        'area'            => 'string',
    ];

}
