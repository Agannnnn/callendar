<?php
require_once __DIR__ . "/../../config.php";

function GET()
{
    require_once __DIR__ . "/../../view/signup.php";
}

function POST()
{
    global $auth;

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->signup($username, $password)) {
        header('Location: ' . APP_URL . 'login/');
        return;
    }
    $error = true;
    return require_once __DIR__ . '/../../view/signup.php';
}

if (preg_match('/^(GET|get)$/', $_SERVER['REQUEST_METHOD'])) {
    GET();
}
if (preg_match('/^(POST|post)$/', $_SERVER['REQUEST_METHOD'])) {
    POST();
}