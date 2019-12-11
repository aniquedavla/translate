<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require_once __DIR__ . '/home.php';
        break;
    case '':
        require_once __DIR__ . '/home.php';
        break;
    case '/signup' :
        require_once __DIR__ . '/signup.php';
        break;
    case '/login' :
        require_once __DIR__ . '/login.php';
        break;
    case '/auth' :
        require_once __DIR__ . '/authenticated.php';
        break;
    default:
        http_response_code(404);
        require_once __DIR__ . '/404.php';
        break;
}
?>