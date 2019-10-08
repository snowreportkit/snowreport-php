<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Vertical extends Attribute
{

    protected $open;
    protected $difficulty;
    protected $groomed;
    protected $night_operation;
    protected $area;
    protected $featured;

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

    protected $strict_type = [
        'open'            => 'boolean',
        'difficulty'      => 'string',
        'groomed'         => 'string',
        'night_operation' => 'boolean',
        'area'            => 'string',
        'featured'        => 'boolean',
    ];
}
