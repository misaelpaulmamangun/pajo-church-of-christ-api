<?php

$container = $app->getContainer();

// Database Connection
$container['db'] = function ($container) {
  $host = $container['settings']['db']['host'];
  $path = $container['settings']['db']['path'];

  return new PDO("$host:$path");
};

// Activating routes in a subfolder
$container['environment'] = function () {
  $scriptName = $_SERVER['SCRIPT_NAME'];
  $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);

  return new Slim\Http\Environment($_SERVER);
};
