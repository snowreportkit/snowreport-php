<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Snow extends Attribute
{

    protected $primary_surface;
    protected $secondary_surface;
    protected $unit;
    protected $sensor;
    protected $depth;
    protected $storm_total;
    protected $snow_overnight;
    protected $snow_12hr;
    protected $snow_24hr;
    protected $snow_48hr;
    protected $snow_4days;
    protected $snow_5days;
    protected $snow_6days;
    protected $snow_7days;
    protected $seasonal;

    protected $required = [
        'unit',
        'snow_12hr',
        'snow_24hr',
        'seasonal',
    ];

    protected $enum = [
        'unit' => ['in', 'cm'],
    ];


    protected function getUnitAttribute() : string
    {
        return $this->unit ?? 'in';
    }

}
