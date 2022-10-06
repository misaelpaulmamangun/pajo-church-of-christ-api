<?php

$container = $app->getContainer();

$app->add(new \Tuupola\Middleware\CorsMiddleware([
  "origin" => ["*"],
  "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
  "headers.allow" => ["Accept", "Content-Type"],
  "headers.expose" => [],
  "credentials" => false,
  "cache" => 0,
]));

$app->add(new Tuupola\Middleware\JwtAuthentication([
  "path" => "/api",
  "secure" => false,
  "secret" => $container['settings']['jwt']['key'],
  "error" => function ($res, $args) {
    return $res->withJSON([
      'success' => false,
      'message' => $args['message'],
      'status' => 401
    ]);
  }
]));
