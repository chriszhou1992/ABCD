<?php

session_start();

$username = $_SESSION["user"];

require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

echo showglikedStat($con, $username);

function showglikedStat($con, $username) {
    $sql = "SELECT glikedCompany FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if(strlen($row['glikedCompany']) === 0):
        return "false";
    endif;
    
    $gliked = $row['glikedCompany'];
    $gliked = explode("<br>", $gliked);
    $gliked = array_slice($gliked, 1);
    
    $total = count($gliked);
    foreach($gliked as $g):
        if(isset($stat[$g])) {
            $stat[$g] += 1.0;
        } else {
            $stat[$g] = 1.0;
        }
    endforeach;
    
    /*
    $num = count($stat);
    foreach($stat as $game => $n):
        $stat[$game] = $n/ $total;
    endforeach;
    */
   arsort($stat);
   
   return json_encode($stat);
}

