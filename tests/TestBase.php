<?php namespace Test;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use PHPUnit\Framework\TestCase;
use SnowReportKit\SnowReportKit\Mountain;

class TestBase extends TestCase
{

    use ArraySubsetAsserts, DummyData;


    /**
     * @param  Mountain  $mountain
     *
     * @return array
     */
    protected function decode(Mountain $mountain) : array
    {
        return (array) json_decode($mountain->export(), true);
    }


    /**
     * @return Mountain|null
     */
    protected function mountainFromConfig() : ?Mountain
    {
        return Mountain::fromConfig(__DIR__.'/files/config.php');
    }


    protected function withName(array $attributes) : array
    {
        if ( ! isset($attributes[ 'info' ])) {
            $attributes[ 'info' ] = [];
        }

        if ( ! isset($attributes[ 'info' ][ 'name' ])) {
            $attributes[ 'info' ][ 'name' ] = 'Acme Ski Area';
        }

        return $attributes;
    }


    protected function assertFirstItemIs($class, $container) : void
    {
        foreach (array_slice($container, 0, 1, true) as $k => $v) {
            $this->assertInstanceOf($class, $v);
        }
    }


    protected function write(array $array, $method) : void
    {
        $method = explode('::', $method)[ 1 ];
        $method = str_replace('test', '', $method);
        $method = strtolower(
            preg_replace(
                ['/([A-Z]+)/', '/_([A-Z]+)([A-Z][a-z])/'],
                ['_$1', '_$1_$2'],
                lcfirst($method)
            )
        );

        @mkdir(__DIR__.'/output/');
        $out = sprintf('%s/output/%s.json', __DIR__, $method);
        file_put_contents($out, json_encode($array, JSON_PRETTY_PRINT));
        $this->combine();
    }


    protected function combine() : void
    {
        $files  = glob(__DIR__.'/output/*.json');
        $arrays = [];
        $out    = [];

        foreach ($files as $file) {
            $array = json_decode(file_get_contents($file), true);
            $out   = array_replace_recursive($out, $array);
        }

        @mkdir(__DIR__.'/data/');
        file_put_contents(__DIR__.'/data/combined.json', json_encode($out, JSON_PRETTY_PRINT));
    }

}
