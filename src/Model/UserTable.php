<?php

namespace Model;

class UserTable extends \WebPaint\Db\TableGateway
{
    protected $name = 'users';
    
    protected $resultSetClass = 'Model\User';
    
    public function signup($data)
    {
        $statement = $this->dbAdapter->prepare(
                'insert into ' . $this->name . ' (email, psswd, username) values (?, ?, ?)');
        $statement->execute(array($data['email'], $data['psswd'], $data['username']));
        
        return $this->dbAdapter->lastInsertId();
    }
}