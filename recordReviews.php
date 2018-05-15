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

$sql = "SELECT appid,appname FROM app_list where isTurkcell=1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
			$appName=$row["appname"];
			recordIosReviews($appName);
			
			} 
	}
		else {
    		echo "0 results";
			}

    $conn->close();

?>