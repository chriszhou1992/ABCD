<?php

session_start();

$username = $_SESSION["user"];

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$sql = "SELECT gamesMightLike FROM users WHERE email = '$username'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);
$games = $row['gamesMightLike'];

showSuggestedBasedOnLike($con, $games);


function showSuggestedBasedOnLike($con, $games) {
    if(strlen($games) === 0):
        echo "<div class='alert alert-white'><i class='fa fa-lightbulb-o'></i>&nbsp&nbspOoops, there is currently no suggestion on games that you might like based on the games you liked.</div>";
        return;
    endif;
    $removeID = 0;
    $games = explode("<br>", $games);
    $games = array_slice($games, 1);
    foreach($games as $g) {
        $g = addslashes($g);
        $sql = "SELECT * FROM games WHERE name = '$g'";
        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }
        $row = mysqli_fetch_array($result);
        if($row) {
            $ranked[$g] = $row['num_likes'];
        } else {
            $notInDataBase[] = $g;
        }
    }
    
    
    if(isset($ranked)) {
        arsort($ranked);
    
        foreach($ranked as $g => $k):

            $sql = "SELECT * FROM games WHERE name = '$g'";
            $result = mysqli_query($con, $sql);
            if(!$result) {
                die('Error: ' . mysqli_error($con));
            }
            $row = mysqli_fetch_array($result);

            $imageSrc = $row['imageURL'];
            echo "<div class='removeDiv'><img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
            $name = $row['name'];
            $send = str_replace("'", "!-!-!", $name);
            echo "<span class='games' name='$send'>$name".'<span class="displayLikes float-right" style="margin-top:19px;">Total Likes: '.$row['num_likes'].'</span>'."</span><hr>";


            $brief = $row['brief'];
            echo "$brief<hr>";
            $g = stripslashes($g);
            $gameName = str_replace(" ", "!-!-!", $g);
            echo '<button type="button" class="btn btn-default dislikeButton" id='.$removeID.' name='.$gameName.' style="margin-right:5px;">'
                    . '<i class="fa fa-thumbs-down"></i>&nbsp&nbsp<span class="justLike">Do not suggest</span><span class="like"> '.$g." </span>"." <span class='justLike'>again</span>"
                . "</button>";
            echo '<button type="button" class="btn btn-default removeButton" id='.$removeID.' name='.$gameName.'>'
                    . '<i class="fa fa-trash-o"></i>&nbsp&nbsp<span class="justLike">Remove</span> </span>'." <span class='justLike'>from the list for now</span>"
                . "</button><hr></div>";
            $removeID += 1;
        endforeach;
    }

    if(isset($notInDataBase)):
        foreach($notInDataBase as $g):
            $g = stripslashes($g);
            $gameName = str_replace(" ", "!-!-!", $g);
            echo "<div class='removeDiv'><span class='gamesNotIn' name=''>$g</span><hr>";
            echo '<button type="button" class="btn btn-default dislikeButton" id='.$removeID.' name='.$gameName.' style="margin-right:5px;">'
                    . '<i class="fa fa-thumbs-down"></i>&nbsp&nbsp<span class="justLike">Do not suggest</span><span class="like"> '.$g." </span>"." <span class='justLike'>again</span>"
                . "</button>";
            echo '<button type="button" class="btn btn-default removeButton" id='.$removeID.' name='.$gameName.'>'
                    . '<i class="fa fa-trash-o"></i>&nbsp&nbsp<span class="justLike">Remove</span> </span>'." <span class='justLike'>from the list for now</span>"
                . "</button><hr></div>";
            $removeID += 1;
        endforeach;
    endif;
}