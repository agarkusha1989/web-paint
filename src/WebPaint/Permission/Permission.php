<?php

namespace WebPaint\Permission;

class Permission
{
    protected $routes;
    protected $controllers;
    
    public function __construct($config)
    {
        if (isset($config['routes']))
        {
            $this->routes = $config['routes'];
        }
        if (isset($config['controllers']))
        {
            $this->controllers = $config['controllers'];
        }
    }
    
    public function routeIsAllowed($role, $route)
    {
        if (!isset($this->routes[$role])) return false;
        
        $rolePermissions = $this->routes[$role];
        
        return in_array($route, $rolePermissions);
    }
    
    public function controllerIsAllowed($role, $controller, $action = null)
    {
        if (!isset($this->controllers[$role])) return false;
        
        $rolePermissions = $this->controllers[$role];
        
        foreach ($rolePermissions as $permission)
        {
            if (is_array($permission))
            {
                if ($controller == $permission[0])
                {
                    if (count($permission) == 1) return true;
                    
                    if ($action != null && is_array($permission[1]))
                    {
                        if (in_array($action, $permission[1])) return true;
                    }
                    else
                    {
                        if ($action == $permission[1]) return true;
                    }
                }
            }
            else 
            {
                if ($controller == $permission) return true;
            }
        }
    }
}