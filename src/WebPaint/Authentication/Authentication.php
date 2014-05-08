<?php

namespace WebPaint\Authentication;

class Authentication
{
    /**
     * Authentication adapter
     * 
     * @var Adapter
     */
    protected $adapter;
    
    /**
     * Authentication storage
     * 
     * @var Storage
     */
    protected $storage;
    
    public function __construct(Storage $storage, Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->storage = $storage;
    }

    public function authenticate()
    {
        if ($this->hasIdentity())
        {
            return true;
        }
        else if ($this->adapter->authenticate())
        {
            $this->storage->write($this->adapter->getRowSet());
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * 
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    public function clearIdentity()
    {
        $this->storage->clear();
    }
    
    public function getIdentity()
    {
        return $this->storage->read();
    }
    
    public function hasIdentity()
    {
        return !$this->storage->isEmpty();
    }
}