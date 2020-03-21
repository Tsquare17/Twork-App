<?php

namespace Twork\Query;

/**
 * Class Post
 * @package Twork\Query
 */
class Post
{
    /**
     * @var array Key-value store for magic post properties.
     */
    protected $properties = [];

    /**
     * @var false|int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->properties[$name];
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }
}
