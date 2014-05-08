<?php

namespace WebPaint\Authentication;

use WebPaint\Db\Adapter as DbAdapter;

/**
 * Authenticate database table adapter
 * 
 */
class Adapter
{
    
    protected $indentity;
    protected $credential;
    
    /**
     *
     * @var \WebPaint\Db\Adapter
     */
    protected $dbAdapter;
    
    /**
     * Database users table
     * 
     * @var string
     */
    protected $table = 'users';
    
    /**
     * Authenticate indentity value
     * 
     * @var string
     */
    protected $indentityColumn = 'email';
    
    /**
     * Authenticate credential value
     * 
     * @var string
     */
    protected $credentialColumn = 'psswd';
    
    /**
     * Password encryption algorythm
     * 
     * @var string
     */
    protected $encryptionAlgorythm = 'md5';
    
    /**
     * Authenticate row set data
     * 
     * @var array|null
     */
    protected $rowSet;
    
    function __construct(DbAdapter $dbAdapter, $table = null, $indentityColumn = null, $credentialColumn = null)
    {
        $this->dbAdapter = $dbAdapter;
        if ($table != null)
        {
            $this->table = $table;
        }
        if ($indentityColumn != null)
        {
            $this->indentityColumn = $indentityColumn;
        }
        if ($credentialColumn != null)
        {
            $this->credentialColumn = $credentialColumn;
        }
    }

    /**
     * Authenticate a identity and credential
     * 
     * @return boolean
     */
    public function authenticate()
    {
        $params = array($this->identity, $this->credential);
        
        $statement = $this->dbAdapter->prepare(
                'select * from ' . $this->table .
                ' where ' . $this->indentityColumn . ' = ?' .
                ' and ' . $this->credentialColumn . ' = ' . 
                $this->encryptionAlgorythm . '(?)');
        $statement->execute($params);
        
        if (!$rowSet = $statement->fetch(DbAdapter::FETCH_ASSOC))
        {
            return false;
        }
        else
        {
            $this->rowSet = $rowSet;
            return true;
        }
    }
    
    public function getRowSet()
    {
        return $this->rowSet;
    }
    
    public function setIndentity($indentity)
    {
        $this->indentity = $indentity;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

}