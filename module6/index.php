<?php

session_start();

define('API_KEY', '7491f5e4');

// Section 1
if(isset($_GET["action"]))
{
	if($_GET["action"] === "add")
	{
		addMovieToCart($_GET["movie_id"]);
	}
	else if($_GET["action"] === "remove")
	{
		removeMovieFromCart($_GET["movie_id"]);
	}
	else if($_GET["action"] === "checkout")
	{
		checkout();
	}
}
else 
{
	displayShoppingCart();
}

// Section 3
function addMovieToCart($movieID)
{
	$cartItems = getCartData();
	
	array_push($cartItems, $movieID);
	file_put_contents("./data/cart.db", implode("|", $cartItems));
	displayShoppingCart();
} 

// Section 5
function checkout()
{
	$cartItems = getCartData();
	
	$count = count($cartItems);
	
	$message = "Congratulations " . $_SESSION['displayName'] . " on Your Purchase of " . $count . " Movies!";
	header("location: ./logon.php?message=" . $message);
}

// Section 2
function displayShoppingCart()
{
	$cartItems = getCartData();
	
	$count = count($cartItems);
	
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Create New User</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	echo "        <p>Welcome, " . $_SESSION['displayName'] . " [<a href='javascript:confirmLogout();'>Logout</a>]</p>\n";
	echo "        <h3>myMovies Xpress!</h3>\n";

	if($count == 0)
	{
		echo "        <p>Add Some Movies to Your Shopping Cart!</p>\n";
	}
	else if($count > 0)
	{
		echo "        <p>There Are " . $count . " Movies in Your Shopping Cart!</p>\n";
		echo "        <table>\n";
		echo "            <tr><th>Poster</th><th>Title (Year)</th><th>View More Info</th><th>Remove Movie</th></tr>\n";
		
		for($x = 0; $x < $count; $x++)
		{
            $rawMovieData = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&i=" . $cartItems[$x] . "&type=movie&r=json");
			$movieData = json_decode($rawMovieData, true);
			
			echo "            <tr>\n";
			echo "                <td><img height=100 src='" . $movieData['Poster'] . "' /></td>\n";
			echo "                <td>" . $movieData['Title'] . " (" . $movieData['Year'] . ")</td>\n";
			echo "                <td><a href='javascript:displayMovieInformation(\"" . $movieData['imdbID'] . "\");'>View More Info</a></td>\n";
			echo '                <td style="text-align: center;"><a href="javascript:confirmRemove(\'' . addslashes($movieData["Title"]) . '\',\'' . $movieData["imdbID"] . '\');">X</a></td>';
			echo "            </tr>\n";
		}
		echo "        </table>";
	}
	echo "        <br />\n";
	echo "        <input type='button' value='Add Movie' onclick='location.href=\"./search.php\";' /> &nbsp;&nbsp;\n";
	echo "        <input type='button' value='Checkout' onClick='javascript:checkout()' " . ($count == 0 ? "disabled" : "") . " />\n";
	echo "        <div id='modalWindow' class='modal'>\n";
	echo "            <div id='modalWindowContent' class='modal-content'>\n";
	echo "            </div>\n";
	echo "        </div>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

// Called from within displayShoppingCart() function
function getCartData()
{
	$cartData = file_get_contents("./data/cart.db");
	$cartItems = explode("|", $cartData);
	if(trim($cartItems[0]) === "")
	{
		unset($cartItems[0]);
	}
	return $cartItems;
}

// Section 4
function removeMovieFromCart($movieID)
{
	$cartItems = getCartData();
	
	$index = array_search($movieID, $cartItems, false);
	unset($cartItems[$index]);
	file_put_contents("./data/cart.db", implode("|", $cartItems));
	displayShoppingCart();
}	

?> 