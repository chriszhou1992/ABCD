<?php
session_start(); 

$username = $_SESSION["user"];

$friend = $_POST["friend"];


require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

if(check($con, $username, $friend)):
    
    $sql = "SELECT gamesLiked FROM users WHERE email = '$username'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);
    $suggest = $row['gamesLiked'];
    $tag = "<br>"."<".$username.">";
    $newSuggest = str_replace("<br>", $tag, $row['gamesLiked']);
    $suggest = explode("<br>", $suggest);
    $suggest = array_slice($suggest, 1);
    
    $sql = "SELECT suggestedByFriends FROM users WHERE name = '$friend' or email = '$friend'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);
    
    if(strlen($row["suggestedByFriends"]) !== 0 && strpos($row["suggestedByFriends"], $tag) !== false) {
        $cur = $row["suggestedByFriends"];
        $curArr = explode($tag, $cur);
        $ret = $curArr[0];
        $curArr = array_slice($curArr, 1);
        $mixed = $curArr[count($curArr)-1];
        if(($pos = strpos($mixed, "<br>")) !== false) {
            $curArr[count($curArr)-1] = substr($mixed, 0, $pos);
            $toBeConcat = substr($mixed, $pos);
        } else {
            $toBeConcat = "";
        }
        
        $lenArr = count($curArr);
        for($i=0; $i<$lenArr; $i++):
            if (($key = array_search($curArr[$i], $suggest)) !== false) {
                unset($suggest[$key]);
            }
        endfor;
        $tail = implode($tag, $suggest);
        if(strlen($tail) !== 0) {
            $newSuggest = $ret . $tag . implode($tag, $curArr) . $tag . $tail . $toBeConcat;
        } else {
            $newSuggest = $ret . $tag . implode($tag, $curArr) . $toBeConcat;;
        }
    } else {
        $newSuggest = $row["suggestedByFriends"] . $newSuggest;
    }
    
    $newSuggest = addslashes($newSuggest);
    $sql = "UPDATE users SET suggestedByFriends = '$newSuggest' WHERE name = '$friend' or email = '$friend'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
endif;

function check($con, $username, $friend) {
    $sql = "SELECT name FROM users WHERE name = '$friend' or email = '$friend'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    $row = mysqli_fetch_array($result);
    if(!$row) {
        echo "user not found";
        return false;
    } else if($row['name'] === $username) {
        echo "yourself";
        return false;
    }
    
    return true;
}

