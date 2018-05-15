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

											$content="Google Play Store (Android) Haftalik Ozet\n";


											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
											    // output data of each row
											    while($row = $result->fetch_assoc()) {
											    	$appIosID=$row["appid"];
											    	$appName=$row["appname"];
											    	echo "\napp ios id from db:".$appIosID;

											 $bipRating=getAndroidRating($appName);
											
											echo "</br>App Name: : ".$appName;	
											echo "</br>App comment rating: ".$bipRating;
											echo "</br>-------</br>";
											

											$content=$content."\n".$appName." ".$bipRating;

											    }

											}

											//$content=$content."\nYardim yazarak sorgulayabileceginiz bilgileri ogrenebilirsiniz. Servislere ait son 5 yoruma ulasmak icin 'servis-ismi yorum' yazip gonderebilirsiniz. Ornek: fizy yorum";

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

$logger=sendBipResponse("5322103846",$content);

echo $logger;

$file="appony.log";
$log=date("Y-m-d h:i:sa")." - DAILY REPORT HAS BEEN SENT content: ".$content." result: ".$logger; 
file_put_contents($file, $log, FILE_APPEND | LOCK_EX);


?>
