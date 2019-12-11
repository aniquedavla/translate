<?php
    //make conenction with msql
    require_once "mysqlLogin.php";
    //shared functions in util.php
    require_once "util.php";
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
    }
    //ask admin to login
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
            $username_temp = sanitizeData($conn,$_SERVER['PHP_AUTH_USER']);
            $password_temp = sanitizeData($conn,$_SERVER['PHP_AUTH_PW']);
            
            //find salt for that user
            $saltsQuery = "SELECT salt1, salt2 FROM USERS WHERE username='$username_temp'";
            $saltsResult = $conn->query($saltsQuery);
            
            //if salt exists means the username is correct
            if($saltsResult){
                $saltRow = $saltsResult->fetch_array(MYSQLI_NUM);            
                //check hashed pw for the username and password entered
                $hashedPw_temp = hash('ripemd128',"$saltRow[0]$password_temp$saltRow[1]");
                $hashedPwQuery = "SELECT hashedPw FROM USERS WHERE username='$username_temp'";
                $hashedPwResult = $conn->query($hashedPwQuery);
                
                $hashedPwRow = $hashedPwResult->fetch_array(MYSQLI_NUM);
        
                if($hashedPw_temp == $hashedPwRow[0]){
                    //admin authenticated
                    ini_set('session.gc_maxlifetime', 60 * 60 * 12); //12hr session limit
                    session_start();
                    $_SESSION['username'] = $username_temp;
                    //to check for session hijacking
                    $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
                    echo "<br>", "Welcome, ".$_SESSION['username'],"<br>";
                    die("<p><a href=/auth>Click here to continue</a></p>");
                    session_destroy();
                }
        
                $hashedPwResult->close();
            } else{
                die(error("invalidcredentials"));
            }

            $saltsResult->close();

    } else {
        //if no username and password set
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        header('HTTP/1.0 401 Unauthorized');
        die ("Please enter your username and password");
    }
    //all other connections are closed in the scope they were defined.
    $conn->close();
?>