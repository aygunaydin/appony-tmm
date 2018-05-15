<?php
error_reporting(E_ALL);
//header('Content-Type: application/json');
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

date_default_timezone_set('Europe/Istanbul');

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

    if ($key=="ctype"){
    $ctype=$val;
    }

    if ($key=="poll"){
    $poll=$val;
    }

    if ($key=="postback"){
    $postback=$val;
    }

    if ($key=="sendtime"){
    $sendtime=$val;
    }

}

//quick menu cevabı
if(isset($postback)){
  foreach ($postback as $key => $val) {
    if ($key=="payload"){
    $payload=$val;
    $payloadMessage=strtolower($payload);
    $keyword=strtolower($payloadMessage);
    }
  }
}


//anket cevabi
if(isset($poll)){
  foreach ($poll as $key => $val) {
    if ($key=="optionids"){
    $optionids=$val;
    $optionPoll=$optionids[0];
    }
  }
}


//$ctype='Buzz';

//client requesti kapatalim.
ob_end_clean();
header("Connection: close\r\n");
header("Content-Encoding: none\r\n");
ignore_user_abort(true); // optional
ob_start();
echo ('Request received. Will be processed soon.');
$size = ob_get_length();
header("Content-Length: $size");
ob_end_flush();     // Strange behaviour, will not work
flush();            // Unless both are called !
ob_end_clean();


fastcgi_finish_request();


echo "\nkeyword is: ".$keyword;
echo "\nmsgId is: ".$msgId;
echo "\nsender is: ".$sender;
echo "\nsendtime id is: ".$sendtime;


$file="appony.log";
$log=date("Y-m-d h:i:sa")." - REQUEST - sender: ".$sender." keyword: ".$keyword." - msgid:".$msgId.PHP_EOL;

file_put_contents($file, $log, FILE_APPEND | LOCK_EX);

$bul = array('ç', 'ğ', 'ş', 'ö', 'ü','ı');
$degistir = array('c', 'g', 's', 'o', 'u','i');
$keyword = str_replace($bul, $degistir, $keyword);


//If json_decode failed, the JSON is invalid.
if(!is_array($decoded)){
    throw new Exception('Received content contained invalid JSON!');
}
//$applist[]='yardim';
$applist[]='rbt';
$applist[]='lifebox';
$applist[]='RBT';
$applist[]='dergilik';
$applist[]='gnc';
$applist[]='fizy';
$applist[]='hesabim';
$applist[]='bip';
$applist[]='akademi';
$applist[]='snapchat';
$applist[]='spotify';
$applist[]='resmiislerim';
$applist[]='whatsapp';
$applist[]='dropbox';
$applist[]='dmags';
$applist[]='upcall';
$applist[]='platinum';
$applist[]='yaani';
$applist[]='akademi';
$applist[]='yanimda';
$applist[]='turkcelltv';
$applist[]='sim';
$applist[]='netflix';
$applist[]='digiturk';
$applist[]='blutv';

$keyword=strtolower($keyword);
$keywords=explode(" ",$keyword);
$keywordLT=$keywords[0];
$keywordRT=$keywords[1];
$appControl=in_array($keywordLT,$applist);
$wordCount=str_word_count($keyword);
echo "\nkeyword word count: ".$wordCount;
echo "\nkeywordLT: ".$keywordLT;
echo "\nkeywordRT: ".$keywordRT;
echo "\napp control result 0/1: ".$appControl;
$appCresult=" CR: ";
//if ($appControl=0) {$appCresult="istek servis ismi icermiyor";}
echo "\nAppcontrol array printR: ";
print_r($appControl)." ".$appCresult;

print_r($applist);
echo "\napplist: ";
print_r($applist);
$receiver=$sender;

