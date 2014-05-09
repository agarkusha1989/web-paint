<?php

namespace Model;

class ImageTable extends \WebPaint\Db\TableGateway
{
    protected $name = 'images';
    
    protected $resultSetClass = 'Model\Image';
    
    public function hasImage($id)
    {
        $statement = $this->dbAdapter->prepare(
                'select count(*) as "exists" from images where id = ?');
        $statement->execute(array($id));
        
        return (bool)$statement->fetchObject()->exists;
    }
    
    public function userIsAllow($id, $user_id)
    {
        $statement = $this->dbAdapter->prepare(
                'select count(*) as "exists" from images where id = ? and user_id = ?');
        $statement->execute(array($id, $user_id));
        
        return (bool)$statement->fetchObject()->exists;
    }
    
    public function changeImage($id, $data)
    {
        $statement = $this->dbAdapter->prepare(
                'update images set `image` = ? where id = ?');
        $statement->execute(array($data, $id));
    }
    
    public function createNew($userId, $imageData, $title)
    {
        $statement = $this->dbAdapter->prepare(
                'insert into images (user_id, image, title) values (?, ?, ?)');
        $statement->execute(array(
            $userId, $imageData, $title
        ));
        
        return $this->dbAdapter->lastInsertId();
    }
}