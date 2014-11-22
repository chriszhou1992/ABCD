<?php
session_start(); 
if(isset($_POST["destroySession"])):
    require_once 'classes/Accounts.php';
    new Accounts();
    if(Accounts::checkIfAdmin($_SESSION["user"])):
        echo "admin";
        unset($_SESSION["user"]);
        return;
    endif; 
    unset($_SESSION["user"]);
    echo "success";
    return;
endif;

$user = $_POST["user"];
$password = $_POST["password"];

require_once 'classes/Accounts.php';
new Accounts();
$feedback = Accounts::verify($user, $password);
if($feedback === "Password verified!"):
    $_SESSION["user"] = Accounts::getEmail($user);
    if(Accounts::checkIfAdmin($_SESSION["user"])):
        echo "admin";
        return;
    endif;
    echo $_SESSION["user"];
    return;
endif;

echo $feedback;



