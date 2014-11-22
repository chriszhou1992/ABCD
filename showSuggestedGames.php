<?php

session_start();

$username = $_SESSION["user"];

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$sql = "SELECT gamesMightLike, suggestedByFriends FROM users WHERE email = '$username'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);
$games = $row['gamesMightLike'];
$suggest = $row['suggestedByFriends'];


if(strlen($games) === 0 && strlen($suggest) === 0):
    echo "<div class='alert alert-white'><i class='fa fa-lightbulb-o'></i>&nbsp&nbspOoops, there is currently no suggestion on games that you might like. Tip: The tab will blink once there are new suggestions for you!</div>";
    return;
endif;

if(isset($suggest)):
    echo '  <div id="suggestByF">';
   if(strlen($suggest) === 0) {
        echo'<a data-toggle="collapse" data-parent="#accordion" href="#friendsList" class="suggestFF">';
    } else {
        echo'<a data-toggle="collapse" data-parent="#accordion" href="#friendsList" class="suggestFF">';
    }
    echo'                Click me to see games suggested by your friends
                </a>
                <hr>
            </div>';
    echo '  <div id="friendsList" class="panel-collapse collapse out">';
    
    showSuggestedByFriend($con, $suggest);
    
    echo '</div>';
endif;

if(isset($games)):
    echo '  <div id="suggestByUs">';
    if(strlen($games) === 0) {
        echo' <a data-toggle="collapse" data-parent="#accordion" href="#ourList" class="suggestFU">';
    } else {
        echo'<a data-toggle="collapse" data-parent="#accordion" href="#ourList" class="blink suggestFU">';
    }
    echo'                Click me to see games suggested based on the games you liked
                </a>
                <hr>
            </div>';
    echo '  <div id="ourList" class="panel-collapse collapse out">';
   
    //showSuggestedBasedOnLike($con, $games);
    echo '</div>';
endif;




function showSuggestedByFriend($con, $suggest) {
    
    if(strlen($suggest) === 0):
        echo "<div class='alert alert-white'><i class='fa fa-lightbulb-o'></i>&nbsp&nbspOoops, there is currently no suggestion on games that you might like from your friends.</div>";
        return;
    endif;
    
    $byFriends = explode("<br>", $suggest);
    $byFriends  = array_slice($byFriends , 1);
    $curFriend = "";
    foreach($byFriends as $g) {
        $delimiter = strpos($g, ">");
        #extract friend
        $friend = substr($g, 1, $delimiter-1);
        $g = substr($g, $delimiter+1);
        if ($curFriend !== $friend) {
            $curFriend = $friend;
            echo "<div class='nameOfFriend'>Games suggested by ". $friend ."</div>";
            echo "<hr>";
        }

        $sql = "SELECT * FROM games WHERE name = '$g'";
        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }
        $row = mysqli_fetch_array($result);

        $imageSrc = $row['imageURL'];
        echo "<div><img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        echo "<span class='games' name='$send'>$name</span>";
        $g = stripslashes($g);
        $gameName = str_replace(" ", "!-!-!", $g);
        echo "<hr>";

        $brief = $row['brief'];
        echo "$brief<hr>";
        
        echo '</div>';
    }
}


