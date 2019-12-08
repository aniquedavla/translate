<?php
    // echo <<< _END
    //     <!DOCTYPE html>
    //     <html>
    //     <head><title>Lame Translate</title></head>
    //     <style>
    //         body{
    //             font-size: 150%;
    //         }
    //         h3{
    //             color: red;
    //         }
    //         button {
    //             color: white;
    //             margin: 4px 2px;
    //             cursor: pointer;
    //             background-color: #008CBA;
    //             font-size: 12px;
    //             padding: 12px 28px;
    //         }
    //         h4{
    //             color:#4CAF50;
    //         }
    //     </style>
    //     <body>
    //         <h4>
    //         Lame Translate - Loozers Achieving Mindboggling Eliteness
    //         <button type="button" onclick="window.location.href = '/login'">Login</button>
    //         <button type="button" onclick="window.location.href = '/signup'">Sign up</button>
    //         </h4>
    //     </body>
    // _END;

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