<?php
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        require __DIR__ . '/home.php';
        break;
    case '':
        require __DIR__ . '/home.php';
        break;
    case '/signup' :
        require __DIR__ . '/signup.php';
        break;
    case '/login' :
        require __DIR__ . '/login.php';
        break;
    case '/auth' :
        require __DIR__ . '/authenticated.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}
?>