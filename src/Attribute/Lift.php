<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Lift extends Attribute
{

    protected $type;
    protected $status;
    protected $persons;
    protected $night_operation;
    protected $area;
    protected $beacon_required;

    protected $enum = [
        'status' => [
            SRK::OPEN,
            SRK::HOLD,
            SRK::CLOSED,
        ],
        'type' => [
            SRK::CHAIRLIFT,
            SRK::SURFACE,
            SRK::ROPETOW,
        ]
    ];

}
