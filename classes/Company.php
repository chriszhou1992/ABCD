<?php
/*
 * Parse company information in JSON.
 */
class Company {
    
    private $id;
    private $name;
    private $brief;
    private $description;
    private $date_founded;
    private $headquarter;
    private $website;
    // the URL to company logo
    private $imageURL;
    private $games;
    
    private $num_likes;
    
    private $valid = false;

	private $con;
    
    private $con;
    
    /*
     * Constrcut a Company instance
     * @param fromJSON true if the data will be obtained from a JSON file; false, if the data will be loaded from database
     */
    function __construct($fromJSON, $src, $con) {
        $this->con = $con;
        if($fromJSON) {
            $this->constructFromJSON($src);
        }
        else {
            $this->loadFromDatabase($src);
        }
    }
    
    function constructFromJSON($src) {
        $str = file_get_contents($src);
        $json = json_decode($str, TRUE);
        
        $results = $json["results"];
        
        if(!isset($results["name"]) || !isset($results["deck"]) ||
               !isset($results["image"]["super_url"])):
            return;
        endif;
        $this->name = $results["name"];
        $this->brief = $results["deck"];
        $this->description = $results["description"];
        $this->date_founded = $results["date_founded"];
        $this->headquarter = $results["location_country"];
        $this->website = $results["website"];
        $this->imageURL = $results["image"]["super_url"];
        
        $this->games = "";
        if(isset($results["developed_games"])) {
            foreach ($results["developed_games"] as $g) {
                $this->games .= "<br>".$g["name"];
            }
        }
        
        $this->num_likes = 0;
        $this->valid = true;
    }
    
    function loadFromDatabase($id) {
        require_once "connecToDB.php";
        $con = connectToDB();
        if($con === false):
            return;
        endif;
        
        $sql = "SELECT * FROM companies where id = ".$id;
        
        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }
        
        $row = mysqli_fetch_array($result);
        
        $this->id = $row[0];
        $this->name = $row[1];
        $this->brief = stripslashes($row[2]);
        // stripslashes because description was stored with mysql_real_escape_string
        $this->description = stripslashes($row[3]);
        $this->date_founded = $row[4];
        $this->headquarter = $row[5];
        $this->website = $row[6];
        $this->imageURL = $row[7];
        $this->games = stripslashes($row[8]);
        $this->num_likes = $row[9];
        
        mysqli_close($con);
        
        echo "<br>Load Successful!<br>";
        
    }
    
    /*
     * Printer to help debug
     */
    function printCompanyInfo() {
        if(!$this->valid):
            return;
        endif;
        
        echo "Name: ".$this->name."<br>";
        echo "Brief: ".$this->brief."<br>";
        echo "Description: ".$this->description."<br>";
        echo "Date-founded: ".$this->date_founded."<br>";
        echo "Headquarter: ".$this->headquarter."<br>";
        echo "Website: ".$this->website."<br>";
        echo "Games: ".$this->games."<br>";
        echo "Images: "."<img src=$this->imageURL></img><br>";
        echo "Num-likes: ".$this->num_likes."<br>";
    }
    
    /*
     * Store the data of the instance into database
     */
    function insert() {
        if(!$this->valid):
            return;
        endif;
        
        $this->name = mysql_real_escape_string($this->name);
        $this->brief = mysql_real_escape_string($this->brief);
        $this->description = mysql_real_escape_string($this->description);
        $this->games = mysql_real_escape_string($this->games);
        $this->headquarter = mysql_real_escape_string($this->headquarter);
        
        $sql = "INSERT INTO companies
        (name, brief, description, date_founded, headquarter, website, imageURL, games, num_likes)
        VALUES
        ('$this->name', '$this->brief', '$this->description', '$this->date_founded', '$this->headquarter', '$this->website', '$this->imageURL', '$this->games', '$this->num_likes')";
        
        if(!mysqli_query($this->con, $sql)) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        //mysqli_close($con);
        
        echo "<br>Insertion Successful!<br>";
    }
    
    /*----------getter----------*/
    function getID() {
        return $this->id;
    }
    
    function getName() {
        return $this->name;
    }
    
    function getBrief() {
        return $this->brief;
    }
    
    function getDescription() {
        return $this->description;
    }
    
    function getDate() {
        return $this->date_founded;
    }
    
    function getHeadquarter() {
        return $this->headquarter;
    }
    
    function getWebsite() {
        return $this->website;
    }
    
    function getImage() {
        return $this->imageURL;
    }
    
    function getGames() {
        return $this->games;
    }
    
    function getLikes() {
        return $this->num_likes;
    }
    
}

// load data from JSON file
//$c = new Company(true, "data/ubisoft.json");
//$c->printCompanyInfo();
// insert will insert data into database so call this function with cautious to prevent inserting duplicate entry
// use: Alter TABLE companies auto_increment = 1 to reset id 
//$c->insert();
/*--------------------*/
// load data from database with id = 1
//$cl = new Company(false, 1);
//$cl->printCompanyInfo();
