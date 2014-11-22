<?php

include_once "connecToDB.php";
include_once "TimelineNodeContent.php";

/**
 * Description of TimelineNode
 *
 * @author Chris
 */
class TimelineNode {
    public $dataID;    //data-id (elementNumber/category/yearOrSomeOtherNumber)
    public $name;   //name that appears under the node
    public $description;    //text that appear above the node on hover
    public $content;    //detailed content
    public $contentHeader;
    public $contentText;
    public $detailsHTML;    //HTML content that contains the details to a node
    public $timelineID;
    
    function __construct($id, $name, $description, TimelineNodeContent $content,
            $contentHeader, $contentText, $detailsHTML, $timelineID) {
        $this->dataID = $id;
        $this->name = $name;
        $this->description = $description;
        $this->content = $content;
        $this->contentHeader = $contentHeader;
        $this->contentText = $contentText;
        $this->detailsHTML = $detailsHTML;
        $this->timelineID = $timelineID;
    }
    
    function draw() {
        //var_dump($this->dataID);
        echo "<div class=\"item\" data-name=\"{$this->name}\" data-id=\"{$this->dataID}\" data-description=\"{$this->description}\">";
            $this->content->draw();
            echo "<h2>{$this->contentHeader}</h2>";
            echo "<span>{$this->contentText}</span>";
            echo "<div class=\"read_more\" data-id=\"{$this->dataID}\">Read more</div>";
        echo "</div>";
        
            //echo "<div class=\"item_open\" data-id=\"{$this->dataID}\" data-access=\"{$this->detailsHTML}\">";
            echo "<div class=\"item_open\" data-id=\"{$this->dataID}\" data-access=\"fetchNodeDetails.php\">";
            //Ajax vs No Ajax
        //echo "<div class=\"item_open\" data-id=\"{$this->dataID}\">";
            echo "<div class=\"timeline_open_content\">";
                echo "<h2 class=\"no-marg-top\">{$this->name}</h2><span>";
                echo $this->detailsHTML;
            echo "</span></div>";
            
            echo "<div class=\"item_open_content\">";
                echo "<img class=\"ajaxloader\" src=\"img/loadingAnimation.gif\" alt=\"\" />";
            echo "</div>"; 
        echo "</div>";
    }
    
    function storeNode($con) {
        if($con === false):
            $con = connectToDB();
        endif;
        
        $name = addslashes($this->name);
        $desc = addslashes($this->description);
        $uri = addslashes($this->content->uri);
        $contentHeader = addslashes($this->contentHeader);
        $brief = addslashes($this->contentText);
        $details = addslashes($this->detailsHTML); 
        
        $sql = "INSERT INTO node(dataID, name, description, contentURI, "
                . "contentType, contentHeader, contentText, detailsHTML, timelineID)"
                . " VALUES ('{$this->dataID}', '$name', '$desc', '$uri',"
                . " 1, '$contentHeader', '$brief', '$details', {$this->timelineID})";
        //var_dump($sql);
        //$sql = mysqli_real_escape_string($con, $sql);
        //var_dump($sql);
        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }
    }
}
