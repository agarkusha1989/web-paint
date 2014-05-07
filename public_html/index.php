<?php

chdir(dirname(__DIR__));

require_once 'src/WebPaint/Application.php';

$app = new WebPaint\Application('config/app.config.php');
$app->run();