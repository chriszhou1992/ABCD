<?php

$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];

require_once 'classes/Accounts.php';
new Accounts();
Accounts::createNewUser($name, $email, $password);
echo "success";