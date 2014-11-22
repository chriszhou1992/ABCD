<?php
	session_start();

	require_once "classes/connecToDB.php";
        $con = connectToDB();
        if($con === false):
            return;
        endif;

	//check correct data is in POST
	if ( !isset($_POST["type"]) || !isset($_POST["id"])) {
		echo "Wrong! Type not set.";
		return;
	}
	
	require_once("classes/Tags.php");
	if ( isset($_POST["content"]) ) {	//insert tag
		//escape tag content
		$content = addslashes($_POST['content']);

		$table = $_POST["type"] . "_tags";
		$newTagID = Tags::insertNewTag($con, $_POST["content"], $table, $_POST["id"]);

		//inserted tag
		echo '<div class="tag gradient' . Tags::$currentColor;
		echo '" tag_id=' . $newTagID . ' author=true likes=0>' . $_POST["content"];
		echo '<div class="tagLikes"></div><i></i>';
		echo "</div>";
		
		//empty tag
		echo '<div class="emptyTag"><i class="fa fa-plus"></i>Add Tag</div>';
	}
	else if ( isset($_POST["likes"]) ) {	//update likes
		$table = $_POST["type"] . "_tags";
		
		echo Tags::updateTagLikes($con, $table, $_POST["likes"], $_POST["id"] );
	}
	else {	//delete tag
		$table = $_POST["type"] . "_tags";
		echo Tags::deleteTag($con, $_POST["id"], $table);
	}