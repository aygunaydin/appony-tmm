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
$jsondata=array();
$sql = "select date_format(a.rate_date,'%m/%d/%Y %h:%i') dater, a.* from appony.android_app_rating_history a where  rate_date > now()-INTERVAL 1 DAY";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	$name=$row["app_name"];
	$rating=$row["rating"];


$jsondata=$jsondata+array($name=>$rating);

//echo $row["app_name"].";".$row["rating"].";".$row["rater_num"].";".$row["dater"]."</br>";
 
   }
} else {
    echo "0 results";
}

echo json_encode($jsondata);

?>
