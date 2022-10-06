<?php

// Create and configure Slim app
return [
  'settings' => [
    'logErrors' => true,
    'logErrorDetails' => true,
    'displayErrorDetails' => true,
    'db' => [
      'host' => 'sqlite',
      'path' =>  __DIR__ . '/../database/index.db'
    ],
    'jwt' => [
      'key' => 'clRYGpjy4wWIKffjQ2oCSBBUYXMEBw7A'
    ]
  ]
];