//content hazirla baslar
if (isset($keywordRT) and $appControl==1) {

	echo "youm filter is activated keywordRT: ".$keywordRT;

	if ($keywordRT=="yorum") {
	$content="son 5 yorum gelecek";
											$servername='192.168.41.105';
											$username='appony';
											$password='appony1020';
											$dbname='appony';
											$conn = new mysqli($servername, $username, $password, $dbname);
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "select appid from appony.app_list a WHERE a.appname='".$keywordLT."'";


											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
											    // output data of each row
											    while($row = $result->fetch_assoc()) {
											    	$appIosID=$row["appid"];
											    	echo "\napp ios id from db:".$appIosID;
											    }

											$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';
											$fizyGet = file_get_contents($fizyUrl);
											$fizyJson=json_decode($fizyGet);
											echo "\nApp comment get url: ".$fizyUrl;

											$bul = array('ç', 'ı', 'ğ', 'ş', 'ö', 'ü');
       										$degistir = array('c', 'i', 'g', 's', 'o', 'u');
       										$y=6;

											for ($x = 5; $x >=1 ; $x--) {
												$y=$y-1;

											$commentid=$fizyJson->feed->entry[$x]->id->label;
											$author=$fizyJson->feed->entry[$x]->author->name->label;
											$comment=$fizyJson->feed->entry[$x]->content->label;
											$comment = str_replace($bul, $degistir, $comment);

											$title=$fizyJson->feed->entry[$x]->title->label;
											$ratingPlaceholder='im:rating';
											$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
											$versionPlaceholder='im:version';
											$version=$fizyJson->feed->entry[$x]->$versionPlaceholder->label;
											$content=$keywordLT." yorum ".$y;
											$content=$content."\nkullanıcı adi: ".$author;
											$content=$content."\npuani: ".$rating;
											$content=$content."\nsurum: ".$version;
											$content=$content."\nbaslik: ".$title;
											$content=$content."\nYorum: ".$comment;
											echo "\ncontent with reviews: ".$content;
											$postResult=sendBipResponse($receiver,$content);
													}
												}


	//getBipReviews($keywordLT);
	} else	if ($keywordRT=="son") {
	$content="son 5 yorum gelecek";
											$servername='192.168.41.105';
											$username='appony';
											$password='appony1020';
											$dbname='appony';
											$conn = new mysqli($servername, $username, $password, $dbname);
												if ($conn->connect_error) {
												    die("Connection failed: " . $conn->connect_error);
												}
												$sql = "select appid from appony.app_list a WHERE a.appname='".$keywordLT."'";


											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
											    // output data of each row
											    while($row = $result->fetch_assoc()) {
											    	$appIosID=$row["appid"];
											    	echo "\napp ios id from db:".$appIosID;
											    }

											$bipURL='http://itunes.apple.com/lookup?id='.$appIosID.'&country=tr';
											$bipGet= file_get_contents($bipURL);
											$bipJson= json_decode($bipGet);
											$releaseDate=$bipJson->results[0]->currentVersionReleaseDate;
											$bipRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
											$bipRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
											$currentVersion=$bipJson->results[0]->version;
											$releaseDate=substr($releaseDate, 0, 10);
											echo "\nApp comment get url: ".$bipURL;
											echo "\nApp comment release date: ".$releaseDate;
											echo "\nApp comment rating: ".$bipRating;
											echo "\nApp comment releasedata: ".$bipRaterNum;

											$bul = array('ç', 'ı', 'ğ', 'ş', 'ö', 'ü');
       										$degistir = array('c', 'i', 'g', 's', 'o', 'u');

											$content=$keywordLT." App Store guncel versiyon: ".$currentVersion;
											$content=$content."\nlansman tarihi: ".$releaseDate;
											$content=$content."\npuani: ".$bipRating;
											$content=$content."\npuanlayan kullanici sayisi: ".$bipRaterNum;
											echo "content for current version: ".$content;
											$postResult=sendBipResponse($receiver,$content);
													}



	//getBipReviews($keywordLT);
	} else {$content="Hatali istek girdin. Sorgulayabilecegin sihirli kelimeleri ogrenmek icin yardim yazip gonderebilirsin.";
			$postResult=sendBipResponse($receiver,$content);
			echo "\nYorum hata content is: ".$content;
 }

}
else {
		if ($keyword=="yardim") {

		$content="\nStore puanlarini ogrenmek icin  servisin ismini yaz gonder.\nOrnek: fizy \n\nApp store'da yayinlanan son versiyona ait bilgilere ulasmak icin servis ismi bosluk son yaz gonder\nOrnek: lifebox son \n\nApp store son 5 yoruma ulasmak icin servis ismi bosluk yorum yaz gonder.\nOrnek: bip yorum\n\nTüm servislerin puanini gorebilmek için hepsi yaz gonder. Sıralı almak için hepsi ios yada hepsi android yaz gonder.\nÖrnek: hepsi ios \n\nSorgulayabilecegin servisleri ogrenmek icin liste yaz gonder.\nOrnek: liste\n\nhttp://appony.ga";
		$postResult=sendBipResponse($receiver,$content);
		echo "\nyardim content is: ".$content;


		} elseif ($keyword=="yorum") {
		$content="Servis ismi bosluk yorum yazarak istedigin servise ait son 5 app store yorumuna ulasabilirsin. Ornek: fizy yorum \n\nSorgulayabilecegin servisler:\nfizy\nlifebox\nbip\nhesabim\ndergilik\nRBT\nupcall\nplatinum\ntty\ngnc\nakademi\nyanimda\nspotify\nwhatsapp\ndmags\nresmiislerim";
		$postResult=sendBipResponse($receiver,$content);
		echo "\nyorum content is: ".$content;


		} elseif ($keyword=="liste") {
		$content="Sorgulayabilecegin servisler:\nfizy\nlifebox\nbip\nhesabim\ndergilik\nRBT\nTurkcellTv\nupcall\nplatinum\ntty\ngnc\nakademi\nyanimda\nspotify\nwhatsapp\ndmags\nresmiislerim\nyaani\nnetflix\nsim\nblutv";
		$postResult=sendBipResponse($receiver,$content);
		echo "\nliste content is: ".$content;


		}elseif ($appControl==1){
		$content=getBipDetailsLite($keyword);
    $logoList[]='bip';
    $logoList[]='fizy';
    $logoList[]='lifebox';
    $logoList[]='dergilik';
    $logoList[]='hesabim';
    $logoList[]='platinum';
    $logoList[]='yaani';
    $logoList[]='upcall';
    $logoControl=in_array($keyword,$logoList);
        if($logoControl==1){
                            $logoUrl=getAppLogo($keyword);
                            $modernContent=getBipModernDetails($keyword,$receiver,$logoUrl);
                            $resultImage=sendBipImage($modernContent);
                            //$postResult=sendBipResponse('905322103846',$modernContent);
                          }
        else {$postResult=sendBipResponse($receiver,$content);}

		echo "\nServiceName case content is: ".$content;

		//$URLmessage="http://turkcell.ga/details.php?app=".$keyword;

		} elseif ($keywordLT=="hepsi"){
		if ($keywordRT=="android") {$con="android";} else{$con="ios";}
    $onbilgi="Talebinizi aldım, kısa süre içerisinde store puanlarını paylaşacağım.⏰";
    $resultOnBilgi=sendBipResponse($receiver,$onbilgi);
		$content=getBipAllDetails($con);
		$postResult=sendBipResponse($receiver,$content);
		echo "\nhepsi content is: ".$content;
		//$URLmessage="http://turkcell.ga/details.php?app=".$keyword;

		} elseif ($keywordLT=='Appony,'){
		$content="Hosgeldin ".$sender.". Senin icin neler yapabilecegimi gormek istersen yardim yazip gondermen yeterli.";
		$postResult=sendBipResponse($receiver,$content);
		echo "\nnull content is: ".$content;

  }elseif($ctype=='Buzz') {
    $result=sendQuickMenu($receiver);
    } else {
      if (isset($keywordLT)) {   $content="Hatali istek girdin: ".$keyword." \nSorgulayabilecegin sihirli kelimeleri ogrenmek icin yardim yazip gonderebilirsin. Aşağıdaki hızlı menüden servisleri sorgulayabilirsin.";}
      //
  		$postResult=sendBipResponse($receiver,$content);
      $result=sendQuickMenu($receiver);
  		echo "\nhata content is: ".$content;
    }

}

