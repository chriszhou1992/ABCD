<?php

/**
 * Description of Tags
 *
 * @author ZHOU
 */
class Tags {

	const COLORS = 16;
	public static $currentColor = 0;

	public static function insertNewTag($con, $content, $table, $ID) {
		// Check connection
        if (mysqli_connect_errno($con)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return;
        }
		
        $sql = "INSERT INTO $table (content, num_likes, company_id)
        VALUES ('$content', 0, $ID)";

		if ($table === "game_tags")
			$sql = "INSERT INTO $table (content, num_likes, game_id)
			VALUES ('$content', 0, $ID)";

        if(!mysqli_query($con, $sql)) {
            die('Error: ' . mysqli_error($con));
        }

		$newTagID = mysqli_insert_id($con);

		Tags::updateUserTagList($con, $newTagID, $table);
		//Tags::updateCompanyOrGameTagList($con, $newTagID, $table, $ID);

		return $newTagID;
    }

	public static function updateUserTagList($con, $newTagID, $field) {

		if (!isset($_SESSION["user"])) {
			echo "No User Logged in!";
			return;
		}

		$table = "authored_" . $field;
		
		$sql = "INSERT INTO $table (email, tag_id)
			VALUES ('" . $_SESSION["user"] . "', $newTagID)";
		$result = mysqli_query($con, $sql);
		if(!$result) {
			die('Error: ' . mysqli_error($con));
		}
	}

	public static function deleteTag($con, $tagID, $table) {
		// Check connection
        if (mysqli_connect_errno($con)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return;
        }

        $sql = "DELETE FROM $table WHERE id = $tagID";
		if(!mysqli_query($con, $sql))
            die('Error: ' . mysqli_error($con));

		$authorTable = "authored_" . $table;
		$sql = "DELETE FROM $authorTable WHERE tag_id = $tagID";
		if(!mysqli_query($con, $sql))
            die('Error: ' . mysqli_error($con));

		$likedTable = "liked_" . $table;
		$sql = "DELETE FROM $likedTable WHERE tag_id = $tagID";
		if(!mysqli_query($con, $sql))
            die('Error: ' . mysqli_error($con));

		return "success";
	}


	public static function updateTagLikes($con, $table, $newLikes, $tagID) {
		if (!isset($_SESSION["user"]))
			return "log in error";

		$likesTable = "liked_" . $table;	//liked_company(game)_tags

		/******test duplicate likes******/
		$sql = "SELECT * FROM $likesTable WHERE
				email = '" . $_SESSION["user"] . "' AND tag_id = $tagID";
		$result = mysqli_query($con, $sql);
		if(!$result)
			die('Error: ' . mysqli_error($con));

		$row = mysqli_fetch_array($result);
		if ($row !== NULL)
			return "duplicate";

		/*****update number of likes*****/
		$sql = "UPDATE $table SET num_likes = $newLikes WHERE id = $tagID";

		$result = mysqli_query($con, $sql);
		if(!$result) {
			die('Error: ' . mysqli_error($con));
		}

		/*****insert new liked tag for user*****/
		$sql = "INSERT INTO $likesTable (email, tag_id)
				VALUES ('" . $_SESSION["user"] . "', $tagID)";

		$result = mysqli_query($con, $sql);
		if(!$result)
			die('Error: ' . mysqli_error($con));

		return "success";
	}
	
	public static function getCompanyTags($con, $company_id) {
		return Tags::getTags($con, "company_tags", "company_id", $company_id);
	}


	public static function getGameTags($con, $game_id) {
		return Tags::getTags($con, "game_tags", "game_id", $game_id);
	}


	private static function getTags($con, $table, $IDFieldName, $ID) {
		if ($ID === NULL)	//error checking
			return array();
		
		// Check connection
        if (mysqli_connect_errno($con)) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
			return;
        }

		$joinTable = "authored_" . $table;

		//join table to obtain authors of a tag
		$sql = "SELECT * FROM $table LEFT JOIN $joinTable
			ON $table.id = $joinTable.tag_id
			WHERE $table.$IDFieldName = $ID ORDER BY num_likes DESC";
		
		$result = mysqli_query($con, $sql);
		if(!$result)
			die('Error: ' . mysqli_error($con));
		
		return $result;
	}

	public static function printTags($results, $belong_id) {
		Tags::$currentColor = 0;

		echo '<div id="tags">';
		if (!empty($results)) {
			$id = 0;
			//TODO: add collapse/expand function for > 5 tags
			while($row = mysqli_fetch_array($results)) {
				echo '<div class="tag gradient' . $id . '" tag_id=' . $row["id"];

				//attribute used to determine correct author
				if ( isset($row["email"]) && isset($_SESSION["user"])
						&& $row["email"] === $_SESSION["user"] )
					echo " author=true";

				echo ' likes=' . $row["num_likes"] . '>' . $row["content"];

				echo '<div class="tagLikes"></div><i></i>';
				echo "</div>";
				$id = ($id + 1) % Tags::COLORS;
			}
		}

		Tags::$currentColor = $id;

		if (isset($_SESSION["user"]) ) {	//user has logged in
			//output HTML for Add Tag button & form
			echo '<div class="emptyTag"><i class="fa fa-plus"></i>Add Tag</div>';
			
			
			
			
			echo '<input type="text" class="tagForm" maxlength="25" ';
			echo 'belong_id=' . $belong_id . ' placeholder="Enter your content (25 characters max)">';
			echo '<button type="button" class="btn btn-warning" id="tagButton">Tag It!</button>';
			//echo '<hr>';
		}
		echo '</div>';
	}
}