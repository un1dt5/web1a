<?php
require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Messages.php';

header('Content-Type: application/json');

$messages = new Messages($db);
$result = new stdClass;
$result->status = 0;

$username = $_GET['username'] ?? null;
if (!$username) {
    $result->msg = 'Username is required';
    die(json_encode($result));
}

$result->data = $messages->getMessagesForUser($username);
$result->status = 1;

echo json_encode($result);
