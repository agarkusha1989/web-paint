<?php

/**
 * Application config.
 * 
 */
return array(
    'authentication' => array(
        'table' => 'users',
        'identityColumn' => 'email',
        'credentialColumn' => 'psswd',
        'encryptionAlgorythm' => 'md5',
        'identityClass' => 'Model\User',
    ),
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
        array(
            'name' => 'paint',
            'options' => array(
                'route' => '/paint',
                'defaults' => array(
                    'controller' => 'paint',
                    'action' => 'index',
                ),
            ),
        ),
        array(
            'name' => 'signup',
            'options' => array(
                'route' => '/signup',
                'defaults' => array(
                    'controller' => 'account',
                    'action' => 'signup',
                ),
            ),
        ),
        array(
            'name' => 'signin',
            'options' => array(
                'route' => '/signin',
                'defaults' => array(
                    'controller' => 'account',
                    'action' => 'signin',
                ),
            ),
        ),
        array(
            'name' => 'signout',
            'options' => array(
                'route' => '/signout',
                'defaults' => array(
                    'controller' => 'account',
                    'action' => 'signout',
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