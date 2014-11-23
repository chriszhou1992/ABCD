<?php 
    session_start();
    $_SESSION['admin'] = NULL;
    if(isset($_SESSION['user'])) {
        $username = $_SESSION['user'];
        require_once "classes/connecToDB.php";
        $con = connectToDB();
        if($con === false):
            return;
        endif;
        $sql = "SELECT administrator FROM users WHERE email = '$username'";

        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }

        $row = mysqli_fetch_array($result);
        
        $_SESSION["admin"] = $row['administrator'];
    }
    
?>

<!DOCTYPE html>
<html>
    <!--- Header --->
    <head>
        <title>Gamers' ABCD</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        
        <!-- Logo -->
        <link rel="shortcut icon" href="images/logo.png">

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/font-awesome.min.css" rel="stylesheet" >
        <!-- Custom styles -->
        <link href="css/customCSS/navbar.css" rel="stylesheet">
        <link href="css/customCSS/homeCarousel.css" rel="stylesheet">
        <link href="css/customCSS/games.css" rel="stylesheet">
        <link href="css/customCSS/form.css" rel="stylesheet">
        <link href="css/customCSS/tags.css" rel="stylesheet">
        <link href="css/customCSS/search.css" rel="stylesheet">
        <link href="css/customCSS/admin.css" rel="stylesheet">
        <link href="css/customCSS/loadmore.css" rel="stylesheet">
        
        
        <!-- Lightbox -->
        <link href="css/lightbox.css" rel="stylesheet" type="text/css" />
        <!-- Timeline CSS -->
        <link href="css/flat.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <!-- Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
        
    </head> <!--- /.Header --->
    
    <!--- Body --->
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand">
                    Gamers' ABCD <i class="fa fa-bookmark-o"></i>
                </span>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <ul class="nav navbar-nav leftside">
                    <li class="customBorder customActive Home"><a href="#"><i class="fa fa-home"></i> Home</a></li>
                    <li class='customBorder customNotActive Games'><a href="#"><i class="fa fa-gamepad"></i> Games</a></li>
                    <li class='customBorder customNotActive Companies'><a href="#"><i class="fa fa-globe"></i> Publishers</a></li>
                    <li class='customBorder customNotActive Credit'><a href="#"><i class="fa fa-star"></i> Credit</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right rightside">
                    <?php
                        if(isset($_SESSION["user"])) {
                            echo '<li class="customBorder customNotActive suggestGames"><a href="#">Games you might like&nbsp<i class="fa fa-question"></i></a></li>';
                            echo '  <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user users"></i> &nbsp&nbsp<i class="fa fa-caret-down usersCaret caretBrown"></i></a>
                                        <ul class="dropdown-menu dropdownLog">
                                            <li><a href=#><i class="fa fa-fighter-jet"></i>&nbspLogged in as '.$_SESSION["user"].'</a></li>
                                            <li><a href=# id="destroySession"><i class="fa fa-sign-out"></i>&nbsp&nbspLog out</a></li>
                                            <li class="divider"></li>
                                            <li class="showLiked"><a href="#" data-toggle="modal"><i class="fa fa-trophy"></i>&nbsp&nbspGames liked</a></li>
                                        </ul>
                                    </li>';
                        }
                        else {
                            echo '<li class="customBorder customNotActive suggestGames" style="display: none;"><a href="#">Games you might like&nbsp<i class="fa fa-question"></i></a></li>';
                            echo '  <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users users"></i> &nbsp&nbsp<i class="fa fa-caret-down usersCaret"></i></a>
                                        <ul class="dropdown-menu dropdownLog">
                                            <li><a href="#login-modal" data-toggle="modal" class="loginTrigger"><i class="fa fa-sign-in"></i>&nbsp&nbsp Log in</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#signup-modal" data-toggle="modal"><i class="fa fa-rocket"></i>&nbsp&nbsp Sign up</a></li>
                                        </ul>
                                    </li>';
                        }
                    ?>

                            
                    <!--
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> &nbsp&nbsp<i class="fa fa-caret-down"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="#login-modal" data-toggle="modal" class="loginTrigger"><i class="fa fa-sign-in"></i>&nbsp&nbsp Log in</a></li>
                            <li class="divider"></li>
                            <li><a href="#signup-modal" data-toggle="modal"><i class="fa fa-rocket"></i>&nbsp&nbsp Sign up</a></li>
                        </ul>
                    </li>
                    -->
                </ul>
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <div class="dropdown">
                            <input type="text" class="form-control customInput dropdown-toggle" id="searchOptions" data-toggle="dropdown" placeholder="Search your favorite games or companies...">
                            <ul class="dropdown-menu pull-left" role="menu" aria-labelledby="searchOptions" id="searchSettings">
                                <li role="presentation" class="dropdown-header searchTitle">Searches based on Likes</li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="searchOption1">Search games whose publishers accumulate the most Likes</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="searchOption2">Search games which accumulate the most Likes</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="searchOption3">Search publishers which publish games with most Likes</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="#" id="searchOption4">Search publishers which accumulate the most Likes</a></li>
                            </ul>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-default customSearch"><i class="fa fa-search"></i></button>
                </form>
            </div><!-- /.navbar-collapse -->
        </nav>
        
        <!-- Modal for Sign up-->
        <div class="modal fade" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="signupLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header signupheader">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="signupLabel">Create a new account</h4>
                    </div>
                    <div class="modal-body">
                        <div class="progress progress-striped active PROGRESS">
                            <div id="progressIndicator" style="display: none">0</div>
                            <div class="progress-bar progress-bar-danger signUpprogress" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            </div>
                        </div>
                        <hr class="signupHR">
                        <div class="input-group NAME">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control input-lg name" placeholder="Name">
                        </div>
                        <hr class="signupHR">
                        <div class="input-group EMAIL">
                            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                            <input type="text" class="form-control input-lg email" placeholder="Email">
                        </div>
                        <hr class="signupHR">
                        <div class="input-group PASSWORD">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span></span>
                            <input type="password" class="form-control input-lg password" placeholder="Password">
                        </div>
                        <hr class="signupHR">
                        <div class="input-group CONFIRM">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span></span>
                            <input type="password" class="form-control input-lg confirm" placeholder="Confirm password">
                        </div>
                        <!--
                        <hr>
                        <div class="checkbox-inline">
                            <label id="agree" rel="popover" data-content="Show words and their frequencies with bars.">
                                <input type="checkbox" id="agreeTo">I agree to the term of use.
                            </label>
                        </div>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" disabled id="signupButton">Sign Up</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.Modal for Sign up -->
        
        <!-- Modal for Log in-->
        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header loginheader">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="loginLabel"><i class="fa fa-sign-in"></i>&nbsp&nbsp Log in</h4>
                    </div>
                    <div class="modal-body">
                        <div class="input-group loginUSER">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control input-lg name loginuser" placeholder="Enter your username or email address">
                        </div>
                        <hr>
                        <div class="input-group loginPASSWORD">
                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                            <input type="password" class="form-control input-lg name loginpassword" placeholder="Enter your password">
                        </div>
                        <div id="loginFeedback"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" id="loginButton">Log in</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.Modal for Log in -->
        
         <!-- Modal for liked-->
        <div class="modal fade" id="liked-modal" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
            <div class="modal-dialog likeDialog">
                <div class="modal-content likeContent">
                    <div class="modal-header loginheader">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="loginLabel">Games Liked</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div id="sendToFriend">
                            <a data-toggle="collapse" data-parent="#accordion" href="#sendTo" id="sendToTitle">
                                Click me to recommend these games to your friend!
                            </a>
                            <hr>
                        </div>
                        <div id="sendTo" class="panel-collapse collapse out">
                            <div id="sendToFeedback"></div>
                            <div class="input-group SENDTO">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="text" class="form-control input-lg friendName" placeholder="Please enter your friend's name or email address here">
                                <span class="input-group-addon shareGames"><i class="fa fa-share"></i></span>
                            </div>
                            <hr>
                        </div>
                        <div id="likedField"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" id="loginButton" data-dismiss="modal">OK</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.Modal for Log in -->
        
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <?php
                    require_once "classes/connecToDB.php";
                    $con = connectToDB();
                    if($con === false):
                        return;
                    endif;

                    $sql = "SELECT imageURL FROM games";

                    $result = mysqli_query($con, $sql);
                    if(!$result) {
                        die('Error: ' . mysqli_error($con));
                    }
                    
                    //$bound = $result->num_rows;
                    $bound = 6;
                    for($id = 1; $id < $bound; $id++):
                        echo "<li data-target='#myCarousel' data-slide-to='$id'></li>&nbsp";
                    endfor;
                ?>
            </ol>
            
            <div class="carousel-inner">
                <?php
                    $row = mysqli_fetch_array($result);
                    $imagSrc = $row['imageURL'];
                    echo "  <div class='item active'>
                                <img src='$imagSrc' alt='Loading...' class='carouselImages'>
                                <div class='container'>
                                    <div class='carousel-caption'>
                                    </div>
                                </div>
                            </div>";
                    
                    $c = 1;
                    while(($row = mysqli_fetch_array($result)) && $c < $bound) {
                        $imagSrc = $row['imageURL'];
                        echo "  <div class='item'>
                                    <img src='$imagSrc' alt='Loading...' class='carouselImages'>
                                    <div class='container'>
                                        <div class='carousel-caption'>
                            
                                        </div>
                                    </div>
                                </div>";
                        $c++;
                    }
                ?>
            </div>
            
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left homeC"></span>
            </a>
            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right homeC"></span>
            </a>
        </div><!-- /.carousel -->
        
        <div id="Games">
            <div id="gamesList">
                <hr>
            <?php
                $sql = "SELECT * FROM games";

                $result = mysqli_query($con, $sql);
                if(!$result) {
                    die('Error: ' . mysqli_error($con));
                }
                $loadCount = 0;
                $latesID = 0;
                $removeID = 0;
                while($row = mysqli_fetch_array($result)) {
                    if($loadCount === 30):
                        echo "<div id=G".$latestID.">";
                        echo "<center><i class='fa fa-chevron-down fa-3x gamesLM' id=".$latestID." name='1'></i></center>";
                        echo "</div>";
                        break;
                    endif;
                    
                    $imageSrc = $row['imageURL'];
                    if(isset($_SESSION['admin'])):
                        $send = str_replace(" ", "!-!-!", $row['name']);
                        echo "<div class='deleteGames' id='$removeID'><i class='fa fa-times removeGames' id = ".$removeID." name =  ".$send."></i>";
                        $removeID++;
                    endif;
                    
                    echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
                    $name = $row['name'];
                    $send = str_replace("'", "!-!-!", $name);
                    echo "<span class='games' name='$send'>$name</span><hr>";
                    $brief = $row['brief'];
                    echo "$brief<hr>";
                    
                    if(isset($_SESSION['admin'])):
                        echo "</div>";
                    endif;
                    
                    $loadCount++;
                    $latestID = $row['id'];
                }
            ?>
            </div>
            <div id="gameField"></div>
        </div>
        
        <div id="Companies">
            <div id="companiesList">
            <?php
                $sql = "SELECT * FROM companies";

                $result = mysqli_query($con, $sql);
                if(!$result) {
                    die('Error: ' . mysqli_error($con));
                }
                
               $loadCount = 0;
                $latesID = 0;
                $removeID = 0;
                while($row = mysqli_fetch_array($result)) {
                     if($loadCount === 30):
                        echo "<div id=C".$latestID.">";
                        echo "<center><i class='fa fa-chevron-down fa-3x companiesLM' id=".$latestID." name='1'></i></center>";
                        echo "</div>";
                        break;
                    endif;
                    
                    $imageSrc = $row['imageURL'];
                    if(isset($_SESSION['admin'])):
                        $send = str_replace(" ", "!-!-!", $row['name']);
                        echo "<div class='deleteCompanies' id='$removeID'><i class='fa fa-times removeCompanies' id = ".$removeID." name =  ".$send."></i>";
                        $removeID++;
                    endif;
                    
                    echo "<img src='$imageSrc' alt='Loading...' style='width: 50px; 'height: 70px;>&nbsp&nbsp";
                    $name = $row['name'];
                    $send = str_replace("'", "!-!-!", $name);
                    echo "<span class='companies' name='$send'>$name</span><hr>";
                    $brief = $row['brief'];
                    echo "$brief<hr>";
                    
                    if(isset($_SESSION['admin'])):
                        echo "</div>";
                    endif;
                    
                    $loadCount++;
                    $latestID = $row['id'];
                }
            ?>
            </div>
            <div id="companyField"></div>
        </div>
        
        <div id="homeField">
            <div class="jumbotron">
                <h2>About <span style="color:red">Gamers' ABCD</span></h2>
                <hr>
                <p>Gamers' ABCD is designed to help you find games you might enjoy. <br>
                    You in turn will help others find games they might like by sharing your opinions on games with us. <br>
                    Join us and share your opinions by clicking Like Buttons for games you are now enjoying and we will present you with games you might like.<br>
                    Before long, you will have a collection of games that you can manage and share with others.
                    </p>
                <hr>
                <a class="btn btn-danger btn-lg" role="button" id="try">Join Gamers' ABCD</a>
            </div>
        </div>
        
        <div id="Credit">Games and Publishers' Data Source: <hr>&nbsp&nbsp&nbsp&nbsp<a href="http://www.giantbomb.com/">Giant Bomb</a></div>
        
        <div id="searchField"></div>
        <div id="suggestField"></div>
        <div id="suggestBC">
                Click me to see publishers whose games you might want to look into
        </div>
        <div id="suggestFieldPie"></div>
        <div id="suggestFieldPieLabel"></div>
        <div id="searching"><i class="fa fa-spinner fa-spin fa-5x"></i></div>
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
        <script src="js/d3.v3.min.js"></script>
        <!-- Custom js -->
        <script src="js/customJS/navbar.js"></script>
        <script src="js/customJS/games.js"></script>
        <script src="js/customJS/userSystems.js"></script>
        <script src="js/customJS/pieChart.js"></script>
        <script src="js/customJS/companyTags.js"></script>
        <script src="js/customJS/gameTags.js"></script>
        <script src="js/customJS/search.js"></script>
        <script src="js/customJS/admin.js"></script>
        <script src="js/customJS/loadmore.js"></script>
        
        <!-- Timeline Scripts -->
        <script type="text/javascript" src="js/jquery.mCustomScrollbar.js"></script>
        <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="js/jquery.timeline.min.js"></script>
        <script type="text/javascript" src="js/image.js"></script>
        <script type="text/javascript" src="js/lightbox.min.js"></script>
        
        <script type="text/javascript" src="js/timeline.js"></script>
        <script type="text/javascript" src="js/customJS/timelineButton.js"></script>
    </body>
</html>
