<?php
define('APP_HOST', '127.0.0.1/callendar');
define('APP_URL', 'http://127.0.0.1/callendar/');
define("DB_HOST", "127.0.0.1");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_NAME", "callendar_project");

class Authentication
{
  private mysqli $db;

  function __construct(mysqli $conn)
  {
    $this->db = $conn;
  }

  /**
   * Making sure it's not login or logout page
   */
  private function checkUrl(): bool
  {
    return !preg_match('/(login|logout)\/(index\.php){0,1}$/', $_SERVER['REQUEST_URI']);
  }

  /**
   * Checks if client has logged in
   */
  public function isAuthenticated(): bool
  {
    // Checks if sessions exists
    if (!(isset($_SESSION['user_id']) && isset($_SESSION['login_info']))) {
      return false;
    }
    // Checks if sessions are not empty
    if (empty($_SESSION['user_id']) || empty($_SESSION['login_info'])) {
      return false;
    }
    $userId = $this->db->real_escape_string($_SESSION['user_id']);
    $loginInfo = $this->db->real_escape_string($_SESSION['login_info']);

    // Checks if login info is valid
    $stmt = $this->db->prepare('SELECT id FROM users WHERE id = ? AND login_info = ?');
    $stmt->bind_param('ss', $userId, $loginInfo);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function signup(string $username, string $password): bool
  {
    $escUsername = $this->db->real_escape_string($username);
    $escPasword = password_hash($this->db->real_escape_string($password), PASSWORD_DEFAULT);

    $stmt = $this->db->prepare('INSERT INTO users (id,username,password) VALUES (UUID(),?,?)');
    $stmt->bind_param('ss', $escUsername, $escPasword);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
      return true;
    } else {
      return false;
    }
  }

  public function login(string $username, string $password): bool
  {
    session_start();

    $escUsername = $this->db->real_escape_string($username);
    $escPasword = $this->db->real_escape_string($password);

    $stmt = $this->db->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $escUsername);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!password_verify($escPasword, $res['password'])) {
      return false;
    }

    if ($stmt->get_result()->num_rows == 1) {
      $id = $res['id'];

      $loginInfo = hash('sha256', (new DateTime())->getTimestamp() . $id);

      $stmt = $this->db->prepare('UPDATE users SET login_info = ? WHERE id = ?');
      $stmt->bind_param('ss', $loginInfo, $id);
      $stmt->execute();

      if ($stmt->affected_rows == 1) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['login_info'] = $loginInfo;

        writeLog('Logged in');

        return true;
      }
    }
    return false;
  }

  public function logout()
  {
    session_destroy();
    writeLog('Logged out');
    return true;
  }
}

function writeLog($activity)
{
  global $DB;

  $username = $DB->real_escape_string($_SESSION['user_id']);
  $activity = $DB->real_escape_string($activity);

  $stmt = $DB->prepare('INSERT INTO `logs` (`user_id`, `activity`) VALUES (?,?)');
  $stmt->bind_param('ss', $username, $activity);
  $stmt->execute();
}

session_start();

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$auth = new Authentication($conn);