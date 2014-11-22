<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Edit Timeline</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/font-awesome.min.css" rel="stylesheet" >
        
        <!-- Custom 
        <link href="css/flat.css" rel="stylesheet">-->
        
        <!-- Custom styles -->
        <link href="css/customCSS/navbar.css" rel="stylesheet">
        <link href="css/customCSS/homeCarousel.css" rel="stylesheet">
        <link href="css/customCSS/form.css" rel="stylesheet">
        <link href="css/customCSS/admin.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
        <script src="js/customJS/userSystems.js"></script>
    </head>
    <body>
        
        <nav class="navbar navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                
                <ul class="nav navbar-nav navbar-left leftside">
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
                </ul>
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
        
        <!--<div>
            <form role="form">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <input type="file" id="exampleInputFile">
                    <p class="help-block">Example block-level help text here.</p>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Check me out
                    </label>
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>-->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>

<?php

