<?php

namespace WebPaint\Controller;

use WebPaint\Application;
use WebPaint\Router\RouterResult;
use WebPaint\View\ViewModel;
use WebPaint\Http\Response;
use WebPaint\View\JsonModel;

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
     * @var \Http\Response
     */
    protected $response;
    
    /**
     *
     * @var \WebPaint\View\ViewModel
     */
    protected $controllerResult;
    
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
    
    /**
     * Get response
     * 
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    protected function checkPermissions(RouterResult $routerResult)
    {
        $authentication = $this->application->getAuthentication();
        $permission     = $this->application->getPermission();
        
        $role           = $authentication->hasIdentity() ? 'user' : 'guest';
        $route          = $routerResult->getMatchedRouterRule()->getName();
        $controller     = $routerResult->getController();
        $action         = $routerResult->getAction();
        
        if (!$permission->routeIsAllowed($role, $route))
        {
            // access denied
            $this->prepareForbiddenResponse(sprintf(
                    'Access to route %s from user role %s denied!',
                    $route, $role));
            return false;
        }
        if (!$permission->controllerIsAllowed($role, $controller, $action))
        {
            // access denied
            $this->prepareNotFoundResponse(sprintf(
                    'Access to controller %s  and/or action %s from user role %s denied!',
                    $controller, $action, $role));
            return false;
        }
        
        return true;
    }
    
    public function run(RouterResult $routerResult)
    {
        // if access denied return to application
        if (!$this->checkPermissions($routerResult))
        {
            return ;
        }
     
        // run controller
        if (!$this->runController($routerResult))
        {
            return ;
        }
        
        // check controller result
        if ($this->controllerResult == null)
        {
            $this->controllerResult = new ViewModel();
        }
        else if (is_array($this->controllerResult))
        {
            $this->controllerResult = new ViewModel($this->controllerResult);
        }
        
        // prepare response
        if ($this->controllerResult instanceof ViewModel)
        {
            // render view
            $renderer = $this->application->getViewRenderer();
            $renderer->setTemplate($routerResult->getController() . '/' . $routerResult->getAction());
            $content = $renderer->render($this->controllerResult);
        
            $this->response = new Response(200, $content);
        }
        else if ($this->controllerResult instanceof JsonModel)
        {
            // create json response
            $headers = array('Content-Type: application/json');
            $content = $this->controllerResult->getJson();
            
            $this->response = new Response(200, $content, $headers);
        }
    }
    
    protected function runController(RouterResult $routerResult)
    {
        // controller filename
        $file = $this->controllersDir . '/' . $routerResult->getController() . '.php';     
        if (!file_exists($file))
        {
            $this->prepareNotFoundResponse(sprintf(
                    'Not found filename %s of controller %s',
                    $file, $routerResult->getController()));
            return ;
        }
        require_once $file;
        
        // controller classname
        $class = str_replace('/', "\\", str_replace(' ', '', ucwords(str_replace('-', ' ', $routerResult->getController())))) . 'Controller';
        if (!class_exists($class, false))
        {
            $this->prepareNotFoundResponse(sprintf(
                    'Not found controller class %s in file %s',
                    $class, $file));
            return ;
        }
        
        // instance object
        $controller = new $class($this);
        
        // check method
        $method = str_replace(' ', '', ucwords(str_replace('-', ' ', $routerResult->getAction()))) . 'Action';
        if (!method_exists($controller, $method))
        {
            $this->prepareNotFoundResponse(sprintf(
                    'Not found controller %s action %s in file %s',
                    $controller, $routerResult->getAction(), $file));
            return false;
        }
        
        $this->controllerResult = call_user_func(array($controller, $method));
        return true;
    }
    
    public function prepareNotFoundResponse($message = '')
    {
        return ($this->response = new Response(404, $message));
    }
    
    public function prepareForbiddenResponse($message = '')
    {
        return ($this->response = new Response(403, $message));
    }
}