<?php namespace Test;

use Carbon\Carbon;

trait DummyData
{

    public static function snowReport() : array
    {
        return [
            'time'        => '2019-01-01 10:03:00',
            'message'     => '__message_text__',
            'snow'        => [
                '__station_a__' =>
                    [
                        'snow_12hr' => 1,
                        'snow_24hr' => 2,
                        'seasonal'  => 3,
                    ],
                '__station_b__' =>
                    [
                        'snow_12hr' => 1,
                        'snow_24hr' => 2,
                        'seasonal'  => 3,
                    ],
            ],
            'temperature' => [
                '__station_a__' => [
                    'c'      => 0,
                    'high_f' => 32,
                    'low_c'  => -10,
                ],
                '__station_b__' => [
                    'f' => 0,
                ],
            ],
            'wind'        => [
                '__station_a__' => [
                    'direction' => 123,
                    'speed_mph' => 2,
                    'gusts_mph' => 3,
                ],
                '__station_b__' => [
                    'direction' => 92,
                    'speed_kph' => 2,
                    'gusts_kph' => 3,
                ],
            ],
        ];
    }


    public static function weatherReadings() : array
    {
        return [
            '2019-01-01 09:05:00' => [
                'time'            => '2019-01-01 09:05:00',
                'humidity'        => 0.2,
                'water_in'        => 5,
                'temperature_f'   => 3,
                'wind_mph'        => 5,
                'gust_mph'        => 15,
                'wind_direction'  => 33,
                'new_snow_in'     => 5,
                'settled_snow_in' => 50,
            ],
        ];
    }


    public static function weatherStations() : array
    {
        return [
            '__station_a__' => [
                'area'        => '__station_a_area__',
                'location'    => [
                    'lat' => 123.45,
                    'lon' => -47.654,
                ],
                'conditions'  => '__station_a_conditions__',
                'temperature' => [
                    'f' => 20,
                ],
                'visibility'  => 100,
                'wind'        => [
                    'direction' => 123,
                    'speed_mph' => 2,
                    'gusts_mph' => 3,
                ],
                'snow'        => [
                    'snow_12hr' => 1,
                    'snow_24hr' => 2,
                    'seasonal'  => 3,
                ],
                'readings'    => static::weatherReadings(),
            ],
            '__station_b__' => [
                'area'        => '__station_b_area__',
                'location'    => [
                    'lat' => 123.45,
                    'lon' => -47.654,
                ],
                'conditions'  => '__station_b_conditions__',
                'temperature' => [
                    'f' => 20,
                ],
                'visibility'  => 100,
                'wind'        => [
                    'direction' => 123,
                    'speed_mph' => 2,
                    'gusts_mph' => 3,
                ],
                'snow'        => [
                    'snow_12hr' => 1,
                    'snow_24hr' => 2,
                    'seasonal'  => 3,
                ],
                'readings'    => static::weatherReadings(),
            ],
        ];
    }


    public static function forecast() : array
    {
        return [
            '2019-01-01' => [
                'date'          => '2019-01-01',
                'summary'       => '__weather_summary__',
                'icon'          => 'snow',
                'humidity'      => 0.7,
                'pressure'      => 1000,
                'dew_point'     => 33,
                'uv_index'      => 1,
                'temperature'   => [
                    'high_f' => 20,
                    'low_f'  => 10,
                ],
                'wind'          => [
                    'direction' => 123,
                    'speed_mph' => 2,
                    'gusts_mph' => 3,
                ],
                'windchill'     => [
                    'high_f' => 20,
                    'low_f'  => 10,
                ],
                'precipitation' => [
                    'type'        => 'snow',
                    'probability' => 0.9,
                    'inches'      => 8,
                ],
            ],
            '2019-01-02' => [
                'date'          => '2019-01-02',
                'summary'       => '__weather_summary__',
                'icon'          => 'snow',
                'humidity'      => 0.7,
                'pressure'      => 1000,
                'dew_point'     => 33,
                'uv_index'      => 1,
                'temperature'   => [
                    'high_f' => 20,
                    'low_f'  => 10,
                ],
                'wind'          => [
                    'direction' => 123,
                    'speed_mph' => 2,
                    'gusts_mph' => 3,
                ],
                'windchill'     => [
                    'high_f' => 20,
                    'low_f'  => 10,
                ],
                'precipitation' => [
                    'type'        => 'snow',
                    'probability' => 0.9,
                    'inches'      => 8,
                ],
            ],
        ];
    }


    public static function weeklySnowfall() : array
    {
        $out = [];

        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $out[ $date ] = [
                'date'   => $date,
                'inches' => $i,
            ];
        }

        return $out;
    }
}
