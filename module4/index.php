<!DOCTYPE html>
<?php
// Start the session
session_start();
echo "Welcome, ".$_SESSION['display'];
if(isset($_GET["movieID"]) && isset($_GET["action"])){
    $movieID = $_GET["movieID"];
    $action = $_GET["action"];
}
?>
<script src="script.js"></script>
<a href="javascript:confirmLogout()">(Logout)</a>
<html id= "movie-color">
<head>
    <title> Account </title>
    <link rel="stylesheet" type="text/css" href="../css/site.css">
</head>
<body>
<div style="text-align: center;">
    <h2 id="movie-title"> myMovies Xpress! </h2>
    <h4 id="login-direct"> This is Your Movie Shopping Cart. Click "Add Movie" To Add More Movies <br> Or Hit The "X" To Remove A Movie.</h4>
    <hr>
</div>
<h2> Shopping Cart </h2>
    <?php
    if($action == "add"){
        addToCart($movieID);
    }
    else if($action == "remove"){
        removeFromCart($movieID);

    }else{
        displayCart();
    }

    function displayCart()
    {
        $movieArray = readMovieIDs();

        $i = 0;
        if(count($movieArray) == 0)
        {
                echo "        <p>Add Some Movies to Your Cart</p>\n";
        }else{
            echo "        <p>" . count($movieArray) . " Movies in Your Shopping Cart</p>\n";
            echo "        <table>\n";
            echo "            <tr>\n";
            echo "                <th>Poster</th>\n";
            echo "                <th>Movie Title</th>\n";
            echo "                <th>Remove</th>\n";
            echo "            </tr>\n";
            for($x = 0; $x < count($movieArray); $x++)
            {
                $rawMovieData = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&i=" . $movieArray[$x] . "&type=movie&r=json");
                $movieData = json_decode($rawMovieData, true);

                echo "            <tr>\n";
                echo "                <td>";
                echo '<img src="' . $movieData["Poster"] . '" height=100 />';
                echo "</td>\n";
                echo "                <td>";
                echo '<a href="http://www.imdb.com/title/' . $movieData["imdbID"] . '" target="_blank"><h1>' . $movieData["Title"] . ' (' . $movieData["Year"] . ')</h1></a>';
                echo "</td>\n";
                echo "                <td>\n";
                echo '<a href="javascript:confirmRemove(\'' . addslashes($movieData["Title"]) . '\',\'' . $movieData["imdbID"] . '\');">';
                echo "<h2>X</h2></a></td>\n";
                echo "            </tr>\n";
            }
            echo "        </table>\n";
        }
    }

    //Add movies to cart
    function addToCart($movieID)
    {
        $movieArray = readMovieIDs();

        array_push($movieArray, $movieID);
        writeMovieIDs("./data/cart.db", $movieArray);
        displayCart();
    }

    function writeMovieIDs($file, $array)
    {
        $array = array_map("trim", $array);
        file_put_contents($file, implode(" ", $array));
    }

    //Remove a movie from cart
    function removeFromCart($movieID)
    {
        $movieArray = readMovieIDs();

        $index = array_search($movieID, $movieArray);
        unset($movieArray[$index]);
        $movieArray = array_values($movieArray);
        writeMovieIDs("./data/cart.db", $movieArray);
        displayCart();
    }

    function readMovieIDs()
    {
        $array = explode(" ", file_get_contents("./data/cart.db"));
        $array = array_map("trim", $array);
        if($array[0] == "") { unset($array[0]); }
        return $array;
    }

    ?>
<button id="addMoviebtn">Add Movie</button>
<script>var btn = document.getElementById('addMoviebtn');
btn.addEventListener('click', function(){
    document.location.href="search.php"
})</script>
</body>
</html>