<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("funcs/dbFunctions.php");



$jsondata=array();

$servername='192.168.41.105';
$username='appony'; 
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
		} 

$app=$_GET["app"];
$keywordLT=$app;
$sql = "select appid from appony.app_list a WHERE a.appname='".$app."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
   // output data of each row
while($row = $result->fetch_assoc()) {
	$appIosID=$row["appid"];
		    }
											$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';
											$fizyGet = file_get_contents($fizyUrl);
											$fizyJson=json_decode($fizyGet);
											//echo "\nApp comment get url: ".$fizyUrl;	

										//$bul = array('İ','ç', 'ı', 'ğ', 'ş', 'ö', 'ü');
       										//$degistir = array('i','c', 'i', 'g', 's', 'o', 'u');
       										$y=6; 
$bul = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü');
$degistir = array('c','c','g','g','i','i','o','o','s','s','u','u');
											for ($x = 5; $x >=1 ; $x--) {
												$y=$y-1;

											$commentid=$fizyJson->feed->entry[$x]->id->label;
											$author=$fizyJson->feed->entry[$x]->author->name->label;
											$comment=$fizyJson->feed->entry[$x]->content->label;
											$comment = str_replace($bul, $degistir, $comment);

											$title=$fizyJson->feed->entry[$x]->title->label;
											$title=str_replace($bul,$degistir,$title);
											$ratingPlaceholder='im:rating';
											$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
											$versionPlaceholder='im:version';
											$version=$fizyJson->feed->entry[$x]->$versionPlaceholder->label;
											$content=array();
											//$content=$keywordLT." yorum ".$y;
											$content=$content+array('yorum'.$y => array("author"=>$author, "rating"=> $rating,"version"=>$version, "title"=>												$title, "comment"=>$comment));

											//$content=$content."\nkullanıcı adi: ".$author;
											//$content=$content."\npuani: ".$rating;
											//$content=$content."\nsurum: ".$version;
											//$content=$content."\nbaslik: ".$title;
											//$content=$content."\nYorum: ".$comment;	
											//echo "\ncontent with reviews: ".$content;
										
											$jsondata=$jsondata+$content;

													}
			}

echo json_encode($jsondata);

?>
