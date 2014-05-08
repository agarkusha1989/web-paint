<?php

namespace WebPaint\Db;

class TableGateway
{
    /**
     *
     * @var Adapter
     */
    protected $dbAdapter;
    
    /**
     * Table name
     * 
     * @var string
     */
    protected $name;
    
    /**
     * Result set class
     * 
     * @var string
     */
    protected $resultSetClass = '\stdClass';
    
    function __construct(Adapter $dbAdapter, $options = null)
    {
        $this->dbAdapter = $dbAdapter;
        if ($options != null)
        {
            if (isset($options['name']))
            {
                $this->name = $options['name'];
            }
            if (isset($options['resultSetClass']))
            {
                $this->resultSetClass = $options['resultSetClass'];
            }
        }
    }
    
    public function find($id)
    {
        $statement = $this->dbAdapter->prepare(
                'select * from ' . $this->name . ' where id = ?');
        $statement->execute(array($id));
        
        if (!$row = $statement->fetch(Adapter::FETCH_ASSOC))
        {
            throw new \Exception('Could not find row with id ' . $id);
        }
        
        return $this->createResultSet($row);
    }
    
    public function insert($data)
    {
    }
    
    public function update($data, $id)
    {
    }
    
    public function delete($id)
    {
    }
    
    protected function createResultSet($row)
    {
        if ($this->resultSetClass == null || !class_exists($this->resultSetClass))
        {
            return $row;
        }
        
        $class = $this->resultSetClass;
        $object = new $class();
        
        if (method_exists($object, 'fromArray'))
        {
            $object->fromArray($row);
        }
        else
        {
            foreach ($row as $name => $value)
            {
                $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
                if (method_exists($object, $setter))
                {
                    $object->$setter($value);
                }
                else
                {
                    $object->$name = $value;
                }
            }
        }
        
        return $object;
    }
}