<?php
session_start();

$name = addslashes($_POST['game']);


require_once "classes/connecToDB.php";
$con = connectToDB();
if($con === false):
    return;
endif;

$sql = "SELECT * FROM games WHERE name = '$name'";

$result = mysqli_query($con, $sql);
if(!$result) {
    die('Error: ' . mysqli_error($con));
}

$row = mysqli_fetch_array($result);

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#title">';
               echo "<div class='gameName' style='color: red;'>".$_POST['game']."</div>".'<span class="displayLikes float-right">Total Likes: '.$row['num_likes'].'</span>';
 echo      '</a>
        </div>';
echo '<div id="title" class="panel-collapse collapse in">';
echo '<div class="panel-body">';
//echo "<div><i class='fa fa-thumbs-up float-right fa-2x'></i></div>";
//echo '<span class="displayLikes">Total Likes: '.$row['num_likes'].'</span>'
        //. '<hr>';
$gameName = str_replace(" ", "!-!-!", $_POST['game']);
if(isset($_SESSION['user']) && checkIfGameLiked($con, $_SESSION['user'], $_POST['game'])) {
    echo '<button type="button" class="btn btn-default float-right unlike" id="likeButton" name='.$gameName.'><i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Unlike</span><span class="like"> '.$_POST['game']."</span></button>";
} else {
    echo '<button type="button" class="btn btn-default float-right" id="likeButton" name='.$gameName.'><i class="fa fa-thumbs-up"></i>&nbsp&nbsp<span class="justLike">Like</span><span class="like"> '.$_POST['game']."</span></button>";
}


/********tag system********/
require_once "classes/Tags.php";
//fetch tags for the company
$results = Tags::getGameTags($con, $row["id"]);

//display tags
Tags::printTags($results, $row["id"]);


echo '</div>';
echo '</div>';

echo "</div>";


$images = explode("<br>", $row['gameImageURLs']);

echo '<div id="gameCarousel" class="carousel slide" data-ride="carousel">
         <div class="carousel-inner">';
$imagSrc = $images[1];
echo "  <div class='item active'>
            <img src='$imagSrc' alt='Loading...' class='gameCarousel largeImages'>
            <div class='container'>
                <div class='carousel-caption'>

                </div>
            </div>
        </div>";
for($i = 2; $i < count($images); $i++):
    echo "  <div class='item'>
                <img src='$images[$i]' alt='Loading...' class='gameCarousel largeImages'>
                <div class='container'>
                    <div class='carousel-caption'>

                    </div>
                </div>
            </div>";
     
endfor;
echo "</div>";
echo  ' <a class="left carousel-control" href="#gameCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left gameC"></span>
        </a>
        <a class="right carousel-control" href="#gameCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right gameC"></span>
        </a>';

echo '</div>';


echo '<div class="panel-group" id="accordion">';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#publisher">
               Publisher
           </a>
        </div>';
echo '<div id="publisher" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
echo $row['publishers']."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#date">
               Release date
           </a>
        </div>';
echo '<div id="date" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
if($row['date_released'] == NULL) {
    $new_date = "To be added...";
} else {
    $date = new DateTime($row['date_released']);
    $new_date= $date->format('M-d-Y');
}
echo $new_date."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#genres">
               Genres
           </a>
        </div>';
echo '<div id="genres" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
$genres = str_replace(" ", "<br>", trim($row['genres'], " "));
echo $genres."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#themes">
               Themes
           </a>
        </div>';
echo '<div id="themes" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
$themes = str_replace(" ", "<br>", trim($row['themes'], " "));
echo $themes."<br>";
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
echo $row['description']."<br>";
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="panel panel-default">
        <div class="panel-heading">
           <a data-toggle="collapse" data-parent="#accordion" href="#platforms">
               Platforms
           </a>
        </div>';
echo '<div id="platforms" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
$platforms = substr($row['platforms'], 4);
echo $platforms."<br>";
echo '</div>';
echo '</div>';
echo '</div>';



echo '<div class="panel panel-default">';
echo ' <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#accordion" href="#gameImages">
            Images
            </a>
       </div>';
echo '<div id="gameImages" class="panel-collapse collapse out">';
echo '<div class="panel-body">';
for($i = 1; $i < count($images); $i++):
    echo "<img src='$images[$i]' alt='Loading...' class='smallImages'>";
endfor;
echo '</div>';
echo '</div>';
echo '</div>';



echo '</div></div>';


function checkIfGameLiked($con, $username, $game) {
    
    $sql = "SELECT gamesLiked FROM users WHERE email = '$username'";
    $result = mysqli_query($con, $sql);
    if(!$result) {
        die('Error: ' . mysqli_error($con));
    }
    
    $row = mysqli_fetch_array($result);
    if($row['gamesLiked'] === null) {
        return false;
    } else {
        $games = $row['gamesLiked'];
    }
    
    $games = stripslashes($games);
    $games = explode("<br>", $games);
    return in_array($game, $games);
}
