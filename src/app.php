<?php

use \Slim\App;

require_once __DIR__ . './../vendor/autoload.php';

$config = require_once __DIR__ . './config/settings.php';

$app = new App($config);

// Dependencies
require_once __DIR__ . './config/dependencies.php';

// Middleware
require_once __DIR__ . './config/middleware.php';

// Routes
require_once __DIR__ . './routes/index.php';
