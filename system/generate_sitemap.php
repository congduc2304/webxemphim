<?php
require '../config.php';
require '../language/lang_'.$conf_language.'.php';
require_once 'DbConnMain.php';
if(isset($_POST['generated_sitemap'])){
  $conn = DbConnMain::connect($mysql_host,$mysql_dbname,$mysql_user,$mysql_pass);
  $change_freq = "daily";
  $priority = "1.0";
  $media_priority = "0.8";
  $site_url = str_replace("system/generate_sitemap.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  $sitemap_file = fopen("../sitemap.xml", "w") or die ("Unable to open sitemap.xml file.");
  $header_content = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="styles/xml-sitemap.xsl"?>
  <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  function writeUrlContent($sitemap_file,$url,$freq,$prior,$img_url,$img_title,$img_caption){
   $main_url_content = "\n".'  <url>
   <loc>'.$url.'</loc>
   <lastmod>'.date("Y-m-d").'</lastmod>
   <changefreq>'.$freq.'</changefreq>
   <priority>'.$prior.'</priority>'.($img_url != null && $img_url != "_no_data" ? "\n".'    <image:image>
    <image:loc>'.$img_url.'</image:loc>
    <image:title>'.$img_title.'</image:title>
    <image:caption>'.$img_caption.'</image:caption>
    </image:image>' : '').'
   </url>';
   fwrite($sitemap_file,$main_url_content);
 }
 fwrite($sitemap_file,$header_content);
 writeUrlContent($sitemap_file,$site_url."index.php",$change_freq,$priority,null,null,null);
 try {
  $stmt = $conn->prepare("SELECT * FROM ".$table_movies."");
  $stmt->execute();
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach($stmt->fetchAll() as $row){
    writeUrlContent($sitemap_file,$site_url."media.php?id=".$row['media_url'],$change_freq,$media_priority,$row['media_cover'],$row['media_name'],$row['media_type']);
  }
}
catch(PDOException $e) {
  echo "MySQL connection failed: " . $e->getMessage();
}
fwrite($sitemap_file,"\n".'</urlset>');
fclose($sitemap_file);
if(!file_exists("../robots.txt")){
  $robots_file = fopen("../robots.txt", "w") or die ("Unable to open robots.txt file.");
  fwrite($robots_file,"Sitemap: ".$site_url."sitemap.xml");
  fclose($robots_file);
}
echo $lang_sitemap_xml_file_generated;
} else {
	echo "Forbidden 403";
}
?>