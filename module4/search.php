<!DOCTYPE html>
<?php
// Start the session
session_start();
echo "        <p>Welcome " . $_SESSION["displayName"] . " <a href='javascript:confirmLogout();'>[logout]</a></p>\n";
?>
<head>
    <title> Search </title>
    <link rel="stylesheet" type="text/css" href="../css/site.css">
</head>
<body>
<div style="text-align: center;">
    <h2 id="movie-title"> myMovies Xpress! </h2>
    <h4 id="login-direct"> Type in part of a movie name and click search to get results. <br> If you would like to add a movie to your cart, hit the plus on the right side.</h4>
    <hr>
</div>
<form action="search.php" method="post">
    <label> Search: </label>
    <label for="keyword"></label><input type="text" id="keyword" name="keyword" value="<?php echo $keyword;?>"/>
    <br>
    <button type="submit">Search </button>
    <button type="button" onclick="location.href='index.php'" id="cancelBtn">Cancel</button>
</form>
<?php
        $keyword = $_POST["keyword"];
        $results = file_get_contents('http://www.omdbapi.com/?apikey=7491f5e4&s=' . $keyword . '&type=movie&r=json');
        $array = json_decode($results, true)["Search"];
        $count = count($array);
        $i = 0;
        echo "<h4>".$count.' '."Movies Found"."</h4>";
        echo "<table style=\"width:50%\" id=\"movieTable\">";
        echo "<tr>";
        echo "<th>Poster</th>";
        echo "<th>Title and Year</th>";
        echo "<th>Add Movie</th>";
        echo "</tr>";
        while ($i < $count) {
            echo "<tr>";
            $poster = $array["Poster"];
            echo "<td><img src ='$poster' alt='Poster'></td>";
            echo "<td>" . $array[$i]["Title"]. ' ' .$array[$i]["Year"]."</td>";
            $movieID = $array[$i]["imdbID"];?>
            <td><a href="javascript:addMovie('<?php echo $movieID?>')">+</a></td>
            <?php echo "</tr>";
            $i++;
        }
        echo "</table>";
?>
</body>
</html>



