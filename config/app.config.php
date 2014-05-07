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
    'front' => array(
        'controllers_dir' => dirname(__DIR__) . '/controller',
    ),
    'router_rules' => array(
        array(
            'name' => 'main',
            'options' => array(
                'route' => '/',
                'defaults' => array(
                    'controller' => 'main',
                    'action'     => 'index',
                ),
            ),
        ),
    ),
    'view' => array(
        'templates_dir' => dirname(__DIR__) . '/view/template',
        'layouts_dir' => dirname(__DIR__) . '/view/layout',
        'layout' => 'main',
    ),
);