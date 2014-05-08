<?php

namespace WebPaint\View;

use WebPaint\Application;

abstract class HelperAbstract
{
    /**
     *
     * @var Application
     */
    protected $application;
    
    function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function __invoke()
    {
        return $this;
    }
}