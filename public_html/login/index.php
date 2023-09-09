<?php
require_once __DIR__ . "/../../config.php";

function GET()
{
  require_once __DIR__ . "/../../view/login.php";
}

function POST()
{
  global $auth;

  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($auth->login($username, $password)) {
    return header('Location: ' . APP_URL);
  }
  $error = true;
  return require_once __DIR__ . '/../../view/login.php';
}

if (preg_match('/^(GET|get)$/', $_SERVER['REQUEST_METHOD'])) {
  GET();
}
if (preg_match('/^(POST|post)$/', $_SERVER['REQUEST_METHOD'])) {
  POST();
}