<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\SRK;

class Message extends Attribute
{

    protected $text;
    protected $level;
    protected $time;
    protected $expires;

    protected $required = [
        'text',
    ];

    protected $enum = [
        'level' => [
            SRK::EMERGENCY,
            SRK::ALERT,
            SRK::CRITICAL,
            SRK::ERROR,
            SRK::WARNING,
            SRK::NOTICE,
            SRK::INFO,
            SRK::DEBUG,
        ],
    ];

    protected $timestamps = [
        'time',
        'expires',
    ];

}
