<?php

require __DIR__ . '/../config.php';

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_errno) {
    die("Fail to connect: " . $mysqli->connect_error);
}
