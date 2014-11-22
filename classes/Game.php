<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
/*
 * Parse company information in JSON into an object.
 */
class Game {
    
    private $id;
    private $name;
    private $brief;
    private $description;
    private $date_released;
    // publisher could be multiple
    private $publisher;
    // different genres and themes and platforms are separated by space
    private $genres;
    private $themes;
    private $platforms;
    // the game profile picture
    private $imageURL;
    // the game-play images
    private $gameImageURLs;
    private $similar_games;
    
    private $num_likes;
    
    private $valid = false;
    
    /*
     * Constrcut a Game instance
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
        
        if(!isset($results["original_release_date"])) {
            return;
        } else {
            $cur = substr($results["original_release_date"], 0 ,4);
            $cur = intval($cur);
            if($cur < 2008):
                return;
            endif;
        }
        
        if(!isset($results["name"]) || !isset($results["deck"]) || !isset($results["description"]) || !isset($results["publishers"][0]["name"]) ||
               !isset($results["image"]["super_url"]) || !isset($results["images"]) || !isset($results["image"]["super_url"])):
            return;
        endif;
        
        
        $this->name = $results["name"];
        $this->brief = $results["deck"];
        $this->description = $results["description"];
        $this->date_released = $results["original_release_date"];
        
        $this->publisher = $results["publishers"][0]["name"];
        
        $this->genres = "";
        if(isset($results["genres"])) {
            foreach ($results["genres"] as $g) {
                $this->genres .= " ".$g["name"];
            }
        }
        
        $this->themes = "";
        if(isset($results["themes"])) {
            foreach ($results["themes"] as $t) {
                $this->themes .= " ".$t["name"];
            }
        }
        
        $this->platforms = "";
        if(isset($results["platforms"])) {
            foreach ($results["platforms"] as $p) {
                $this->platforms .= "<br>".$p["name"];
            }
        }
        
        $this->similar_games = "";
        if(isset($results["similar_games"])) {
            foreach ($results["similar_games"] as $s) {
                $this->similar_games .= "<br>".$s["name"];
            }
        }
        
        $this->imageURL = $results["image"]["super_url"];
        
        $this->gameImageURLs = "";
        if(isset($results["images"])) {
            foreach ($results["images"] as $i) {
                $this->gameImageURLs .= "<br>".$i["super_url"];
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
        
        $sql = "SELECT * FROM games where id = ".$id;
        
        $result = mysqli_query($con, $sql);
        if(!$result) {
            die('Error: ' . mysqli_error($con));
        }
        
        $row = mysqli_fetch_array($result);
        //var_dump($row);
        
        $this->id = $row[0];
        $this->name = stripslashes($row[1]);
        $this->brief = stripslashes($row[2]);
        // stripslashes because description was stored with mysql_real_escape_string
        $this->description = stripslashes($row[3]);
        $this->date_released = stripslashes($row[4]);
        $this->publisher = stripslashes($row[5]);
        $this->genres = stripslashes($row[6]);
        $this->themes = stripslashes($row[7]);
        $this->platforms = stripslashes($row[8]);
        // the game logo
        $this->imageURL = $row[9];
        // in-game images
        $this->gameImageURLs = $row[10];
        $this->similar_games = stripslashes($row[11]);
        $this->num_likes = $row[12];
        $this->story = $row[13];
        
        mysqli_close($con);
    }
    
    /*
     * Printer to help debug
     */
    function printGameInfo() {
        if(!$this->valid):
            return;
        endif;
        
        echo "Name: ".$this->name."<br>";
        echo "Brief: ".$this->brief."<br>";
        echo "Description: ".$this->description."<br>";
        echo "Date-released: ".$this->date_released."<br>";
        echo "Publishers: ".$this->publisher."<br>";
        echo "Genres: ".$this->genres."<br>";
        echo "Themes: ".$this->themes."<br>";
        echo "Platforms: ".$this->platforms."<br>";
        echo "Similar-games: ".$this->similar_games."<br>";
        echo "Logo: "."<img src=$this->imageURL></img><br>";
        echo "Num-likes: ".$this->num_likes."<br>";
        
        $imgs = substr($this->gameImageURLs, 4);
        
        while( ($pos = strpos($imgs, "<br>")) != false) {
            $img = substr($imgs, 0, $pos);
            echo "<img src=$img></img>";
            $imgs = substr($imgs, $pos+4);
        }
        echo "<img src=$imgs></img>";
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
        $this->date_released = mysql_real_escape_string($this->date_released);
        $this->publisher = mysql_real_escape_string($this->publisher);
        $this->genres = mysql_real_escape_string($this->genres);
        $this->similar_games = mysql_real_escape_string($this->similar_games);
        
        $sql = "INSERT INTO games
        (name, brief, description, date_released, publishers, genres, themes, platforms, imageURL, gameImageURLs, similar_games, num_likes)
        VALUES
        ('$this->name', '$this->brief', '$this->description', '$this->date_released', '$this->publisher', '$this->genres', '$this->themes', '$this->platforms', '$this->imageURL', '$this->gameImageURLs', '$this->similar_games', '$this->num_likes')";
        
        if(!mysqli_query($this->con, $sql)) {
            die('Error: ' . mysqli_error($this->con));
        }
        
        //mysqli_close($this->con);
        
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
        return $this->date_released;
    }

    function getPublishers() {
        return $this->publisher;
    }

    function getGenres() {
        return $this->genres;
    }

    function getThemes() {
        return $this->themes;
    }

    function getPlatforms() {
        return $this->platforms;
    }

    function getImageURL() {
        return $this->imageURL;
    }

    function getGameImages() {
        return $this->gameImageURLs;
    }

    function getSimilarGames() {
        return $this->similar_games;
    }

    function getLikes() {
        return $this->num_likes;
    }

    function getStory() {
        return $this->story;
    }
}

// load data from JSON file
//$g = new Game(true, "data/assasBlackFlag.json");
//$g->printGameInfo();
// insert will insert data into database so call this function with cautious to prevent inserting duplicate entry
// use: Alter TABLE companies auto_increment = 1 to reset id 
//$g->insert();
/*--------------------*/
// load data from database with id = 1
//$gl = new Game(false, 1);
//$gl->printGameInfo();
