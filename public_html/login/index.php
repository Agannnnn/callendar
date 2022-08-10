<?php
require_once __DIR__ . "/../../config.php";

if (Authentication::check()) {
  header('Location: ' . APP_URL);
  die();
}

function handleGet()
{
  require_once __DIR__ . "/../../view/login.php";
}

function handlePost()
{
  global $DB;

  $username = $DB->real_escape_string($_POST['username']);
  $password = md5($DB->real_escape_string($_POST['password']));
  $loginInfo = md5(date('D/n/Y G:i:s') . $username . $password);

  $stmt = $DB->prepare("UPDATE `akun` SET `info_login` = ? WHERE `username` = ? AND `password` = ?");
  $stmt->bind_param('sss', $loginInfo, $username, $password);
  $stmt->execute();

  if ($stmt->execute()) {
    session_regenerate_id(true);

    $_SESSION['login_info'] = $loginInfo;
    $_SESSION['username'] = $username;

    writeLog('Melakukan login');

    header('Location: ' . APP_URL);
    return;
  }
  $error = true;
  return require_once __DIR__ . '/../../view/login.php';
}

if (preg_match('/^(GET|get)$/', $_SERVER['REQUEST_METHOD'])) {
  HandleGet();
}
if (preg_match('/^(POST|post)$/', $_SERVER['REQUEST_METHOD'])) {
  HandlePost();
}
