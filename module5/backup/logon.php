<?php
	session_unset();
		
	// Section 1
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		authenticateUser();
	}
	else {
		displayLogonForm();
	}

	// Section 2
	function authenticateUser(){
		$file = fopen("./data/credentials.db", "r") or die("Unable to open file!");
		$data = fgets($file);
		fclose($file);
		$valuesArray = explode(":", $data);
		$credentials = $valuesArray[0];
		if($credentials == $_POST["userName"] . " " . $_POST["password"]){
			session_start();
			$_SESSION["display"] = $valuesArray[1];
			header("Location: ./index.php");
		}
		else{
			echo "The Provided Username or Password Is Incorrect!";
		}
	}
	
	// Section 3
	function displayLogonForm() {
		echo "<!DOCTYPE HTML>\n";
		echo "<html>\n";
		echo "    <head>\n";
		echo "        <title>Welcome to myMovies Xpress!</title>\n";
		echo "        <script src='script.js'></script>\n";
		echo "    </head>\n";
		echo "    <body>\n";
		echo "        <p>&nbsp;<p>\n";
		echo "        <h2>myMovies Xpress!</h2><br />\n";
		echo "        <p>Enter Your Credentials to Login:</p>\n";
		echo "        <form method='post' action='./logon.php'>\n";
		echo "            Username: <input type='text' name='userName' required/> <br />\n";
		echo "            Password: <input type='password' name='password' required /><br />\n";
		echo "            <input type='submit' value='Login' />\n";
		echo "            <input type='reset' value='Clear' />\n";
		echo "        </form>\n";
		echo "        <p><a href='../index.html'>ePortfolio</a></p>\n";
		echo "    </body>\n";
		echo "</html>";			
	}
?>