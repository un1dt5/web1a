<?php
require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Messages.php';

header('Content-Type: application/json');

$messages = new Messages($db);
$result = new stdClass;
$result->status = 0;

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['to_username']) || !isset($data['content'])) {
    $result->msg = 'Missing required fields';
    die(json_encode($result));
}

if ($messages->createMessage($_SESSION['username'], $data['to_username'], $data['content'])) {
    $result->status = 1;
    $result->msg = 'Message sent successfully';
} else {
    $result->msg = 'Failed to send message';
}

echo json_encode($result);
