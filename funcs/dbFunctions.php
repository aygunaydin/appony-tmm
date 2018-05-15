<?php
require("config.inc.php");
require("Database.class.php");

function getAppLogo($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}
			$sql = "select * from appony.app_logo a WHERE a.appname='".$appName."';";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$url=$row["url"];
		    }

		    }

	$conn->close();
	//echo $trendData.'</br>';
	//echo $raterData.'</br>';
	if (!isset($url)){$url='http://timsac.turkcell.com.tr/scontent/p2p/08032018/14/Pf96e54a2f777cdaa283a28037c59aeb6427793cb873f7c40702f3f71528c212e5.jpg';}

	return $url;

}

function createRatingRecord($appID,$raterCount,$rating,$bipCurrentRating,$bipCurrentRaterNum,$version,$CurrentVersionReleaseDate)
{
echo "</br>INFO-DB IOS Values-appid: ".$appID;
echo "</br>INFO-DB IOS Values-rater num: ".$raterCount;
echo "</br>INFO-DB IOS Values-rating: ".$rating;;
echo "</br>INFO-DB IOS Values-current-rater num: ".$bipCurrentRaterNum;
echo "</br>INFO-DB IOS Values-current-rating: ".$bipCurrentRating;;
echo "</br>INFO-DB IOS Values-Version: ".$version;;
echo "</br>INFO-DB IOS Values-Version release date: ".$CurrentVersionReleaseDate;

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO appony.`app_rating_history` (`app_id`, `rater_num`, `rating`, `current_version_rater_num`, `current_version`,`current_version_id`,`current_version_release_date`) VALUES ('".$appID."', '".$raterCount."', '".$rating."','".$bipCurrentRaterNum."', '".$bipCurrentRating."', '".$version."', '".$CurrentVersionReleaseDate."');";

	if ($conn->query($sql) === TRUE) {
    	echo "</br>INFO: New IOS record created successfully: ".$appID;
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();
}

function createAndroidRatingRecord($appname,$raterCount,$rating)
{
echo "</br>INFO-DB Android Values-appname: ".$appname;
echo "</br>INFO-DB Android Values-rater num: ".$raterCount;
echo "</br>INFO-DB Android Values-rating: ".$rating;;
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO appony.`android_app_rating_history` (`app_name`, `rater_num`, `rating`) VALUES ('".$appname."', '".$raterCount."', '".$rating."');";

	if ($rating>0) {
		if ($conn->query($sql) === TRUE) {
	    	echo "</br>INFO: New Android record created successfully: ".$appname;
		} else {
	    	echo "Error: " . $sql . "<br>" . $conn->error;
		}
	} else {
	    	echo "Error: Android puani alinamadi";
	}



	$conn->close();
}



function getBipAllDetails($con){

//if ($conn=="ios") {$order="rating";} else {$wrapper="apps-wrapper";}
$sqlIOS="select appname, arl.rating from appony.app_list app,
(select a.app_id, a.rating from appony.app_rating_history a,
(select app_id, rating, max(rate_date) dater from appony.app_rating_history group by app_id) b
where a.app_id=b.app_id
and a.rate_date = b.dater
) arl
where app.appid=arl.app_id
order by app.isTurkcell desc, arl.rating desc";

$sqlAndroid="select appname, arl.rating from appony.app_list app,
(select a.app_name, a.rating from appony.android_app_rating_history a,
(select app_name, rating, max(rate_date) dater from appony.android_app_rating_history group by app_name) b
where a.app_name=b.app_name
and a.rate_date = b.dater
) arl
where app.appname =arl.app_name
order by app.isTurkcell desc, arl.rating desc
";


if ($con=="ios") {$sqlOrder=$sqlIOS;} else {$sqlOrder=$sqlAndroid;}

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = $sqlOrder;
$result = $conn->query($sql);
$i=0;
$content="AppStore - GooglePlay - Servis";

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$appName=$row["appname"];
	    	$i++;

				$iosRating=getIOSBipRating($appName);
				$iosRaterNum=getIOSRaterNum($appName);
				$AndroidRating=getAndroidRating($appName);
				$AndroidRaterNum=getAndroidRaterNum($appName);
				$ImageURL=getImageUrl($appName);
				$contentInc=$iosRating." - ".$AndroidRating." - ".$appName;
				$content=$content."\n".$contentInc;
				//$content=$content."http://turkcell.ga/details.php?app=".$appName."\n";
				echo "\nGet All App service list: ".$i." - ".$contentInc;

			    }

			    $conn->close();

	}
				return $content;


}

function createApp($appID,$appName,$androidName)
{
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO appony.`app_list` (`appid`, `appname`,`android_name`) VALUES ('".$appID."', '".$appName."','".$androidName."');";

	if ($conn->query($sql) === TRUE) {
    	echo "</br>INFO: New record created successfully</br>";
	} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();

}

function getAndroidRating($appname){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_name, format(rating,2) rating, rater_num from appony.android_app_rating_history a WHERE a.app_name='".$appname."' and rate_date in (
select max(rate_date) from appony.android_app_rating_history where app_name='".$appname."');
";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }

	$conn->close();
	return $rating;
}

}


function getAndroidRatingMinusOne($appname){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_name, format(rating,2) rating, rater_num from appony.android_app_rating_history a WHERE a.app_name='".$appname."' and date(rate_date) in (
select date(max(rate_date)) from appony.android_app_rating_history where app_name='".$appname."')";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }

	$conn->close();
	return $rating;
}

}

function getAndroidRatingMinusTwo($appname){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_name, format(rating,2) rating, rater_num from appony.android_app_rating_history a WHERE a.app_name='".$appname."' and date(rate_date) in (
select date(max(rate_date))-1 from appony.android_app_rating_history where app_name='".$appname."')";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }

	$conn->close();
	return $rating;
}

}




function getAndroidBipRating($appname){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_name, format(rating,4) rating, rater_num from appony.android_app_rating_history a WHERE a.app_name='".$appname."' and rate_date in (
select max(rate_date) from appony.android_app_rating_history where app_name='".$appname."');
";



$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }

	$conn->close();
	return $rating;
}
}



function getAndroidRaterNum($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_name, rating, rater_num from appony.android_app_rating_history a WHERE a.app_name='".$appname."' and rate_date in (
select max(rate_date) from appony.android_app_rating_history where app_name='".$appname."');
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }

	$conn->close();
	return $raterCount;
}
}


