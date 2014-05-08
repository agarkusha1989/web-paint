<?php

class PaintController extends WebPaint\Controller\ControllerAbstract
{
    public function indexAction()
    {
        $authentication = $this->front->getApplication()->getAuthentication();
        
        if (!$authentication->hasIdentity())
        {
            header('Location: /signin');
        }
        else
        {
            
        }
    }
}