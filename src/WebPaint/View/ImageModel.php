<?php

namespace WebPaint\View;

class ImageModel
{
    protected $mimeType;
    protected $data;
    
    function __construct($mimeType = 'image/png', $data = null)
    {
        $this->mimeType = $mimeType;
        $this->data = $data;
    }

    
    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getData()
    {
        return $this->data;
    }
    
    public function setMimeType($mime)
    {
        $this->mimeType = $mime;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
}