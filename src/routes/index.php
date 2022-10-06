<?php

use App\Controllers\AuthController;
use App\Controllers\MemberController;

const MEMBERS = "/members";

$app->post('/login', AuthController::class . ':login');
$app->post('/register', AuthController::class . ':register');

$app->group('/api', function () use ($app) {
  // Members Route
  $app->get(MEMBERS, MemberController::class . ':index');
  $app->post(MEMBERS, MemberController::class . ':create');
  $app->delete(MEMBERS, MemberController::class . ':delete');
  $app->put(MEMBERS, MemberController::class . ':update');
});
