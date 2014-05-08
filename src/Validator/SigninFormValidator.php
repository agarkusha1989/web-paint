<?php

namespace Validator;

class SigninFormValidator
{
    protected $messages;
    protected $data;
    
    public function isValid()
    {
        return (count($this->messages) == 0);
    }
    
    public function getMessages()
    {
        return $this->messages;
    }

    public function getData()
    {
        return $this->data;
    }
    
    public function validate($data)
    {
        $this->messages = array();
        $this->data     = array();
        
        if (!isset($data['email']) || empty($data['email']))
        {
            $this->messages['email'] = 'Email can not be empty';
        }
        else if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-]+\@[a-zA-Z0-9\.\-_]+$/', $data['email']))
        {
            $this->messages['email'] = 'Incorrect email format';
        }
        else
        {
            $this->data['email'] = $data['email'];
        }
        
        if (!isset($data['password']) || empty($data['password']))
        {
            $this->messages['password'] = 'Password can not be empty';
        }
        else 
        {
            $this->data['password'] = $data['password'];
        }
    }
}