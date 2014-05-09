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
    'permissions' => array(
        'routes' => array(
            'guest' => array('main', 'signin', 'signup'),
            'user' => array(
                'main', 
                'images', 'images-load', 'images-change', 'images-delete',
                'paint', 'paint-save', 'paint-save-as', 
                'signout'),
        ),
        'controllers' => array(
            'guest' => array(
                'main', 
                array('account', array('signin', 'signup')),
            ),
            'user' => array(
                'main',
                array('account', array('signout')),
                array('paint', array('index', 'save', 'saveas')),
                'images',
            ),
        ),
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
            'name' => 'images',
            'options' => array(
                'route' => '/images',
                'defaults' => array(
                    'controller' => 'images',
                    'action' => 'index',
                ),
            ),
        ),
        array(
            'name' => 'images-load',
            'options' => array(
                'route' => '/images/load',
                'defaults' => array(
                    'controller' => 'images',
                    'action' => 'load',
                ),
            ),
        ),
        array(
            'name' => 'images-change',
            'options' => array(
                'route' => '/images/change',
                'defaults' => array(
                    'controller' => 'images',
                    'action' => 'change',
                ),
            ),
        ),
        array(
            'name' => 'images-delete',
            'options' => array(
                'route' => '/images/delete',
                'defaults' => array(
                    'controller' => 'images',
                    'action' => 'delete',
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
            'name' => 'paint-save',
            'options' => array(
                'route' => '/paint/save',
                'defaults' => array(
                    'controller' => 'paint',
                    'action' => 'save',
                ),
            ),
        ),
        array(
            'name' => 'paint-save-as',
            'options' => array(
                'route' => '/paint/saveas',
                'defaults' => array(
                    'controller' => 'paint',
                    'action' => 'saveas',
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