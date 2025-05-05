<?php

require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Exercises.php';


header('Content-Type: application/json');

$exer = new Exercises($db);
$result = new stdClass;
$result->status = 0;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    $result->msg = 'You are not logged in';
    die(json_encode($result));
}

if ($_SESSION['type'] === 'student') {
    $result->msg = 'Student is not authorized to take this action';
    die(json_encode($result));
}

if (!isset($_GET['id']) || !in_array((int)$_GET['id'], $exer->getAllId())) {
    $result->msg = 'invalid id';
    die(json_encode($result));
}

if ($exer->delete($_GET['id'])) {
    $result->status = 1;
}

echo json_encode($result);
