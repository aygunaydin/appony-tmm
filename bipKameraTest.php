<?php
error_reporting(E_ALL);
//header('Content-Type: application/json');
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

date_default_timezone_set('Europe/Istanbul');

echo "STARTED</br>";

$content='http://www.bip.ai/wp-content/uploads/2016/05/hackathonlast.png';


function sendBipResponse($receiver,$content){

$tnxid=rand(1000,9999);
$postdata['txnid']=$tnxid;
$receiverArray['type']=2;
$receiverArray['address']="905322103846";
$postdata['receiver']=$receiverArray;
$listArray0['type']=2;
$listArray0['message']=$content;
$listArray0['size']=133844;
$listArray0['ratio']='0,6';
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




	$logger=sendBipResponse("905322103846",$content);




?>
