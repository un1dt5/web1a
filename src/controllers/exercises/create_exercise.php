<?php

require __DIR__ . '/../../databases/connection.php';
require __DIR__ . '/../../models/Exercises.php';


header('Content-Type: application/json');

$exer = new Exercises($db);
$result = new stdClass;
$result->status = 0;

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] != true) {
    $result->msg = 'You are not logged in';
    die(json_encode($result));
}

if ($_SESSION['type'] === 'student') {
    $result->msg = 'Student is not authorized to take this action';
    die(json_encode($result));
}

if (!isset($_FILES['file'])) {
    $result->msg = 'invalid file';
    die(json_encode($result));
}

if (!isset($_POST['title']) && !isset($_POST['desc']) && $_POST['title'] !== '' && $_POST['desc'] !== '') {
    $result->msg = 'Title and description cannot be missing';
    die(json_encode($result));
}

$file = $_FILES['file'];

if ($file['size'] > 20 * 1024 * 1024) {
    $result->msg = 'File size should not be larger than 20MB';
    die(json_encode($result));
}
$filename = pathinfo($file['name'], PATHINFO_FILENAME);
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['png', 'jpg', 'jpeg', 'docx', 'pdf', 'txt'])) {
    $result->msg = $ext . ' extension not allowed';
    die(json_encode($result));
}

$path_upload = __DIR__ . '/../../../uploads/works/' . $filename . '.' . $ext;

if (move_uploaded_file($file["tmp_name"], $path_upload)) {
    $uploaded = '/../uploads/works/' . $filename . '.' . $ext;
    $data = [
        'authorId' => $_SESSION['id'],
        'title' => $_POST['title'],
        'desc' => $_POST['desc'],
        'url' => $uploaded
    ];
    if ($exer->create($data)) {
        $result->status = 1;
        $result->url = $uploaded;
    } else {
        $result->msg = 'Databases error :\'(';
    }
} else {
    $result->msg = 'Sorry, there was an error uploading your file';
}

echo json_encode($result);
