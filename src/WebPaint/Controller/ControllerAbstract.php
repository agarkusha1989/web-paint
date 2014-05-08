<?php

namespace WebPaint\Controller;

abstract class ControllerAbstract
{
    /**
     * Application front controller
     * 
     * @var Front
     */
    protected $front;
    
    public function __construct(Front $front)
    {
        $this->front = $front;
    }
    
    public function notFoundAction()
    {
        return $this->front->prepareNotFoundResponse();
    }
    
    protected function render($template, $vars = array())
    {
        ob_start();
        extract($vars);
        require_once $template;
        return ob_get_clean();
    }
}