<?php

namespace WebPaint;

class Application
{
    /**
     * Application db adapter instance
     * 
     * @var Db\Adapter;
     */
    protected $dbAdapter;
    
    /**
     * Application config container
     * 
     * @var Config\Container
     */
    protected $config;
    
    /**
     * Router dispatcher instance
     * 
     * @var Router\RouterDispatcher
     */
    protected $routerDispatcher;
    
    public function __construct($configFilename)
    {
        spl_autoload_register(array($this, 'loadClass'));
        
        if (!file_exists($configFilename))
        {
            throw new \InvalidArgumentException(sprintf(
                    "Config filename %s not found, application init failed", 
                    $configFilename
            ));
        }
        
        $this->config = new Config\Container(include $configFilename);
    }
    
    /**
     * Get application db adapter instance
     * 
     * @return Db\Adapter
     * @throws \RuntimeException
     */
    public function getDbAdapter()
    {
        if (!($this->dbAdapter instanceof Db\Adapter))
        {
            $config = $this->getConfig();
            if (!isset($config->db))
            {
                throw new \RuntimeException("Initialization error database, the configuration is empty or not installed");
            }
            $this->dbAdapter = new Db\Adapter($config->db->toArray());
        }
        return $this->dbAdapter;
    }
    
    /**
     * Get application config container
     * 
     * @return Config\Container
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    public function getRouterDispatcher()
    {
        if (!($this->routerDispatcher instanceof Router\RouterDispatcher))
        {
            $config = $this->getConfig();
            if (!isset($config->router_rules))
            {
                throw new \RuntimeException("Initialization error router, the configuration is empty or not installed");
            }
            $this->routerDispatcher = new Router\RouterDispatcher($config->router_rules->toArray());
        }
        return $this->routerDispatcher;
    }
    
    /**
     * Method to load classes
     * 
     * @param string $class 
     */
    public function loadClass($class)
    {
        $filename = dirname(__DIR__) . '/' . str_replace("\\", '/', $class) . '.php';
        if (file_exists($filename))
        {
            require_once $filename;
        }
    }
    
    /**
     * The run application!
     */
    public function run()
    {
        // FIXME get requested route
        $route = $_SERVER['REQUEST_URI'];
        
        $routerDispatcher = $this->getRouterDispatcher();
        
        try 
        {
            $result = $routerDispatcher->dispatch($route);
        }
        catch (\WebPaint\Router\RouteNotFound $exc) 
        {
            // FIXME dispatch failed
            
        }
        
        // TODO rendering application output
        echo 'Web paint is running!';
    }
}