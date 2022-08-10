<?php
require_once __DIR__ . "/../config.php";

Authentication::check();

function handleGet()
{
  global $DB;

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

  if ($nextMonth == 13) {
    $nextMonth = 1;
    $nextYear = $y + 1;
  }

  $prevMonth = $m - 1;
  $prevYear = $y;

  if ($prevMonth == 0) {
    $prevMonth = 12;
    $prevYear = $y - 1;
  }

  $calendar = [];
  for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $m, $y); $day++) {
    $now = "$y/$m/$day";
    $stmt = $DB->prepare("SELECT `e_id`, `nama` FROM `acara` WHERE `dari` <= ? AND `sampai` >= ?");
    $stmt->bind_param('ss', $now, $now);
    $stmt->execute();
    $res = $stmt->get_result();

    $events = [];
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($events, [
          'name' => $row['nama'],
          'e_id' => $row['e_id']
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
  handleGet();
}
