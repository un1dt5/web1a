<?php

require __DIR__ . '/../databases/connection.php';
require __DIR__ . '/../models/Users.php';


header('Content-Type: application/json');

$user = new Users($db);
$result = new stdClass;
$result->auth = false;

if (isset($_POST)) {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $user->getPassword($data['username']);
    if (password_verify($data['password'], $password)) {
        $info = $user->getUser($data['username']);
        $_SESSION['avt'] = $user->getAvatar($info['id']);
        $_SESSION['is_login'] = true;
        $_SESSION['id'] = $info['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['fullname'] = $info['fullname'];
        $_SESSION['type'] = $info['type'];
        $_SESSION['phone'] = $info['phone'];
        $_SESSION['email'] = $info['email'];
        $result->auth = true;
    }
}

echo json_encode($result);
