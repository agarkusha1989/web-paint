<?php

namespace WebPaint\Router;

class RouterDispatcher
{
    /**
     *
     * @var RouterRule[]
     */
    protected $rules;
    
    public function __construct($rules = array())
    {
        $this->rules = array();
        foreach ($rules as $rule)
        {
            $this->addRule($rule);
        }
    }
    
    /**
     * Dispatch route 
     * 
     * @param type $route
     * @return \WebPaint\Router\RouterResult
     * @throws RouteNotFound
     */
    public function dispatch($route)
    {
        foreach ($this->rules as $rule)
        {
            if ($rule->match($route))
            {
                $defaults = $rule->getOption('defaults');
                
                if (!isset($defaults['controller']))
                {
                    throw new RouteNotFound(sprintf(
                            "Unable to determine the controller for the route %s",
                            $route
                    ));
                }
                $controller = $defaults['controller'];
                
                if (!isset($defaults['action']))
                {
                    throw new RouteNotFound(sprintf(
                            "Unable to determine the action of the controller %s for the route %s",
                            $controller, $route));
                }
                $action     = $defaults['action'];
                
                return new RouterResult($rule, $route, $controller, $action);
            }
        }
        
        throw new RouteNotFound($route);
    }
    
    public function addRule($rule)
    {
        if (is_array($rule))
        {
            if (!isset($rule['name']) || !isset($rule['options']) 
                    || !is_array($rule['options']))
            {
                throw new \InvalidArgumentException("Invalid router rule, was passed an array not containing the required keys 'name' and/or 'options'");
            }
            $name    = $rule['name'];
            $options = $rule['options'];
            $rule    = new RouterRule($name, $options);
        }
        
        $this->rules[] = $rule;
    }
}