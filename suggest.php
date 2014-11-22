<?php

session_start();

$username = $_SESSION["user"];
$game = $_POST["game"];

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$searchGame = addslashes($game);
$sql = "SELECT similar_games FROM games WHERE name = '$searchGame'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);


if(strlen($row['similar_games']) === 0) {
    echo "no similar games";
    return;
}
else {
    $similar = $row['similar_games'];
    $similar = explode("<br>", $similar);
    $similar = array_slice($similar, 1);
}

//$similar = "<br>".implode("<br>", $similar);
//var_dump($similar);

$sql = "SELECT * FROM users WHERE email = '$username'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);

if(strlen($row['gamesMightLike']) === 0) {
    $mightLike = [];
} else {
    $mightLike = explode("<br>", $row['gamesMightLike']);
    $mightLike = array_slice($mightLike, 1);
}

if(strlen($row['gamesLiked']) === 0) {
    $gamesLiked = [];
} else {
    $gamesLiked = stripslashes( $row['gamesLiked']);
    $gamesLiked = explode("<br>", $gamesLiked);
    $gamesLiked = array_slice($gamesLiked, 1);
}

if(strlen($row['gamesDisliked']) === 0) {
    $disliked = [];
} else {
    $disliked = explode("<br>", $row['gamesDisliked']);
    $disliked = array_slice($disliked, 1);
}
var_dump($similar);
var_dump($gamesLiked);
$lenArr = count($similar);
for($i=0; $i<$lenArr; $i++):
    if (($key = array_search($similar[$i], $gamesLiked)) !== false || ($key = array_search($similar[$i], $disliked)) !== false) {
        unset($similar[$i]);
    }
endfor;

$newArr = array_unique(array_merge($mightLike, $similar));
if(count(newArr) === 0) {
    echo "no similar games";
    return;
}


$newGamesMightLike = "<br>".implode("<br>", $newArr);

$newGamesMightLike = addslashes($newGamesMightLike);
$sql = "UPDATE users SET gamesMightLike = '$newGamesMightLike' WHERE email = '$username'";
$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

echo "similar games added";