function getIOSRaterNum($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

$iosID=getIosID($appname);

$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_id, rating, rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and rate_date in (
select max(rate_date) from appony.app_rating_history where app_id='".$iosID."')";

//echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }
	$conn->close();
	return $raterCount;
}
}

function getImageUrl($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appname."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

$fizyUrl='http://itunes.apple.com/lookup?id='.$appIosID.'&country=tr';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
$fizyImageURL=$fizyJson->results[0]->artworkUrl512;

	$conn->close();
	return $fizyImageURL;
}
}


function getImage100Url($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appname."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

$fizyUrl='http://itunes.apple.com/lookup?id='.$appIosID.'&country=tr';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
$fizyImageURL=$fizyJson->results[0]->artworkUrl100;

	$conn->close();
	return $fizyImageURL;
}
}



function getImageUrl120($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appname."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

$fizyUrl='http://itunes.apple.com/lookup?id='.$appIosID.'&country=tr';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
$fizyImageURL=$fizyJson->results[0]->artworkUrl100;

	$conn->close();
	return $fizyImageURL;
}
}




function getIOSRating($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

$iosID=getIosID($appname);

$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_id, rating, rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and rate_date in (
select max(rate_date) from appony.app_rating_history where app_id='".$iosID."')";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }
	$conn->close();
	return $rating;
}
}

function getIOSRatingMinusOne($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

$iosID=getIosID($appname);

$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_id, rating, rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and date(rate_date) in (
select date(max(rate_date))-1 from appony.app_rating_history where app_id='".$iosID."')";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }
	$conn->close();
	return $rating;
}
}

function getIOSBipRating($appname){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

$iosID=getIosID($appname);

$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select app_id, format(rating,2) rating , rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and rate_date in (
select max(rate_date) from appony.app_rating_history where app_id='".$iosID."')";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$raterCount=$row["rater_num"];
    	$rating=$row["rating"];
    }
	$conn->close();
	return $rating;
}
}




function getAndroidTRend($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select format(rating,5) rating,rater_num, format(date_format(rate_date,'%m'),'0') ay, format(date_format(rate_date,'%d'),'0') gun,
			date_format(rate_date,'%Y') yil from appony.android_app_rating_history a WHERE a.app_name='".$appName."' order by rate_date asc;";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    if ($appName=="upcall"){
    $trendData="{ x: new Date(2016,9,11), y: 0 }";
    $raterData="{ x: new Date(2016,9,11), y: 0 }";
    }else {
    $trendData="{ x: new Date(2016,7,2), y: 0 }";
    $raterData="{ x: new Date(2016,7,2), y: 0 }";
    }


//{ x: new Date(2010,1,3), y: 510 },\n";
    while($row = $result->fetch_assoc()) {
    	$ay=$row["ay"]-1;
    	$trendData=$trendData.",{ x: new Date(".$row["yil"].",".$ay.",".$row["gun"]."), y: ".$row["rating"]."}";
    	//$raterData=$raterData."</br>,{ x: '".$row["dater"]."', y: ".$row["rater_num"]."}";
    }

	$conn->close();
	//echo $trendData.'</br>';
	//echo $raterData.'</br>';
	return $trendData;

}

}

function getIosID($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}
			$sql = "select appid from appony.app_list a WHERE a.appname='".$appName."';";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$appIosID=$row["appid"];
		    }

		    }

	$conn->close();
	//echo $trendData.'</br>';
	//echo $raterData.'</br>';
	return $appIosID;

}


function getIosTRend($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);

$appIosID=getIosID($appName);

	//echo "</br>apple name: ".$appName;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	//echo "</br>apple ID: ".$appIosID;
	$sql = "select rating,rater_num, format(date_format(rate_date,'%m'),'0') ay, format(date_format(rate_date,'%d'),'0') gun,
			date_format(rate_date,'%Y') yil from appony.app_rating_history a WHERE a.app_id='".$appIosID."' order by rate_date asc;";
//echo "</br> SQL: ".$sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    if ($appName=="upcall"){
    $trendData="{ x: new Date(2016,9,11), y: 0 }";
    $raterData="{ x: new Date(2016,9,11), y: 0 }";
    }else {
    $trendData="{ x: new Date(2016,7,2), y: 0 }";
    $raterData="{ x: new Date(2016,7,2), y: 0 }";
    }


//{ x: new Date(2010,1,3), y: 510 },\n";
    while($row = $result->fetch_assoc()) {
    	$ay=$row["ay"]-1;
    	$trendData=$trendData.",{ x: new Date(".$row["yil"].",".$ay.",".$row["gun"]."), y: ".$row["rating"]."}";
    	//$raterData=$raterData."</br>,{ x: '".$row["dater"]."', y: ".$row["rater_num"]."}";
    }

	$conn->close();
	//echo $trendData.'</br>';
	//echo $raterData.'</br>';
	return $trendData;

}

}



function getBoxDetails($appName){
$iosRating=getIOSRating($appName);
$iosRaterNum=getIOSRaterNum($appName);
$AndroidRating=getAndroidRating($appName);
$AndroidRaterNum=getAndroidRaterNum($appName);
$ImageURL=getImageUrl($appName);
echo "									<section class=\"box feature\">\n";
echo "										<a href=\"details.php?app=".$appName."\" class=\"image featured\" alt=\"Detayları görmek için logoya tıklayın...\"><img src=\"".$ImageURL."\" alt=\"Detayları görmek için logoya tıklayın...\" /></a>\n";
echo "										<div class=\"inner\">\n";
echo "											<header>\n";
echo "												<center><h2>".$appName."</h2></center>\n";
echo "												<p><p><center><a href=\"details.php?app=".$appName."\" class=\"button big icon fa-apple\">".$iosRating."</a>
																			<a href=\"details.php?app=".$appName."\" class=\"button big icon fa-android\">".$AndroidRating."</a>
																			</p></center> </p>\n";
echo "											</header>\n";
echo "											<p>".$appName." Apple Store'da <b>".$iosRaterNum." </b> , Google Play'de  <b>".$AndroidRaterNum."</b> kullanıcı tarafından değerlendirilmiştir </p>\n";
echo "										</div>\n";
echo "									</section>\n";
}


