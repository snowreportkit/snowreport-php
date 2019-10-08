<?php namespace SnowReportKit\SnowReportKit;

use Carbon\Carbon;
use GuzzleHttp\Client;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use SnowReportKit\SnowReportKit\Attribute\Attribute;

abstract class Base
{

    protected $required    = [];
    protected $container   = [];
    protected $enum        = [];
    protected $strict_type = [];
    protected $dates       = [];
    protected $times       = [];
    protected $timestamps  = [];
    protected $carbon      = [];
    protected $append      = [];
    protected $hidden      = [
        'required',
        'container',
        'hidden',
        'enum',
        'strict_type',
        'dates',
        'times',
        'timestamps',
        'carbon',
        'append',
    ];


    /**
     * @param $attributes
     *
     * @return Base|self
     * @throws ReflectionException
     */
    public static function make($attributes) : Base
    {
        $instance = new static();

        foreach ($attributes as $k => $v) {
            if (is_subclass_of($v, Attribute::class)) {
                $instance->set($k, $v->make($k));
                continue;
            }

            $class = str_replace('_', ' ', $k);
            $class = ucwords($class);
            $class = str_replace(' ', '', $class);

            $attribute = 'SnowReportKit\\SnowReportKit\\Attribute\\'.$class;
            if (is_array($v) && class_exists($attribute) && ! in_array($k, $instance->container, true)) {
                $instance->set($k, $attribute::make($v));
                continue;
            }

            /**
             * This will drop off the 's' in the case of plural attribute names
             */
            $attribute = 'SnowReportKit\\SnowReportKit\\Attribute\\'.$class;
            $attribute = preg_replace('/s$/', '', $attribute);
            if (is_array($v) && class_exists($attribute) && in_array($k, $instance->container, true)) {
                foreach ($v as $name => $data) {
                    $instance->add($k, $name, $attribute::make($data));
                }
                continue;
            }

            $v = self::mutateTimeFields($k, $instance, $v);

            $instance->$k = $v;
        }

        self::addComputedAttributes($instance);

        return $instance;
    }


    public static function fromFile(string $json_filename) : Base
    {
        $json = file_get_contents($json_filename);

        return static::make(json_decode($json, true));
    }


    public static function fromUrl(string $url, Client $client = null) : Base
    {
        if ( ! $client) {
            $client = self::getClient($url);
        }

        $data = $client->get($url)->getBody()->getContents();

        return static::make(json_decode($data, true));
    }


    public static function getClient(string $url) : Client
    {
        return new Client([
            'base_uri' => $url,
            'timeout'  => 10,
        ]);
    }


    public function set($keys, $val) : void
    {
        if (is_string($keys)) {
            $keys = explode('.', $keys);
        }
        $level = $this;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( ! isset($level->$key) || ! is_array($level->$key)) {
                $level->$key::make([]);
            }

            $level = $level->$key;
        }

