<?php

class Query {
    
    private $con;
    
    /*
     * Once record is found, create corresponding instance and then call the instance method to print info.
     * Thus, include the classes here
     */
    function __construct() {
        require_once "connecToDB.php";
        $this->con = connectToDB();
        if($this->con === false):
            return;
        endif;
    }
    
    /*
     * Use case-insensitive mathod to search if names of records in database contain the search words
     */
    function queryDatabase($name) {
        
        $companyFound = $this->queryCompany($name);
        $gameFound = $this->queryGame($name);
        //return $companyFound;
        return array_merge($gameFound, $companyFound);
    }
    
    function queryCompany($name) {
        
        // Check connection
        if (mysqli_connect_errno($this->con)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            return;
        }
        
        $sql = "SELECT * FROM companies";
        
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        $found = [];
        while ($row = mysqli_fetch_array($result)) {
            if(gettype(stripos($row['name'], $name)) !== "boolean"):
                $found[] = $row;
            endif;
        }
        return $found;
    }
    
    function queryGame($name) {
        
        // Check connection
        if (mysqli_connect_errno($this->con)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            return;
        }
        
        $sql = "SELECT * FROM games";
        
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        $found = [];
        while ($row = mysqli_fetch_array($result)) {
            if(gettype(stripos($row['name'], $name)) !== "boolean"):
                $found[] = $row;
            endif;
        }
        return $found;
    }  
}

//$s = new Query();
//var_dump($s->queryCompany("The"));

