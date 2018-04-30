<?php

define('API_KEY', '[YOUR_API_KEY]');

// Section 1
if(isset($_GET['movie_id']))
{
	createMessage($_GET['movie_id']);
}
else
{
	echo "Error: Required Movie ID Was NOT Provided!\n";
}

// Section 2
function createMessage($movieID)
{
	$rawMovieData = file_get_contents("http://www.omdbapi.com/?apikey=" . API_KEY . "&i=" . $movieID . "&type=movie&r=json");
	$movieData = json_decode($rawMovieData, true);

	echo "<div class='modal-header'>\n";
    echo "    <span class='close'>[Close]</span>\n";
    echo "    <h2>" . $movieData['Title'] . " (" . $movieData['Year'] . ") Rated " . $movieData['Rated'] . " " . $movieData['Runtime'] . "<br />" . $movieData['Genre'] . "</h2>\n";
	echo "</div>\n";
	echo "<div class='modal-body'>\n";
    echo "    <p>Actors: " . $movieData['Actors'] . "<br />Directed By: " . $movieData['Director'] . "<br />Written By: " . $movieData['Writer'] . "</p>\n";
	echo "</div>\n";
	echo "<div class='modal-footer'>\n";
	echo "    <p>" . $movieData['Plot'] . "</p>\n";
	echo "</div>\n";
}

?>