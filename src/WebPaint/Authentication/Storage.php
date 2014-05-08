<?php

namespace WebPaint\Authentication;

/**
 * Authentication storage
 */
class Storage
{
    protected $index = 'auth';
    
    public function __construct()
    {
        if (!session_id()) session_start();
    }
    
    public function isEmpty()
    {
        return !isset($_SESSION[$this->index]);
    }
    
    public function read()
    {
        return $_SESSION[$this->index];
    }
    
    public function write($data)
    {
        $_SESSION[$this->index] = $data;
    }
    
    public function clear()
    {
        unset($_SESSION[$this->index]);
    }
}