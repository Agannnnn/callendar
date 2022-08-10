<?php
require_once __DIR__ . "/../../config.php";

writeLog('Melakukan logout');

session_destroy();
header('Location: ' . APP_URL . 'login');
