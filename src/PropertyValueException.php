<?php namespace SnowReportKit\SnowReportKit;

use Exception;

class PropertyValueException extends Exception
{

    public function __construct($class, $prop, $enum, $given)
    {
        parent::__construct(
            sprintf(
                '`%s` requires that `%s` be one of type [ `%s` ]. `%s` was given.',
                $class,
                $prop,
                implode('`, `', $enum),
                $given
            )
        );
    }

}
