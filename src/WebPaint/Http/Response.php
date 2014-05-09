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
}