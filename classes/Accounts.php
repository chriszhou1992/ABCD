<?php

class Accounts {
    # rememeber the connection
    private static  $con;
    
    /*
     * Set up and connect to the accounts database
     */
    function __construct() {
        require_once "connecToDB.php";
        Accounts::$con = connectToDB();
        if(Accounts::$con === false):
            return;
        endif;
    }
    
    public static function createNewUser($name, $email, $password) {
        $name = addslashes($name);
        $email = addslashes($email);
        $hashed = crypt($password);
        
        
        $sql = "INSERT INTO users (email, name, hashed)
        VALUES
        ('$email', '$name', '$hashed')";
        
        if(!mysqli_query(Accounts::$con, $sql)) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
    }
    
    public static function nameExists($name) {
        $name = addslashes($name);
        $sql = "SELECT * FROM users WHERE name = '$name'";
        
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        $entry = mysqli_fetch_array($result);
        if($entry !== null):
            return true;
        endif;
        return false;
    }
    
    public static function emailExists($email) {
        $email = addslashes($email);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        $entry = mysqli_fetch_array($result);
        if($entry !== null):
            return true;
        endif;
        return false;
    }
    
    public static function verify($user, $password) {
        $user = addslashes($user);
        $sql = "SELECT hashed FROM users WHERE name = '$user' or email = '$user'";
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        $entry = mysqli_fetch_array($result);
        if($entry === null) {
            return "The account does not exist.";
        } else {
            if(crypt($password, $entry['hashed']) === $entry['hashed']) {
                return "Password verified!";
            } else {
                return "Invalid password";
            }
        }
    }
    
    public static function getEmail($user) {
        $user = addslashes($user);
        $sql = "SELECT email FROM users WHERE name = '$user' or email = '$user'";
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        
        $entry = mysqli_fetch_array($result);
        if($entry === null) {
            return "The account does not exist.";
        } else {
            return $entry['email'];
        }
    } 
    
    public static function getName($user) {
        $user = addslashes($user);
        $sql = "SELECT name FROM users WHERE name = '$user' or email = '$user'";
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        
        $entry = mysqli_fetch_array($result);
        if($entry === null) {
            return "The account does not exist.";
        } else {
            return $entry['name'];
        }
    } 
    
    public static function makeChanges($sql) {
        $result = mysqli_query(Accounts::$con, $sql);
        if (!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }
        return "success";
    }
    
    public static function checkIfAdmin($email) {
        $sql = "SELECT administrator FROM users WHERE email = '$email'";

        $result = mysqli_query(Accounts::$con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error(Accounts::$con));
        }

        $row = mysqli_fetch_array($result);
        
        if($row['administrator'] !== NULL):
            return true;
        endif;
        return false;
    }
}

/*
new Accounts();
echo Accounts::loadPreferences("Bing-Jui");
/*
echo Accounts::verify("x.ox1025@gmail.com", "123456");

$hashed = addslashes(crypt("123456"));
if(crypt("123456", $hashed) === $hashed) {
    echo "Password verified!";
}
 */