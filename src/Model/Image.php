<?php

namespace Entity;

class Image
{
    public $id;
    public $user_id;
    public $image_sr;
    public $title;
    
    /**
     * Init class property from array
     * 
     * @param array $data
     */
    public function fromArray(array $data)
    {
        $this->id        = (!empty($data['id']) ? $data['id'] : null);
        $this->user_id   = (!empty($data['user_id']) ? $data['user_id'] : null);
        $this->image_src = (!empty($data['image_src']) ? $data['image_src'] : null);
        $this->title     = (!empty($data['title']) ? $data['title'] : null);
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