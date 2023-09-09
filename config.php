<?php
define('APP_HOST', '127.0.0.1/callendar');
define('APP_URL', 'http://127.0.0.1/callendar/');
define("DB_HOST", "127.0.0.1");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "callendar_project");

$DB = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

class Authentication
{
  static function check()
  {
    global $DB;

    if (!isset($_SESSION['username']) || !isset($_SESSION['login_info'])) {
      if (!preg_match('/(login|logout)\/(index\.php){0,1}$/', $_SERVER['REQUEST_URI'])) {
        header('Location: ' . APP_URL . 'login/');
      }
      return false;
    }

    $username = $DB->real_escape_string($_SESSION['username']);
    $stmt = $DB->prepare("SELECT `info_login` FROM `akun` WHERE `username` = ?");
    $stmt->bind_param('s', $username);
    if (!$stmt->execute()) {
      if (!preg_match('/(login|logout)\/(index\.php){0,1}$/', $_SERVER['REQUEST_URI'])) {
        header('Location: ' . APP_URL . 'login/');
      }
      return false;
    }

    $result = $stmt->get_result();
    $loginInfo = $result->fetch_assoc();

    if ($loginInfo['info_login'] != $_SESSION['login_info']) {
      if (!preg_match('/(login|logout)\/(index\.php){0,1}$/', $_SERVER['REQUEST_URI'])) {
        header('Location: ' . APP_URL . 'login/');
      }
      return false;
    }
    return true;
  }
}

function writeLog($message)
{
  global $DB;

  $username = $DB->real_escape_string($_SESSION['username']);
  $message = $DB->real_escape_string($message);

  $stmt = $DB->prepare('INSERT INTO `logs` (`username`, `pesan`) VALUES (?,?)');
  $stmt->bind_param('ss', $username, $message);
  $stmt->execute();
}

session_start();