        $level->{array_shift($keys)} = $val;
    }


    public function add($keys, $index, $val) : void
    {

        if (is_string($keys)) {
            $keys = explode('.', $keys);
        }
        $level = $this;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( ! isset($level->$key) || ! is_array($level->$key)) {
                $level->$key::make([]);
            }

            $level = $level->$key;
        }

        $level->{array_shift($keys)}[ $index ] = $val;
    }


    public function remove($keys, $index) : void
    {

        if (is_string($keys)) {
            $keys = explode('.', $keys);
        }
        $level = $this;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if ( ! isset($level->$key) || ! is_array($level->$key)) {
                $level->$key::make([]);
            }

            $level = $level->$key;
        }

        unset($level->{array_shift($keys)}[ $index ]);
    }


    /**
     * @param  string  $prop
     * @param  static  $instance
     * @param  string  $val
     *
     * @return string|Carbon
     */
    protected static function mutateTimeFields($prop, $instance, $val)
    {
        if (in_array($prop, $instance->dates, true)) {
            $val = Carbon::parse($val)->format('Y-m-d');
        }

        if (in_array($prop, $instance->times, true)) {
            $val = Carbon::parse($val)->format('H:i');
        }

        if (in_array($prop, $instance->timestamps, true)) {
            $val = Carbon::parse($val)->toIso8601String();
        }

        if (in_array($prop, $instance->carbon, true)) {
            $val = Carbon::parse($val);
        }

        return $val;
    }


    /**
     * @param  static  $instance
     *
     * @throws ReflectionException
     */
    protected static function addComputedAttributes(&$instance) : void
    {
        $reflect = new ReflectionClass($instance);
        $props   = $reflect->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED);

        foreach ($props as $prop) {
            $match = [];
            preg_match('/get(\w+)Attribute/', $prop->getName(), $match);

            if ( ! $match) {
                continue;
            }

            $attr = strtolower(
                preg_replace(
                    ['/([A-Z]+)/', '/_([A-Z]+)([A-Z][a-z])/'],
                    ['_$1', '_$1_$2'],
                    lcfirst($match[ 1 ])
                )
            );

            $instance->append[] = $attr;
            $instance->$attr    = $instance->{$match[ 0 ]}();
        }

        foreach ($instance->timestamps as $prop) {
            if (property_exists($instance, $prop.'_js')) {
                continue;
            }
            $time                    = Carbon::parse($instance->$prop);
            $instance->append[]      = $prop.'_js';
            $instance->{$prop.'_js'} = (int) $time->valueOf();
        }
    }


    public function get($path, $index = null)
    {
        if (is_string($path)) {
            $path = explode('.', $path);
        }

        $out = $this;
        foreach ($path as $p) {
            $out = $out->$p;
        }

        return $index ? $out[ $index ] : $out;
    }


    public function toArray() : array
    {
        $out   = [];
        $props = $this->getPublicProperties();

        foreach ($props as $k => $v) {
            if ($v instanceof Attribute) {
                $out[ $k ] = $v->toArray();
                continue;
            }

            if (is_array($v) && in_array($k, $this->container, true)) {
                foreach ($v as $name => $data) {
                    $out[ $k ][ $name ] = $data->toArray();
                }
                continue;
            }

            $out[ $k ] = $v;
        }

        $this->validateRequired();

        return $out;
    }


    public function toJson() : string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }


    /**
     * @return array
     * @throws ReflectionException
     */
    protected function getPublicProperties() : array
    {
        $reflect = new ReflectionClass($this);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $props = array_filter($props, function ($prop) {
            return ! in_array($prop->getName(), $this->hidden, true);
        });

        array_walk($props, static function (&$prop) {
            $prop = $prop->getName();
        });

        $props = array_merge($props, $this->append);

        $out = [];
        foreach ($props as $prop) {
            if ($this->$prop !== null) {
                $out[ $prop ] = $this->$prop;
            }
        }

        return $out;
    }


    protected function validateRequired() : void
    {
        foreach ($this->required as $required) {
            if ($this->$required === null) {
                throw new MissingPropertyException(
                    sprintf(
                        '`%s` requires the `%s` property to be set.',
                        strtolower((new ReflectionClass($this))->getShortName()),
                        $required
                    )
                );
            }
        }

        foreach ($this->enum as $prop => $enum) {
            if ($this->$prop !== null && ! in_array($this->$prop, $enum, true)) {
                throw new PropertyValueException(
                    strtolower((new ReflectionClass($this))->getShortName()),
                    $prop,
                    $this->enum[ $prop ],
                    $this->$prop
                );
            }
        }

        foreach ($this->strict_type as $prop => $type) {
            if ($this->$prop !== null && gettype($this->$prop) !== $type) {
                throw new InvalidTypeException(
                    strtolower((new ReflectionClass($this))->getShortName()),
                    $prop,
                    $this->strict_type[ $prop ],
                    gettype($this->$prop)
                );
            }
        }
    }


    public function __get(string $property)
    {
        return $this->get($property);
    }


    public function __toString() : string
    {
        return $this->toJson();
    }

}
