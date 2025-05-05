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

$username = isset($_GET['username']) ? $_GET['username'] : null;

$user_data = $user->getUser($username);
if (!$user_data) {
    $result->msg = 'User not found';
    die(json_encode($result));
}

$avatar = $user->getAvatar($user_data['id']);

$result->status = 1;
$result->data = [
    'id' => $user_data['id'],
    'username' => $username, // Thêm username vào response
    'fullname' => $user_data['fullname'],
    'email' => $user_data['email'],
    'phone' => $user_data['phone'],
    'type' => $user_data['type'],
    'avatar' => $avatar
];

echo json_encode($result);
