<?php

namespace WebPaint\Config;

/**
 * Config container class
 * 
 */
class Container
{
    /**
     * Config array data
     * 
     * @var array
     */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = array();
        foreach ($data as $name => $value)
        {
            if (is_array($value))
            {
                $this->data[$name] = new static($value);
            }
            else
            {
                $this->data[$name] = $value;
            }
        }
    }
    
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    
    /**
     * Method converts the internal representation of an array in php
     * 
     * @return array
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->data as $name => $value)
        {
            if ($value instanceof static)
            {
                $array[$name] = $value->toArray();
            }
            else
            {
                $array[$name] = $value;
            }
        }
        return $array;
    }
}