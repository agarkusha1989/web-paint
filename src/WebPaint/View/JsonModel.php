<?php

namespace WebPaint\View;

class JsonModel
{
    protected $vars = array();
    
    public function getJson()
    {
        return json_encode($this->vars);
    }
    
    public function setVariable($name, $value)
    {
        $this->vars[$name] = $value;
    }
}