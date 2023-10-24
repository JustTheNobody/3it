<?php

use app\AppContainer;
use Models\ConnectDB;
use Controllers\HandleController;

require 'vendor/autoload.php';

$appContainer = new AppContainer();

$db = new ConnectDB();
$connect = $db->makeConnection( $appContainer->get('db') );

$controller = new HandleController( $connect );
$controller->handleRequest();