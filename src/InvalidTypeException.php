<?php namespace SnowReportKit\SnowReportKit;

use Exception;

class InvalidTypeException extends Exception
{

    public function __construct($class, $prop, $expected, $given)
    {
        parent::__construct(
            sprintf(
                '`%s` requires that `%s` be %s, but %s was given.',
                $class,
                $prop,
                $expected,
                $given
            )
        );
    }

}
