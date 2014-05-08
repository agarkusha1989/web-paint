<?php

namespace WebPaint\View\Helper;

use WebPaint\View\HelperAbstract;

class Authentication extends HelperAbstract
{
    public function hasIdentity()
    {
        return $this->application->getAuthentication()->hasIdentity();
    }
    
    public function getIdentity()
    {
        return $this->application->getAuthentication()->getIdentity();
    }
}