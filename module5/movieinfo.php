<?php
session_start();

if (isset($_GET["movie_id"]))
{
    createIMessage();
}else{
    echo "Required Movie ID Was NOT Provided";
}

function createIMessage(){
    $movie_id = $_GET["movie_id"];
    $movie = file_get_contents("http://www.omdbapi.com/?apikey=7491f5e4&i=" . $movie_id . "&type=movie&r=json");
    $array = json_decode($movie, true);
    ?>
    <!DOCTYPE html>
    <link rel="stylesheet" type="text/css" href="../css/site.css">
    <div class='modal-header'>
    <span class='close'>[Close]</span>
    <h2><?php echo $array["Title"]?> (<?php echo $array["Year"]?>) Rated <?php echo $array["Rated"]; echo " "; echo $array["Runtime"]?><br /><?php echo $array["Genre"]?></h2>
    </div>
    <div class='modal-body'>
    <p>Actors: <?php echo $array["Actors"]?><br />Directed By: <?php echo $array["Director"]?><br />Written By: <?php echo $array["Writer"]?></p>
    </div>
    <div class='modal-footer'>
    <p><?php echo $array["Plot"]?></p>
    </div>
    <?php
}
?>