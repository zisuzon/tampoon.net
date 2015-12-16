<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <script src="../js/script.js"></script>
    <style>
        <style>
        *{ margin: 0; padding: 0;}

        html, body{
            width: 100%;
            height: 100%;
        }

        body{ font-family: arial, sans-serif; font-size: 16px; background-color: black; color: white; }

        a{ text-decoration: none; color: cornflowerblue; }

        input{ width: 300px; height: 35px; font-weight: bold; font-size: 25px; border-radius: 7px; }

        p{ margin-top: 10px; margin-bottom: 10px; }

        #main{ width: 500px; margin-left: auto; margin-right: auto; font-size: 20px; text-align: center; }
    </style>
</head>
<body>
<div id="main">
<h1>Change your password</h1>
<form method="post" name="the_form">
    <input type="email" name="email" placeholder="Your email" value="<?php echo (!empty($_GET['email'])) ? trim($_GET['email']) : '' ?>"><br>
    <input type="password" name="password_1" placeholder="New Password"><br>
    <input type="password" name="password_2" placeholder="Repeat Password"><br>
</form>
    <p id="containerLinkAction">
<a href="#" onclick="changePassword();">Change</a><br>
    </p>
<p id="return_from_changePassword"></p>
    </div>
</body>
</html>