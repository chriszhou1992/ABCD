<?php

/*
 * Comment
 */
class Comment {
    
    # rememeber the connection
    private $con;
    # boolean, false if the connection is not created successfully
    private $connected;
    
    /*
     * Set up and connect to the comment database
     */
    function __construct() {
        require_once "connecToDB.php";
        $this->con = connectToDB();
        if($this->con === false):
            return;
        endif;
        
        $this->connected = true;       
    }
    
    /*
     * Update the comment database
     * Should be called right after connecting to the database
     * @var: name: the name of the commentator
     * @var: comment: the body of the comment
     */
    function updateComments($name, $comment) {

        if (!isset($name) || !$this->connected):
            return;
        endif;
            
        if($name == '') {
            $name = "Anonymous";
        }
        else {
            $name = $this->commentsFilter(mysql_real_escape_string($name));
        }
        $comment = $this->commentsFilter(mysql_real_escape_string($comment));
        $date = date("Y-m-d H:i:s T");

        # get the parent comment of this new comment
        $sql = "SELECT pid FROM generalComment WHERE cid = 1";
        $parentID = mysqli_query($this->con, $sql);
        if (!$parentID ) {
            die('Error: ' . mysqli_error($this->con));
        }

        $row = mysqli_fetch_array($parentID);

        # pid is case sensitive
        $pid = (int)$row['pid'];
        
        # put new comment into the table with table number tid and bind it to its parent by setting its pid field
        $sql = "INSERT INTO generalComment (PID, name, comment, date)
        VALUES
        ('$pid', '$name', '$comment', '$date')";
        
         if(!mysqli_query($this->con, $sql)) {
            die('Error: ' . mysqli_error($this->con));
        }
    }
    
    /*
     * Filter a sentence by breaking it apart and checking against bad words in the database
     * @var toBefiltered: the target
     */
    function commentsFilter($toBeFiltered) {
        $splited = explode(' ', $toBeFiltered);
        
        $sql = "SELECT replacement FROM filter WHERE badWord='".$splited[0]."'";
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
        $row = mysqli_fetch_array($result);
        if($row) {
            $ret = $row['replacement'];
        }
        else {
            $ret = $splited[0];
        }
        
        for($i = 1; $i < count($splited); ++$i) {
            $sql = "SELECT replacement FROM filter WHERE badWord='".$splited[$i]."'";
            $result = mysqli_query($this->con, $sql);
            if (!$result) {
                die('Error: ' . mysqli_error($this->con));
            }
            $row = mysqli_fetch_array($result);
            if($row) {
                $ret = $ret.' '.$row['replacement'];
            }
            else {
                $ret = $ret.' '.$splited[$i];
            }
        }
       
        return $ret;
    }
    
    /*
     * Show all comments
     * @var pid: the parent id of the current comments to be shown
     * @var spaces: to indent each children comment
     */
    function showComments($pid, $spaces) {
        
      
        $sql = "SELECT * FROM generalComment WHERE cid <> 1 AND pid =".$pid;
        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error($this->con));
        }
       
        $s = $spaces."&nbsp&nbsp&nbsp&nbsp&nbsp";
        while ($row = mysqli_fetch_array($result)) {
            echo '<p2>';
            $title = "Reply";
            $cid = (int)$row['CID'];
            $url = "/CS411/comment.php?CID=".$cid;
           
            echo $spaces.'</p2><p3>Name: '.$row['name']."<br></p3><p2>";
            echo $spaces.'Comment: '.$row['comment']."<br>";  
            echo $spaces."Date: ".$row['date']."<br>";
            if (isset($_GET['CID']) && $_GET['CID'] == $cid) {
                $this->askForComment($cid, $spaces);
            }
            else {
                echo $spaces."<a href=$url>$title</a><br>";
            }

            $this->showComments($cid, $s);
        }
        echo '</p2>';
       
    }
    
    /*
     * Display the submit fields for user to input name and comment
     * @var pid: the parent id of the new current
     * @var spaces: indent the submit fields to be aligned with the parent comment
     */
    function askForComment($pid, $spaces) {
        
        # store the parent id to be stored in the child new comment
        $sql = "UPDATE generalComment SET PID=".$pid." WHERE CID=1";
        if (!mysqli_query($this->con, $sql)) {
            die('Error: ' . mysqli_error($this->con));
        }

        echo '<form action="comment.php" method="get">';
        echo $spaces.'Name: <br>'.$spaces.'<input type="text" class=submit-field name="name"><br>';
        echo $spaces.'Comment: <br>'.$spaces.'<input type="text" class=submit-field name="comment"><br>';
        echo $spaces.'<input type="Submit" class=submit-button Value="Submit">';
        echo '</form>';
    }
}

$comment = new Comment();
if(isset($_GET["CID"]) && $_GET["CID"] == 1) {
    $comment->askForComment(1, "");
}
else {
    $url = "comment.php?CID=1";
    echo "<a href=$url>New Comment</a><br>";
}
if(isset($_GET["name"])):
    $comment->updateComments($_GET["name"], $_GET["comment"]);
endif;
$comment->showComments(1, "");