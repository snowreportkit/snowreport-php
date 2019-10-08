<?php namespace SnowReportKit\SnowReportKit;

use ReflectionException;
use SnowReportKit\SnowReportKit\Attribute\Event;
use SnowReportKit\SnowReportKit\Attribute\Station;
use SnowReportKit\SnowReportKit\Attribute\Weather;

class Mountain extends Base
{

    protected $meta;
    protected $info;
    protected $roads;
    protected $weather;
    protected $lifts;
    protected $cameras;
    protected $trails;
    protected $events;
    protected $freestyle;

    protected $required = [
        'meta',
        'info',
    ];

    protected $container = [
        'roads',
        'cameras',
        'lifts',
        'trails',
        'events',
    ];


    /**
     * @param $attributes
     *
     * @return Base|self
     * @throws ReflectionException
     */
    public static function make($attributes) : Base
    {
        if (is_string($attributes)) {
            $attributes = ['info' => ['name' => $attributes]];
        }

        if ( ! isset($attributes[ 'meta' ])) {
            $attributes[ 'meta' ] = [
                'version' => 'v1',
            ];
        }

        return parent::make($attributes);
    }


    public function useDarkSkyForecast(float $lat, float $lon, string $api_key = '') : Base
    {
        $this->getWeather()->useDarkSkyForecast($lat, $lon, $api_key);

        return $this;
    }


    public function addWeatherReading($station, array $reading) : Base
    {
        $this->getWeather();

        if ($station instanceof Station) {
            $station->addReading($reading);

            return $this;
        }

        if (isset($this->weather->stations[ $station ])) {
            $this->weather->stations[ $station ]->addReading($reading);

            return $this;
        }

        return $this;
    }


    public function addWeatherReadings($station, array $readings) : Base
    {
        $this->getWeather();

        if ($station instanceof Station) {
            $station->addReadings($readings);

            return $this;
        }

        if (isset($this->weather->stations[ $station ])) {
            $this->weather->stations[ $station ]->addReadings($readings);

            return $this;
        }

        return $this;
    }


    public function addSnowReport(array $report) : Base
    {
        $this->getWeather()->addSnowReport($report);

        return $this;
    }


    public function addSnowfallForDate($date, $snowfall) : Base
    {
        $this->getWeather()->addSnowfallForDate($date, $snowfall);

        return $this;
    }


    public function addEvent(string $name, $event) : Base
    {
        if ( ! $event instanceof Event) {
            $event = Event::make($event);
        }

        $this->events[ $name ] = $event;

        return $this;
    }


    protected function getWeather() : Weather
    {
        if ( ! $this->weather || ! $this->weather instanceof Weather) {
            $this->weather = Weather::make([]);
        }

        return $this->weather;
    }

}
