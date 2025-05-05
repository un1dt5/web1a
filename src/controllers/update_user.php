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

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || empty($data['username'])) {
    $result->msg = 'Username is required';
    die(json_encode($result));
}

if ($_SESSION['type'] === 'admin') {
    $canUpdate = true;
} else if ($_SESSION['type'] === 'teacher') {
    $userData = $user->getUser($data['username']);
    $canUpdate = ($userData['type'] === 'student');

    if (!$canUpdate) {
        $result->msg = 'You can only update students information';
        die(json_encode($result));
    }
} else if ($_SESSION['type'] === 'student') {
    if ($_SESSION['username'] !== $data['username']) {
        $result->msg = 'You can only update your own information';
        die(json_encode($result));
    }

    $data['username'] = $_SESSION['username'];
    $data['fullname'] = $_SESSION['fullname'];
    $data['type'] = 'student';
    $canUpdate = true;
} else {
    $canUpdate = false;
}

if (!$canUpdate) {
    $result->msg = 'You are not authorized to update this user';
    die(json_encode($result));
}

if ($user->update($data['username'], $data)) {
    $result->status = 1;
    $result->msg = 'User updated successfully';

    if ($_SESSION['username'] === $data['username']) {
        $_SESSION['fullname'] = $data['fullname'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['phone'] = $data['phone'];
        $_SESSION['avatar'] = $data['avatar'];
    }
} else {
    $result->msg = 'Failed to update user';
}

echo json_encode($result);
