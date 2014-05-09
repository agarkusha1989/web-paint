<?php

namespace Model;

use WebPaint\Db\Adapter;

class ImageTable extends \WebPaint\Db\TableGateway
{
    protected $name = 'images';
    
    protected $resultSetClass = 'Model\Image';
    
    public function deleteImage($id)
    {
        $statement = $this->dbAdapter->prepare(
                'delete from images where id = ?');
        $statement->execute(array($id));
    }
    
    public function getImageData($id)
    {
        $statement = $this->dbAdapter->prepare(
                'select image from images where id = ?');
        $statement->execute(array($id));
        
        if (!$row = $statement->fetch(Adapter::FETCH_ASSOC))
        {
            throw new \Exception('Could not get image data with image id ' . $id);
        }
        
        return $row['image'];
    }
    
    public function getUserImages($userId)
    {
        $statement = $this->dbAdapter->prepare(
                'select id, title from images where user_id = ?');
        $statement->execute(array($userId));
        
        $rowSet = array();
        while ($row = $statement->fetch(\WebPaint\Db\Adapter::FETCH_ASSOC))
        {
            $rowSet[] = $this->createResultSet($row);
        }
        return $rowSet;
    }
    
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