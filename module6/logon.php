<?php

session_start();
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
// Section 1
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if(isset($_POST['action']))
	{
		if($_POST['action'] === 'create')
		{
			createUser($_POST["username"], $_POST["password"], $_POST["displayname"], $_POST["email"]);
		}
		else if($_POST['action'] === 'login')
		{
			authenticateUser($_POST["username"], $_POST["password"]);
		}
		else if($_POST['action'] === 'reset')
		{
			resetPassword($_POST["username"], $_POST["password"]);
		}
    }
}
else if(isset($_GET['form']))
{
	if($_GET['form'] === 'create')
	{
		displayCreateAccountForm();
	}
	else if($_GET['form'] === 'reset')
	{
		displayPasswordResetForm($_GET['username']);
	}
}
else
{
	$message = "";
	
	if(isset($_GET['message']))
	{
		$message = $_GET['message'];
	}
	session_unset();
	displayLogonForm($message);
}

// Section 2
function authenticateUser($username, $password)
{
	$userInfo = file_get_contents("./data/credentials.db");
	$array = explode(",", $userInfo);

	$userLogon = $username . " " . $password;
	$dbLogon = $array[0] . " " . $array[1];

	if($userLogon === $dbLogon)
	{
		$_SESSION['login_fail'] = false;
		$_SESSION['displayName'] = $array[2];
		header("location: ./index.php");
	}
	else
	{
		$_SESSION['login_fail'] = true;
		displayLogonForm("The Username and/or Password is Incorrect! Please Try Again.", $username);
	}
}

// Section 4
function createUser($username, $password, $displayName, $email)
{
	$userData = $username . "," . $password . "," . $displayName . "," . $email;
	
	file_put_contents("./data/credentials.db", $userData);
	displayLogonForm("The User Account Successfully Created! Please Login.", $username);
}

// Section 6
function displayCreateAccountForm()
{
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Create New User</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	echo "        <p>Enter Your Information Below to Create a User Account!</p>\n";
	echo "        <form action='logon.php' onsubmit='return validateCreateAccountForm();' method='post'>\n";
	echo "            <table>\n";
	echo "                <tr><td style='text-align: right;'>Display Name: </td><td><input type='text' id='displayname' name='displayname' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Username: </td><td><input type='text' id='username' name='username' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Email Address: </td><td><input type='text' id='email' name='email' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Confirm Email Address: </td><td><input type='text' id='confirmEmail' name='confirmEmail' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Password: </td><td><input type='password' id='password' name='password' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Confirm Password: </td><td><input type='password' id='confirmPassword' name='confirmPassword' required /></td></tr>\n";
	echo "                <tr><td colspan=2><input type='hidden' name='action' value='create' /></td></tr>\n";
	echo "                <tr><td colspan=2><input type='button' value='Cancel' onClick='javascript:cancel(\"create\")' /> &nbsp;&nbsp;\n";
	echo "                <input type='Reset' value='Clear' /> &nbsp;&nbsp;\n";
	echo "                <input type='Submit' value='Submit' /></td></tr>\n";
	echo "            </table>\n";
	echo "        </form>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

// Section 3
function displayLogonForm($message = null, $username = "")
{
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Log into myMovies Xpress!</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	if($message != null)
	{
		echo "        <p style='color: red;'>" . $message . "</p>\n";
	}
	echo "        <p>Enter Your Credentials to Login!</p>\n";
	echo "        <form action='logon.php' method='post'>\n";
	echo "            <table>\n";
	echo "                <tr><td>Username: </td><td><input type='text' id='username' name='username' value='" . $username . "' required /></td></tr>\n";
	echo "                <tr><td>Password: </td><td><input type='password' name='password' required /></td></tr>\n";
	echo "                <tr><td colspan=2>\n";
	echo "                    <input type='hidden' name='action' value='login' />\n";
	echo "                    <input type='Reset' valus='Clear' /> &nbsp;&nbsp;\n";
	echo "                    <input type='Submit' value='Login' />\n";
	echo "                </td></tr>\n";
	echo "                <tr><td colspan=2>\n";
	echo "                    <a href='javascript:createAccount()'>Create Account</a>\n";
	if(isset($_SESSION['login_fail']) && $_SESSION['login_fail'] == true)
	{
		echo "                    &nbsp;&nbsp; <a href='javascript:forgotPassword()'>Forgot Password</a>\n";
	}
	echo "                </td></tr>\n";
	echo "            </table>\n";
	echo "        </form>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

// Section 9
function displayPasswordResetForm($username)
{
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Change Password</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	echo "        <form action='logon.php' onsubmit='return validateResetPasswordForm();' method='post'>\n";
	echo "            <table>\n";
	echo "                <tr><td style='text-align: right;'>Username: </td><td><input type='text' name='username' readonly value='" . $_GET['username'] . "' /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Password: </td><td><input type='password' id='password' name='password' required /></td></tr>\n";
	echo "                <tr><td style='text-align: right;'>Confirm Password: </td><td><input type='password' id='confirmPassword' name='confirmPassword' required /></td></tr>\n";
	echo "                <tr><td colspan=2>\n";
	echo "                    <input type='hidden' name='action' value='reset' />\n";
	echo "                    <input type='button' value='Cancel' onClick='javascript:cancel(\"reset\")' /> &nbsp;&nbsp;\n";
	echo "                    <input type='reset' value='Reset' /> &nbsp;&nbsp;\n";
	echo "                    <input type='submit' value='Submit' />\n";
	echo "                </td></tr>\n";
	echo "        </form>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

// Section 8
function resetPassword($username, $password)
{
	$userInfo = file_get_contents("./data/credentials.db");
	$array = explode(",", $userInfo);
	
	if($username === $array[0])
	{
		$array[1] = $password;
		file_put_contents("./data/credentials.db", implode(",", $array));
		displayLogonForm("Your Password Was Successfully Reset! Please Login.", $username);
	}
	else
	{
		displayLogonForm("The Specified Username Does Not Exist! Please Try Again.");
	}
}

?>