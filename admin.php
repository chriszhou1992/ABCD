<?php

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

if(isset($_POST['game'])):
   removeFromTable($con, "games", "name", $_POST['game']);
endif;

if(isset($_POST['company'])):
    removeFromTable($con, "companies", "name", $_POST['company']);
endif;

function removeFromTable($con, $tablename, $target, $name) {
    $name = addslashes($name);
    
    $sql = "DELETE FROM ".$tablename." WHERE ".$target." = '$name'";
    
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
}