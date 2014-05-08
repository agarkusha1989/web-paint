<?php

namespace WebPaint\View;

class Renderer
{
    /**
     * A default rendering layout name
     * 
     * @var string
     */
    protected $layout;
    
    /**
     * A default rendering template name
     * 
     * @var string
     */
    protected $template;
    
    /**
     * Application view layouts directory
     * 
     * @var string
     */
    protected $layoutsDir;
    
    /**
     * Application view templates directory
     * 
     * @var string
     */
    protected $templatesDir;
    
    public function __construct(array $options)
    {
        if (!isset($options['layouts_dir']))
        {
            throw new \InvalidArgumentException("Invalid view renderer options arrray, not found option 'layouts_dir");
        }
        $layoutsDir = $options['layouts_dir'];
        if (!is_dir($layoutsDir))
        {
            throw new \InvalidArgumentException(sprintf(
                    "Invalid layouts dir %s not found",
                    $layoutsDir));
        }
        $this->layoutsDir = $layoutsDir;
        
        if (!isset($options['templates_dir']))
        {
            throw new \InvalidArgumentException("Invalid view renderer options arrray, not found option 'templates_dir");
        }
        $templatesDir = $options['templates_dir'];
        if (!is_dir($templatesDir))
        {
            throw new \InvalidArgumentException(sprintf(
                    "Invalid templates dir %s not found",
                    $templatesDir));
        }
        $this->templatesDir = $templatesDir;
        
        if (isset($options['layout']))
        {
            $file = $this->layoutsDir . '/' . $options['layout'] . '.phtml';
            if (!file_exists($file))
            {
                throw new \InvalidArgumentException(sprintf(
                        'Invalid layout name %s',
                        $options['layout']));
            }
            $this->layout = $options['layout'];
        }
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }
    
    public function render(ViewModel $view)
    {
        $content = '';
        $template = $view->getTemplate();
        if ($template == null && $this->template != null)
        {
            $template = $this->template;
        }
        
        if ($template != null)
        {
            $file = $this->templatesDir . '/' . $template . '.phtml';
            if (!file_exists($file))
            {
                throw new \RuntimeException(sprintf(
                        'Failed to render template %s, file %s not found',
                        $template, $file
                ));
            }
            $content = $this->renderPhpFile($file, $view->getVars());
        }
        
        $layout = $view->getLayout();
        if ($layout == null && $this->layout != null)
        {
            $layout = $this->layout;
        }
        if ($layout != null)
        {
            $file = $this->layoutsDir . '/' . $layout . '.phtml';
            if (!file_exists($file))
            {
                throw new \RuntimeException(sprintf(
                        'Failed to render layout %s, file %s not found',
                        $layout, $file
                ));
            }
            $content = $this->renderPhpFile($file, array('content' => $content));
        }
        
        return $content;
    }
    
    public function renderPhpFile($file, $variables = array())
    {
        ob_start();
        extract($variables);
        require $file;
        return ob_get_clean();
    }
}