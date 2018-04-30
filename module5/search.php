<?php
	session_start();
?>
<!DOCTYPE html>
<html id= "movie-color">
<head>
	<title> Search Movies! </title>
	<script src='./script.js'></script>
	<link rel="stylesheet" type="text/css" href="../css/site.css">
</head>
<body>
<?php echo  "<p>Welcome ". $_SESSION["display"]; ?>
<a href='javascript:confirmLogout();'> [logout]</a><p>
<div style="text-align: center;">
	<h2 id="movie-title"> myMovies Xpress! </h2>
	<h4 id="login-direct"> Enter One of More Keyword(s) Below to Find a Movie! <br>
	<hr>
</div>
</body>
</html>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		displayResults();
	}
	else 
	{
		displaySearch();
	}

	// Special function not required by the project
	function cmp($a, $b)
	{
		return strcmp($a["Title"], $b["Title"]);
	}
	
	function displaySearch()
	{
		echo "<!DOCTYPE HTML>\n";
		echo "    <body>\n";
		echo "        <form method='post' action='./search.php'>\n";
		echo "            Search: <input type='text' name='keyword' required/> <br />\n";
		echo "            <input type='submit' value='Search' />\n";
		echo "            <input type='button' value='Cancel' onclick='location.href = \"./index.php\";' />\n";
		echo "        </form>\n";
		echo "    </body>\n";
		echo "</html>\n";
	}

	function displayResults()
	{
		$results = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&s=".urlencode($_POST["keyword"])."&type=movie&r=json");
		$resultsArray = json_decode($results, true)["Search"];
		usort($resultsArray, cmp);
		
		echo "<!DOCTYPE HTML>\n";
        echo "<html id= \"movie-color\">\n";
		echo "    <head>\n";
		echo "        <title>Search Movies!</title>\n";
		echo "        <script src='script.js'></script>\n";
		echo "    </head>\n";
		echo "    <body>\n";
		echo "        <p>" . count($resultsArray) . " Movies Found!</p>\n";
		if(count($resultsArray) > 0)
		{
			echo "        <table>\n";
			echo "            <tr>\n";
			echo "                <th>Poster</th>\n";
			echo "                <th>Movie Title</th>\n";
			echo "                <th>Add to Cart</th>\n";
			echo "            </tr>\n";
		
			foreach($resultsArray as $key => $value)
			{
				echo "            <tr>\n";
				echo "                <td>";
				echo '<img src="' . $value["Poster"] . '" height=100 />';
				echo "</td>\n";
				echo "                <td>";
				echo '<a href="http://www.imdb.com/title/' . $value["imdbID"] . '" target="_blank"><h1>' . $value["Title"] . ' (' . $value["Year"] . ')</h1></a>';
				echo "</td>\n";
				echo "                <td>\n";
				echo '<a href="javascript:addMovie(\'' . $value["imdbID"] . '\');"><h2>+</h2></a>';
				echo "</td>\n";
				echo "            </tr>\n";
			}
			echo "        </table>\n";
		}
		else
		{
			echo "<p>No Movies Found!<p>";
		}
		echo "        <br />\n";
		echo "        <input type='button' value='Cancel' onclick='location.href = \"./index.php\";' />\n";
		echo "    </body>\n";
		echo "</html>\n";
	}
?>
