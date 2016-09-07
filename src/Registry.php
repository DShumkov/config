<?php namespace DShumkov\Config;

interface Registry
{
    public function get($name);
    public function set($key, $value);
    public function getAll();
}
