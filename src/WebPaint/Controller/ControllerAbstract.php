<?php

namespace WebPaint\Controller;

use WebPaint\Http\Response;

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
    
    public function forbiddenAction($message = '')
    {
        return new Response(403, $message);
    }
    
    public function errorAction($message = '')
    {
        return new Response(500, $message);
    }
    
    public function notFoundAction($message = '')
    {
        return new Response(404, $message);
    }
    
    protected function render($template, $vars = array())
    {
        ob_start();
        extract($vars);
        require_once $template;
        return ob_get_clean();
    }
}