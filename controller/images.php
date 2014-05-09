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
            return new WebPaint\View\ViewModel(array('message' => 'Image with id = ' . $id . ' not found'), 'error/404err');
        }
        else if (!$imageTable->userIsAllow($id, $userId))
        {
            $message = sprintf(
                    'Forbidden to access image with id = %d from user id %s',
                    $id, $userId);
            return new WebPaint\View\ViewModel(array('message' => $message), 'error/403err');
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
}