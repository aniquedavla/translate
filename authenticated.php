<?php
    //make conenction with msql
    require_once "mysqlLogin.php";
    require_once "util.php";
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
    }
    function headerHtml(){
        echo <<< _END
            <!DOCTYPE html>
            <html>
            <head><title>Lame Translate</title></head>
            <style>
                body{
                    font-size: 150%;
                }
                h3{
                    color: red;
                }
                button {
                    color: white;
                    margin: 4px 2px;
                    cursor: pointer;
                    background-color: #008CBA;
                    font-size: 16px;
                    padding: 12px 28px;
                }
                h4{
                    color:#4CAF50;
                }
            </style>
            <body>
                <h4>Lame Translate - Loozers Achieving Mindboggling Eliteness
                <button type="button" onclick="window.location.href = '/'">Logout</button></h4>
            </body>
        _END;
    }
    session_start();
    //regenrate session id everytime to prevent session fixation.
    session_regenerate_id();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        //check for session hijacking 
        if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT'])) {
            die(error("differentuser"));
        }
        else {
            destroy_session_and_data();
            headerHtml();
            echo "Welcome ". $username;
        };
    }
?>
