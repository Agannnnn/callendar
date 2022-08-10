<?php
include_once __DIR__ . "/../../config.php";

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');

function handleGet()
{
  global $DB;

  if (!isset($_GET['eId'])) {
    http_response_code(404);
    echo json_encode(['code' => 404, 'message' => 'Data acara tidak dapat ditemukan 1']);
    return;
  }
  $eId = $DB->real_escape_string($_GET['eId']);

  $stmt = $DB->prepare("SELECT `nama`, `deskripsi`, `dari`, `sampai` FROM `acara` WHERE `e_id` = ?");
  $stmt->bind_param('s', $eId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode(['code' => 404, 'message' => 'Data acara tidak dapat ditemukan 2']);
    return;
  }

  http_response_code(200);
  echo json_encode($result->fetch_assoc());
  return;
}

function handlePost()
{
  global $DB;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $eId = "";
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_';
  for ($i = 0; $i < 8; $i++) {
    $eId .= $characters[rand(0, strlen($characters) - 1)];
  }
  $name = $DB->real_escape_string($requestBody->name);
  $description = $DB->real_escape_string($requestBody->description);
  $from = $DB->real_escape_string($requestBody->dateFrom);
  $to = $DB->real_escape_string($requestBody->dateTo);

  $stmt = $DB->prepare("INSERT INTO `acara` (`e_id`, `nama`, `deskripsi`, `dari`, `sampai`) VALUES (?,?,?,?,?)");
  $stmt->bind_param('sssss', $eId, $name, $description, $from, $to);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['code' => 200, 'message' => 'Data acara berhasil ditambahkan']);
    return;
  }

  writeLog("Menambah jadwal");

  http_response_code(400);
  echo json_encode(['code' => 400, 'message' => 'Data acara tidak berhasil ditambahkan']);
  return;
}

function handlePatch()
{
  global $DB;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $eId = $DB->real_escape_string($requestBody->eId);
  $name = $DB->real_escape_string($requestBody->name);
  $description = $DB->real_escape_string($requestBody->description);
  $from = $DB->real_escape_string($requestBody->dateFrom);
  $to = $DB->real_escape_string($requestBody->dateTo);

  $stmt = $DB->prepare("UPDATE `acara` SET `nama` = ?, `deskripsi` = ?, `dari` = ?, `sampai` = ? WHERE `e_id` = ?");
  $stmt->bind_param('sssss', $name, $description, $from, $to, $eId);
  if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(['code' => 200, 'message' => 'Data acara berhasil diubah']);
    return;
  }

  writeLog("Mengubah jadwal");

  http_response_code(500);
  echo json_encode(['code' => 500, 'message' => 'Data acara tidak berhasil diubah']);
  return;
}

function handleDelete()
{
  global $DB;

  $requestBody = json_decode(trim(file_get_contents('php://input')));

  $eId = $DB->real_escape_string($requestBody->eId);

  $stmt = $DB->prepare("DELETE FROM `acara` WHERE `e_id` = ?");
  $stmt->bind_param('s', $eId);
  if ($stmt->execute()) {
    http_response_code(203);
    echo json_encode(['code' => 203, 'message' => 'Data acara berhasil dihapus']);
    return;
  }

  writeLog("Menghapus jadwal");

  http_response_code(404);
  echo json_encode(['code' => 404, 'message' => 'Data acara tidak berhasil dihapus']);
  return;
}

if (preg_match('/^GET$/', $_SERVER['REQUEST_METHOD'])) {
  handleGet();
  die();
}

if (preg_match('/^POST$/', $_SERVER['REQUEST_METHOD'])) {
  $method = json_decode(trim(file_get_contents('php://input')))->method;
  if (preg_match('/^POST$/', $method)) {
    HandlePost();
  } else if (preg_match('/^PATCH$/', $method)) {
    handlePatch();
  } else if (preg_match('/^DELETE$/', $method)) {
    handleDelete();
  }
  die();
}
