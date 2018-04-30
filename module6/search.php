<?php

session_start();

define('API_KEY', '7491f5e4');

// Section 1
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['keyword']))
{
	displaySearchResults($_POST['keyword']);
}
else
{
	displaySearchForm();
}

// Special function not required by the project
// This function sorts the search results by movie title
function cmp($a, $b)
{
	return strcmp($a["Title"], $b["Title"]);
}

// Section 2
function displaySearchForm()
{
	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Search for Some Movies</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	echo "        <p>Welcome, " . $_SESSION['displayName'] . " [<a href='javascript:confirmLogout();'>Logout</a>]</p>\n";
	echo "        <h3>myMovies Xpress!</h3>\n";
	echo "        <form action='./search.php' method='post'>\n";
	echo "            <p>Enter One of More Keyword(s) Below to Find a Movie!</p>\n";
	echo "            Search: <input type='text' name='keyword' required><br><br>\n";
	echo "            <input type='button' value='Cancel' onClick='location.href=\"./index.php\"'>\n";
	echo "            &nbsp;&nbsp; <input type='submit' value='Search'>\n";
	echo "        </form>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

// Section 3
function displaySearchResults($keyword)
{
	$results = file_get_contents("http://www.omdbapi.com/?apikey=" . API_KEY . "&s=" . urlencode($keyword) ."&type=movie&r=json");
	$searchResults = json_decode($results, true)["Search"];
	usort($searchResults, cmp);
	$count = count($searchResults);

	echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "    <head>\n";
	echo "        <title>Search Results</title>\n";
	echo "        <link rel='stylesheet' type='text/css' href='../site.css'>\n";
	echo "        <script type='text/javascript' src='./script.js'></script>\n";
	echo "    </head>\n";
	echo "    <body>\n";
	echo "        <p>Welcome, " . $_SESSION['displayName'] . " [<a href='javascript:confirmLogout();'>Logout</a>]</p>\n";
	echo "        <h3>myMovies Xpress!</h3>\n";
	echo "        <p>" . $count . " Movies Found!\n";
	if($count > 0)
	{
		echo "        <table>\n";
		echo "            <tr><th>Poster</th><th>Title (Year)</th><th>Add Movie</th></tr>\n";
		foreach($searchResults as $key => $value)
		{
			echo "            <tr>\n";
			echo "                <td><img height=100 src='" . $value["Poster"] . "' /></td>\n";
			echo "                <td>" . $value["Title"] . " (" . $value["Year"] . ")</td>\n"; 
			echo "                <td style='text-align: center;'><a href='javascript:addMovie(\"" . $value["imdbID"] . "\")'>+</a></td>\n";
			echo "            </tr>";
		}
		echo "        </table>";
		
	}
	echo "        <input type='button' value='Cancel' onClick='location.href=\"./index.php\"'>\n";
	echo "    </body>\n";
	echo "</html>\n";
}

?> 