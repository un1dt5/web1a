<?php

require __DIR__ . '/../databases/connection.php';
require __DIR__ . '/../models/Users.php';


header('Content-Type: application/json');


$user = new Users($db);
$result = new stdClass;
$result->status = 0;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    $result->msg = 'You are not logged in';
    die(json_encode($result));
}

$is_student = $_SESSION['type'] === 'student' ? true : false;

if (isset($_POST)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($is_student && (isset($data['username']) || isset($data['fullname']))) {
        $result->msg = 'Student couldn\'t update username or fullname';
        die(json_encode($result));
    }
    $info = $data;
    if ($is_student) {
        $info['username'] = $_SESSION['username'];
        $info['fullname'] = $_SESSION['fullname'];
    }
    if ($user->update($_SESSION['username'], $info)) {
        $result->status = 1;
        $result->msg = 'Updated';
        $_SESSION['username'] = $info['username'];
        $_SESSION['fullname'] = $info['fullname'];
        $_SESSION['email'] = $info['email'];
        $_SESSION['phone'] = $info['phone'];
    } else {
        $result->msg = 'Something went wrong !!';
    }
}

echo json_encode($result);
