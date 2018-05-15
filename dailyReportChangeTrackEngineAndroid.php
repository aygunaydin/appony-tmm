<?php
error_reporting(E_ALL);
//header('Content-Type: application/json');
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

date_default_timezone_set('Europe/Istanbul');

echo "STARTED</br>";


											$servername='192.168.41.105';
											$username='appony';
											$servername='192.168.41.105';
											$username='appony';
											$password='appony1020';
											$dbname='appony';
											$conn = new mysqli($servername, $username, $password, $dbname);
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "select appid,appname from appony.app_list where isturkcell=1 and appname in ('bip','lifebox','fizy','yaani','dergilik')";

											$inform="Google Play (Android) Puani degisenler: \n";
											$informText='';


											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
											    // output data of each row
											    while($row = $result->fetch_assoc()) {
											    	$appIosID=$row["appid"];
											    	$appName=$row["appname"];
											    	echo "\napp ios id from db:".$appIosID;

											$yesterdayMinusOneRating=getAndroidRatingMinusOne($appName);
											$yesterdayMinusTwoRating=getAndroidRatingMinusTwo($appName);
											$logoUrl=getAppLogo($appName);
											if ($logoUrl==''){ $logoUrl=getAppLogo('hesabim');}
													if ($yesterdayMinusOneRating!='' && $yesterdayMinusTwoRating!=''){
															if ($yesterdayMinusTwoRating!=$yesterdayMinusOneRating){

															$informText=$appName." dun:".$yesterdayMinusTwoRating." bugun: ".$yesterdayMinusOneRating;
															$modernContent='{
															   "txnid":"8899",
															   "receiver":{
															      "type":1
															   },
															   "composition":{
															      "list":[
															         {
															            "type":13,
															            "tmmtype":0,
															            "singletmm":{
															               "title":"Google Play Store Puan Değişikliği",
															               "description":"'.$informText.'",
															               "image":{
															                  "url": "'.$logoUrl.'",
															                  "ratio": 1,
															                  "button": {
															                    "name" : "Detaylar için tıklayın",
															                    "url" : "http://appony.ga/details.php?app='.$appName.'"
															                  }
															               }
															            }
															         }
															      ]
															   }
															}';
															$resultImage=sendBipImage($modernContent);
															}

													}

											//$content=$content."\n".$appName." ".$bipRating." (". $bipRaterNum.") Son: ".$bipLatestRating." ( ".$bipLatestRaterNum .",".$releaseDate.")\n";
											echo " \n".$appName."dun: ".$yesterdayMinusTwoRating." bugun: ".$yesterdayMinusOneRating."</br>";

											    }


											}

											//$content=$content."\nYardim yazarak sorgulayabileceginiz bilgileri ogrenebilirsiniz. Servislere ait son 5 yoruma ulasmak icin 'servisismi yorum' yazip gonderebilirsiniz. Ornek: fizy yorum";

function sendBipResponse($receiver,$content){

$tnxid=rand(1000,9999);
$postdata['txnid']=$tnxid;
$receiverArray['type']=2;
$receiverArray['address']="905322103846";
$postdata['receiver']=$receiverArray;
$listArray0['type']=0;
$listArray0['message']=$content;
$listArray[0]=$listArray0;
$composition0['list']=$listArray;
$postdata['composition']=$composition0;
//echo "\npostdata array: ".$postdata;
//$postdataJson = "json=".json_encode($postdata)."&";
$postdataJson = json_encode($postdata);
echo "\n\npostdata json: ".$postdataJson."\n\n\n";

$bipTesURL="http://tims.turkcell.com.tr/tes/rest/spi/sendmsgserv";
$username="bu2705614779894449";
$password="bu270562f6d5476";



$ch = curl_init($bipTesURL);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,  Array("Content-Type: application/json"));
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
curl_setopt($ch, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476");
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdataJson);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
$result = curl_exec($ch);
return $result;

}

echo "</br> FINISHED: gonderilecek mesaj (bos ise degisen yok): ".$informText;



if ( $informText!=''){
	//$changeContent=$inform.$informText;
	//$logger=sendBipResponse("905322103846",$changeContent);
	//$logger=sendBipResponse("905322103846",$content);
//echo $logger;

$file="appony.log";
$log=date("Y-m-d h:i:sa")." - DAILY REPORT HAS BEEN SENT content: ".$modernContent." result: ".$resultImage;
file_put_contents($file, $log, FILE_APPEND | LOCK_EX);
}


	function sendBipImage($contentImage){
	$bipTesURL="http://tims.turkcell.com.tr/tes/rest/spi/sendmsgserv";
	$username="bu2705614779894449";
	$password="bu270562f6d5476";

	$ch = curl_init($bipTesURL);
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,  Array("Content-Type: application/json"));
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
	curl_setopt($ch, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $contentImage);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	$result = curl_exec($ch);
	return $result;
	}


?>
