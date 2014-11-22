<?php
session_start();
if(!isset($_SESSION["user"])):
    echo "please log in to like";
    return;
endif;

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

if(isset($_POST["game"])):
    $game = addslashes($_POST["game"]);

    $sql = "SELECT num_likes FROM games WHERE name = '$game'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);

    $num_likes = $row['num_likes'] + 1;

    $sql = "UPDATE games SET num_likes = '$num_likes' WHERE name = '$game'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo $num_likes;
    
    putInToGameLikedList($con, $_SESSION["user"], $game);
    removeFromMightLikedList($con, $_SESSION["user"], $game);
    sortByCompany($con, $_SESSION["user"], $game);
    
endif;

if(isset($_POST["company"])):
    $company = addslashes($_POST["company"]);

    $sql = "SELECT num_likes FROM companies WHERE name = '$company'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    
    $num_likes = $row['num_likes'] + 1;
    
    $sql = "UPDATE companies SET num_likes = '$num_likes' WHERE name = '$company'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo $num_likes;
    
    putInToCompanyLikedList($con, $_SESSION["user"], $company);
endif;


function putInToGameLikedList($con, $username, $game) {
    $sql = "SELECT gamesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if($row['gamesLiked'] === null) {
        $game = "<br>".$game;
    } else {
        $game = $row['gamesLiked']."<br>".$game;
    }
    
    $game = addslashes($game);
    $sql = "UPDATE users SET gamesLiked = '$game' WHERE email = '$username'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
}

function removeFromMightLikedList($con, $username, $game) {
    $sql = "SELECT gamesMightLike FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if($row['gamesMightLike'] === null) {
        return;
    } else {
        $mightLike = $row['gamesMightLike'];
        $mightLike = explode("<br>", $mightLike);
        $mightLike = array_slice($mightLike, 1);
    }
    
    $game = stripslashes($game);
    if (($key = array_search($game, $mightLike)) !== false) {
        unset($mightLike[$key]);
    } else {
        return;
    }
    
    $mightLike = "<br>".implode("<br>", $mightLike);
    
    $mightLike = addslashes($mightLike);
    $sql = "UPDATE users SET gamesMightLike = '$mightLike' WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
}

function putInToCompanyLikedList($con, $username, $company) {
    $sql = "SELECT companiesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if($row['companiesLiked'] === null) {
        $company = "<br>".$company;
    } else {
        $company = $row['companiesLiked']."<br>".$company;
    }
    
    $company = addslashes($company);
    $sql = "UPDATE users SET companiesLiked = '$company' WHERE email = '$username'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
}

 function sortByCompany($con, $username, $game) {
    $sql = "SELECT publishers FROM games WHERE name = '$game'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if(count($row['publishers']) > 1) {
        /* to be added */
        return;
    } else {
        $publisher = $row['publishers'];
    }
    
    $sql = "SELECT glikedCompany FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if(strlen($row['glikedCompany']) === 0) {
        $publisher = "<br>".$publisher;
    } else {
        $publisher = $row['glikedCompany']."<br>".$publisher;
    }
    
    $publisher = addslashes($publisher);
    $sql = "UPDATE users SET glikedCompany = '$publisher' WHERE email = '$username'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
 }