function getDJScore($appName){
$DJScore='4.1';
//$ImageURL=getImageUrl($appName);
echo "									<section class=\"box feature\">\n";
echo "										<div class=\"inner\">\n";
echo "											<header>\n";
echo "												<center><h2>DJ Score</h2></center>\n";
echo "												<p><center><a href=\"#\" class=\"button big icon fa-heart\">".$DJScore."</a></center></p>\n";
echo "											</header>\n";
echo "										</div>\n";
echo "									</section>\n";
}


function getDJios($appName){
$iosRating=round(getIOSRating($appName),2);
//$ImageURL=getImageUrl($appName);
echo "									<section class=\"box feature\">\n";
echo "										<div class=\"inner\">\n";
echo "											<header>\n";
echo "												<center><h2>App Store</h2></center>\n";
echo "												<p><center><a href=\"#\" class=\"button big icon fa-apple\">".$iosRating."</a></center></p>\n";
echo "											</header>\n";
echo "										</div>\n";
echo "									</section>\n";
}

function getDJandroid($appName){
$AndroidRating=round(getAndroidBipRating($appName),2);
//$ImageURL=getImageUrl($appName);
echo "									<section class=\"box feature\">\n";
echo "										<div class=\"inner\">\n";
echo "											<header>\n";
echo "												<center><h2>Google Play</h2></center>\n";
echo "												<p><center><a href=\"#\" class=\"button big icon fa-android\">".$AndroidRating."</a></center></p>\n";
echo "											</header>\n";
echo "										</div>\n";
echo "									</section>\n";
}

function getDJincident($appName){
$activeIncident='15';
//$ImageURL=getImageUrl($appName);
echo "									<section class=\"box feature\">\n";
echo "										<div class=\"inner\">\n";
echo "											<header>\n";
echo "												<center><h2>Açık Incident</h2></center>\n";
echo "												<p><center><a href=\"#\" class=\"button big icon fa-bullseye\">".$activeIncident."</a></center></p>\n";
echo "											</header>\n";
echo "										</div>\n";
echo "									</section>\n";
}



function getBipDetails($appName){
$iosRating=getIOSRating($appName);
$iosRaterNum=getIOSRaterNum($appName);
$AndroidRating=getAndroidBipRating($appName);
$AndroidRaterNum=getAndroidRaterNum($appName);
$ImageURL=getImageUrl($appName);
$content="";
$content=$content.$appName;
$content=$content."\n\nApp Store puani: ".$iosRating;
$content=$content."\nApp Store oy sayisi: ".$iosRaterNum;
$content=$content."\n\nGoogle Play puani: ".$AndroidRating;
$content=$content."\nGoogle Play oy sayisi: ".$AndroidRaterNum;;
//$content=$content."http://turkcell.ga/details.php?app=".$appName."\n";
return $content;
}

function getBipModernDetails($appName,$msisdn,$logoUrl){
	$sqlAnd="select rating,rater_num from appony.android_app_rating_history andr
					where  andr.app_name='".$appName."' and DATE(andr.rate_date)
					in (select max(DATE(rate_date)) from appony.android_app_rating_history where app_name='".$appName."' )";

	$iosID=getIosID($appName);

	$sqlIOS="select app_id, rating, rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and rate_date in (
	select max(rate_date) from appony.app_rating_history where app_id='".$iosID."')";

	echo '<hr/> SQL IOS: '.$sqlIOS;
	echo '<hr/> SQL AND: '.$sqlAnd;
	echo '<hr/> RESULT:</br';


	//$content=$appname."\n";
	$content="";

					$servername='192.168.41.105';
					$username='appony';
					$password='appony1020';
					$dbname='appony';
					$conn = new mysqli($servername, $username, $password, $dbname);


							if ($conn->connect_error) {
								    die("Connection failed: " . $conn->connect_error);
								}


								$result2 = $conn->query($sqlIOS);

								if ($result2->num_rows > 0) {
										// output data of each row
										while($row2 = $result2->fetch_assoc()) {
											$iosRating=$row2["rating"];
											$iosRaterNum=$row2["rater_num"];
										}
																				//	echo '<hr/> SQL AND: '.$content;
										$content=$content.'IOS puanı: '.$iosRating;
																				//	echo '<hr/> SQL AND: '.$content;
										$content=$content.' | oy sayısı: '.$iosRaterNum;
																				//	echo '<hr/> SQL AND: '.$content;
										}


										$resultAnd = $conn->query($sqlAnd);

										if ($resultAnd->num_rows > 0) {
										    // output data of each row
										    while($rowAnd = $resultAnd->fetch_assoc()) {
										    	$AndroidRating=$rowAnd["rating"];
													$AndroidRaterNum=$rowAnd["rater_num"];
										    }
										$content=$content.'\nAndroid puani: '.$AndroidRating;
										$content=$content.' | oy sayisi: '.$AndroidRaterNum;
												}

$modernContent='{
   "txnid":"9999",
   "receiver":{
      "type":2,
      "address": "'.$msisdn.'"
   },
   "composition":{
      "list":[
         {
            "type":13,
            "tmmtype":0,
            "singletmm":{
               "title":"'.$appName.' Store Puanları",
               "description":"'.$content.'",
               "image":{
                  "url": "'.$logoUrl.'",
                  "ratio": 1,
                  "button": {
                    "name" : "Detaylar için Tıkla",
                    "url" : "http://appony.ga/details.php?app='.$appName.'"
                  }
               }
            }
         }
      ]
   }
}';
//$content=$content."http://turkcell.ga/details.php?app=".$appName."\n";
return $modernContent;
}





function getBipDetailsLite($appname){
$sqlAnd="select rating,rater_num from appony.android_app_rating_history andr
				where  andr.app_name='".$appname."' and DATE(andr.rate_date)
				in (select max(DATE(rate_date)) from appony.android_app_rating_history where app_name='".$appname."' )";

$iosID=getIosID($appname);

$sqlIOS="select app_id, rating, rater_num from appony.app_rating_history a WHERE a.app_id='".$iosID."' and rate_date in (
select max(rate_date) from appony.app_rating_history where app_id='".$iosID."')";

echo '<hr/> SQL IOS: '.$sqlIOS;
echo '<hr/> SQL AND: '.$sqlAnd;
echo '<hr/> RESULT:</br';


