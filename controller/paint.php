<?php

use Model\ImageTable;
use WebPaint\Controller\ControllerAbstract;
use WebPaint\View\JsonModel;

class PaintController extends ControllerAbstract
{
    protected $imageTable;
    
    /**
     * 
     * @return ImageTable
     */
    public function getImageTable()
    {
        if (!$this->imageTable)
        {
            $dbAdapter = $this->front->getApplication()->getDbAdapter();
            $this->imageTable = new ImageTable($dbAdapter);
        }
        return $this->imageTable;
    }
    
    public function indexAction()
    {
    }
    
    public function saveAction()
    {   
        $json       = new JsonModel();
        $imageTable = $this->getImageTable();
        $userId     = $this->front->getApplication()->getAuthentication()->getIdentity()->id;
        
        // process image data
        if (!isset($_GET['id']))
        {
            $json->setVariable('error', 'bad request, param id not found or null');
        }
        else if (0 == (int)($id = $_GET['id']))
        {
            $json->setVariable('error', 'bad request, id is not int or equals 0');
        }
        else if (!$imageTable->userIsAllow($id, $userId))
        {
            $json->setVariable('error', 'image with id ' . $id . ' not found or access denied from this user');
        }
        else
        {
            // Remove the headers (data:,) part.
            $filteredData = substr($GLOBALS['HTTP_RAW_POST_DATA'], strpos($GLOBALS['HTTP_RAW_POST_DATA'], ",")+1);

            // Need to decode before saving since the data we received is already base64 encoded
            $decodedData = base64_decode($filteredData);

            $imageTable->changeImage($id, $decodedData);
            
            $json->setVariable('status', 'true');
        }
        
        return $json;
    }
    
    public function saveasAction()
    {
        $json       = new JsonModel();
        $userId     = $this->front->getApplication()->getAuthentication()->getIdentity()->id;
        $imageTable = $this->getImageTable();
        $filename   = isset($_GET['filename']) ? $_GET['filename'] : null;
        
        // process image data
        if (empty($filename))
        {
            $json->setVariable('error', 'bad request filename required');
        }
        else
        {
            // Remove the headers (data:,) part.
            $filteredData = substr($GLOBALS['HTTP_RAW_POST_DATA'], strpos($GLOBALS['HTTP_RAW_POST_DATA'], ",")+1);

            // Need to decode before saving since the data we received is already base64 encoded
            $decodedData = base64_decode($filteredData);

            $id = $imageTable->createNew($userId, $decodedData, $filename);
            
            $json->setVariable('status', 'true');
            $json->setVariable('id', $id);
        }
        
        return $json;
    }
}