<?php
class MysqlSiteData
{
public function getSiteSettings($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass,$table_settings) {
try {
    $conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_dbname;charset=utf8", $mysql_user, $mysql_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM ".$table_settings." WHERE `identify`='1'");
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
	foreach($stmt->fetchAll() as $row){
		return array($row['site_name'],$row['site_title'],$row['meta_desc'],$row['meta_keys']);
	}
} catch(PDOException $e) {
	echo $e->getMessage();
}
$conn = null;
}
}
?>