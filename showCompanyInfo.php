<?php
session_start();
$name = addslashes($_POST['company']);


require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$sql = "SELECT * FROM companies WHERE name = '$name'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);

echo '<div class="panel-group" id="accordion">';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#publisher">';
               echo "<div class='gameName' style='color: red;'>".$_POST['company']."</div>".'<span class="displayLikes float-right">Total Likes: '.$row['num_likes'].'</span>';
 echo      '</a>
        </div>';
echo '<div id="publisher" class="panel-collapse collapse in">';
echo '<div class="panel-body">';
//echo "<div><i class='fa fa-thumbs-up float-right fa-2x'></i></div>";
//echo '<span class="displayLikes">Total Likes: '.$row['num_likes'].'</span>'
        //. '<hr>';
$companyName = str_replace(" ", "!-!-!", $_POST['company']);
if(isset($_SESSION['user']) && checkIfCompanyLiked($con, $_SESSION['user'], $_POST['company'])) {
    echo '<button type="button" class="btn btn-default float-right unlike" id="likeButton" name='.$companyName.'><i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Unlike</span><span class="like"> '.$_POST['company']."</span></button>";
} else {
    
    echo '<button type="button" class="btn btn-default float-right" id="likeButton" name='.$companyName.'><i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Like</span><span class="like"> '.$_POST['company']."</span></button>";
}

/********tag system********/
require_once "classes/Tags.php";
//fetch tags for the company
$results = Tags::getCompanyTags($con, $row["id"]);

//display tags
Tags::printTags($results, $row["id"]);

echo '</div>';
echo '</div>';
echo '</div>';

echo "</div></div>";

include_once 'classes/CompanyTimeline.php';
echo '<button type="button" id="showTimeline" class="btn btn-default">Show Timeline</button>';
//draw timeline
echo '<div id="timeline" class="timelineFlat tl1">';
$t = new CompanyTimeline($name);
$t->draw();
echo '</div>';




$logo = $row['imageURL'];
echo "<div align='center'><img src='$logo' alt='Loading...' class='companyLogo'></div>";


/*********start of info panel*********/
echo '<div class="panel-group" id="accordion">';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#headquarter">
               Headquarter
           </a>
        </div>';
echo '<div id="headquarter" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
if($row['headquarter'] == null) {
    $headquarter = "To be added...";
} else {
    $headquarter = $row['headquarter'];
}
echo $headquarter."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#date">
               Date founded
           </a>
        </div>';
echo '<div id="date" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
if($row['date_founded'] == NULL) {
    $new_date = "To be added...";
} else {
	date_default_timezone_set("America/Chicago");
    $date = new DateTime($row['date_founded']);
    $new_date= $date->format('M-d-Y');
}
echo $new_date."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#description">
               Description
           </a>
        </div>';
echo '<div id="description" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
if($row['description'] == null) {
    $description = "To be added...";
} else {
    $description = $row['description'];
}
echo $description."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#website">
               Website
           </a>
        </div>';
echo '<div id="website" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
$website = $row['website'];
echo "<a href='$website'>$website</a>"."<br>";
echo '</div>';
echo '</div>';
echo '</div>';


echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#games">
               Games developed
           </a>
        </div>';
echo '<div id="games" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
echo substr($row['games'], 4)."<br>";
echo '</div>';
echo '</div>';
echo '</div>';


echo '</div></div>';


function checkIfCompanyLiked($con, $username, $company) {
    
    $sql = "SELECT companiesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if($row['companiesLiked'] === null) {
        return false;
    } else {
        $companies = $row['companiesLiked'];
    }
    
    $companies = stripslashes($companies);
    $companies = explode("<br>", $companies);
    return in_array($company, $companies);
}
