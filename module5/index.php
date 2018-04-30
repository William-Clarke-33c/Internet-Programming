<?php
session_start();
include 'email.php';
	?>
	<!DOCTYPE html>
	<html id= "movie-color">
	<head>
		<title> Movie Cart </title>
		<script src='./script.js'></script>
		<link rel="stylesheet" type="text/css" href="../css/site.css">
	</head>
	<body>
    <?php echo  "<p>Welcome ". $_SESSION["display"]; ?>
	<a href='javascript:confirmLogout();'> [logout]</a><p>
	<div style="text-align: center;">
		<h2 id="movie-title"> myMovies Xpress! </h2>
		<h4 id="login-direct"> This is Your Movie Shopping Cart. Click "Add Movie" To Add More Movies. <br>
		Or Hit The "X" To Remove A Movie. You Can Also Hit More Information To Learn More About That Movie.</h4>
		<hr>
	</div>
<?php
	// Section 1
	if (isset($_GET["action"]))
	{
		if($_GET["action"] == "add")
		{
			addToCart($_GET["id"]);
		}
		if($_GET["action"] == "remove")
		{
			removeFromCart($_GET["id"]);
		}
		if($_GET["action"] == "checkout"){
		    checkout();
        }
	}
	else if ($_SESSION["display"] == ""){

    } else{
		displayCart();
	}

	// Section 3
	function addToCart($movieID)
	{
		$movieArray = readMovieIDs();
		
		array_push($movieArray, $movieID);
		writeMovieIDs("./data/cart.db", $movieArray);
		displayCart();
	}
	
	// Section 4
	function removeFromCart($movieID)
	{
		$movieArray = readMovieIDs();
		
		$index = array_search($movieID, $movieArray);
		unset($movieArray[$index]);
		$movieArray = array_values($movieArray);
		writeMovieIDs("./data/cart.db", $movieArray);
		displayCart();
	}
	
	// Special function not required by the project
	function readMovieIDs()
	{
		$array = explode(" ", file_get_contents("./data/cart.db"));
		$array = array_map("trim", $array);
		if($array[0] == "") { unset($array[0]); }
		return $array;
	}
	
	// Special function not required by the project
	function writeMovieIDs($file, $array)
	{
		$array = array_map("trim", $array);
		file_put_contents($file, implode(" ", $array));
	}

	//MODULE 5 SECTION 3
    function checkout()
    {
    	echo"Checkout Receipt Emailed";
        $movieArray = readMovieIDs();
        $size = count($movieArray);
        $verifyUser = $_SESSION["userName"];
        if ($size >= 1) {
            $i = 0;
            while ($i < $size) {
                $movie = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&i=" . $movieArray[$i] . "&type=movie&r=json");
                $array = json_decode($movie, true);
                $checkOutArray[$i] = $checkOutString = " " . $array["Title"] . " (" . $array["Year"] . ")";
                $i++;
            }
            $j = 0;
            $MESSAGE_BODY = $_SESSION["display"]."," . "\n";
            $MESSAGE_BODY .= "\n";
            $MESSAGE_BODY .= "Thanks for shopping with myMovie Express!" . "\n";
            $MESSAGE_BODY .= "\n";
            $MESSAGE_BODY .= "Congratulations on your purchase of $size movies!" . "\n";
            $MESSAGE_BODY .= "\n";
            while ($j < $size) {
                $MESSAGE_BODY .= ($j+1).". ". $checkOutArray[$j] . "\n";
                $j++;
            }
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
                        $checkOutEmail = $arr[$i][3];
                        break;
                    } else {
                        $i++;
                    }
                }
                sendEmail($checkOutEmail, $MESSAGE_BODY);
            }
        }
    }

	// Section 2
	function displayCart()
	{
		$movieArray = readMovieIDs();
		if(count($movieArray) == 0)
		{
			echo "        <p>Add Some Movies to Your Cart</p>\n";
		}
		else
		{
			echo "        <p>" . count($movieArray) . " Movies in Your Shopping Cart</p>\n";
            echo "        <table>\n";
            echo "            <tr>\n";
            echo "                <th>Poster</th>\n";
            echo "                <th>Movie Title</th>\n";
            echo "                <th>More Information</th>\n";
            echo "                <th>Add to Cart</th>\n";
            echo "            </tr>\n";
			for($x = 0; $x < count($movieArray); $x++)
			{
				$rawMovieData = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&i=" . $movieArray[$x] . "&type=movie&r=json");
				$movieData = json_decode($rawMovieData, true);
                $temphold = $movieData["imdbID"];

				echo "            <tr>\n";
				echo "                <td>";
				echo '<img src="' . $movieData["Poster"] . '" height=100 />';
				echo "</td>\n";
				echo "                <td>";
				echo '<a href="http://www.imdb.com/title/' . $movieData["imdbID"] . '" target="_blank"><h1>' . $movieData["Title"] . ' (' . $movieData["Year"] . ')</h1></a>';
				echo "</td>\n";
				echo"<td>\n";
				echo"<a href=\"javascript:void(0);\" onclick='displayMovieInformation(\"$temphold\");'>View More Info</a>";
				echo "                <td>\n";
				echo '<a href="javascript:confirmRemove(\'' . addslashes($movieData["Title"]) . '\',\'' . $movieData["imdbID"] . '\');">';
				echo "<h2>X</h2></a></td>\n";
				echo "            </tr>\n";
			}
			echo "        </table>\n";
		}
		echo "        <br />\n";
		echo "        <input type='button' value='Add Movie' onclick='location.href = \"./search.php\";' />\n";
        if(count($movieArray) > 0) {
            echo "<button onclick='checkout();'>Checkout</button>";
        }
	}
?>
	<link rel="stylesheet" type="text/css" href="../css/site.css">
	<script src='script.js'></script>
	<div id="modalWindow" class="modal">
		<div id="modalWindowContent" class="modal-content">
		</div>
	</div>
	</body>
</html>
