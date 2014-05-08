<?php

namespace Validator;

class SignupFormValidator
{
    /**
     *
     * @var \WebPaint\Db\Adapter
     */
    protected $dbAdapter;
    
    protected $messages;
    protected $data;
    
    public function getDbAdapter()
    {
        if (!$this->dbAdapter)
        {
            throw new \RuntimeException();
        }
        return $this->dbAdapter;
    }

    public function setDbAdapter(\WebPaint\Db\Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }
    
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
        else if ($this->hasEmail($data['email']))
        {
            $this->messages['email'] = 'User with this email address already exists';
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
        
        if (!isset($data['confirm']) || empty($data['confirm']))
        {
            $this->messages['confirm'] = 'Confirm password can not be empty';
        }
        else if (isset($data['password']) && $data['confirm'] != $data['password'])
        {
            $this->messages['confirm'] = 'Passwords do not match';
        }
        else
        {
            $this->data['confirm'] = $data['confirm'];
        }
        
        if (!isset($data['username']) || empty($data['username']))
        {
            $this->messages['username'] = 'Username can not be empty';
        }
        else 
        {
            $this->data['username'] = $data['username'];
        }
    }
    
    protected function hasEmail($email)
    {
        $statement = $this->dbAdapter->prepare(
                'select count(*) as "exists" from users where email = ?');
        $statement->execute(array($email));
        
        $row = $statement->fetch(\WebPaint\Db\Adapter::FETCH_ASSOC);
        
        return (bool)$row['exists'];
    }
}