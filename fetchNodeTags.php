<?php
include_once "classes/Tags.php";
require_once "classes/connecToDB.php";

$con = connectToDB();
if($con === false):
    return;
endif;

echo "<div class=\"timeline_open_content\">";
    echo "<h2 class=\"no-marg-top\">Tags</h2><span>";
    
    $array = Tags::getGameTagsByName($con, $_GET['name']);
    $results = $array[0];
    $id = $array[1];
    Tags::printTags($results, $id);
    
    echo "<button id=\"gameButton\" class=\"btn btn-primary\" type=\"button\">";
    echo $_GET["name"];
    echo "</button>";
echo "</span></div>";
