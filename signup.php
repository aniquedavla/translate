<?php
    //make conenction with msql
    require_once "mysqlLogin.php";
    //shared functions in util.php
    require_once "util.php";

    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
    }
    
    if(isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmPass'])){
        echo "Data entered";
        $salt1 = bin2hex(random_bytes(8));
        $salt2 = bin2hex(random_bytes(8));
        echo $salt1, $salt2;
        $fullName = sanitizeData($conn, $_POST['fullName']);
        $email = sanitizeData($conn, $_POST['email']);
        $username = sanitizeData($conn, $_POST['username']);
        $password = sanitizeData($conn, $_POST['password']);
        $hashedPw = hash('ripemd128',"$salt1$password$salt2");
        $query = "INSERT INTO USERS(fullName, email, username, hashedPW, salt1, salt2) VALUES('$fullName','$email','$username', '$hashedPw','$salt1', '$salt2')";
        $result = $conn->query($query);
        echo $result;
        if (!$result) {
            die(error("failUser"));
        } else {
            //12hr session limit
            ini_set('session.gc_maxlifetime', 60 * 60 * 12);
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
            echo "<br>", "Welcome, ".$username,"<br>";
            die("<p><a href=/auth>Click here to continue</a></p>");
            session_destroy();
        }

    } else{
        signUpForm();
    }

    $conn->close();
    
    function signUpForm(){
        //sign up form
        echo <<< _END
            <!DOCTYPE html>
            <html>
            <head><title>Lame Translate - Sign Up</title></head>
            <style>
                body{
                    font-size: 150%;
                }
                form {
                    margin-top: 25px;
                    text-align: left;
                    margin-bottom: 25px;
                    display: block;
                }
                input[type=number]{
                    
                }
                input [type=submit]{
                    text-align: right;
                }
                h3{
                    color: red;
                }
                table, th, td {
                    border: 1px solid black;
                }
                fieldset{
                    border: 5px solid skyblue;
                    padding: 10px;
                }
            </style>
            <body>
                <form method='post' action='/signup' enctype='multipart/form-data'>
                    <fieldset>
                    <legend>Sign Up</legend>
                    <label for="fullName">Full Name</label>
                    <input type='text' name='fullName' size='20' >
                    <br>
                    <label for="email">Email</label>
                    <input type='email' name='email' size='20' required>
                    <br>
                    <label for="username">Username</label>
                    <input type='text' name='username' size='20' required>
                    <br>
                    <label for="password">Set Password</label>
                    <input type='text' name='password' size='20' required>
                    <br>
                    <label for="confirmPass">Confirm Password</label>
                    <input type='text' name='confirmPass' size='20' required>
                    <br>
                    <input type='submit' value='Sign Up' size='100'>
                    </fieldset>
                </form>
        _END;
    }
?>
