<?php
    //make conenction with msql
    require_once "mysqlLogin.php";
    require_once "util.php";
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
    }
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        //check for session hijacking 
        if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT'])) {
            die(error("differentuser"));
        }
        else {
            destroy_session_and_data();
            echo "Welcome ". $username;
        };
    }
?>
