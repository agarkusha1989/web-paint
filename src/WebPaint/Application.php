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