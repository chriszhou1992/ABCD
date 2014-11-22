<?php

session_start();

$username = $_SESSION["user"];

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$sql = "SELECT gamesLiked FROM users WHERE email = '$username'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);

$gamesLiked = $row['gamesLiked'];
$gamesLiked = explode("<br>", $gamesLiked);
$gamesLiked = array_slice($gamesLiked, 1);

$bound = count($gamesLiked);
if($bound ===0):
    echo "<div class='alert alert-white'><i class='fa fa-crosshairs'></i>&nbsp&nbspYour collection is empty because you have not liked any game yet!</div>";
    return;
endif;

$count = 0;
foreach($gamesLiked as $game):
    $sql = "SELECT * FROM games where name = '$game'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    
    $imageSrc = $row['imageURL'];
    echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
    $name = $row['name'];
    
    $send = str_replace("'", "!-!-!", $name);
    echo "<span class='games' name='$send'>$name</span><hr>";
    $brief = $row['brief'];
    if($count+1 === $bound) {
        echo "$brief";
    } else {
        echo "$brief<hr>";
    }
    $count += 1;
endforeach;
