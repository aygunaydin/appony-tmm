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
												$sql = "select appid,appname from appony.app_list where isturkcell=1 and appname!='tty'";

											$content="AppStore (IOS) Gunluk Ozet\n";
											$inform="App Store (IOS) Puani degisenler: \n";
											$informText='';


											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
											    // output data of each row
											    while($row = $result->fetch_assoc()) {
											    	$appIosID=$row["appid"];
											    	$appName=$row["appname"];
											    	echo "\napp ios id from db:".$appIosID;

											$bipURL='http://itunes.apple.com/lookup?id='.$appIosID.'&country=tr';
											$bipGet= file_get_contents($bipURL);
											$bipJson= json_decode($bipGet);
											$releaseDate=$bipJson->results[0]->currentVersionReleaseDate;
											$bipLatestRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
											$bipRating=$bipJson->results[0]->averageUserRating;
											$bipLatestRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
											//$bipLatestRaterNum=nvl($bipLatestRaterNum,0);
											$bipRaterNum=$bipJson->results[0]->userRatingCount;
											$currentVersion=$bipJson->results[0]->version;
											$releaseDate=substr($releaseDate, 0, 10);
											echo "</br>App Name: : ".$appName;	
											echo "</br>App comment get url: ".$bipURL;	
											echo "</br>App comment release date: ".$releaseDate;
											echo "</br>App comment rating: ".$bipRating;
											echo "</br>App comment releasedata: ".$bipRaterNum;
											echo "</br>-------</br>";
											

											$yesterdayRating=getIOSRatingMinusOne($appName);
											 if($yesterdayRating!='' AND $bipRating!='') {

														if ($yesterdayRating!=$bipRating){	
														$informText=$informText." \n".$appName." dun: ".$yesterdayRating." bugun: ".$bipRating;
														}

												}

											$content=$content."\n".$appName." ".$bipRating." (". $bipRaterNum.") Son: ".$bipLatestRating." ( ".$bipLatestRaterNum .",".$releaseDate.")\n";

											    }

											}

											$content=$content."\nYardim yazarak sorgulayabileceginiz bilgileri ogrenebilirsiniz. Servislere ait son 5 yoruma ulasmak icin 'servisismi yorum' yazip gonderebilirsiniz. Ornek: fizy yorum";


											  echo "</br> informText:  ".$informText;
function sendBipResponse($receiver,$content){
$tnxid=rand(1000,9999);
$postdata['txnid']=$tnxid;
$receiverArray['type']=1;
//$receiverArray['address']="905322103846";
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





if ( $informText!=''){
	$changeContent=$inform.$informText;
	$logger2=sendBipResponse("905322103846",$changeContent);
	//$logger=sendBipResponse("905322103846",$content);
//echo $logger;

$file="appony.log";
$log=date("Y-m-d h:i:sa")." - DAILY REPORT HAS BEEN SENT content: ".$content." result: ".$logger2; 
file_put_contents($file, $log, FILE_APPEND | LOCK_EX);

	}	



?>
