<?php

namespace WebPaint\Db;

class Adapter extends \PDO
{
    public function __construct($options)
    {
        if (!isset($options['dsn']))
        {
            throw new \InvalidArgumentException('Invalid database adapter construct options: undefined index "dsn"');
        }
        $dsn           = $options['dsn'];
        $username      = isset($options['username']) ? $options['username'] : '';
        $password      = isset($options['password']) ? $options['password'] : '';
        $driverOptions = isset($options['driver_options']) ? $options['driver_options'] : array();
        
        parent::__construct($dsn, $username, $password, $driverOptions);
    }
}