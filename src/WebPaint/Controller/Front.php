<?php

namespace WebPaint\Controller;

use WebPaint\Application;
use WebPaint\Router\RouterResult;

class Front
{
    /**
     * Running application instance
     * 
     * @var \WebPaint\Application
     */
    protected $application;
    
    public function __construct(Application $app)
    {
        $this->application = $app;
    }
    
    public function run(RouterResult $routerResult)
    {
        
    }
}