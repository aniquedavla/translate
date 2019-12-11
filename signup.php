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
        //echo "Data entered";
        $salt1 = bin2hex(random_bytes(8));
        $salt2 = bin2hex(random_bytes(8));
        //echo $salt1, $salt2;
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
            //to check for session hijacking
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
            echo "<br>", "Welcome, ".$username,"<br>";
            die("<p><a href=/auth>Click here to continue</a></p>");
            session_destroy();
        }
        
        $result->close();

    } else{
        signUpForm();
    }
    
    //all other connections are closed in the scope they were defined.
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
                input[type=submit]{
                    background-color: #008CBA;
                    color:white;
                    font-size: 20px;
                    padding: 4px 12px;
                    cursor: pointer;
                }
                h3{
                    color: red;
                }
                fieldset{
                    border: 5px solid skyblue;
                    padding: 10px;
                }
                button {
                    color: white;
                    margin: 4px 2px;
                    cursor: pointer;
                    background-color: #008CBA;
                    font-size: 12px;
                    padding: 12px 28px;
                }
                h4{
                    color:#4CAF50;
                }
            </style>
            <body>
                <h4>Lame Translate - Loozers Achieving Mindboggling Eliteness
                <button type="button" onclick="window.location.href = '/login'">Login</button></h4>
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
                    <input type='submit' value='Sign Up'>
                    </fieldset>
                </form>
        _END;
    }
?>
