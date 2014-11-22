<?php

session_start();

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

if(isset($_POST['lastest'])):
   loadmore($con, $_POST['lastest'], "games", $_POST['name']);
endif;

if(isset($_POST['lastestC'])):
   loadmoreC($con, $_POST['lastestC'], "companies", $_POST['name']);
endif;

function loadmore($con, $latest, $table, $iter) {
    $sql = "SELECT * FROM ".$table." WHERE id > '$latest'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $loadCount = 0;
    $removeID = $iter * 30;
    while($row = mysqli_fetch_array($result)) {
        if($loadCount === 30):
            echo "<div id=G".$latestID.">";
            $iter++;
            echo "<center><i class='fa fa-chevron-down fa-3x gamesLM' id=".$latestID." name=".$iter."></i></center>";
            echo "</div>";
            break;
        endif;

        $imageSrc = $row['imageURL'];
        if(isset($_SESSION['admin'])):
            $send = str_replace(" ", "!-!-!", $row['name']);
            echo "<div class='deleteGames' id='$removeID'><i class='fa fa-times removeGames' id = ".$removeID." name =  ".$send."></i>";
            $removeID++;
        endif;

        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        echo "<span class='games' name='$send'>$name</span><hr>";
        $brief = $row['brief'];
        echo "$brief<hr>";

        if(isset($_SESSION['admin'])):
            echo "</div>";
        endif;

        $loadCount++;
        $latestID = $row['id'];
    }
}

function loadmoreC($con, $latest, $table, $iter) {
    $sql = "SELECT * FROM ".$table." WHERE id > '$latest'";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $loadCount = 0;
    $removeID = $iter * 30;
    while($row = mysqli_fetch_array($result)) {
         if($loadCount === 30):
            echo "<div id=C".$latestID.">";
            $iter++;
            echo "<center><i class='fa fa-chevron-down fa-3x companiesLM' id=".$latestID." name=".$iter."></i></center>";
            echo "</div>";
            break;
        endif;

        $imageSrc = $row['imageURL'];
        if(isset($_SESSION['admin'])):
            $send = str_replace(" ", "!-!-!", $row['name']);
            echo "<div class='deleteCompanies' id='$removeID'><i class='fa fa-times removeCompanies' id = ".$removeID." name =  ".$send."></i>";
            $removeID++;
        endif;

        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        echo "<span class='companies' name='$send'>$name</span><hr>";
        $brief = $row['brief'];
        echo "$brief<hr>";

        if(isset($_SESSION['admin'])):
            echo "</div>";
        endif;

        $loadCount++;
        $latestID = $row['id'];
    }
}

