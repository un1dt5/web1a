<?php

require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Exercises.php';
require __DIR__ . '/../../models/Users.php';


header('Content-Type: application/json');

$exer = new Exercises($db);
$user = new Users($db);
$result = new stdClass;
$result->status = 0;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    $result->msg = 'You are not logged in';
    die(json_encode($result));
}

$result->data = $exer->getAll();

if ($_SESSION['type'] === 'student') {
    $submits = $exer->getSubmit($_SESSION['id']);
    foreach ($result->data as $i => $v) {
        foreach ($submits as $s) {
            if ($v['id'] == $s['exerciseId']) {
                $result->data[$i]['submited'] = [
                    'url' => $s['url']
                ];
            }
        }
    }
} else {
    $students = $user->getStudents();
    foreach ($result->data as $i => $v) {
        $result->data[$i]['submited'] = [];
        foreach ($students as $student) {
            $submits = $exer->getSubmit($student['id']);
            foreach ($submits as $s) {
                if ($v['id'] == $s['exerciseId']) {
                    array_push($result->data[$i]['submited'], [
                        'userId' => $student['id'],
                        'username' => $student['username'],
                        'fullname' => $student['fullname'],
                        'url' => $s['url']
                    ]);
                }
            }
        }
    }
}

$result->status = 1;
echo json_encode($result);
