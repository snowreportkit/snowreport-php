<?php namespace SnowReportKit\SnowReportKit\Traits;

trait LengthFormulas
{

    protected function inToCm(int $in) : int
    {
        return (int) ($in / 0.39370);
    }


    protected function cmToIn(int $cm) : float
    {
        return (float) number_format($cm * 0.39370, 2);
    }

}
