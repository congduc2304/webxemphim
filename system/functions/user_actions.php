<?php
session_start();
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
if(isset($_POST['update_movie_views'])){
	$movie_id = $_POST['update_movie_views'];
if(!isset($_SESSION["watched_movie_".$_POST['update_movie_views']])){
try {
    $stmt = $conn->prepare("SELECT media_views FROM ".$table_movies." WHERE `id`='".$movie_id."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		$new_count = $row['media_views'] + 1;
		$conn->exec("UPDATE ".$table_movies." SET media_views='".$new_count."' WHERE id='".$movie_id."'");
	}
	$_SESSION["watched_movie_".$movie_id] = true;
} catch(PDOException $e) {
	echo $e->getMessage();
}
}
if(isset($_SESSION['username'])){
try {
    $stmt = $conn->prepare("SELECT watched_media FROM ".$table_members." WHERE `username`='".$_SESSION['username']."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		$watched_media = array();
		if($row['watched_media'] != ""){
			$watched_media = explode(",",$row['watched_media']);
		}
		if(!in_array($movie_id,$watched_media)){
			array_push($watched_media,$movie_id);
			$finalized_data = implode(",",$watched_media);
			$_SESSION['my_watched_media'] = $finalized_data;
			$conn->exec("UPDATE ".$table_members." SET watched_media='".$finalized_data."' WHERE username='".$_SESSION['username']."'");
		}
	}
} catch(PDOException $e) {
	echo $e->getMessage();
}
}
}
if(isset($_POST['add_to_favorites'])){
	$movie_id = $_POST['add_to_favorites'];
if(isset($_SESSION['username'])){
try {
    $stmt = $conn->prepare("SELECT favorite_media FROM ".$table_members." WHERE `username`='".$_SESSION['username']."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		$favorite_media = array();
		if($row['favorite_media'] != ""){
			$favorite_media = explode(",",$row['favorite_media']);
		}
		if(!in_array($movie_id,$favorite_media)){
			array_push($favorite_media,$movie_id);
			echo json_encode(array("response" => "true","message" => $lang_favorites_added));
		} else {
			$favorite_media = array_diff($favorite_media,array($movie_id));
			echo json_encode(array("response" => "false","message" => $lang_favorites_removed));
		}
		$finalized_data = implode(",",$favorite_media);
		$_SESSION['my_favorite_media'] = $finalized_data;
		$conn->exec("UPDATE ".$table_members." SET favorite_media='".$finalized_data."' WHERE username='".$_SESSION['username']."'");
	}
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
} else {
	echo json_encode(array("response" => "error","message" => $lang_favorites_add_must_be_logged_error));
}
}
if(isset($_POST['like_media'])){
	$movie_id = $_POST['like_media'];
	$movie_name = $_POST['media_name_liked'];
if(isset($_SESSION['username'])){
try {
    $stmt = $conn->prepare("SELECT liked_media FROM ".$table_members." WHERE `username`='".$_SESSION['username']."'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		$liked_media = array();
		if($row['liked_media'] != ""){
			$liked_media = explode(",",$row['liked_media']);
		}
		if(!in_array($movie_id,$liked_media)){
			array_push($liked_media,$movie_id);
			$finalized_data = implode(",",$liked_media);
			$_SESSION['my_liked_media'] = $finalized_data;
			$conn->exec("UPDATE ".$table_members." SET liked_media='".$finalized_data."' WHERE username='".$_SESSION['username']."'");
			$stmt2 = $conn->prepare("SELECT media_likes FROM ".$table_movies." WHERE `id`='".$movie_id."'");
			$stmt2->execute();
			$result2 = $stmt2->setFetchMode(PDO::FETCH_ASSOC);
			foreach($stmt2->fetchAll() as $brow){
				$new_count = $brow['media_likes'] + 1;
				$conn->exec("UPDATE ".$table_movies." SET media_likes='".$new_count."' WHERE id='".$movie_id."'");
			}
			echo json_encode(array("response" => "true","message" => str_replace("%a",$movie_name,$lang_like_added_msg)));
		} else {
			echo json_encode(array("response" => "error","message" => $lang_like_already_added_error));
		}
	}
} catch(PDOException $e) {
	echo json_encode(array("response" => "error","message" => $e->getMessage()));
}
} else {
	echo json_encode(array("response" => "error","message" => $lang_like_add_must_be_logged_error));
}
}
if(isset($_POST['post_review'])){
if($_POST['post_review'] != null && $_POST['post_review'] != ""){
	$review = addslashes($_POST['post_review']);
	$media_id = $_POST['media_id'];
	if(strlen($review) <= $conf_max_review_chars){
	if(isset($_SESSION['username'])){
try {
	$stmt = $conn->prepare("SELECT avatar_img FROM ".$table_members." WHERE `username`='".$_SESSION['username']."'");
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($stmt->rowCount() != 0) {
		$conn->exec("INSERT INTO ".$table_reviews." (media_id, username, write_date, message) VALUES ('".$media_id."', '".$_SESSION['username']."', '".date($date_format)."', '".$review."')");
		echo $lang_review_posted_msg;
	} else {
		echo $lang_dialog_unknown_error;
	}
} catch(PDOException $e) {
	echo $e->getMessage();
}
	} else {
		echo $lang_media_reviews_warning_msg;
	}
	} else {
		echo str_replace("%a",$conf_max_review_chars,$lang_media_reviews_text_too_long);
	}
} else {
	echo $lang_media_reviews_field_empty_error;
}
}
?>