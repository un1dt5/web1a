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

$current_username = $_SESSION['username'];
$is_admin = $_SESSION['type'] === 'admin';
$is_teacher = $_SESSION['type'] === 'teacher';
$is_student = $_SESSION['type'] === 'student';

$data = json_decode(file_get_contents('php://input'), true);
$target_username = isset($data['target_username']) ? $data['target_username'] : $current_username;

$target_user = $user->getUser($target_username);
if (!$target_user) {
    $result->msg = 'User not found';
    die(json_encode($result));
}

$can_edit = false;

if ($is_admin) {
    $can_edit = ($target_user['type'] !== 'admin' || $target_username === $current_username);
} elseif ($is_teacher) {
    $can_edit = ($target_user['type'] === 'student');
} elseif ($is_student) {
    $can_edit = ($target_username === $current_username);
}

if (!$can_edit) {
    $result->msg = 'You do not have permission to edit this profile';
    die(json_encode($result));
}

if ($is_student && $target_username === $current_username) {
    $data['username'] = $target_user['username'];
    $data['fullname'] = $target_user['fullname'];
}

if ($user->update($target_username, $data)) {
    $result->status = 1;
    $result->msg = 'Profile updated successfully';

    if ($target_username === $current_username) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['fullname'] = $data['fullname'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['phone'] = $data['phone'];
    }

    if ($data['username'] !== $target_username) {
        $result->data = [
            'new_username' => $data['username']
        ];
    }
} else {
    $result->msg = 'Failed to update profile';
}

echo json_encode($result);
