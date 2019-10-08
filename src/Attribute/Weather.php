<?php namespace SnowReportKit\SnowReportKit\Attribute;

use Carbon\Carbon;
use GuzzleHttp\Client;
use SnowReportKit\SnowReportKit\Base;

class Weather extends Attribute
{

    protected $summary;
    protected $report;
    protected $stations;
    protected $forecast;
    protected $weekly_snowfall;

    protected $container = [
        'stations',
        'forecast',
        'weekly_snowfall',
    ];

    protected $strict_type = [
        'summary' => 'string',
    ];


    public function useDarkSkyForecast( float $lat, float $lon, string $api_key = '' ) : Base
    {
        if( ! $this->forecast ) {
            $this->forecast = [];
        }

        $client = new Client( [
            'base_uri' => 'https://api.darksky.net/forecast/' . $api_key . '/',
            'timeout'  => 10,
        ] );

        $response  = $client->get(
            implode( ',', [ $lat, $lon ] ),
            [
                'query' => [
                    'exclude' => 'currently,minutely,hourly,flags',
                ],
            ]
        );
        $response  = json_decode( $response->getBody()->getContents(), true );
        $forecasts = $response[ 'daily' ][ 'data' ];

        foreach( $forecasts as $f ) {

            $precipitation = [
                'type'        => $f[ 'precipType' ] ?? 'clear',
                'probability' => $f[ 'precipProbability' ],
                'inches'      => 0,
            ];

            switch( $precipitation[ 'type' ] ) {
                case( 'snow' ):
                    $precipitation[ 'inches' ] = $f[ 'precipAccumulation' ];
                    break;
                case( 'rain' ):
                    $precipitation[ 'inches' ] = $f[ 'precipIntensityMax' ];
                    break;
                case( 'sleet' ):
                    $precipitation[ 'inches' ] = $f[ 'precipAccumulation' ] ?? $f[ 'precipIntensityMax' ] ?? 0;
                    break;
                default:
                    $precipitation[ 'inches' ] = 0;
            }

            $this->forecast[ Carbon::parse( $f[ 'time' ] )->format( 'Y-m-d' ) ] =
                Forecast::make( [
                    'date'          => Carbon::parse( $f[ 'time' ] ),
                    'summary'       => $f[ 'summary' ],
                    'icon'          => $f[ 'icon' ],
                    'humidity'      => $f[ 'humidity' ],
                    'pressure'      => $f[ 'pressure' ],
                    'dew_point'     => $f[ 'dewPoint' ],
                    'uv_index'      => $f[ 'uvIndex' ],
                    'temperature'   => [
                        'high_f' => $f[ 'temperatureHigh' ],
                        'low_f'  => $f[ 'temperatureLow' ],
                    ],
                    'wind'          => [
                        'speed_mph' => $f[ 'windSpeed' ],
                        'gusts_mph' => $f[ 'windGust' ],
                        'direction' => $f[ 'windBearing' ] ?? null,
                    ],
                    'windchill'     => [
                        'high_f' => $f[ 'apparentTemperatureHigh' ],
                        'low_f'  => $f[ 'apparentTemperatureLow' ],
                    ],
                    'precipitation' => $precipitation,
                ] );
        }

        return $this;
    }


    public function addSnowReport( array $report ) : Base
    {
        $this->report = Report::make( $report );

        return $this;
    }


    public function addSnowfallForDate( $date, $snowfall ) : Base
    {
        $this->weekly_snowfall[ $date ] = $snowfall;

        return $this;
    }

}