$content=$appname."\n";

				$servername='192.168.41.105';
				$username='appony';
				$password='appony1020';
				$dbname='appony';
				$conn = new mysqli($servername, $username, $password, $dbname);


						if ($conn->connect_error) {
							    die("Connection failed: " . $conn->connect_error);
							}


							$result2 = $conn->query($sqlIOS);

							if ($result2->num_rows > 0) {
									// output data of each row
									while($row2 = $result2->fetch_assoc()) {
										$iosRating=$row2["rating"];
										$iosRaterNum=$row2["rater_num"];

									}
																				echo '<hr/> SQL AND: '.$content;
									$content=$content."\n\nApp Store puani: ".$iosRating;
																				echo '<hr/> SQL AND: '.$content;
									$content=$content."\nApp Store oy sayisi: ".$iosRaterNum;
																				echo '<hr/> SQL AND: '.$content;
									}

									$result = $conn->query($sqlAnd);

									if ($result->num_rows > 0) {
									    // output data of each row
									    while($row = $result->fetch_assoc()) {
									    	$AndroidRating=$row["rating"];
												$AndroidRaterNum=$row["rater_num"];

									    }
											echo '<hr/> SQL AND: '.$content;

											$content=$content."\n\nGoogle Play puani: ".$AndroidRating;
																						echo '<hr/> SQL AND: '.$content;
											$content=$content."\nGoogle Play oy sayisi: ".$AndroidRaterNum;
											echo '<hr/> SQL AND: '.$content;
											}




					$conn->close();
					//echo $trendData.'</br>';
					//echo $raterData.'</br>';
					return $content;

}



function getBoxDetailsMin($appName){
$iosRating=getIOSRating($appName);
$iosRaterNum=getIOSRaterNum($appName);
$AndroidRating=getAndroidRating($appName);
$AndroidRaterNum=getAndroidRaterNum($appName);
$ImageURL=getImageUrl($appName);


echo "												<p><p><center><a class=\"button3 big icon fa-apple\">".$iosRating."</a>
																			<a class=\"button3 big icon fa-android\">".$AndroidRating."</a>
																			</p></center> </p>\n";
echo "											<p>".$appName." Apple Store'da <b>".$iosRaterNum." </b> , Google Play'de  <b>".$AndroidRaterNum."</b> kullanıcı tarafından değerlendirilmiştir </p>\n";

}



function getIOSlatestRating($appName){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT appid,appname FROM app_list where appname='".$appName."'";
$result = $conn->query($sql);

//echo 'sql: '.$sql;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


//echo "INFO: started";
$bipURL='http://itunes.apple.com/lookup?id='.$row["appid"].'&country=tr';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$bipCurrentRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
$appID=$bipJson->results[0]->trackId;
//echo "</br>INFO-appID: ".$appID;
//echo "</br>INFO-appname: ".$row["appname"];
//echo "</br>INFO-Rating: ".$bipRating;
//echo "</br>INFO-RaterNumber: ".$bipRaterNum;
//$return=createRatingRecord($appID,$bipRaterNum,$bipRating);
//echo "</br>INFO: completed ".$return;
//echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";

    }
} else {
    //echo "0 results";
}

return $bipCurrentRating;

}


function getIOSlatestRelease($appName){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT appid,appname FROM app_list where appname='".$appName."'";
$result = $conn->query($sql);

//echo 'sql: '.$sql;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


//echo "INFO: started";
$bipURL='http://itunes.apple.com/lookup?id='.$row["appid"].'&country=tr';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$CurrentVersionReleaseDate=$bipJson->results[0]->currentVersionReleaseDate;
$appID=$bipJson->results[0]->trackId;
//echo "</br>INFO-appID: ".$appID;
//echo "</br>INFO-appname: ".$row["appname"];
//echo "</br>INFO-Rating: ".$bipRating;
//echo "</br>INFO-RaterNumber: ".$bipRaterNum;
//$return=createRatingRecord($appID,$bipRaterNum,$bipRating);
//echo "</br>INFO: completed ".$return;
//echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";

    }
} else {
    //echo "0 results";
}

return $CurrentVersionReleaseDate;

}



function getIOSlatestRaterNum($appName){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT appid,appname FROM app_list where appname='".$appName."'";
$result = $conn->query($sql);

//echo 'sql: '.$sql;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


//echo "INFO: started";
$bipURL='http://itunes.apple.com/lookup?id='.$row["appid"].'&country=tr';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$bipCurrentRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
$appID=$bipJson->results[0]->trackId;
//echo "</br>INFO-appID: ".$appID;
//echo "</br>INFO-appname: ".$row["appname"];
//echo "</br>INFO-Rating: ".$bipRating;
//echo "</br>INFO-RaterNumber: ".$bipRaterNum;
//$return=createRatingRecord($appID,$bipRaterNum,$bipRating);
//echo "</br>INFO: completed ".$return;
//echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";

    }
} else {
    //echo "0 results";
}

return $bipCurrentRaterNum;

}


function getIOSlatestVersion($appName){

$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT appid,appname FROM app_list where appname='".$appName."'";
$result = $conn->query($sql);

//echo 'sql: '.$sql;

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


//echo "INFO: started";
$bipURL='http://itunes.apple.com/lookup?id='.$row["appid"].'&country=tr';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$bipCurrentRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
$version=$bipJson->results[0]->version;
$appID=$bipJson->results[0]->trackId;
//echo "</br>INFO-appID: ".$appID;
//echo "</br>INFO-appname: ".$row["appname"];
//echo "</br>INFO-Rating: ".$bipRating;
//echo "</br>INFO-RaterNumber: ".$bipRaterNum;
//$return=createRatingRecord($appID,$bipRaterNum,$bipRating);
//echo "</br>INFO: completed ".$return;
//echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";

    }
} else {
    //echo "0 results";
}

return $version;

}



function getBoxDetailsLastMin($appName){
$iosRating=getIOSlatestRating($appName);
if (is_null($iosRating)==TRUE){ $iosRating='n/a';}
$iosRaterNum=getIOSlatestRaterNum($appName);
if (is_null($iosRaterNum)==TRUE){ $iosRaterNum=0;}
$version=getIOSlatestVersion($appName);
$releaseDate=getIOSlatestRelease($appName);
//$AndroidRating=getAndroidRating($appName);
//$AndroidRaterNum=getAndroidRaterNum($appName);
//$ImageURL=getImageUrl($appName);


echo "												<p><p><center><a class=\"button2 big icon fa-apple\">".$iosRating."</a>";
//																			<a class=\"button3 big icon fa-android\">".$AndroidRating."</a>
echo "																			</p></center> </p>\n";
echo "											<p>".$appName." <b>".$version."</b>  sürümü <b>".$releaseDate."</b> tarihinde yayınlanmıştır. Son sürüm Apple Store'da <b>".$iosRaterNum." </b> kişi tarafından değerlendirilmiştir.</p>\n";

}


