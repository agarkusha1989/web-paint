<?php

namespace WebPaint\View;

use WebPaint\Application;

class HelperManager
{
    /**
     * Running application
     * 
     * @var Application
     */
    protected $application;
    
    /**
     * Loaded helper instances
     * 
     * @var array
     */
    protected $helpers = array();
    
    protected $aliases = array(
        'authentication' => 'WebPaint\View\Helper\Authentication',
    );
    
    public function __construct(Application $application)
    {
        $this->application = $application;
    }
    
    public function get($name)
    {
        if (!$this->has($name))
        {
            if (isset($this->aliases[$name]))
            {
                $name = $this->aliases[$name];
            }
            
            if (!class_exists($name))
            {
                throw new \InvalidArgumentException(sprintf(
                        'Error get view helper, helper %s not found',
                        $name
                ));
            }
            
            $this->helpers[$name] = new $name($this->application);
        }
        return $this->helpers[$name];
    }
    
    public function has($name)
    {
        return isset($this->helpers[$name]);
    }
}