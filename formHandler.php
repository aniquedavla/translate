<?php
    //make conenction with msql
    require_once "mysqlLogin.php";
    require_once "util.php";
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
    }

    if(!empty(isset($_POST['fromLang'])) && !empty(isset($_POST['toLang'])) && $_FILES){
        $filename = $_FILES['tranModel']['name'];
        //echo $filename;
        //checks if uploaded file is a plaintext file.
        if($_FILES['tranModel']['type']=="text/plain"){
            $fileContents = file_get_contents($filename);
            $filenameSanitized = sanitizeData($conn, $filename);
            //echo $filenameSanitized;
            //echo $fileContents;
            echo "<br>";
            //$fileContents = preg_replace('/\s+/', '', $fileContents);
            echo "<br>";
            //sanitize the input
            $name = $filenameSanitized;
            $fileData = sanitizeData($conn, $fileContents);
            echo $name;
            echo $fileData;
            // $sendDataQuery = "INSERT INTO FileData(name, filedata, filename) VALUES" . "('$name','$fileData','$filenameSanitized')";
        // $sendResult = $conn->query($sendDataQuery); 
        // if (!$sendResult) echo "INSERT failed: $sendResult" . "Please try again later!";
        } else{
            error("wrongfile");
        }
    }
?>