<?php
include_once __DIR__ . "/../../config.php";

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');

function GET()
{
  global $conn;

  if (!isset($_GET['eId'])) {
    http_response_code(404);
    echo json_encode(['code' => 404, 'message' => 'Event id is not provided']);
    return;
  }

  $eId = $conn->real_escape_string($_GET['eId']);
  $owner = $conn->real_escape_string($_SESSION['user_id']);

  $stmt = $conn->prepare("SELECT name, description, start, end FROM events WHERE id = ? AND owner = ?");
  $stmt->bind_param('ss', $eId, $owner);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode(['code' => 404, 'message' => 'No event found']);
    return;
  }

  http_response_code(200);
  echo json_encode($result->fetch_assoc());
  return;
}

function POST()
{
  global $conn;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $name = $conn->real_escape_string($requestBody->name);
  $description = $conn->real_escape_string($requestBody->description);
  $start = $conn->real_escape_string($requestBody->dateFrom);
  $end = $conn->real_escape_string($requestBody->dateTo);

  $owner = $conn->real_escape_string($_SESSION['user_id']);

  $stmt = $conn->prepare("INSERT INTO events (id, name, description, start, end, owner) VALUES (UUID(),?,?,?,?,?)");
  $stmt->bind_param('sssss', $name, $description, $start, $end, $owner);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['code' => 200, 'message' => 'New event is saved']);
    return;
  }

  writeLog("Added event");

  http_response_code(400);
  echo json_encode(['code' => 400, 'message' => 'Failed to save new event']);
  return;
}

function PATCH()
{
  global $conn;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $eId = $conn->real_escape_string($requestBody->eId);
  $name = $conn->real_escape_string($requestBody->name);
  $description = $conn->real_escape_string($requestBody->description);
  $start = $conn->real_escape_string($requestBody->dateFrom);
  $end = $conn->real_escape_string($requestBody->dateTo);

  $owner = $conn->real_escape_string($_SESSION['user_id']);

  $stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, start = ?, end = ? WHERE id = ? AND owner = ?");
  $stmt->bind_param('ssssss', $name, $description, $start, $end, $eId, $owner);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['code' => 200, 'message' => 'Event is updated']);
    return;
  }

  writeLog("Updated event: $eId");

  http_response_code(500);
  echo json_encode(['code' => 500, 'message' => 'Failed to update event']);
  return;
}

function DELETE()
{
  global $conn;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $eId = $conn->real_escape_string($requestBody->eId);

  $owner = $conn->real_escape_string($_SESSION['user_id']);

  $stmt = $conn->prepare("DELETE FROM events WHERE id = ? AND owner = ?");
  $stmt->bind_param('ss', $eId, $owner);
  if ($stmt->execute()) {
    http_response_code(203);
    echo json_encode(['code' => 203, 'message' => 'Event is deleted']);
    return;
  }

  writeLog("Removed event: $eId");

  http_response_code(404);
  echo json_encode(['code' => 404, 'message' => 'Failed to delete event']);
  return;
}

if (preg_match('/^GET$/', $_SERVER['REQUEST_METHOD'])) {
  GET();
  exit;
}

if (preg_match('/^POST$/', $_SERVER['REQUEST_METHOD'])) {
  $method = json_decode(trim(file_get_contents('php://input')))->method;
  if (preg_match('/^POST$/', $method)) {
    POST();
  } else if (preg_match('/^PATCH$/', $method)) {
    PATCH();
  } else if (preg_match('/^DELETE$/', $method)) {
    DELETE();
  }
  exit;
}