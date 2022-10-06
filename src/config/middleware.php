<?php

$container = $app->getContainer();


$app->options('/{routes:.+}', function ($request, $response) {
  return $response;
});

$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
    ->withHeader('Access-Control-Allow-Origin', '*')
    ->withHeader(
      'Access-Control-Allow-Headers',
      'X-Requested-With, Content-Type, Accept, Origin, Authorization'
    )
    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->add(new Tuupola\Middleware\JwtAuthentication([
  "path" => "/test1",
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
