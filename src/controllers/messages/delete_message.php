<?php
require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Messages.php';

header('Content-Type: application/json');

$messages = new Messages($db);
$result = new stdClass;
$result->status = 0;

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'])) {
    $result->msg = 'Message ID is required';
    die(json_encode($result));
}

if ($messages->deleteMessage($data['id'], $_SESSION['username'])) {
    $result->status = 1;
    $result->msg = 'Message deleted successfully';
} else {
    $result->msg = 'Failed to delete message';
}

echo json_encode($result);
