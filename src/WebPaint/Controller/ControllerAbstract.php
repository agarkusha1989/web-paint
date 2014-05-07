<?php

namespace WebPaint\Controller;

abstract class ControllerAbstract
{
    public function notFoundAction()
    {
    }
    
    protected function render($template, $vars = array())
    {
        ob_start();
        extract($vars);
        require_once $template;
        return ob_get_clean();
    }
}