//content bitis
//$receiver=$sender;


//date_default_timezone_set('Europe/Istanbul');

echo "\n\nINFO - ".date('d/m/Y h:i:s', time())." - receiver is ".$receiver." and  message is:\n ".$content."\n\n";





// if ($appControl==1){
// $ImageURL=getImageUrl($keyword);
// $resultImage=sendBipImage($receiverArray,$content,$imageURL);
// } else {
//}

echo "\n\nINFO - POSTRESULT : ".date('d/m/Y h:i:s', time())." - \n".$postResult;
echo "\n\nINFO - POSTRESULT : ".date('d/m/Y h:i:s', time())." - \n".$postResult;





function sendBipResponse($receiver,$content){

//$postdataRaw ='{"txnid":"200","receiver":{"type":2, "address":"'.$receiver.'"}, "composition": {"list": [{"type":0,"message":"'.$content.'"}]}}';

$tnxid=rand(1000,9999);
$postdata['txnid']=$tnxid;
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
echo "\n\npostdata json: ".$postdataJson."\n\n\n";
$file="appony.log";
$log=date("Y-m-d h:i:sa")." - RESPONSE - receiver: ".$receiver." jsonString: ".$postdataJson." - tnxid:".$tnxid.PHP_EOL;

file_put_contents($file, $log, FILE_APPEND | LOCK_EX);



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
