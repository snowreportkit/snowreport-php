<?php namespace SnowReportKit\SnowReportKit\Traits;

trait WindFormulas
{

    protected function mphToKph(int $mph) : int
    {
        return $mph * 1.609344;
    }


    protected function kphToMph(int $kph) : int
    {
        return $kph / 1.609344;
    }


    protected function directionToCardinal($direction, $uppercase = true) : ?string
    {
        if ($direction < 0 || $direction === null) {
            return null;
        }

        if ($direction >= 11 && $direction < 34) {
            $cardinal = 'nne';
        } elseif ($direction >= 34 && $direction < 56) {
            $cardinal = 'ne';
        } elseif ($direction >= 56 && $direction < 79) {
            $cardinal = 'ene';
        } elseif ($direction >= 79 && $direction < 101) {
            $cardinal = 'e';
        } elseif ($direction >= 101 && $direction < 124) {
            $cardinal = 'ese';
        } elseif ($direction >= 124 && $direction < 146) {
            $cardinal = 'se';
        } elseif ($direction >= 146 && $direction < 169) {
            $cardinal = 'sse';
        } elseif ($direction >= 169 && $direction < 191) {
            $cardinal = 's';
        } elseif ($direction >= 191 && $direction < 214) {
            $cardinal = 'ssw';
        } elseif ($direction >= 214 && $direction < 236) {
            $cardinal = 'sw';
        } elseif ($direction >= 236 && $direction < 259) {
            $cardinal = 'wsw';
        } elseif ($direction >= 259 && $direction < 281) {
            $cardinal = 'w';
        } elseif ($direction >= 281 && $direction < 304) {
            $cardinal = 'wnw';
        } elseif ($direction >= 304 && $direction < 326) {
            $cardinal = 'nw';
        } elseif ($direction >= 326 && $direction < 349) {
            $cardinal = 'nnw';
        } else {
            $cardinal = 'n';
        }

        return $uppercase ? strtoupper($cardinal) : $cardinal;
    }

}
