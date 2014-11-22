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
$sql = "SELECT gamesMightLike FROM users WHERE email = '$username'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);

if(strlen($row['gamesMightLike']) === 0) {
    return;
}
else {
    $mightLike = $row['gamesMightLike'];
    $mightLike = explode("<br>", $mightLike);
}

$key = array_search($game, $mightLike);
unset($mightLike[$key]);

$mightLike = implode("<br>", $mightLike);

$mightLike = addslashes($mightLike);
$sql = "UPDATE users SET gamesMightLike = '$mightLike' WHERE email = '$username'";
$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

if(isset($_POST['dislike'])):
    $sql = "SELECT gamesDisliked FROM users WHERE email = '$username'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);

    if(strlen($row['gamesDisliked']) === 0) {
        $disliked = "<br>".$game;
    }
    else {
        $disliked = $row['gamesDisliked']."<br>".$game;
    }
    
    $disliked = addslashes($disliked);
    $sql = "UPDATE users SET gamesDisliked = '$disliked' WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
endif;