function getBoxDetailsMin2($appName){
$iosRating=getIOSRating($appName);
$iosRaterNum=getIOSRaterNum($appName);
$AndroidRating=getAndroidRating($appName);
$AndroidRaterNum=getAndroidRaterNum($appName);
$ImageURL=getImageUrl($appName);

echo "												<p><p><center><a class=\"button2 big icon fa-apple\">".$iosRating."</a>
																			<a class=\"button2 big icon fa-android\">".$AndroidRating."</a>
																			</p></center> </p>\n";
echo "											<p>".$appName." Apple Store'da <b>".$iosRaterNum." </b> , Google Play'de  <b>".$AndroidRaterNum."</b> kullanıcı tarafından değerlendirilmiştir </p>\n";

}



function getIosAllTrend(){

$fizyTrend=getIosTRend('fizy');
$bipTrend=getIosTRend('bip');
$depoTrend=getIosTRend('lifebox');
echo "  <script type=\"text/javascript\">\n";
echo "  window.onload = function () {\n";
echo "      var chartIOS = new CanvasJS.Chart(\"chartContainerIOS\",\n";
echo "      {\n";
echo "\n";
echo "          title:{\n";
echo "              text: \"IOS App Store Rating Trend\",\n";
echo "              fontSize: 30\n";
echo "          },\n";
echo "                        animationEnabled: true,\n";
echo "          axisX:{\n";
echo "\n";
echo "              gridColor: \"Yellow\",\n";
echo "              tickColor: \"silver\",\n";
echo "              valueFormatString: \"DD/MMM\"\n";
echo "\n";
echo "          },                        \n";
echo "                        toolTip:{\n";
echo "                          shared:true\n";
echo "                        },\n";
echo "          theme: \"theme1\",\n";
echo "          axisY: {\n";
echo "              gridColor: \"Silver\",\n";
echo "              tickColor: \"silver\"\n";
echo "          },\n";
echo "          legend:{\n";
echo "              verticalAlign: \"center\",\n";
echo "              horizontalAlign: \"right\"\n";
echo "          },\n";
echo "          data: [\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 2,\n";
echo "              name: \"Fizy\",\n";
echo "              markerType: \"square\",\n";
echo "              color: \"#F08080\",\n";
echo "              dataPoints: [\n";
echo $fizyTrend;
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"Bip\",\n";
echo "              color: \"#20B2AA\",\n";
echo "              markerType: \"triangle\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $bipTrend;
//  echo "              { x: new Date(2016,08,02), y: 510 },\n";
//  echo "              { x: new Date(2016,08,03), y: 560 },\n";
//  echo "              { x: new Date(2016,08,04), y: 540 },\n";
// echo "               { x: new Date(2016,08,05), y: 558 }\n";
echo "              ]\n";
echo "          },\n";
echo "          {           \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"lifebox\",\n";
echo "              color: \"#00B2FF\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $depoTrend;
//  echo "              { x: new Date(2016,08,02), y: 410 },\n";
//  echo "              { x: new Date(2016,08,03), y: 660 },\n";
//  echo "              { x: new Date(2016,08,04), y: 740 },\n";
// echo "               { x: new Date(2016,08,05), y: 858 }\n";
echo "              ]\n";
echo "          }\n";
echo "          \n";
echo "          ],\n";
echo "          legend:{\n";
echo "            cursor:\"pointer\",\n";
echo "            itemclick:function(e){\n";
echo "              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {\n";
echo "                  e.dataSeries.visible = false;\n";
echo "              }\n";
echo "              else{\n";
echo "                e.dataSeries.visible = true;\n";
echo "              }\n";
echo "              chartIOS.render();\n";
echo "            }\n";
echo "          }\n";
echo "      });\n";
echo "\n";
echo "chartIOS.render();\n";
echo "}\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js\"></script>\n";
echo "\n";
echo "  <div id=\"chartContainerIOS\" style=\"height: 300px; width: 95%;\">\n";
echo "  </div>\n";

}


function getAndroidAllTrend(){

$fizyTrend=getAndroidTRend('fizy');
$bipTrend=getAndroidTRend('bip');
$depoTrend=getAndroidTRend('depo');
$akademiTrend=getAndroidTRend('akademi');
echo "  <script type=\"text/javascript\">\n";
echo "  window.onload = function () {\n";
echo "      var chart2 = new CanvasJS.Chart(\"chartContainer2\",\n";
echo "      {\n";
echo "\n";
echo "          title:{\n";
echo "              text: \"IOS App Store Rating Trend\",\n";
echo "              fontSize: 30\n";
echo "          },\n";
echo "                        animationEnabled: true,\n";
echo "          axisX:{\n";
echo "\n";
echo "              gridColor: \"Yellow\",\n";
echo "              tickColor: \"silver\",\n";
echo "              valueFormatString: \"DD/MMM\"\n";
echo "\n";
echo "          },                        \n";
echo "                        toolTip:{\n";
echo "                          shared:true\n";
echo "                        },\n";
echo "          theme: \"theme1\",\n";
echo "          axisY: {\n";
echo "              gridColor: \"Silver\",\n";
echo "              tickColor: \"silver\"\n";
echo "          },\n";
echo "          legend:{\n";
echo "              verticalAlign: \"center\",\n";
echo "              horizontalAlign: \"right\"\n";
echo "          },\n";
echo "          data: [\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 2,\n";
echo "              name: \"Fizy\",\n";
echo "              markerType: \"square\",\n";
echo "              color: \"#F08080\",\n";
echo "              dataPoints: [\n";
echo $fizyTrend;
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"Bip\",\n";
echo "              color: \"#20B2AA\",\n";
echo "              markerType: \"triangle\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $bipTrend;
//  echo "              { x: new Date(2016,08,02), y: 510 },\n";
//  echo "              { x: new Date(2016,08,03), y: 560 },\n";
//  echo "              { x: new Date(2016,08,04), y: 540 },\n";
// echo "               { x: new Date(2016,08,05), y: 558 }\n";
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"Akademi\",\n";
echo "              color: \"#20B2AA\",\n";
echo "              markerType: \"triangle\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $akademiTrend;
//  echo "              { x: new Date(2016,08,02), y: 510 },\n";
//  echo "              { x: new Date(2016,08,03), y: 560 },\n";
//  echo "              { x: new Date(2016,08,04), y: 540 },\n";
// echo "               { x: new Date(2016,08,05), y: 558 }\n";
echo "              ]\n";
echo "          },\n";
echo "          {           \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"Depo\",\n";
echo "              color: \"#00B2FF\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $depoTrend;
//  echo "              { x: new Date(2016,08,02), y: 410 },\n";
//  echo "              { x: new Date(2016,08,03), y: 660 },\n";
//  echo "              { x: new Date(2016,08,04), y: 740 },\n";
// echo "               { x: new Date(2016,08,05), y: 858 }\n";
echo "              ]\n";
echo "          }\n";
echo "          \n";
echo "          ],\n";
echo "          legend:{\n";
echo "            cursor:\"pointer\",\n";
echo "            itemclick:function(e){\n";
echo "              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {\n";
echo "                  e.dataSeries.visible = false;\n";
echo "              }\n";
echo "              else{\n";
echo "                e.dataSeries.visible = true;\n";
echo "              }\n";
echo "              chart2.render();\n";
echo "            }\n";
echo "          }\n";
echo "      });\n";
echo "\n";
echo "chart2.render();\n";
echo "}\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js\"></script>\n";
echo "\n";
echo "  <div id=\"chartContainer2\" style=\"height: 300px; width: 95%;\">\n";
echo "  </div>\n";

}



