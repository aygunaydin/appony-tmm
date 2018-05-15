<?php
error_reporting(E_ALL);
//header('Content-Type: application/json');
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");


if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}
 
//Make sure that the content type of the POST request has been set to application/json
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}
 
//Receive the RAW post data.
$contentFromBip = trim(file_get_contents("php://input"));
 
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($contentFromBip, true);
//print_r($decoded);
 


foreach ($decoded as $key => $val) {
    //print "$key = $val\n";
    
    if ($key=="content"){
    $keyword=$val;
    }

    if ($key=="msgid"){
    $msgId=$val;
    }


    if ($key=="sender"){
    $sender=$val;
    }


    if ($key=="sendtime"){
    $sendtime=$val;
    }

}


echo "\nkeyword is: ".$keyword;
echo "\nmsgId is: ".$msgId;
echo "\nsender is: ".$sender;
echo "\nsendtime id is: ".$sendtime;



//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
 
$applist[]='yardim';
$applist[]='rbt';
$applist[]='lifebox';
$applist[]='RBT';
$applist[]='dergilik';
$applist[]='gnc';
$applist[]='fizy';
$applist[]='hesabim';
$applist[]='bip';
$applist[]='akademi';
$applist[]='spotify';
$applist[]='tty';
$applist[]='whatsapp';
$applist[]='dropbox';
$applist[]='dmags';
$applist[]='upcall';
$applist[]='platinum';
$applist[]='akademi';
$applist[]='yanimda';



$keywordLT=ltrim($keyword);
$keyword=strtolower($keyword);
$appControl=in_array($keyword,$applist);
echo "\napp control result: ";
print_r($appControl);
echo "\napplist: ";
print_r($applist);


if ($keyword=="yardim") {

$content="\nsorgulayabileceginiz servisler\n
			fizy\n
			lifebox\n
			bip\n
			hesabim\n
			dergilik\n
			RBT\n
			upcall\n
			platinum\n
			tty\n
			gnc\n
			akademi\n
			yanimda\n
			spotify\n
			whatsapp\n
			dmags\n
			";
} elseif ($appControl==1){

$content=getBipDetails($keyword);
$URLmessage="http://turkcell.ga/details.php?app=".$keyword;

} elseif ($keywordLT=='Appony,' or $keyword==''){
	$content="Hosgeldin ".$sender;


}else { $content="hatali istek girdin. sorgulayabilecegin sihirli kelimeleri ogrenmek icin yardim yazip gonderebilirsin"; }

$receiver=$sender;


date_default_timezone_set('Europe/Istanbul');

echo "\n\nINFO - ".date('d/m/Y h:i:s', time())." - receiver is ".$receiver." and  message is: ".$content."\n\n"; 


 

if ($appControl==1){
$imageURL=getImage100Url($keyword);
echo "imageURL: ".$imageURL;
$postResult=sendBipImage($receiver,$content,$imageURL);
 } else {
$postResult=sendBipResponse($receiver,$content);
}

echo "\n\nINFO - POSTRESULT : ".date('d/m/Y h:i:s', time())." - \n".$postResult; 



function sendBipResponse($receiver,$content){

//$postdataRaw ='{"txnid":"200","receiver":{"type":2, "address":"'.$receiver.'"}, "composition": {"list": [{"type":0,"message":"'.$content.'"}]}}';

$postdata['txnid']=rand(200,10000);
$receiverArray['type']=2;
$receiverArray['address']=$receiver;
$postdata['receiver']=$receiverArray;
$listArray0['type']=0;
$listArray0['message']=$content;
$listArray[0]=$listArray0;
$composition0['list']=$listArray;
$postdata['composition']=$composition0;
//echo "\npostdata array: ".$postdata;
//$postdataJson = "json=".json_encode($postdata)."&";
$postdataJson = json_encode($postdata);
echo "\npostdata json: ".$postdataJson;

// $contentArray=array(
//    'txnid' => '200',
//    'receiver' => array( 'type' => 2,'address' => $receiver),
//    'composition' => array('list' => 0 array (0 => array(
//          				'type' => 0, 'message' => $content ))));
//$postdata=json_encode($contentArray);
//$postData=json_encode($bipRespArray);
$bipTesURL="http://tims.turkcell.com.tr/tes/rest/spi/sendmsgserv";
$username="bu2705614779894449";
$password="bu270562f6d5476";
  
// $curl = curl_init(); 
// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
// curl_setopt($curl, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476"); 
// curl_setopt($curl, CURLOPT_POST, true); 
// curl_setopt($curl, CURLOPT_POSTFIELDS, $postdataJson); 
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
// curl_setopt($curl, CURLOPT_URL, $bipTesURL); 
// //curl_setopt($curl, CURLOPT_HTTPHEADER,  array("Content-Type : application/json","Accept: application/json")); 
// $result=curl_exec($curl);
// curl_close($curl); 

$ch = curl_init($bipTesURL);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,  Array("Content-Type: application/json")); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
curl_setopt($ch, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdataJson);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POST, true);
$result = curl_exec($ch);

//print_r($result); 
//echo "statuscode: ".$status_code;
//echo $result;
return $result;

}



function sendBipImage($receiver,$content,$imageURL){

//$postdataRaw ='{"txnid":"200","receiver":{"type":2, "address":"'.$receiver.'"}, "composition": {"list": [{"type":0,"message":"'.$content.'"}]}}';

$postdata['txnid']=rand(200,10000);
$receiverArray['type']=2;
$receiverArray['address']=$receiver;
$postdata['receiver']=$receiverArray;
// $listArray0['type']=0;
// $listArray0['message']=$content;
// $listArray[0]=$listArray0;
$listArray1['type']=2;
$listArray1['message']=$imageURL;
$listArray1['size']=133844;
//$listArray1['ratio']=0.6;
$listArray[0]=$listArray1;
$composition0['list']=$listArray;
$postdata['composition']=$composition0;
//echo "\npostdata array: ".$postdata;
//$postdataJson = "json=".json_encode($postdata)."&";
$postdataJson = json_encode($postdata);
$trueData=str_replace("\\/", "/", $postdataJson);
echo "\npostdata with image json: ".$trueData;

// $contentArray=array(
//    'txnid' => '200',
//    'receiver' => array( 'type' => 2,'address' => $receiver),
//    'composition' => array('list' => 0 array (0 => array(
//          				'type' => 0, 'message' => $content ))));
//$postdata=json_encode($contentArray);
//$postData=json_encode($bipRespArray);
$bipTesURL="http://tims.turkcell.com.tr/tes/rest/spi/sendmsgserv";
$username="bu2705614779894449";
$password="bu270562f6d5476";
  
// $curl = curl_init(); 
// curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
// curl_setopt($curl, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476"); 
// curl_setopt($curl, CURLOPT_POST, true); 
// curl_setopt($curl, CURLOPT_POSTFIELDS, $postdataJson); 
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
// curl_setopt($curl, CURLOPT_URL, $bipTesURL); 
// //curl_setopt($curl, CURLOPT_HTTPHEADER,  array("Content-Type : application/json","Accept: application/json")); 
// $result=curl_exec($curl);
// curl_close($curl); 

$ch = curl_init($bipTesURL);
//curl_setopt($ch, CURLOPT_POST, true);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER,  Array("Content-Type: application/json")); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
curl_setopt($ch, CURLOPT_USERPWD, "bu2705614779894449:bu270562f6d5476"); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $trueData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_POST, true);
$result = curl_exec($ch);

//print_r($result); 
//echo "statuscode: ".$status_code;
//echo $result;
return $result;

}




?>