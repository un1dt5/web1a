<?php

require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Exercises.php';


header('Content-Type: application/json');

$exer = new Exercises($db);
$result = new stdClass;
$result->status = 0;

function randomString($l = 6)
{
    $cs = '0123456789abcdefghijklmnopqrstuvwxyz';
    $csl = strlen($cs);
    $result = '';
    for ($i = 0; $i < $l; $i++) {
        $result .= $cs[rand(0, $csl - 1)];
    }
    return $result;
}

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    $result->msg = 'You are not logged in';
    die(json_encode($result));
}

if ($_SESSION['type'] !== 'student') {
    $result->msg = 'Student is only authorized to take this action';
    die(json_encode($result));
}

if (!isset($_FILES['file'])) {
    $result->msg = 'invalid file';
    die(json_encode($result));
}

if (!(isset($_POST['type']) && isset($_POST['id']))) {
    $result->msg = 'invalid param';
    die(json_encode($result));
}

$file = $_FILES['file'];

if ($file['size'] > 20 * 1024 * 1024) {
    $result->msg = 'File size should not be larger than 20MB';
    die(json_encode($result));
}

$filename = pathinfo($file['name'], PATHINFO_FILENAME) . '_' . randomString();
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'txt'])) {
    $result->msg = $ext . ' extension not allowed';
    die(json_encode($result));
}

$path_upload = __DIR__ . '/../../../uploads/submit_works/' . $filename . '.' . $ext;

if (move_uploaded_file($file["tmp_name"], $path_upload)) {
    $uploaded = '/uploads/submit_works/' . $filename . '.' . $ext;
    if ($exer->submit($_POST['id'], $_SESSION['id'], $uploaded)) {
        $result->status = 1;
        $result->url = $uploaded;
    } else {
        $result->msg = 'Databases error :\'(';
    }
} else {
    $result->msg = 'Sorry, there was an error uploading your file';
}

echo json_encode($result);
