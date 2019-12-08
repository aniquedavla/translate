<?php
    echo <<< _END
        <!DOCTYPE html>
        <html>
        <head><title>Lame Translate</title></head>
        <style>
            body{
                text-align:center;
                font-size: 150%;
            }
            h3{
                color: red;
            }
            button {
                font-align:center;
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
            <h4>Lame Translate - Loozers Achieving Mindboggling Eliteness</h4>
            <button type="button" onclick="window.location.href = '/login'">Login</button>
            <button type="button" onclick="window.location.href = '/signup'">Sign up</button>
        </body>
    _END;
?>