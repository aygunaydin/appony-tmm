<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

getBoxDetailsLastMin('bip');

$hostname= gethostname();
echo "</br>servername: ".gethostname();
if ($hostname=='66392eb327fe') { echo ' cluster: disaster';}
if ($hostname=='464eed6109b0') { echo ' cluster: development';}
echo "</br>uname:".php_uname();
echo "</br>ip adress ".$_SERVER['SERVER_ADDR'];





echo "</br></br></br>INFO: started";
$bipURL='http://itunes.apple.com/lookup?id=997943308&country=us';
$bipGet= file_get_contents($bipURL);
$bipJson= json_decode($bipGet);
//$bipImageURL=$bipJson->results[0]->artworkUrl512;
$bipRating=$bipJson->results[0]->averageUserRating;
$bipRaterNum=$bipJson->results[0]->userRatingCount;
$bipCurrentRating=$bipJson->results[0]->averageUserRatingForCurrentVersion;
$bipCurrentRaterNum=$bipJson->results[0]->userRatingCountForCurrentVersion;
$appID=$bipJson->results[0]->trackId;
echo "</br>INFO-appID: ".$appID;
echo "</br>INFO-appname: ".$row["appname"];
echo "</br>INFO-Rating: ".$bipRating;
echo "</br>INFO-RaterNumber: ".$bipRaterNum;
echo "</br>-------------------------</br>-------------------------</br>-------------------------</br>-------------------------</br>";
echo $bipJson;

?>
