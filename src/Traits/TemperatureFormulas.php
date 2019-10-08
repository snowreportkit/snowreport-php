<?php namespace SnowReportKit\SnowReportKit\Traits;

trait TemperatureFormulas
{

    protected function cToF(int $c) : int
    {
        return ($c * 0.5555) + 32;
    }


    protected function fToC(int $f) : int
    {
        return ($f - 32) * 0.5555;
    }


    /**
     * "Windchill temperature is defined only for temperatures at or below 10 °C (50 °F)
     *  and wind speeds above 4.8 kilometres per hour (3.0 mph)"
     *
     * @param  int  $wind_mph
     * @param  int  $temp_f
     *
     * @return float
     * @see https://en.wikipedia.org/wiki/Wind_chill#North_American_and_United_Kingdom_wind_chill_index
     */
    protected function windchill(int $wind_mph, int $temp_f) : ?float
    {
        if ($wind_mph < 3 || $temp_f > 50) {
            return null;
        }

        $v = pow($wind_mph, 0.16);
        $t = $temp_f;

        return 35.74 + (0.6215 * $t) - (35.75 * $v) + ((0.4275 * $t) * $v);
    }

}
