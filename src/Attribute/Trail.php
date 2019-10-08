<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Trail extends Attribute
{

    protected $open;
    protected $type;
    protected $groomed;
    protected $snow_making;
    protected $night_operation;
    protected $difficulty;
    protected $area;
    protected $featured;

    protected $required = [
        'open',
        'type',
        'difficulty',
        'groomed',
    ];

    protected $enum = [
        'type'       => [
            SRK::DOWNHILL,
            SRK::CROSSCOUNTRY,
        ],
        'groomed'    => [
            SRK::AM,
            SRK::PM,
            SRK::UNGROOMED,
        ],
        'difficulty' => [
            SRK::GREEN,
            SRK::BLUE,
            SRK::BLACK,
            SRK::DOUBLE,
            SRK::BACKCOUNTRY,
        ],
    ];

}
