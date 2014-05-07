<?php

namespace WebPaint;

class Application
{
    /**
     * Application config container
     * 
     * @var Config\Container
     */
    protected $config;
    
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
     * Get application config container
     * 
     * @return Config\Container
     */
    public function getConfig()
    {
        return $this->config;
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
        echo 'Web paint is running!';
    }
}