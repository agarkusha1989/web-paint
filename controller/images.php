<?php

use Model\ImageTable;
use WebPaint\Controller\ControllerAbstract;
use WebPaint\View\ImageModel as ImageViewModel;

class ImagesController extends \WebPaint\Controller\ControllerAbstract
{
    protected $imageTable;
    
    /**
     * 
     * @return ImageTable
     */
    protected function getImageTable()
    {
        if (!$this->imageTable)
        {
            $dbAdapter = $this->front->getApplication()->getDbAdapter();
            $this->imageTable = new ImageTable($dbAdapter);
        }
        return $this->imageTable;
    }
    
    public function loadAction()
    {
        $userId     = $this->front->getApplication()->getAuthentication()->getIdentity()->id;
        $id         = isset($_GET['id']) ? $_GET['id'] : null;
        $image      = new ImageViewModel();
        $imageTable = $this->getImageTable();
        
        if ($id == null)
        {
            return $this->notFoundAction('Image with id = ' . $id . ' not found');
        }
        else if (!$imageTable->userIsAllow($id, $userId))
        {
            return $this->forbiddenAction(sprintf(
                    'Forbidden to access image with id = %d from user id %s',
                    $id, $userId));
        }
        else
        {
            try {
                $imageData = $imageTable->getImageData($id);
            } catch(\Exception $e) {
                return new WebPaint\View\ViewModel(array('message' => $e->getMessage(), 'error/500err'));
            }
            
            $image->setData($imageData);
            
            return $image;
        }
    }
    
    public function indexAction()
    {
        $userId = $this->front->getApplication()->getAuthentication()->getIdentity()->id;
        
        return array(
            'images' => $this->getImageTable()->getUserImages($userId),
        );
    }
    
    public function changeAction()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $userId = $this->front->getApplication()->getAuthentication()->getIdentity()->id;
        
        if ($id == null)
        {
            return $this->badRequestAction("Bad request param id is required");
        }
        else if (!$this->getImageTable()->userIsAllow($id, $userId))
        {
            return $this->forbiddenAction(sprintf(
                    'Access denied for user id %d', $userId));
        }
        else
        {
            return array(
                'id' => $id,
            );
        }
    }
    
}