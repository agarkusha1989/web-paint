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
    
    /**
     * Controllers directory
     * 
     * @var string
     */
    protected $controllersDir;
    
    /**
     *
     * @var mixed
     */
    protected $response;
    
    public function __construct(Application $app)
    {
        $this->application = $app;
        $config = $this->application->getConfig();
        if (!isset($config->front->controllers_dir))
        {
            throw new \RuntimeException('Failed to initialize the front, not specified controllers directory');
        }
        $this->controllersDir = $config->front->controllers_dir; 
    }
    
    /**
     * Get running application instance
     * 
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function run(RouterResult $routerResult)
    {
        $file = $this->controllersDir . '/' . $routerResult->getController() . '.php';
        
        if (!file_exists($file))
        {
            return $this->prepareNotFoundResponse();
        }
        require_once $file;
        $class = str_replace('/', "\\", str_replace(' ', '', ucwords(str_replace('-', ' ', $routerResult->getController())))) . 'Controller';
        if (!class_exists($class, false))
        {
            return $this->prepareNotFoundResponse();
        }
        $controller = new $class($front);
        $method = str_replace(' ', '', ucwords(str_replace('-', ' ', $routerResult->getAction()))) . 'Action';
        if (!method_exists($controller, $method))
        {
            return $this->prepareNotFoundResponse();
        }
        
        $view = call_user_func(array($controller, $method));
        if ($view == null)
        {
            $view = new \WebPaint\View\ViewModel();
        } 
        else if (is_array($view))
        {
            $view = new \WebPaint\View\ViewModel($view);
        }
        $renderer = $this->application->getViewRenderer();
        $renderer->setTemplate($routerResult->getController() . '/' . $routerResult->getAction());
        
        $content = $renderer->render($view);
        
        $this->response = $content;
    }
    
    public function prepareNotFoundResponse()
    {
        return $this->response = 'not found';
    }
}