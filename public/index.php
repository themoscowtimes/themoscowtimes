<?php

$_SERVER['HTTPS'] = 'on'; //off for dev env only

// Root path without symlinks
define('ROOT', realpath(__DIR__ . '/../') . '/');

// Composer autoloader
require ROOT . 'vendor/autoload.php';

// Config paths
if(Sulfur\Manager\Detect::manager()){
	$paths = require ROOT . 'config/manager/config.php';
} else {
	$paths = require ROOT . 'config/config.php';
}

// Handle http request
Sulfur\App::http($paths, require ROOT . 'config/env.php');
