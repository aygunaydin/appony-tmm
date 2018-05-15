<?php

error_reporting(E_ALL);
//header('Content-Type: application/json');
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

date_default_timezone_set('Europe/Istanbul');

$result=getBipDetailsLite('fizy');

echo '<hr/> MAIN: '.$result;

 ?>