function recordIosReviews($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appName."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
//$fizyImageURL=$fizyJson->results[0]->artworkUrl512;
//echo $$fizyGet;

$maxID=getMaxIosCommentID($appName);

for ($x = 50; $x >= 1; $x--) {

$commentid=$fizyJson->feed->entry[$x]->id->label;
$author=$fizyJson->feed->entry[$x]->author->name->label;
$comment=$fizyJson->feed->entry[$x]->content->label;
$title=$fizyJson->feed->entry[$x]->title->label;
$ratingPlaceholder='im:rating';
$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
echo "</br>INFO: recordIosReviews URL: ".$fizyUrl;
echo "</br>INFO: recordIosReviews MAX Comment ID from DB: ".$maxID;
echo "</br>INFO: recordIosReviews Comment ID from Store: ".$commentid;
echo "</br>INFO: recordIosReviews App Name: ".$appName;
echo "</br>INFO: recordIosReviews Author: ".$author;
		if ($maxID<$commentid) {
		insertStoreComment('ios',$commentid,$author,$title,$comment,$appName,$rating);
    	echo "</br>INFO DB: recording on DB, counter: ".$x ;


		}

		}
	}
}


function getStoreTrends($appName){
$appleTrend=getIosTRend($appName);
$androidTrend=getAndroidTRend($appName);
echo "  <script type=\"text/javascript\">\n";
echo "  window.onload = function () {\n";
echo "      var chartIOS = new CanvasJS.Chart(\"chartContainerIOS\",\n";
echo "      {\n";
echo "\n";
echo "          title:{\n";
echo "              text: \"".$appName." Store Ratings Trend\",\n";
echo "              fontSize: 30\n";
echo "          },\n";
echo "                        animationEnabled: true,\n";
echo "          axisX:{\n";
echo "\n";
echo "              gridColor: \"Yellow\",\n";
echo "              tickColor: \"silver\",\n";
echo "              valueFormatString: \"DD/MMM\"\n";
echo "\n";
echo "          },                        \n";
echo "                        toolTip:{\n";
echo "                          shared:true\n";
echo "                        },\n";
echo "          theme: \"theme1\",\n";
echo "          axisY: {\n";
echo "              gridColor: \"Silver\",\n";
echo "              tickColor: \"silver\"\n";
echo "          },\n";
echo "          legend:{\n";
echo "              verticalAlign: \"center\",\n";
echo "              horizontalAlign: \"right\"\n";
echo "          },\n";
echo "          data: [\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 2,\n";
echo "              name: \"App Store (IOS)\",\n";
echo "              markerType: \"square\",\n";
echo "              color: \"#F08080\",\n";
echo "              dataPoints: [\n";
echo $appleTrend;
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"Google Play (Android)\",\n";
echo "              color: \"#20B2AA\",\n";
echo "              markerType: \"triangle\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $androidTrend;
//  echo "              { x: new Date(2016,08,02), y: 510 },\n";
//  echo "              { x: new Date(2016,08,03), y: 560 },\n";
//  echo "              { x: new Date(2016,08,04), y: 540 },\n";
// echo "               { x: new Date(2016,08,05), y: 558 }\n";
echo "              ]\n";
echo "          }\n";
echo "          \n";
echo "          ],\n";
echo "          legend:{\n";
echo "            cursor:\"pointer\",\n";
echo "            itemclick:function(e){\n";
echo "              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {\n";
echo "                  e.dataSeries.visible = false;\n";
echo "              }\n";
echo "              else{\n";
echo "                e.dataSeries.visible = true;\n";
echo "              }\n";
echo "              chartIOS.render();\n";
echo "            }\n";
echo "          }\n";
echo "      });\n";
echo "\n";
echo "chartIOS.render();\n";
echo "}\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js\"></script>\n";
echo "\n";
echo "  <div id=\"chartContainerIOS\" style=\"height: 300px; width: 95%;\">\n";
echo "  </div>\n";

}


