<?php
    //sanitizes user input to prevent hacking attempts for sql injection and cross site scripting
    function sanitizeData($conn, $str){
        return htmlentities(sanitizeForMysql($conn, $str));
    }

    function sanitizeForMysql($conn, $str){
        // if(get_magic_quotes_gpc()){
        //     $str = stripslashes($str);
        // }
        return $conn->real_escape_string($str);
    }

    //Error handler
    function error($errorType){
        if($errorType === "wronginput"){
            echo <<< _END
                <h3> Wrong or incomplete input. Please enter all inputs in the right format!</h3>
            _END;
        } else if($errorType === "connectionerror"){
            echo <<< _END
                <h3 style="color:red;">There was an error getting the right information. Please try again later! <br> If the problem persists contact admin@serversideexperts.com</h3>
            _END;
        }
        else if($errorType === "failUser"){
            echo <<< _END
                <h3 style="color:red;">Failed to register user. Enter all fields correctly. Make sure username and email are unique.</h3>
            _END;
        }
        else if($errorType === "invalidcredentials"){
            echo <<< _END
                <h3 style="color:red;">Invalid username/password combination</h3>
            _END;
        } else if($errorType === "differentuser"){
            echo <<< _END
                <h3 style="color:red;">There was a technical error. Please login again. Thank You!</h3>
            _END;
        } else if($errorType === "wrongfile"){
            echo <<< _END
                <h3 style="color:red;">Upload the file in the correct format. Try agian!</h3>
            _END;
        } else if($errorType === "invalidjson"){
            echo <<< _END
                <h3 style="color:red;">Please enter a json file in the right format.</h3>
            _END;
        } else if($errorType === "sns"){
            echo <<< _END
                <h3 style="color:red;">There was a technical error getting the right info. Try again later.</h3>
            _END;
        }
    }
    
    // Delete all the information in the session array
    function destroy_session_and_data(){
        $_SESSION = array();
        setcookie(session_name(), '' , time() - 2592000, '/');
        session_destroy();
    }

?>