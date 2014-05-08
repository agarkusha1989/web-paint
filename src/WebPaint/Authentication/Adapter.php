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
    
    function __construct(DbAdapter $dbAdapter, $options = array())
    {
        $this->dbAdapter = $dbAdapter;
        if (isset($options['table']))
        {
            $this->table = $table;
        }
        if (isset($options['indentityColumn']))
        {
            $this->indentityColumn = $indentityColumn;
        }
        if (isset($options['credentialColumn']))
        {
            $this->credentialColumn = $credentialColumn;
        }
        if (isset($options['encryptionAlgorythm']))
        {
            $this->encryptionAlgorythm = $options['encryptionAlgorythm'];
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