function getBenchTrends($appName1,$appName2){
$appleTrend1=getIosTRend($appName1);
$androidTrend1=getAndroidTRend($appName1);
$appleTrend2=getIosTRend($appName2);
$androidTrend2=getAndroidTRend($appName2);
echo "  <script type=\"text/javascript\">\n";
echo "  window.onload = function () {\n";
echo "      var chartIOS = new CanvasJS.Chart(\"chartContainerIOS\",\n";
echo "      {\n";
echo "\n";
echo "          title:{\n";
echo "              text: \"".$appName1." vs ".$appName2." Store Ratings Trend\",\n";
echo "              fontSize: 30\n";
echo "          },\n";
echo "                        animationEnabled: true,\n";
echo "          axisX:{\n";
echo "\n";
echo "              gridColor: \"Yellow\",\n";
echo "              tickColor: \"silver\",\n";
echo "              valueFormatString: \"DD/MMM\"\n";
echo "\n";
echo "          },                        \n";
echo "                        toolTip:{\n";
echo "                          shared:true\n";
echo "                        },\n";
echo "          theme: \"theme1\",\n";
echo "          axisY: {\n";
echo "              gridColor: \"Silver\",\n";
echo "              tickColor: \"silver\"\n";
echo "          },\n";
echo "          legend:{\n";
echo "              verticalAlign: \"center\",\n";
echo "              horizontalAlign: \"right\"\n";
echo "          },\n";
echo "          data: [\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 2,\n";
echo "              name: \"".$appName1." IOS\",\n";
echo "              markerType: \"triangle\",\n";
echo "              color: \"#0090c5\",\n";
echo "              dataPoints: [\n";
echo $appleTrend1;
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              name: \"".$appName1." Android\",\n";
echo "              color: \"#0010c5\",\n";
echo "              markerType: \"triangle\",\n";
echo "              lineThickness: 2,\n";
echo "\n";
echo "              dataPoints: [\n";
echo $androidTrend1;
//  echo "              { x: new Date(2016,08,02), y: 510 },\n";
//  echo "              { x: new Date(2016,08,03), y: 560 },\n";
//  echo "              { x: new Date(2016,08,04), y: 540 },\n";
// echo "               { x: new Date(2016,08,05), y: 558 }\n";
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 1,\n";
echo "              name: \"".$appName2." IOS\",\n";
echo "              markerType: \"square\",\n";
echo "              color: \"#FA8258\",\n";
echo "              dataPoints: [\n";
echo $appleTrend2;
echo "              ]\n";
echo "          },\n";
echo "          {        \n";
echo "              type: \"line\",\n";
echo "              showInLegend: true,\n";
echo "              lineThickness: 1,\n";
echo "              name: \"".$appName2." Android\",\n";
echo "              markerType: \"square\",\n";
echo "              color: \"#8A0808\",\n";
echo "              dataPoints: [\n";
echo $androidTrend2;
echo "              ]\n";
echo "          }\n";
echo "          \n";
echo "          ],\n";
echo "          legend:{\n";
echo "            cursor:\"pointer\",\n";
echo "            itemclick:function(e){\n";
echo "              if (typeof(e.dataSeries.visible) === \"undefined\" || e.dataSeries.visible) {\n";
echo "                  e.dataSeries.visible = false;\n";
echo "              }\n";
echo "              else{\n";
echo "                e.dataSeries.visible = true;\n";
echo "              }\n";
echo "              chartIOS.render();\n";
echo "            }\n";
echo "          }\n";
echo "      });\n";
echo "\n";
echo "chartIOS.render();\n";
echo "}\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" src=\"https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js\"></script>\n";
echo "\n";
echo "  <div id=\"chartContainerIOS\" style=\"height: 400px; width: 95%;\">\n";
echo "  </div>\n";

}


function getMaxIosCommentID($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			    die("Connection failed: " . $conn->connect_error);
			}
			$sql = "select coalesce(max(comment_id),'0') maxID from appony.comments a WHERE a.store='ios' and a.appname='".$appName."';";

		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		    	$maxID=$row["maxID"];
		    }

		    }

	$conn->close();
	//echo $trendData.'</br>';
	//echo $raterData.'</br>';
	return $maxID;

}

function insertStoreComment($store,$id,$user,$title,$comment,$appName,$rating){
echo "<br> inser app name: ".$appName;
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "INSERT INTO appony.`comments` (`store`, `comment_id`, `user`,`title`,`comment`,`appname`,`rating`) VALUES ('".$store."', '".$id."', '".$user."','".$title."','".$comment."','".$appName."','".$rating."');";

	if ($conn->query($sql) === TRUE) {
    			echo "</br>INFO DB: New record created successfully";
				echo "</br>INFO DB: to db app : ".$appName;
				echo "</br>INFO DB: to db yorum : ".$comment;
				echo "</br>INFO DB: to db yazar : ".$user;
				echo "</br>INFO DB: to db baslik : ".$title;
				echo "</br>INFO DB: to db ID : ".$id;
				echo "</br>---------------------------------------------";

	} else {
    	echo "</br>INFO DB: Error: " . $sql . "<br>" . $conn->error;
	}
	$conn->close();

}


function getIosReviews($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appName."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

    $conn->close();

$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
//$fizyImageURL=$fizyJson->results[0]->artworkUrl512;
//echo $$fizyGet;

echo "<h2><center> Güncel App Store Yorumları </center></h2>";
for ($x = 1; $x <= 49; $x++) {

$id=$fizyJson->feed->entry[$x]->id->label;
$author=$fizyJson->feed->entry[$x]->author->name->label;
$comment=$fizyJson->feed->entry[$x]->content->label;
$title=$fizyJson->feed->entry[$x]->title->label;
$ratingPlaceholder='im:rating';
$versionPlaceholder='im:version';
$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
$version=$fizyJson->feed->entry[$x]->$versionPlaceholder->label;

//$rating='soon...';

echo "			<!-- Banner -->\n";
echo "				<div id=\"banner-wrapper\">\n";
echo "					<div id=\"banner\" class=\"box container\">\n";
echo "						<div class=\"row\">\n";
echo "							<div class=\"12u 12u(medium)\">\n";
echo "								<h4>".$author." </br>".$title."</h4>\n";
echo "								<p1>".$comment."</p1>\n";
echo "								</br><p1><b>verdiği puan: ".$rating."</p1></b>\n";
echo "								</br><p1><b>versiyon: ".$version."</p1></b>\n";
echo "							</div>\n";
echo "						</div>\n";
echo "					</div>\n";
echo "				</div></br>\n";


		}
	}
}


function getIosReviewsMin($appName){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appid from appony.app_list a WHERE a.appname='".$appName."'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }

    $conn->close();

$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';
$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
//$fizyImageURL=$fizyJson->results[0]->artworkUrl512;
//echo $$fizyGet;

