<?php

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

if(isset($_POST['option1'])):
    $sql = "SELECT g.*, c.num_likes
            FROM games g
            INNER JOIN companies c
            ON g.publishers=c.name
            having c.num_likes = (
                select MAX(num_likes) from companies)";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo "<div class='alert alert-black alert-dismissable'>
            <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
            <i class='fa fa-info-circle customInfo'></i>&nbsp&nbsp&nbsp";
    if($result->num_rows === 1) {
        echo "There is &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp game found in the database whose publisher accumulates the most Likes.";
    } else {
        echo "There are &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp games found in the database whose publishers accumulate the most Likes.";
    }
    echo "</div><div class='dismissableHR'><br></div>";
    
    
    while($row = mysqli_fetch_array($result)) {
        $imageSrc = $row['imageURL'];
        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        if(isset($row['headquarter'])) {
            echo "<span class='companies' name='$send'>$name</span><hr>";
        } else {
            echo "<span class='games' name='$send'>$name</span><hr>";
        }
        $brief = $row['brief'];
        echo "$brief<hr>";
    }
endif;

if(isset($_POST['option2'])):
    $sql = "SELECT *
            From games
            where num_likes = (
                select MAX(num_likes) from games)";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo "<div class='alert alert-black alert-dismissable'>
            <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
            <i class='fa fa-info-circle customInfo'></i>&nbsp&nbsp&nbsp";
    if($result->num_rows === 1) {
        echo "There is &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp game found in the database with the most Likes.";
    } else {
        echo "There are &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp games found in the database withe the most Likes.";
    }
    echo "</div><div class='dismissableHR'><br></div>";
    
    
    while($row = mysqli_fetch_array($result)) {
        $imageSrc = $row['imageURL'];
        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        if(isset($row['headquarter'])) {
            echo "<span class='companies' name='$send'>$name</span><hr>";
        } else {
            echo "<span class='games' name='$send'>$name</span><hr>";
        }
        $brief = $row['brief'];
        echo "$brief<hr>";
    }
endif;

if(isset($_POST['option3'])):
     $sql = "SELECT DISTINCT c.*, g.num_likes
            FROM companies c
            INNER JOIN games g
            ON c.name=g.publishers
            having g.num_likes = (
                select MAX(num_likes) from games)";
    ini_set('max_execution_time', 300000);

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo "<div class='alert alert-black alert-dismissable'>
            <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
            <i class='fa fa-info-circle customInfo'></i>&nbsp&nbsp&nbsp";
    if($result->num_rows === 1) {
        echo "There is &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp publisher found in the database which publishes games with the most Likes.";
    } else {
        echo "There are &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp publishers found in the database which publish games with the most Likes.";
    }
    echo "</div><div class='dismissableHR'><br></div>";
    
    
    while($row = mysqli_fetch_array($result)) {
        $imageSrc = $row['imageURL'];
        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        if(isset($row['headquarter'])) {
            echo "<span class='companies' name='$send'>$name</span><hr>";
        } else {
            echo "<span class='games' name='$send'>$name</span><hr>";
        }
        $brief = $row['brief'];
        echo "$brief<hr>";
    }
endif;

if(isset($_POST['option4'])):
     $sql = "SELECT *
            From companies
            where num_likes = (
                select MAX(num_likes) from companies)";

    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }

    echo "<div class='alert alert-black alert-dismissable'>
            <button type='button' class='close customClose' data-dismiss='alert' aria-hidden='true'><i class='fa fa-times'></i></button>
            <i class='fa fa-info-circle customInfo'></i>&nbsp&nbsp&nbsp";
    if($result->num_rows === 1) {
        echo "There is &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp publisher found in the database with the most Likes.";
    } else {
        echo "There are &nbsp<strong class='customStrong'>".$result->num_rows."</strong>&nbsp publishers found in the database with the most Likes.";
    }
    echo "</div><div class='dismissableHR'><br></div>";
    
    
    while($row = mysqli_fetch_array($result)) {
        $imageSrc = $row['imageURL'];
        echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
        $name = $row['name'];
        $send = str_replace("'", "!-!-!", $name);
        if(isset($row['headquarter'])) {
            echo "<span class='companies' name='$send'>$name</span><hr>";
        } else {
            echo "<span class='games' name='$send'>$name</span><hr>";
        }
        $brief = $row['brief'];
        echo "$brief<hr>";
    }
endif;
