<?php namespace SnowReportKit\SnowReportKit\Attribute;

use SnowReportKit\SnowReportKit\Traits\TemperatureFormulas;

class Temperature extends Attribute
{

    use TemperatureFormulas;

    protected $f;
    protected $c;
    protected $high_f;
    protected $low_f;
    protected $high_c;
    protected $low_c;


    protected function getFAttribute() : ?int
    {
        if ($this->f === null && $this->c === null) {
            return null;
        }

        return $this->f ?? $this->cToF($this->c);
    }


    protected function getCAttribute() : ?int
    {
        if ($this->f === null && $this->c === null) {
            return null;
        }

        return $this->c ?? $this->fToC($this->f);
    }


    protected function getHighFAttribute() : ?int
    {
        if ($this->high_f === null && $this->high_c === null) {
            return null;
        }

        return $this->high_f ?? $this->cToF($this->high_c);
    }


    protected function getHighCAttribute() : ?int
    {
        if ($this->high_f === null && $this->high_c === null) {
            return null;
        }

        return $this->high_c ?? $this->fToC($this->high_f);
    }


    protected function getLowFAttribute() : ?int
    {
        if ($this->low_f === null && $this->low_c === null) {
            return null;
        }

        return $this->low_f ?? $this->cToF($this->low_c);
    }


    protected function getLowCAttribute() : ?int
    {
        if ($this->low_f === null && $this->low_c === null) {
            return null;
        }

        return $this->low_c ?? $this->fToC($this->low_f);
    }


}
