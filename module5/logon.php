<?php
session_unset();
session_start();
include 'email.php';
?>
<!DOCTYPE html>
<html id= "movie-color">
    <head>
        <title> Movie Xpress </title>
        <link rel="stylesheet" type="text/css" href="../css/site.css">
    </head>
    <body>
    <div style="text-align: center;">
        <h2 id="movie-title"> myMovies Xpress! </h2>
        <h4 id="login-direct"> Welcome to myMoviesXpress!</h4>
        <h4 id="login-direct"> Type In Your Credentials To Login Or Click Create Account.</h4>
        <hr>
    </div>
    </body>
</html>
    <?php

    //Section 1

    if (isset($_GET["action"]))
    {
        if($_GET["action"] == "validate"){
            validateUser();
        }
        if($_GET["action"] == "forgot"){
            forgotPassword();
        }
        if($_GET['action'] == 'login'){
            authenticateUser();
        }
        if($_GET['action'] == 'create'){
            createUser();
        }
        if($_GET['action'] == 'reset'){
            resetPassword();
        }
    }
    if (isset($_GET["form"])){
        if($_GET["form"] == "create"){
            displayCreateAccount();
        }
        if($_GET["form"] == "reset"){
            displayForgotPassword();
        }
    }else{
        displayLogin();
    }


    //Check Users Login Credentials
    function authenticateUser(){
        $file = "data/credentials.db";
        $file_size = filesize($file);
        if ($file_size > 0) {
            $dataArray = file($file);
            foreach ($dataArray as $key => $str) {
                $dataArray[$key] = explode(",", $str);
            }
            $arr = array_values($dataArray);
            $i = 0;
            while($i < count($dataArray)){
                    $checkUser = $arr[$i][0];
                    $checkPass = $arr[$i][1];
                    $loginDisplayName = $arr[$i][2];
                    if ($_POST["userName"] == $checkUser) {
                        if ($_POST["password"] == $checkPass) {
                            session_start();
                            $_SESSION["display"] = $loginDisplayName;
                            header("Location: ./index.php");
                            break;
                        } else {
                            echo "Password Is Incorrect!";
                            $_SESSION["fail"] = "true";
                            break;
                        }

                    } else {
                        $i++;
                        if($i == count($dataArray)){
                            echo"User does not exist.";
                        }
                    }
            }
        }else{
            echo "No Accounts Exist, Create an Account";
        }
    }

//Display the Login Form
    function displayLogin(){

        echo "<html>\n";
        echo "    <head>\n";
        echo "        <script src='script.js'></script>\n";
        echo "    </head>\n";
        echo "    <body>\n";
        echo "        <p>&nbsp;<p>\n";
        echo "        <p>Enter Your Credentials to Login:</p>\n";
        echo "        <form method='post' action='./logon.php?action=login'>\n";
        echo "            Username: <input type='text' name='userName' required/> <br />\n";
        echo "            Password: <input type='password' name='password' required />\n";
        echo "                              <input type = 'hidden' value = 'login' />";
        echo"<br>";
        echo"<button type='submit' name='Login' value='Login'>Login</button>";
        echo"<button type='reset'  value='Reset'>Clear</button>";
        echo"<br>";
        echo "<a href='javascript:createAccount();'> Create Account</a>";
        if($_SESSION["fail"] == "true") {
            $tempUser = $_POST['userName'];
            echo "<a href='javascript:forgotPassword(\"$tempUser\");'> Forgot Password?</a>";
        }
        echo "        </form>\n";
        echo "        <p><a href='../index.html'>ePortfolio</a></p>\n";
        echo "    </body>\n";
        echo "</html>";

    }

//SECTION 6
function displayCreateAccount(){
    echo "<html>\n";
    echo "    <head>\n";
    echo "        <script src='script.js'></script>\n";
    echo "    </head>\n";
    echo "    <body>\n";
    echo "        <p>&nbsp;<p>\n";
    echo "        <p>Create Account:</p>\n";
    echo "         <form name = 'createAccountForm' action='./logon.php?action=create' onsubmit='return validateCreateAccountForm();' method='post'>\n";
    ?>                Display Name: <input type='text' name='displayName' required/> <br />
                      Username: <input type='text' name='userName' required/> <br />
                      Email Address: <input type='text' name='emailAdd' id="createEmail" required/> <br />
                      Confirm Email Address: <input type='text' name = 'confirmemailAdd' id="createConfirmEmail" required/> <br />
                      Password: <input type='password' name='password' id="createPassword" required /><br />
                      Confirm Password: <input type='password' name='confirmPassword' id="createConfirmPassword"required/> <br />
                                  <input type = 'hidden' name = 'action' value = 'create' />
    <?php
    echo"<button type='submit' name='Login' value='Login'>Login</button>";
    echo"<button type='reset'  value='Reset'>Clear</button>";
    echo"<button onclick='cancel(\"Create Account\");'>Cancel</button>";
    echo "        </form>\n";
    echo "        <p><a href='../index.html'>ePortfolio</a></p>\n";
    echo "    </body>\n";
    echo "</html>";
}

