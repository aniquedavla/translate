<?php
    //sesssion security
    //all sessions are deleted once a user logs out. the page is routed to home.php when a user logs out.
    //make conenction with msql
    require_once "mysqlLogin.php";
    require_once "util.php";
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error){
        die(error("connectionerror"));
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
            headerHtml();
            echo <<< _END
                <legend><h2>Welcome, <b>$username</b></h2></legend>
                <p>Upload a translation model from a language to another in a json file.
                <br>Only one language-pair per json file allowed. All key-value pairs should be under one JSON object only.
                <br>Select the language you are translating below.</p>
                <form id= "modelForm" method='post' action='/auth' enctype='multipart/form-data'>
                    <fieldset>
                    <legend>Uplod a translation model</legend>
                    <label for="tranName">Translation name:</label>
                    <input type='text' name='tranName' size='20'>
                    <br>
                <label for="languageSelector">Select the language you are translating:</label>
                    <div id="langugeSelector">
                        <label for="fromLang">From:</label>
                        <select id="fromLang" name="fromLang" form="modelForm" required>
                            <option value=""></option>
                            <option value="french">English</option>
                            <option value="french">French</option>
                            <option value="spanish">Spanish</option>
                            <option value="italian">Italian</option>
                            <option value="german">German</option>
                            <option value="russian">Russian</option>
                        </select>
                        <label for="toLang">To:</label>
                        <select id="toLang" name="toLang" form="modelForm" required>
                            <option value=""></option>
                            <option value="french">English</option>
                            <option value="french">French</option>
                            <option value="spanish">Spanish</option>
                            <option value="italian">Italian</option>
                            <option value="german">German</option>
                            <option value="russian">Russian</option>
                        </select>
                    </div>
                    <label for="tranModel">Translation model:</label>
                    <input type='file' name='tranModel' size='20' required>
                    <br>
                    <input type='submit' name='submitButton' value='Submit'>

                    </fieldset>
                </form> 
                <br>
                <form id= "translationForm" method='post' action='/auth' enctype='multipart/form-data'>
                    <fieldset>
                    <legend>Translate your text</legend>
                    <label for="languageSelectorTranslate">Select the language you are translating:</label>
                    <div id="langugeSelectorTranslate">
                        <label for="fromLangTranslate">From:</label>
                        <select id="fromLangTranslate" name="fromLangTranslate" form="translationForm" required>
                            <option value=""></option>
                            <option value="french">English</option>
                            <option value="french">French</option>
                            <option value="spanish">Spanish</option>
                            <option value="italian">Italian</option>
                            <option value="german">German</option>
                            <option value="russian">Russian</option>
                        </select>
                        <label for="toLangTranslate">To:</label>
                        <select id="toLangTranslate" name="toLangTranslate" form="translationForm" required>
                            <option value=""></option>
                            <option value="french">English</option>
                            <option value="french">French</option>
                            <option value="spanish">Spanish</option>
                            <option value="italian">Italian</option>
                            <option value="german">German</option>
                            <option value="russian">Russian</option>
                        </select>
                    </div>
                    <label for="searchTranslation">Text to translate:</label>
                    <textarea name='searchTranslation' rows="2" cols="50" placeholder="text to translate" form="translationForm" required></textarea> 
                    <br>
                    <input type='submit' name='submitButtonTranslate' value='Submit'>
                    </fieldset>
                </form> 
            _END;
            
            if(!empty($_POST['fromLang']) && isset($_POST['fromLang']) && !empty($_POST['toLang']) && isset($_POST['toLang']) && $_FILES){
                $filename = $_FILES['tranModel']['name'];
                $fromLang = sanitizeData($conn, $_POST['fromLang']);
                $toLang = sanitizeData($conn, $_POST['toLang']);
                $translationName = sanitizeData($conn, $_POST['tranName']);
                //echo $filename;
                //checks if uploaded file has text content.
                //echo $_FILES['tranModel']['type'];
                if($_FILES['tranModel']['type']=="application/json"){
                    $fileContents = file_get_contents($filename);
                    if(isJson($fileContents)){
                        $filenameSanitized = sanitizeData($conn, $filename);
                        //echo $filenameSanitized;
                        //echo $fileContents;
                        echo "<br>";
                        //$fileContents = preg_replace('/\s+/', '', $fileContents);
                        echo "<br>";
                        //sanitize the input
                        $name = $filenameSanitized;
                        $fileDataSanitized = sanitizeData($conn, $fileContents);
                        //echo $name;
                        //echo $fileDataSanitized;
                        if(!function_exists('json_decode')) die(error("sns"));
                        $jsonArray = json_decode($fileContents, true);
                        
                        //placeholders to prevent hacking attempts
                        $insertStmt = $conn->prepare("INSERT INTO TranslationModels(username, translationName, fromLanguage, toLanguage, translationModel) VALUES(?,?,?,?,?)");
                        $insertStmt->bind_param('sssss', $username, $translationName, $fromLang, $toLang, $fileContents);
                        $insertStmt->execute();
                        printf("%d Information added successfully. \n", $insertStmt->affected_rows);
                        $insertStmt->close();
                    } else {
                        error("invalidjson");
                    }
                } else{
                    error("wrongfile");
                }
            }
        }
    } else {
        die(error("failUser"));
    }

    function headerHtml(){
        echo <<< _END
            <!DOCTYPE html>
            <html>
            <head><title>Lame Translate</title></head>
            <style>
                body{
                    font-size: 130%;
                    font-style: normal;
                    font-family: Helvetica Neue;
                }
                h3{
                    color: red;
                }
                input {
                    padding:10px;
                    display: inline-block;
                }
                input[type=submit]{
                    background-color: #008CBA;
                    color:white;
                    font-size: 15px;
                    padding: 4px 12px;
                    cursor: pointer;
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
                h2 {
                    font-family: Helvetica Neue;
                }
                b {
                    color: #87CEFA;
                }
                p{
                    font-family: Minion Pro;
                }
                label #searchTranslation{
                    padding-bottom: 12px;
                }
                fieldset{
                    border: 5px solid gray;
                    padding: 10px;
                }
            </style>
            <body>
                <h4>Lame Translate - Loozers Achieving Mindboggling Eliteness
                <button type="button" onclick="window.location.href = '/'">Logout</button></h4>
                <hr>
            </body>
        _END;
    }
    //stack overflow
    //https://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php/15198925
    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    //sesssion security
    //all sessions are deleted once a user logs out. the page is routed to home.php when a user logs out.                
?>
