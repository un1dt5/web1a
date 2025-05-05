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

if ($_SESSION['type'] !== 'admin') {
    $result->msg = 'Admin is only authorized to take this action';
    die(json_encode($result));
}

$data = $user->getAll();
$result->data = [];
foreach ($data as $v) {
    array_push($result->data, [
        'id' => $v['id'],
        'username' => $v['username'],
        'fullname' => $v['fullname'],
        'phone' => $v['phone'],
        'email' => $v['email'],
        'type' => $v['type']
    ]);
}
$result->status = 1;
echo json_encode($result);
