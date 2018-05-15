<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");
 


function createNewJiraTask($titleF, $taskBody) {
$task =  $taskBody;
$title=  $titleF;

if($task == "") {
    die('need a task por favor!');
}
echo $task."\n";
 
// set these in your bash profile or other environment locations or hardcode em!
$username='tcaygaydin';
$password='XXXXXX';
$reporter='tcaygaydin';
$assignee='tcaygaydin';
$project='10113'; // this is the insights project ID
 
// do the login routine
$curl = curl_init();
$loginUrl = "http://jira.turkcell.com.tr/login.jsp?os_username=" . $username . "&os_password=" . $password . "&os_cookie=true"; 
curl_setopt($curl,CURLOPT_URL, $loginUrl );
curl_setopt($curl,CURLOPT_POST, true );
curl_setopt($curl,CURLOPT_COOKIEFILE, '/tmp/cookie' );
curl_setopt($curl,CURLOPT_COOKIEJAR, '/tmp/cookiejar' );
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true );
curl_setopt($curl,CURLOPT_FOLLOWLOCATION, false );
curl_setopt($curl,CURLOPT_NOBODY, true );        
curl_exec( $curl );
 
// grab the xsrf token from the create issue screen
curl_setopt($curl,CURLOPT_NOBODY, false );   
curl_setopt($curl,CURLOPT_URL, "http://jira/secure/CreateIssue.jspa?pid=10001&issuetype=3" );
$createPage = curl_exec( $curl );
$results = preg_match('/atlassian-token" content="([^"]+)"/ism', $createPage, $matches);
$token = $matches[1];
 
// pass the token and your arguments to create the new issue
$postUrl =  "http://jira.turkcell.com.tr/secure/CreateIssueDetails.jspa?atl_token={$token}&pid={$project}&issuetype=1&summary={$title}";
$postUrl .= "&descriptiion={$task}&assignee={$assignee}&reporter={$reporter}&priority=3&isCreateIssue=true&hasWorkStarted=false&Create=Create";
curl_setopt($curl,CURLOPT_RETURNTRANSFER, false );
curl_setopt($curl,CURLOPT_URL,  $postUrl );
curl_exec( $curl );
curl_close( $curl );

}

$servername='46.101.113.44';
$username='appony'; 
$password='appony1020';
$dbname='appony';
$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "select appid from appony.app_list a WHERE a.appname='fizy'";


$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$appIosID=$row["appid"];
    }
}    

$fizyUrl='https://itunes.apple.com/tr/rss/customerreviews/id='.$appIosID.'/sortBy=mostRecent/json';

$fizyGet = file_get_contents($fizyUrl);
$fizyJson=json_decode($fizyGet);
//$fizyImageURL=$fizyJson->results[0]->artworkUrl512;
//echo $$fizyGet;

for ($x = 2; $x >= 1; $x--) {
$commentid=$fizyJson->feed->entry[$x]->id->label;
$author=$fizyJson->feed->entry[$x]->author->name->label;
$comment=$fizyJson->feed->entry[$x]->content->label;
$title=$fizyJson->feed->entry[$x]->title->label;
$ratingPlaceholder='im:rating';
$rating=$fizyJson->feed->entry[$x]->$ratingPlaceholder->label;
echo "</br>INFO: recordIosReviews URL: ".$fizyUrl;
//echo "</br>INFO: recordIosReviews MAX Comment ID from DB: ".$maxID;
echo "</br>INFO: recordIosReviews Comment ID from Store: ".$commentid;
//echo "</br>INFO: recordIosReviews App Name: ".$appName;
echo "</br>INFO: recordIosReviews Author: ".$author;
echo "</br>";


$maxID=getMaxIosCommentID('fizy');
$commentJ=$commentid.' nolu yorum: '.$comment;
		if ($rating<4) {	
		echo "</br>INFO JIRA: STARTED: ".$x;		
		createNewJiraTask($title, $commentJ);
    	echo "</br>INFO DB: Creating Jira, counter: ".$x;		
		echo "</br>";
	}
}








?>