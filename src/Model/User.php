<?php

namespace Model;

class User
{
    public $id;
    public $email;
    public $psswd;
    public $username;
    
    /**
     * Init class property from array
     * 
     * @param array $data
     */
    public function fromArray(array $data)
    {
        $this->id       = (!empty($data['id']) ? $data['id'] : null);
        $this->email    = (!empty($data['email']) ? $data['email'] : null);
        $this->psswd    = (!empty($data['psswd']) ? $data['psswd'] : null);
        $this->username = (!empty($data['username']) ? $data['username'] : null);
    }
    
    /**
     * Get array copy of object propertys
     * 
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}