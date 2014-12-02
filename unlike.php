<?php
session_start();

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

    $num_likes = $row['num_likes'] - 1;

    $sql = "UPDATE games SET num_likes = '$num_likes' WHERE name = '$game'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo $num_likes;
    
    removeFromGameLikedList($con, $_SESSION["user"], $game);
    removeFromSuggestList($con, $_SESSION["user"], $game);
    removeFromglikedList($con, $_SESSION["user"], $game);
endif;

if(isset($_POST["company"])):
    $company = addslashes($_POST["company"]);

    $sql = "SELECT num_likes FROM companies WHERE name = '$company'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    
    $num_likes = $row['num_likes'] - 1;
    
    $sql = "UPDATE companies SET num_likes = '$num_likes' WHERE name = '$company'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo $num_likes;
    
    removeFromCompanyLikedList($con, $_SESSION["user"], $company);
endif;


function removeFromGameLikedList($con, $username, $game) {
    $sql = "SELECT gamesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    $gamesLiked = explode("<br>", $row['gamesLiked']);
    
    if (($key = array_search($game, $gamesLiked)) !== false) {
        unset($gamesLiked[$key]);
    }
    
    $gamesLiked = implode("<br>", $gamesLiked);
    
    $gamesLiked = addslashes($gamesLiked);
    $sql = "UPDATE users SET gamesLiked = '$gamesLiked' WHERE email = '$username'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
}

function removeFromCompanyLikedList($con, $username, $company) {
    $sql = "SELECT companiesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    $companiesLiked = explode("<br>", $row['companiesLiked']);
    
    if (($key = array_search($company, $companiesLiked)) !== false) {
        unset($companiesLiked[$key]);
    }
    
    $companiesLiked = implode("<br>", $companiesLiked);
    
    $companiesLiked = addslashes($companiesLiked);
    $sql = "UPDATE users SET companiesLiked = '$companiesLiked' WHERE email = '$username'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
}


function removeFromSuggestList($con, $username, $game) {
    
    $sql = "SELECT similar_games FROM games WHERE name = '$game'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);

    if(strlen($row['similar_games']) === 0) {
         $similar = array();
    }
    else {
        $similar = $row['similar_games'];
        $similar = explode("<br>", $similar);
        $similar = array_slice($similar, 1);
    }

    

    $sql = "SELECT gamesMightLike FROM users WHERE email = '$username'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);

    if(strlen($row['gamesMightLike']) === 0) {
        $mightLike = array();
    } else {
        $mightLike = explode("<br>", $row['gamesMightLike']);
        $mightLike = array_slice($mightLike, 1);
    }

    $lenArr = count($similar);
    for($i=0; $i<$lenArr; $i++):
        if (($key = array_search($similar[$i], $mightLike)) !== false) {
            unset($mightLike[$key]);
        }
    endfor;
    
    if(count($mightLike) === 0) {
        $newGamesMightLike = NULL;
    } else {
        $newGamesMightLike = "<br>".implode("<br>", $mightLike);
    }

    $newGamesMightLike = addslashes($newGamesMightLike);
    $sql = "UPDATE users SET gamesMightLike = '$newGamesMightLike' WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
}

function removeFromglikedList($con, $username, $game) {
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
    $gliked = $row['glikedCompany'];
    $gliked = explode("<br>", $gliked);
    $key = array_search($publisher, $gliked);
    unset($gliked[$key]);
    
    $gliked = implode("<br>", $gliked);
    
    $gliked = addslashes($gliked);
    $sql = "UPDATE users SET glikedCompany = '$gliked' WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
}