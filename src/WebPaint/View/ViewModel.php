<?php

namespace WebPaint\View;

class ViewModel
{
    protected $template;
    protected $layout;
    protected $vars = array();
    
    function __construct($vars = array(), $template = null, $layout = null)
    {
        $this->template = $template;
        $this->layout   = $layout;
        $this->vars     = $vars;
    }

    
    public function getTemplate()
    {
        return $this->template;
    }

    public function getLayout()
    {
        return $this->layout;
    }
    
    public function getVars()
    {
        return $this->vars;
    }
    
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

        public function setVariable($name, $value)
    {
        $this->vars[$name] = $value;
    }
}