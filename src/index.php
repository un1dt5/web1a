<?php

require('Router.php');

session_start();

$router = new Router;

$router->get('/', function () {
    include 'views/index.php';
});

$router->get('/login', function () {
    include 'views/login.php';
});

$router->get('/logout', function () {
    include 'views/logout.php';
});

$router->get('/profile', function () {
    include 'views/profile.php';
});

$router->get('/exercises', function () {
    include 'views/exercises.php';
});

$router->get('/challenges', function () {
    include 'views/challenges.php';
});

$router->get('/students', function () {
    include 'views/students.php';
});

$router->get('/admin_list', function () {
    include 'views/list.php';
});

$router->get('/admin_messages', function () {
    include 'views/messages.php';
});

$router->get('/api/students', function () {
    include 'controllers/students.php';
});

$router->post('/api/login', function () {
    include 'controllers/login.php';
});

$router->post('/api/edit', function () {
    include 'controllers/profile.php';
});

$router->post('/api/change_password', function () {
    include 'controllers/change_password.php';
});

$router->post('/api/upload', function () {
    include 'controllers/upload.php';
});

$router->get('/api/exercises', function () {
    include 'controllers/exercises/exercises.php';
});

$router->post('/api/create_exercise', function () {
    include 'controllers/exercises/create_exercise.php';
});

$router->get('/api/delete_exercise', function () {
    include 'controllers/exercises/delete_exercise.php';
});

$router->post('/api/submit', function () {
    include 'controllers/exercises/submit_exercise.php';
});

$router->get('/api/all_users', function () {
    include 'controllers/all_users.php';
});

// Thêm các routes mới vào file index.php

// Route cho trang Users
$router->get('/users', function () {
    include 'views/users.php';
});

// API routes
$router->get('/api/users', function () {
    include 'controllers/users.php';
});

$router->post('/api/add_user', function () {
    include 'controllers/add_user.php';
});

$router->post('/api/update_user', function () {
    include 'controllers/update_user.php';
});

$router->get('/api/delete_user', function () {
    include 'controllers/delete_user.php';
});

// Thêm API endpoints cho lấy và cập nhật thông tin người dùng
$router->get('/api/get_user_profile', function () {
    include 'controllers/get_user_profile.php';
});

$router->post('/api/update_profile', function () {
    include 'controllers/update_profile.php';
});

$router->get('/profile', function () {
    include 'views/profile.php';
});

// Message routes
$router->get('/api/messages', function () {
    include 'controllers/messages/get_messages.php';
});

$router->post('/api/messages/create', function () {
    include 'controllers/messages/create_message.php';
});

$router->post('/api/messages/update', function () {
    include 'controllers/messages/update_message.php';
});

$router->post('/api/messages/delete', function () {
    include 'controllers/messages/delete_message.php';
});

$router->run();
