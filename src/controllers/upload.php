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

$targetId = isset($_POST['target_id']) ? $_POST['target_id'] : $_SESSION['id'];

if ($targetId !== $_SESSION['id']) {
    if ($_SESSION['type'] === 'admin') {
        $canUpdate = true;
    } else if ($_SESSION['type'] === 'teacher') {
        // Teacher chỉ có thể đổi avatar cho student
        $query = 'SELECT type FROM account WHERE id = ? LIMIT 1';
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $targetId);
        $stmt->execute();
        $stmt->bind_result($userType);
        $stmt->fetch();
        $stmt->close();

        $canUpdate = ($userType === 'student');
    } else {
        $canUpdate = false;
    }

    if (!$canUpdate) {
        $result->msg = 'Bạn không có quyền thay đổi avatar của người dùng này';
        die(json_encode($result));
    }
}

function signFile()
{
    $time = hash('sha256', floor(microtime(true) * 1000));
    $result = substr($time, 0, 8) . '-';
    $result .= substr($time, 8, 4) . '-';
    $result .= substr($time, 12, 4) . '-';
    $result .= substr($time, 16, 4) . '-';
    $result .= substr($time, 17, 12);
    return $result;
}

if (!isset($_FILES['file'])) {
    $result->msg = 'invalid image';
    die(json_encode($result));
}

$file = $_FILES['file'];

if ($file['size'] > 5 * 1024 * 1024) {
    $result->msg = 'File size should not be larger than 5MB';
    die(json_encode($result));
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if ($ext !== 'jpg' && $ext !== 'png' && $ext !== 'jpeg') {
    $result->msg = $ext . ' extension not allowed';
    die(json_encode($result));
}

// Tạo thư mục nếu chưa tồn tại
$uploadDir = __DIR__ . '/../../uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filename = signFile();
$path_upload = $uploadDir . $filename . '.' . $ext;

$query = 'SELECT avatar FROM account WHERE id = ?';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $targetId);
$stmt->execute();
$stmt->bind_result($oldAvatar);
$stmt->fetch();
$stmt->close();

if (move_uploaded_file($file["tmp_name"], $path_upload)) {
    $result->status = 1;
    $result->msg = 'Avatar has been changed';
    $uploaded = 'uploads/' . $filename . '.' . $ext;

    $query = 'UPDATE account SET avatar = ? WHERE id = ?';
    $stmt = $db->prepare($query);
    $stmt->bind_param('si', $uploaded, $targetId);
    $stmt->execute();
    $stmt->close();

    if ($oldAvatar && $oldAvatar !== 'uploads/default.jpg') {
        $oldAvatarPath = __DIR__ . '/../../' . $oldAvatar;
        if (file_exists($oldAvatarPath)) {
            unlink($oldAvatarPath);
        }
    }

    $result->url = $uploaded;
} else {
    $result->msg = 'Sorry, there was an error uploading your file';
}

echo json_encode($result, JSON_UNESCAPED_SLASHES);
