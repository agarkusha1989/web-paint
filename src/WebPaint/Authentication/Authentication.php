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
    
    protected $identityClass = '\stdClass';
    
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
        $array = $this->storage->read();
        
        if (!class_exists($this->identityClass))
        {
            return $array;
        }
        $class = $this->identityClass;
        $identity = new $class();
        if (method_exists($identity, 'fromArray'))
        {
            $identity->fromArray($array);
        }
        else
        {
            foreach ($array as $name => $value)
            {
                $setterMethod = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
                
                if (method_exists($identity, $setterMethod))
                {
                    $identity->$setterMethod($value);
                }
                $identity->$name = $value;
            }
        }
        return $identity;
    }
    
    public function hasIdentity()
    {
        return !$this->storage->isEmpty();
    }
    
    public function setIdentityClass($identityClass)
    {
        $this->identityClass = $identityClass;
    }

}