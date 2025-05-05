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

if ($_SESSION['type'] !== 'admin' && $_SESSION['type'] !== 'teacher') {
    $result->msg = 'You are not authorized to perform this action';
    die(json_encode($result));
}

if ($_SESSION['type'] === 'teacher') {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data['type'] !== 'student') {
        $result->msg = 'Teachers can only add students';
        die(json_encode($result));
    }
}

if (isset($_POST)) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['username']) || !isset($data['password']) || !isset($data['fullname']) || !isset($data['email']) || !isset($data['type'])) {
        $result->msg = 'Missing required fields';
        die(json_encode($result));
    }

    // Kiểm tra username có tồn tại chưa
    if ($user->userExists($data['username'])) {
        $result->msg = 'Username already exists';
        die(json_encode($result));
    }

    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    if ($user->insert($data)) {
        // Tạo avatar mặc định cho người dùng
        $id = $user->getLastId();
        $user->insertAvatar($id, '/uploads/default.jpg');

        $result->status = 1;
        $result->msg = 'User created successfully';
    } else {
        $result->msg = 'Failed to create user';
    }
}

echo json_encode($result);
