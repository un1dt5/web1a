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

if (isset($_POST)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['password']) && isset($data['old_password'])) {
        if (password_verify($data['old_password'], $user->getPassword($_SESSION['username']))) {
            $hash_password = password_hash($data['password'], PASSWORD_DEFAULT);
            if ($user->changePassword($_SESSION['username'], $hash_password)) {
                $result->status = 1;
                $result->msg = 'Password updated!';
            } else {
                $result->msg = 'Password couldn\'t change :((';
            }
        } else {
            $result->msg = 'Old password incorrect!';
        }
    } else {
        $result->msg = 'Something went wrong !!';
    }
}

echo json_encode($result);
