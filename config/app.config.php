<?php

/**
 * Application config.
 * 
 */
return array(
    'db' => array(
        'dsn'            => 'mysql:dbname=web_paint_db;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8',
        ),
        // TODO add your db username and password
        'username'       => 'root', 
        'password'       => 'toor', 
    ),
);