//SECTION 7
function displayForgotPassword(){
    $userNameReset = $_GET["username"];
    $_SESSION["fail"] = "false";
    echo "<html>\n";
    echo "    <head>\n";
    echo "        <script src='script.js'></script>\n";
    echo "    </head>\n";
    echo "    <body>\n";
    echo "        <p>&nbsp;<p>\n";
    echo "        <p>Reset Password:</p>\n";
    echo "        <form name = 'resetForm' action='./logon.php?action=reset' onsubmit=\"return validateResetPasswordForm();\" method=\"post\">\n";
    echo "            Username: <input type='text' name='userNameReset' value=\"$userNameReset\"> <br />\n";
    echo "            Password: <input type='password' name='passwordReset' required /><br />\n";
    echo "            Confirm Password: <input type='password' name='confirmPasswordReset' required/> <br />\n";
    echo "                              <input type = 'hidden' name = 'action' value = 'reset' />";
    echo"<button type='submit' name='Login' value='Login'>Login</button>";
    echo"<button type='reset'  value='Reset'>Clear</button>";
    echo"<button onclick='cancel(\"Forgot Password\");'>Cancel</button>";
    echo"<br>";
    echo "        </form>\n";
    echo "        <p><a href='../index.html'>ePortfolio</a></p>\n";
    echo "    </body>\n";
    echo "</html>";

}

//SECTION 5
function validateUser(){
    $file = "data/credentials.db";
    $userNameCheck = $_SESSION["userName"];
    $credentials = $_SESSION["userName"]. "," . $_SESSION["password"]. "," . $_SESSION["displayName"]. "," . $_SESSION["emailAdd"]. PHP_EOL;
    $validateUsername = $_GET["username"];
    if($validateUsername == $userNameCheck){
        echo "Account Validated. You May Login";
        file_put_contents($file, $credentials, FILE_APPEND );
    }else{
        echo "Account Verification Failure, Try Again.";
    }

}

//SECTION 7
function forgotPassword(){
    $_SESSION["fail"] = "false";
    $verifyUser = $_GET["username"];
    $file = "data/credentials.db";
    $file_size = filesize($file);
    if ($file_size > 0) {
        $dataArray = file($file);
        foreach ($dataArray as $key => $str) {
            $dataArray[$key] = explode(",", $str);
        }
        $arr = array_values($dataArray);
        $i = 0;
        while ($i < count($dataArray)) {
            $checkUser = $arr[$i][0];
            if ($verifyUser == $checkUser) {
                $resetEmail = $arr[$i][3];
                break;
            }else{
                $i++;
            }
        }
    }
    echo "Check email for reset link.";
    $resetString = "Click the link to reset your password, 
                        http://139.62.210.181/~cw114766/module5/logon.php?form=reset&username=".$verifyUser;
    sendEmail($resetEmail,$resetString);

}

//SECTION 8
function resetPassword(){
        $userReset = $_POST["userNameReset"];
        $passWordReset = $_POST["passwordReset"];
        $file = "data/credentials.db";
        $file_size = filesize($file);
        if ($file_size > 0) {
            $dataArray = file($file);
            foreach ($dataArray as $key => $str) {
                $dataArray[$key] = explode(",", $str);
            }
            $arr = array_values($dataArray);
            $i = 0;
            $j = 0;
            while ($j < count($dataArray)) {
                $arr[$j][3] = trim($arr[$j][3]);
                $j++;
            }
            while ($i < count($dataArray)) {
                $checkUser = $arr[$i][0];
                $checkPass = $arr[$i][1];
                if ($userReset == $checkUser) {
                    if ($passWordReset != $checkPass) {
                        echo "Password Successfully Reset.";
                        $arr[$i][1] = $passWordReset;
                        $fp = fopen($file,"w");
                        foreach($arr as $temp) {
                            fputcsv($fp, $temp);
                        }
                        break;
                    } else {
                        echo "You entered the current password.";
                        break;
                    }

                } else {
                    $i++;
                    if($i == count($dataArray)){
                        echo"Username not found";
                    }
                }
            }
        }
}

//SECTION 4
function createUser(){
        $_SESSION["userName"] = $_POST["userName"];
    $_SESSION["password"] = $_POST["password"];
    $_SESSION["displayName"] = $_POST["displayName"];
    $_SESSION["emailAdd"] = $_POST["emailAdd"];
    $credentials = $_POST["userName"]. "," . $_POST["password"]. "," . $_POST["displayName"]. "," . $_POST["emailAdd"]. PHP_EOL;
    $file = "data/credentials.db";
    $verifyEmail = $_POST["emailAdd"];
    $verifyUser = $_POST["userName"];
    $file_size = filesize($file);
        if ($file_size > 0) {
            $dataArray = file('data/credentials.db');
            foreach ($dataArray as $key => $str) {
                $dataArray[$key] = explode(",", $str);
            }
            $arr = array_values($dataArray);
            $i = 0;
            while($i < count($dataArray)){
                    $checkUser = $arr[$i][0];
                    if ($_POST["userName"] == $checkUser) {
                        echo "Username already exist";
                        break;
                    } else {
                        $i++;
                    }
                    if($i == count($dataArray) && $_POST["userName"] != $checkUser){
                        echo "Verification Email Sent. ";
                        sendEmail($verifyEmail, " Click the link to verify your account,
                        // http://139.62.210.181/~cw114766/module5/logon.php?action=validate&username=".$verifyUser);
                        break;
                    }
            }
        }else{
            file_put_contents($file, $credentials, FILE_APPEND );
        }
}

?>



