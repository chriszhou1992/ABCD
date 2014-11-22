<?php

include_once 'Timeline.php';
include_once 'TimelineNodeImage.php';

/**
 * Description of CompanyTimeline
 *
 * @author Chris
 */
class CompanyTimeline extends Timeline{
    public $timelineID;
    function __construct($name) {
        parent::__construct();
        
        $name = addslashes($name);
        
        $sql = "SELECT * FROM timeline WHERE title = '$name'";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        if (mysqli_num_rows($result) == 0) {
            $this->createTimeline($name);
        } else {
            $row = mysqli_fetch_array($result);
            $this->fetchTimeline($row["id"]);
        }
    }
    
    private function fetchTimeline($timelineID) {
        $sql = "SELECT * FROM node WHERE timelineID = '$timelineID'";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        while ($row = mysqli_fetch_array($result)) {
            $name = stripslashes($row["name"]);
            $description = stripslashes($row["description"]);
            $header = stripslashes($row["contentHeader"]);
            $brief = stripslashes($row["contentText"]);
            $details = stripslashes($row["detailsHTML"]);
            
            $content = new TimelineNodeImage(stripslashes($row["contentURI"]), $brief);
            
            $n = new TimelineNode($row["dataID"], $name, $description, $content, $header, 
                    $brief, $details, $this->timelineID);
            
            $this->nodes[] = $n; 
        }
    }
    
    private function createTimeline($companyName) {
        $sql = "INSERT INTO timeline (title) VALUES ('$companyName')";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        $this->timelineID = mysqli_insert_id($this->con);
        
        $sql = "SELECT * FROM games WHERE publishers = '$companyName' ORDER BY date_released";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        while ($row = mysqli_fetch_array($result)) {
            $date = new DateTime($row['date_released']);
            $id = $date->format('d/m/Y');
            
            //
            
            $name = $date->format('M-d');
            $description = $row["name"];
            $header = $name;
            $brief = $row["brief"];
            $details = $row["description"];
            
            $content = new TimelineNodeImage($row["imageURL"], $brief);
            
            $n = new TimelineNode($id, $name, $description, $content, $header, 
                    $brief, $details, $this->timelineID);
            
            $n->storeNode($this->con);
            $this->nodes[] = $n; 
        }
    }
    
    function draw() {
        foreach ( $this->nodes as $n) {
            //var_dump($n);
            $n->draw();
        }
        
        /*echo "<script type=\"text/javascript\">";
        echo "alert('A');";
        echo "$(\"#companyField\").trigger(\"click\");";
        echo "alert('ABCDE');";
        echo "</script>";*/
    }
}
