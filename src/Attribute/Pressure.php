<?php namespace SnowReportKit\SnowReportKit\Attribute;

class Pressure extends Attribute
{

    protected $pressure;


    /**
     * @return float|null
     */
    public function getInHgAttribute() : ?float
    {
        return $this->pressure;
    }


    /**
     * @return float|null
     */
    public function getMillibarAttribute() : ?float
    {
        return $this->pressure * 33.864;
    }


    /**
     * @return float|null
     */
    public function getAtmAttribute() : ?float
    {
        return $this->pressure * 29.921;
    }

}
