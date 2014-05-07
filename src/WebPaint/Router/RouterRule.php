<?php

namespace WebPaint\Router;

class RouterRule
{
    protected $name;
    protected $options = array();
    
    public function __construct($name, array $options)
    {
        $this->name    = $name;
        $this->options = $options;
        
        if (!$this->hasOption('route'))
        {
            throw new \InvalidArgumentException('Invalid router options, was missed a required option "route"');
        }
    }
    
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }
    
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }
    
    public function match($route)
    {
        // FIXME router rule match
        if ($this->getOption('route') != $route)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}