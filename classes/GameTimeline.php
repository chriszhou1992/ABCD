<?php

include_once 'Timeline.php';
include_once 'TimelineNode.php';
include_once 'TimelineNodeImage.php';
/**
 * Description of GameTimeline
 *
 * @author Chris
 */
class GameTimeline extends Timeline{
    public $timelineID;
    function __construct($name) {
        parent::__construct();
        
        $name = addslashes($name);
        
        $sql = "SELECT * FROM timeline WHERE title = '$name'";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        if (mysqli_num_rows($this->con, $result) == 0) {
            createTimeline($name);
        } else {
            
        }
        
        
    }
    
    private function createTimeline($companyName) {
        $sql = "INSERT INTO timeline (title) VALUES ('$companyName')";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        $sql = "SELECT * FROM games WHERE publishers = '$companyName' ORDER BY date_released";
        $result = mysqli_query($this->con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        $this->timelineID = mysqli_insert_id($this->con);
        while ($row = mysqli_fetch_array($result)) {
            $date = new DateTime($row['date_released']);
            $id = $date->format('m/d/Y');
            
            $name = $date->format('M-d-Y');
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
        foreach ($this->nodes as $n) {
            $n->draw();
        }
    }
}
