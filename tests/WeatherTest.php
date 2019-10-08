<?php namespace Test;

use Carbon\Carbon;
use SnowReportKit\SnowReportKit\Attribute\Forecast;
use SnowReportKit\SnowReportKit\Attribute\Report;
use SnowReportKit\SnowReportKit\Attribute\Station;
use SnowReportKit\SnowReportKit\Attribute\Weather;
use SnowReportKit\SnowReportKit\Attribute\WeeklySnowfall;
use SnowReportKit\SnowReportKit\Mountain;

class WeatherTest extends TestBase
{

    public function testWeather() : void
    {
        $weather = [
            'summary'         => '__summary__',
            'report'          => static::snowReport(),
            'stations'        => static::weatherStations(),
            'forecast'        => static::forecast(),
            'weekly_snowfall' => static::weeklySnowfall(),
        ];

        $data     = $this->withName(compact('weather'));
        $mountain = Mountain::make($data);
        $this->write($mountain->toArray(), __METHOD__);

        $this->assertEquals('__summary__', $mountain->get('weather.summary'));
        $this->assertInstanceOf(Weather::class, $mountain->get('weather'));
        $this->assertInstanceOf(Report::class, $mountain->get('weather.report'));
        $this->assertInstanceOf(Station::class, $mountain->get('weather.stations', '__station_b__'));
        $this->assertInstanceOf(Forecast::class, $mountain->get('weather.forecast', '2019-01-02'));
        $this->assertInstanceOf(
            WeeklySnowfall::class,
            $mountain->get('weather.weekly_snowfall', Carbon::now()->format('Y-m-d'))
        );
    }


    public function testAddSnowReport() : void
    {
        $weather = [
            'summary'  => '__summary__',
            'stations' => static::weatherStations(),
            'report'   => static::snowReport(),
        ];

        $data     = $this->withName(compact('weather'));
        $mountain = Mountain::make($data);

        $this->assertInstanceOf(Weather::class, $mountain->get('weather'));
        $this->assertInstanceOf(Report::class, $mountain->get('weather.report'));

        $weather = [
            'summary'  => '__summary__',
            'stations' => static::weatherStations(),
        ];

        $data     = $this->withName(compact('weather'));
        $mountain = Mountain::make($data);
        $mountain->addSnowReport(static::snowReport());

        $this->assertInstanceOf(Weather::class, $mountain->get('weather'));
        $this->assertInstanceOf(Report::class, $mountain->get('weather.report'));
    }


    public function testDarkSkyForecast() : void
    {
        if ( ! $_ENV[ 'TEST_DARK_SKY_API' ]) {
            $this->assertTrue(true);

            return;
        }

        $weather = [
            'summary'         => '__summary__',
            'report'          => static::snowReport(),
            'stations'        => static::weatherStations(),
            'weekly_snowfall' => static::weeklySnowfall(),
        ];

        $data     = $this->withName(compact('weather'));
        $mountain = Mountain::make($data);
        $mountain->useDarkSkyForecast(
            $_ENV[ 'DARK_SKY_LAT' ],
            $_ENV[ 'DARK_SKY_LON' ],
            $_ENV[ 'DARK_SKY_KEY' ]
        );
        $this->write($mountain->toArray(), __METHOD__);

        $this->assertInstanceOf(Weather::class, $mountain->get('weather'));
        $this->assertInstanceOf(Forecast::class, $mountain->get(
            'weather.forecast',
            Carbon::now()->format('Y-m-d')
        ));
    }


    public function testAddWeatherReadings() : void
    {

        $weather = [
            'summary'  => '__summary__',
            'stations' => static::weatherStations(),
        ];

        foreach ($weather[ 'stations' ] as $name => $station) {
            $weather[ 'stations' ][ $name ][ 'readings' ] = [];
        }

        $data     = $this->withName(compact('weather'));
        $mountain = Mountain::make($data);

        $reading = self::weatherReadings();
        $reading = array_shift($reading);

        $readings = [];
        for ($i = 0; $i < 6; $i++) {
            $reading[ 'time' ] = Carbon::parse($reading[ 'time' ])->addMinutes(5)->toIso8601String();

            $readings[] = $reading;
        }

        $mountain->addWeatherReading('__station_a__', $readings[ 0 ]);
        $mountain->addWeatherReading('__station_a__', $readings[ 1 ]);
        $mountain->addWeatherReading('__station_b__', $readings[ 0 ]);
        $mountain->addWeatherReading('__station_b__', $readings[ 2 ]);

        $this->assertArraySubset([
            'weather' => [
                'stations' => [
                    '__station_a__' => [
                        'readings' => [
                            $readings[ 0 ][ 'time' ] => $readings[ 0 ],
                            $readings[ 1 ][ 'time' ] => $readings[ 1 ],
                        ],
                    ],
                    '__station_b__' => [
                        'readings' => [
                            $readings[ 0 ][ 'time' ] => $readings[ 0 ],
                            $readings[ 2 ][ 'time' ] => $readings[ 2 ],
                        ],
                    ],
                ],
            ],
        ], $mountain->toArray());

        $mountain->addWeatherReadings('__station_a__', [$readings[ 3 ], $readings[ 4 ]]);
        $mountain->addWeatherReadings('__station_b__', [$readings[ 4 ], $readings[ 5 ]]);

        $this->assertArraySubset([
            'weather' => [
                'stations' => [
                    '__station_a__' => [
                        'readings' => [
                            $readings[ 0 ][ 'time' ] => $readings[ 0 ],
                            $readings[ 1 ][ 'time' ] => $readings[ 1 ],
                            $readings[ 3 ][ 'time' ] => $readings[ 3 ],
                            $readings[ 4 ][ 'time' ] => $readings[ 4 ],
                        ],
                    ],
                    '__station_b__' => [
                        'readings' => [
                            $readings[ 0 ][ 'time' ] => $readings[ 0 ],
                            $readings[ 2 ][ 'time' ] => $readings[ 2 ],
                            $readings[ 4 ][ 'time' ] => $readings[ 4 ],
                            $readings[ 5 ][ 'time' ] => $readings[ 5 ],
                        ],
                    ],
                ],
            ],
        ], $mountain->toArray());
    }
}
