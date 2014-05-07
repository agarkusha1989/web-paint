<?php

namespace WebPaint;

class Application
{
    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'));
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