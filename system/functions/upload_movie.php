<?php
require '../../config.php';
require '../../language/lang_'.$conf_language.'.php';
require '../slug_generator/SlugGenerator.php';
require '../slug_generator/SlugOptions.php';
use Ausi\SlugGenerator\SlugGenerator;
require_once '../DbConnMain.php';
$conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
function notEmpty($varq){
	if(isset($varq) && $varq != null && $varq != ""){
		return addslashes($varq);
	} else {
		return "_no_data";
	}
}
$media_name = strip_tags(notEmpty($_POST['media_name']));
if($media_name != "_no_data"){
$media_desc = notEmpty($_POST['media_desc']);
$media_desc = preg_replace("/<p(.*?)>/","<div$1>",$media_desc);
$media_desc = str_replace("</p>","</div>",$media_desc);
$media_type = notEmpty($_POST['media_type']);
$media_genre1 = notEmpty($_POST['media_genre1']);
$media_genre2 = notEmpty($_POST['media_genre2']);
$media_duration = strip_tags(notEmpty($_POST['media_duration']));
$media_language = strip_tags(notEmpty($_POST['media_language']));
$media_release_date = strip_tags(notEmpty($_POST['media_released']));
$media_actors = strip_tags(notEmpty($_POST['media_actors']));
$media_director = strip_tags(notEmpty($_POST['media_director']));
$media_leaders = strip_tags(notEmpty($_POST['media_leaders']));
$media_other_info = strip_tags(notEmpty($_POST['media_other_info']));
$media_properties = array();
if(isset($_POST['media_properties'])){
	$media_properties = $_POST['media_properties'];
}
if(empty($media_properties)){
	array_push($media_properties,"_no_data");
}
$media_cover = notEmpty($_POST['media_cover']);
$media_trailer = str_replace(" ","",notEmpty($_POST['media_trailer']));
$media_series_preload = notEmpty($_POST['media_series']);
$media_series = (strpos($media_series_preload,'[/html]') ? $media_series_preload : str_replace(" ","",$media_series_preload));
$media_genre_all = $media_genre1."=div=".$media_genre2;
$media_uniq_id = $_POST['media_uniq_id'];
$slugit = new SlugGenerator;
$slug_url = $slugit->generate($media_name);
try {
	$stmt = $conn->prepare("SELECT media_uniq_id FROM ".$table_movies." WHERE `media_uniq_id`='".$media_uniq_id."'");
	$stmt->execute();
	$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	if ($stmt->rowCount() != 0) {
		$conn->exec("UPDATE ".$table_movies." SET media_name='".$media_name."', media_desc='".$media_desc."', media_type='".$media_type."', media_genre='".$media_genre_all."', media_duration='".$media_duration."', media_language='".$media_language."', media_released='".$media_release_date."', media_actors='".$media_actors."', media_director='".$media_director."', media_leaders='".$media_leaders."', media_other_info='".$media_other_info."', media_properties='".implode("=div=",$media_properties)."', media_cover='".$media_cover."', media_trailer='".$media_trailer."', media_series='".$media_series."', media_url='".$slug_url."' WHERE media_uniq_id='".$media_uniq_id."'");
		echo $lang_popup_admin_movie_updated;
	} else {
		$conn->exec("INSERT INTO ".$table_movies." (media_name, media_desc, media_type, media_genre, media_duration, media_language, media_released, media_actors, media_director, media_leaders, media_other_info, media_properties, media_cover, media_trailer, media_series, media_likes, media_views, media_upload_date, media_url, media_uniq_id) VALUES ('".$media_name."', '".$media_desc."', '".$media_type."', '".$media_genre_all."', '".$media_duration."', '".$media_language."', '".$media_release_date."', '".$media_actors."', '".$media_director."', '".$media_leaders."', '".$media_other_info."', '".implode("=div=",$media_properties)."', '".$media_cover."', '".$media_trailer."', '".$media_series."', '0', '0', '".date($date_format)."', '".$slug_url."', '".$media_uniq_id."')");
		echo $lang_toast_media_uploaded;
	}
} catch(PDOException $e) {
	echo $e->getMessage();
}
} else {
	echo $lang_popup_admin_error_empty_name_field;
}
?>