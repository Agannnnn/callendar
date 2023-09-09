<?php
require_once __DIR__ . "/../../config.php";

$auth->logout();
header('Location: ' . APP_URL . 'login/');