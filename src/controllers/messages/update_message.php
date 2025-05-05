<?php
require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Messages.php';

header('Content-Type: application/json');

$messages = new Messages($db);
$result = new stdClass;
$result->status = 0;

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['content'])) {
    $result->msg = 'Missing required fields';
    die(json_encode($result));
}

if ($messages->updateMessage($data['id'], $data['content'], $_SESSION['username'])) {
    $result->status = 1;
    $result->msg = 'Message updated successfully';
} else {
    $result->msg = 'Failed to update message';
}

echo json_encode($result);