echo "<h2>".$appName."</h2>";
for ($x = 1; $x <= 15; $x++) {

$id=$fizyJson->feed->entry[$x]->id->label;
$author=$fizyJson->feed->entry[$x]->author->name->label;
$comment=$fizyJson->feed->entry[$x]->content->label;
$title=$fizyJson->feed->entry[$x]->title->label;
$ratingPlaceholder='im:rating';
$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
//$rating='soon...';

echo "									<section class=\"box feature\">\n";
echo "<p>\n";
echo "								<h4>".$author." </br>".$title."</h4>\n";
echo "								<p1>".$comment."</p1>\n";
echo "								</br><p1><b>verdiği puan: ".$rating."</p1></b>\n";
echo "</p></br>\n";
echo "								</section>\n";


		}
	}
}

function getTopMenu(){
echo "								<ul>\n";
echo "									<li class=\"current\"><a href=\"index.php\" class=\"icon fa-home\">  Anasayfa</a></li>\n";
echo "									<li class=\"current\">\n";
echo "										<a href=\"#\" class=\"icon fa-mobile\"> Apps</a>\n";
echo "										<ul>\n";
echo "											<li><a href=\"details.php?app=fizy\">Fizy</a></li>\n";
echo "											<li><a href=\"details.php?app=bip\">Bip</a></li>\n";
echo "											<li><a href=\"details.php?app=lifebox\">lifebox</a></li>\n";
echo "											<li><a href=\"details.php?app=akademi\">Akademi</a></li>\n";
echo "											<li><a href=\"details.php?app=upcall\">Upcall</a></li>\n";
echo "											<li><a href=\"details.php?app=RBT\">ÇalarkenDinlet</a></li>\n";
echo "											<li><a href=\"details.php?app=hesabim\">Hesabım</a></li>\n";
echo "											<li><a href=\"details.php?app=cropy\">Cropy</a></li>\n";
echo "											<li><a href=\"details.php?app=platinum\">Platinum</a></li>\n";
echo "											<li><a href=\"details.php?app=dergilik\">Dergilik</a></li>\n";
echo "											<li><a href=\"details.php?app=gnc\">Gnctrkcll</a></li>\n";
echo "											<li><a href=\"details.php?app=resmiislerim\">Resmi İşlerim</a></li>\n";
echo "											<li><a href=\"details.php?app=yaani\">Yaani</a></li>\n";
echo "										</ul>\n";
echo "									</li>\n";
echo "									<li class=\"current\">\n";
echo "										<a href=\"#\" class=\"icon fa-balance-scale\">  Benchmarking</a>\n";
echo "										<ul>\n";
echo "											<li><a href=\"bench.php?app1=fizy&app2=spotify\" class=\"icon fa-spotify\"> fizy-spotify</a></li>\n";
echo "											<li><a href=\"bench.php?app1=lifebox&app2=dropbox\" class=\"icon fa-dropbox\"> lifebox-dropbox</a></li>\n";
echo "											<li><a href=\"bench.php?app1=bip&app2=whatsapp\" class=\"icon fa-whatsapp\"> bip-whatsapp</a></li>\n";
echo "											<li><a href=\"bench.php?app1=dergilik&app2=dmags\" class=\"icon fa-book\"> Dergilik-Dmags</a></li>\n";
echo "											<li><a href=\"bench.php?app1=hesabim&app2=yanimda\" class=\"icon fa-mobile\"> Hesabım-Yanımda</a></li>\n";
echo "										</ul>\n";
echo "									</li>\n";
echo "									\n";
echo "								</ul>\n";
}


function getAllApps(){
$servername='192.168.41.105';
$username='appony';
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "select appname, arl.rating from appony.app_list app,
(select a.app_id, a.rating from appony.app_rating_history a,
(select app_id, rating, max(rate_date) dater from appony.app_rating_history group by app_id) b
where a.app_id=b.app_id
and a.rate_date = b.dater
) arl
where app.appid=arl.app_id
order by app.isTurkcell desc, arl.rating desc";


$result = $conn->query($sql);
$i=0;
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$appName=$row["appname"];
	    	$i++;
	    	if ($i%2==0) {$wrapper="apps2-wrapper";} else {$wrapper="apps-wrapper";}


			echo "			<!-- Features -->\n";
			echo "				<div id=\"".$wrapper."\">\n";
			echo "					<div class=\"container\">\n";
			echo "						<div class=\"row\" name=\"uygulamalar\">\n";
			echo "							<div class=\"4u 12u(medium)\">\n";
			echo "\n";
			echo "								<!-- Box -->\n";
													$imageURL=getImageUrl($appName);
													echo '<center><a href="details.php?app='.$appName.'"><img src="'.$imageURL.' " height="256"   width="256"></a></center>';
			echo "\n";
			echo "							</div>\n";
			echo "							<div class=\"8u 12u(medium)\">\n";
			echo "<center><h3>".$appName."</h3></center>";
			echo "								<!-- Box -->\n";
												getBoxDetailsMin($appName);
			echo "\n";
			echo "							</div>\n";
			echo "						</div>\n";
			echo "					</div>\n";
			echo "				</div>\n";
			echo "\n";

			    }

			    $conn->close();

	}
}



function sendQuickMenu($receiver){
//$tnxid=rand(1000,9999);
$postdata='{
     "txnid":"85955",
     "receiver":{
        "type":2,
        "address": "'.$receiver.'"
     },
     "composition":{
        "list":[
           {
              "type":13,
              "tmmtype":3,
              "quickreplytmm":{
                 "postbackid":"ASDF",
                 "buttonlist":[
                    {
                       "type":"1",
                       "name":"🎧 fizy",
                       "payload":"fizy"
                    },  {
                       "type":"1",
                       "name":" 🤳🏼 bip",
                       "payload":"bip"
                    },
                    {
                         "type":"1",
                         "name":"📦 lifebox",
                         "payload":"lifebox"
                     },
                    {
                       "type":"1",
                       "name":"📰  dergilik",
                       "payload":"dergilik"
                    },
                    {
                       "type":"1",
                       "name":"👤 hesabım",
                       "payload":"hesabim"
                    },
                    {
                       "type":"1",
                       "name":"📞 upcall",
                       "payload":"upcall"
                    },
    {
                     "type":"1",
                     "name":"🔮 platinum",
                     "payload":"platinum"
                  	},
	     {
                     "type":"1",
                     "name":"❓ Diğer",
                     "payload":"liste"
                  }
                 ]
              }
           }
        ]
     }
  }';
//echo "\npostdata array: ".$postdata;
//$postdataJson = "json=".json_encode($postdata)."&";
sendBipImage($postdata);

}







?>
