<?php

function connectToDB() {
    
    $host = "localhost";
    $user = "admin";
    $password = "admin";
    $table = "timeline";

	
    
    // Create connection
    $con = new mysqli($host, $user, $password, $table);
    // Check connection
    if (mysqli_connect_errno($con)) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        return false;
    }
    
    return $con;
}

