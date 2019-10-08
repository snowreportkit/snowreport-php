<?php namespace Test;

use SnowReportKit\SnowReportKit\Attribute\Address;
use SnowReportKit\SnowReportKit\Attribute\Forecast;
use SnowReportKit\SnowReportKit\Attribute\Hours;
use SnowReportKit\SnowReportKit\Attribute\Info;
use SnowReportKit\SnowReportKit\Attribute\Meta;
use SnowReportKit\SnowReportKit\Attribute\Report;
use SnowReportKit\SnowReportKit\Attribute\Season;
use SnowReportKit\SnowReportKit\Attribute\Station;
use SnowReportKit\SnowReportKit\Attribute\Temperature;
use SnowReportKit\SnowReportKit\Attribute\Weather;
use SnowReportKit\SnowReportKit\Attribute\WeeklySnowfall;
use SnowReportKit\SnowReportKit\Attribute\Wind;
use SnowReportKit\SnowReportKit\Mountain;

class JsonImportTest extends TestBase
{

    public $mountain;


    public function testLoadFromJson() : void
    {
        $this->assertInstanceOf(Mountain::class, $this->mountain);
        $this->assertInstanceOf(Meta::class, $this->mountain->meta);
        $this->assertInstanceOf(Info::class, $this->mountain->info);
        $this->assertInstanceOf(Address::class, $this->mountain->info->address);
        $this->assertInstanceOf(Hours::class, $this->mountain->info->hours);
        $this->assertInstanceOf(Season::class, $this->mountain->info->season);
    }


    public function testWeatherFromJson() : void
    {
        $weather = $this->mountain->weather;
        $this->assertInstanceOf(Weather::class, $weather);
        $this->assertInstanceOf(Report::class, $weather->report);
        $this->assertFirstItemIs(Temperature::class, $weather->report->temperature);
        $this->assertFirstItemIs(Wind::class, $weather->report->wind);
        $this->assertFirstItemIs(Station::class, $weather->stations);
        $this->assertFirstItemIs(Forecast::class, $weather->forecast);
        $this->assertFirstItemIs(WeeklySnowfall::class, $weather->weekly_snowfall);
    }


    protected function setUp() : void
    {
        $this->mountain = Mountain::fromFile(__DIR__.'/data/combined.json');

        parent::setUp();
    }

}
