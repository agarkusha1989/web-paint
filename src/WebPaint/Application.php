<?php

namespace WebPaint;

class Application
{
    /**
     * Application authentication service
     * 
     * @var Authentication\Authentication
     */
    protected $authentication;
    
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
    
    /**
     * Application front controller
     * 
     * @var Controller\Front
     */
    protected $front;
    
    /**
     * Application permissions
     * 
     * @var Permission\Permission
     */
    protected $persmission;
    
    /**
     * Router dispatcher instance
     * 
     * @var Router\RouterDispatcher
     */
    protected $routerDispatcher;
    
    /**
     * View renderer
     * 
     * @var View\Renderer
     */
    protected $viewRenderer;
    
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
    
    public function getAuthentication()
    {
        if (!($this->authentication instanceof Authentication\Authentication))
        {
            $config = $this->getConfig();
            if (!isset($config->authentication))
            {
                // default options
                $options = array();
            }
            else
            {
                $options = $config->authentication->toArray();
            }
            $dbAdapter = $this->getDbAdapter();
            
            $this->authentication = new Authentication\Authentication(
                    new Authentication\Storage(),
                    new Authentication\Adapter($dbAdapter, $options));
            
            $config = $this->getConfig();
            if (isset($config->authentication->identityClass))
            {
                $this->authentication->setIdentityClass($config->authentication->identityClass);
            }
        }
        return $this->authentication;
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
     * Get application front controller
     * 
     * @return Controller\Front
     */
    public function getFront()
    {
        if (!($this->front instanceof Controller\Front))
        {
            $this->front = new Controller\Front($this);
        }
        return $this->front;
    }
    
    /**
     * 
     * @return Permission\Permission
     */
    public function getPermission()
    {
        if (!($this->persmission instanceof Permission\Permission))
        {
            $config = $this->getConfig();
            if (!isset($config->permissions))
            {
                throw new \RuntimeException("Initialization error permissions, the configuration is empty or not installed");
            }
            $this->persmission = new Permission\Permission($config->permissions->toArray());
        }
        return $this->persmission;
    }
    
    /**
     * 
     * @return Router\RouterDispatcher
     * @throws \RuntimeException
     */
    public function getRouterDispatcher()
    {
        if (!($this->routerDispatcher instanceof Router\RouterDispatcher))
        {
            $config = $this->getConfig();
            if (!isset($config->router_rules))
            {
                throw new \RuntimeException("Initialization error router, the configuration is empty or not installed");
            }
            $this->routerDispatcher = new Router\RouterDispatcher($config->router_rules->toArray());
        }
        return $this->routerDispatcher;
    }
 
    /**
     * Get view renderer
     * 
     * @return View\Renderer
     * @throws \RuntimeException
     */
    public function getViewRenderer()
    {
        if (!($this->viewRenderer instanceof View\Renderer))
        {
            $this->viewRenderer = new View\Renderer($this);
        }
        return $this->viewRenderer;
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
        $route            = $this->getRoute();;
        $routerDispatcher = $this->getRouterDispatcher();
        $front            = $this->getFront();
        
        try 
        {
            $routerResult = $routerDispatcher->dispatch($route);

            $front->run($routerResult);
        }
        catch (\WebPaint\Router\RouteNotFound $exc) 
        {
            // dispatch failed create not found response
            $front->prepareNotFoundResponse($exc->getMessage());
        }
        
        $response = $front->getResponse();
        $statusCode = $response->getStatusCode();
        
        if ($statusCode != 200)
        {
            $message = $response->getContent();
            
            switch ($statusCode)
            {
                case 404:
                    $template = 'error/404err';
                    break;
                case 403:
                    $template = 'error/403err';
                    break;
                case 500:
                    $template = 'error/500err';
                    break;
                default:
                    $template = null;
            }
            $view = new View\ViewModel(array('message' => $message), $template);
            $content = $this->getViewRenderer()->render($view);
            $response->setContent($content);
        }
        
        // rendering application output
        $response->send();
    }
    
    public function getRoute()
    {
        return str_replace(
                '?' . $_SERVER['QUERY_STRING'], 
                '',
                str_replace(
                        $_SERVER['SCRIPT_NAME'],
                        '',
                        $_SERVER['REQUEST_URI']));
    }
}