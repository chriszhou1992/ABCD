<?php

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirm = $_POST["confirm"];


if($name !== "no"):
    if(filter_var($name, FILTER_VALIDATE_EMAIL)) {
        echo "invalid";
        return;
    }
    $count = 0;
    for($i=0; $i<strlen($name); $i++):
        $curC = substr($name, $i, 1);
        
        if(ctype_alpha($curC)):
            $count += 1;
        endif;
        if($count >= 6):
            require_once 'classes/Accounts.php';
            new Accounts();
            if(Accounts::nameExists($name)) {
                echo "exists";
                return;
            }
            echo "true";
            break;
        endif;
    endfor;
    if($count < 6):
        echo "false";
    endif;
endif;

if($email !== "no"):
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require_once 'classes/Accounts.php';
        new Accounts();
        if(Accounts::emailExists($email)) {
            echo "exists";
            return;
        }
        echo "true";
    } else {
        echo "false";
    }
endif;

if($password !== "no" && $confirm == "no"):
    if(strlen($password) >= 6) {
        echo "true";
    } else {
        echo "false";
    }
endif;

if($password !== "no" && $confirm !== "no"):
    if($password === $confirm) {
        echo "true";
    } else {
        echo "false";
    }
endif;