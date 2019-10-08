<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Base;

class Station extends Attribute
{

    protected $area;
    protected $location;
    protected $conditions;
    protected $temperature;
    protected $visibility;
    protected $wind;
    protected $snow;
    protected $readings;

    protected $container = [
        'readings',
    ];

    protected $strict_type = [
        'area'       => 'string',
        'conditions' => 'string',
    ];


    public function addReading($reading) : Base
    {
        if ( ! $reading instanceof Reading) {
            $reading = Reading::make($reading);
        }

        $this->readings[ $reading->time ] = $reading;

        return $this;
    }


    public function addReadings(array $readings) : Base
    {
        $add = [];
        foreach ($readings as $reading) {
            if ( ! $reading instanceof Reading) {
                $reading = Reading::make($reading);

                $add[ $reading->time ] = $reading;
            }
        }

        $this->readings = array_merge($this->readings, $add);

        return $this;
    }


}
