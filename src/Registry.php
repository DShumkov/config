<?php namespace DShumkov\Config;

abstract class Registry
{
    protected static $values = [];

    public static function get($name)
    {
        return self::$values[$name];
    }

    public static function set($key, $value)
    {
        self::$values[$key] = $value;
    }
}
