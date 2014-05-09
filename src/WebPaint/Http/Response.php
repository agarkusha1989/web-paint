<?php

namespace WebPaint\Http;

class Response
{
    protected $statusCode;
    protected $content;
    protected $headers;
    
    function __construct($statusCode = 200, $content = '', $headers = array())
    {
        $this->statusCode = $statusCode;
        $this->content    = $content;
        $this->headers    = $headers;
    }
    
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function send()
    {
        if (function_exists('http_response_code'))
        {
            http_response_code($this->statusCode);
        }
        else
        {
            heaader('HTTP/1.1 ' . $this->statusCode);
        }
        
        foreach ($this->headers as $header)
        {
            header($header);
        }
        
        echo $this->content;
    }
    
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }
}