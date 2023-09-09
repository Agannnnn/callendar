<?php
require_once __DIR__ . "/../config.php";

if (!$auth->isAuthenticated()) {
  $auth->logout();
  header('Location: ' . APP_URL . 'login/');
  exit;
}

function GET()
{
  global $conn;

  $m = $_GET['m'] ?? "";
  $y = $_GET['y'] ?? "";
  if ($m == "") {
    $m = getdate()['mon'];
  }
  if ($y == "") {
    $y = getdate()['year'];
  }

  $nextMonth = $m + 1;
  $nextYear = $y;

  // Setting the next month to January (1) and next year to year + 1
  if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear = $y + 1;
  }

  $prevMonth = $m - 1;
  $prevYear = $y;

  // Setting the previous month to December (12) and the year to this year - 1
  if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear = $y - 1;
  }

  $calendar = [];
  for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $m, $y); $day++) {
    $now = "$y/$m/$day";
    $stmt = $conn->prepare("SELECT id, name FROM events WHERE start <= ? AND end >= ?");
    $stmt->bind_param('ss', $now, $now);
    $stmt->execute();
    $res = $stmt->get_result();

    $events = [];
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($events, [
          'e_id' => $row['id'],
          'name' => $row['name'],
        ]);
      }
    }

    array_push($calendar, [
      'date' => $day,
      'event' => $res->num_rows,
      'events' => $events
    ]);
  }

  return require_once __DIR__ . "/../view/dashboard.php";
}

if (preg_match("/^(GET|get)$/", $_SERVER["REQUEST_METHOD"])) {
  GET();
}