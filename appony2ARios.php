<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("funcs/dbFunctions.php");

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

$sql = "select  b.appname, date_format(a.rate_date,'%m/%d/%Y %h:%i') dater, a.* from appony.app_rating_history a, appony.app_list b
where a.app_id=b.appid and rate_date > now()-INTERVAL 1 DAY
order by appname asc";
$result = $conn->query($sql);
//$jsondata='{';
$jsondata=array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


	$name=$row["appname"];
	$rating=$row["rating"];
//	$jsondata=$jsondata.'"'.$name.'" : '.$rating.' , ';


$jsondata=$jsondata+array($name=>$rating);
	//array_push($jsondata,$name=>$rating);
	//echo json_encode($jsondata);

//echo $row["appname"].";".$row["app_id"].";".$row["rating"].";".$row["rater_num"].";".$row["dater"].";".$row["current_version"].";".$row["current_version_rater_num"].";".$row["current_version_id"].";".$row["current_version_release_date"]."</br>";


    }
} else {
    echo "0 results";
}

//$jsondata=$jsondata.'bitti:0}';
//echo $jsondata;
echo json_encode($jsondata);



?>
