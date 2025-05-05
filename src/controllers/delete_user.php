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

// Lấy username cần xóa
$username = $_GET['username'] ?? '';

if (empty($username)) {
    $result->msg = 'Username is required';
    die(json_encode($result));
}

if ($_SESSION['username'] === $username) {
    $result->msg = 'You cannot delete your own account';
    die(json_encode($result));
}

if ($_SESSION['type'] === 'admin') {
    $canDelete = true;
} else if ($_SESSION['type'] === 'teacher') {
    $userData = $user->getUser($username);
    $canDelete = ($userData['type'] === 'student');
} else {
    $canDelete = false;
}

if (!$canDelete) {
    $result->msg = 'You are not authorized to delete this user';
    die(json_encode($result));
}

if ($user->deleteUser($username)) {
    $result->status = 1;
    $result->msg = 'User deleted successfully';
} else {
    $result->msg = 'Failed to delete user';
}

echo json_encode($result);
