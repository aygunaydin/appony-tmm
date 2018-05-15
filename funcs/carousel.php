<?php
$content='{
	"txnid": "Carousel",
	"receiver": {
		"type": 2,
		"address":"905322103846"

	},
	"composition": {
		"list": [{
			"type": 13,
			"tmmtype": 4,
			"carouseltmm": {
				"postbackid": "ASDF",
				"list": [{
						"title": "Sinemalarda Bu Hafta",
						"description": "Bu hafta 7 film gosterime giriyor...",
						"image": {
							"url": "http://timsac.turkcell.com.tr/scontent/p2p/19022018/09/Pe336b30c03db3493052cdede73334c1b57443c13c219e3b7d70441d99add1e0d7.png",
							"ratio": 1
						},
						"buttonlist": [{
								"type": "0",
								"name": "Detaylı Bilgi",
								"url": "http://www.imdb.com/title/tt3783958/"
							},
							{
								"type": "1",
								"name": "Bilet Al",
								"payload": "tt3783958"
							}
						]
					},
					{
						"title": "Adanada her taşın altından uyuşturucu çıktı",
						"description": "Adanada Narkotik Suçlarla Mücadele Şube Müdürlüğü ekiplerinin yaptığı operasyonda her taşın altında esrar ve hap bulundu.",
						"image": {
							"url": "http://timsac.turkcell.com.tr/scontent/p2p/19022018/09/Pe336b30c03db3493052cdede73334c1b57443c13c219e3b7d70441d99add1e0d7.png",
							"ratio": 1
						},
						"buttonlist": [{
								"type": "0",
								"name": "Detaylı Bilgi",
								"url": "http://www.milliyet.com.tr/adana-da-her-tasin-altindan-gundem-2450615/"
							},
							{
								"type": "1",
								"name": "Diğer resimler",
								"payload": "resimler"
							}
						]
					},
					{
						"title": "Kıdem tazminatında formül belli oldu",
						"description": "Kıdem tazminatında işçi ve işvereni rahatlatacak formüller belli oldu. Yeni sistemde her çalışan kıdemini alabilecek",
						"image": {
							"url": "http://timsac.turkcell.com.tr/scontent/p2p/19022018/09/Pe336b30c03db3493052cdede73334c1b57443c13c219e3b7d70441d99add1e0d7.png",
							"ratio": 1
						}
					}
				]
			}
		}]
	}
}';

$result=sendBipResponse('905322103846',$content);

echo $content."\n\n".$result;

function sendBipResponse($receiver,$content){

//$postdataRaw ='{"txnid":"200","receiver":{"type":2, "address":"'.$receiver.'"}, "composition": {"list": [{"type":0,"message":"'.$content.'"}]}}';
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
curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
$result = curl_exec($ch);

//print_r($result);
//echo "statuscode: ".$status_code;
//echo $result;
return $result;

}

?>
