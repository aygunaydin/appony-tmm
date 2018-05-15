<!DOCTYPE HTML>
<!--
	Verti by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Appony: Powered by VAS-MiS</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body class="homepage">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("funcs/dbFunctions.php");

//echo "INFO: jenkins - github akisi test";
echo "<div id=\"page-wrapper\">\n";
echo "\n";
echo "			<!-- Header -->\n";
echo "				<div id=\"header-wrapper\">\n";
echo "					<header id=\"header\" class=\"container\">\n";
echo "\n";
echo "						<!-- Logo -->\n";
echo "							<div id=\"logo\">\n";
echo "								<h1><a href=\"index.php\" class=\"icon fa-heartbeat\">Appony</a></h1>\n";
echo "								<span>powered by VAS-MIS</span>\n";
echo "							</div>\n";
echo "\n";





echo "			<!-- Features -->\n";
echo "				<div id=\"features-wrapper\">\n";
echo "					<div class=\"container\">\n";
echo "						<div class=\"row\" name=\"uygulamalar\">\n";
echo "							<div class=\"3u 12u(medium)\">\n";
echo "\n";
echo "								<!-- Box -->\n";
										getDJScore('fizy');
echo "\n";
echo "							</div>\n";
echo "							<div class=\"3u 12u(medium)\">\n";
echo "\n";
echo "								<!-- Box -->\n";
									getDJandroid('fizy');
echo "\n";
echo "							</div>\n";

echo "							<div class=\"3u 12u(medium)\">\n";
echo "\n";
echo "								<!-- Box -->\n";
									getDJios('fizy');
									//https://dirtexchanger.com/wp-content/themes/septera-child/css/images/icons/camera-icon.png
echo "\n";
echo "							</div>\n";

echo "							<div class=\"3u 12u(medium)\">\n";
echo "\n";
echo "								<!-- Box -->\n";
									getDJincident('fizy');
echo "\n";
echo "							</div>\n";
echo "						</div>\n";
echo "					</div>\n";
echo "				</div>\n";
echo "\n";








echo "\n";
echo "		<!-- Scripts -->\n";
echo "\n";
echo "			<script src=\"assets/js/jquery.min.js\"></script>\n";
echo "			<script src=\"assets/js/jquery.dropotron.min.js\"></script>\n";
echo "			<script src=\"assets/js/skel.min.js\"></script>\n";
echo "			<script src=\"assets/js/util.js\"></script>\n";
echo "			<!--[if lte IE 8]><script src=\"assets/js/ie/respond.min.js\"></script><![endif]-->\n";
echo "			<script src=\"assets/js/main.js\"></script>\n";


?>
	</body>
</html>
