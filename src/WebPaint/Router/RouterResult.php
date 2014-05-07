<?php

namespace WebPaint\Router;

class RouterResult
{
    /**
     *
     * @var RouterRule
     */
    protected $matchedRouterRule;
    
    protected $route;
    protected $controller;
    protected $action;
    
    function __construct(RouterRule $matchedRouterRule, $route, $controller, $action)
    {
        $this->matchedRouterRule = $matchedRouterRule;
        $this->route             = $route;
        $this->controller        = $controller;
        $this->action            = $action;
    }

    
    public function getAction()
    {
        return $this->action;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * 
     * @return RouterRule
     */
    public function getMatchedRouterRule()
    {
        return $this->matchedRouterRule;
    }
    
    public function getRoute()
    {
        return $this->route;
    }
}