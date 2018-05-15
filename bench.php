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

$appName1=$_GET['app1'];
$appName2=$_GET['app2'];
//echo "INFO - app: ".$appName;

echo "<div id=\"page-wrapper\">\n"; 
echo "\n"; 
echo "			<!-- Header -->\n"; 
echo "				<div id=\"header-wrapper\">\n"; 
echo "					<header id=\"header\" class=\"container\">\n"; 
echo "\n"; 
echo "						<!-- Logo -->\n"; 
echo "							<div id=\"logo\">\n"; 
echo "								<h1><a href=\"index.php\" class=\"icon fa-stethoscope\">Appony</a></h1>\n"; 
echo "								<span>powered by VAS-MIS</span>\n"; 
echo "							</div>\n"; 
echo "\n"; 
echo "						<!-- Nav -->\n"; 
echo "							<nav id=\"nav\">\n"; 
								//Aygun: getTopMenu() returns navigation menu items
								getTopMenu();
echo "							</nav>\n"; 
echo "\n"; 
echo "					</header>\n"; 
echo "				</div>\n"; 
echo "\n"; 


echo "			<!-- Features -->\n"; 
echo "				<div id=\"apps-wrapper\">\n"; 
echo "					<div class=\"container\">\n"; 
echo "						<div class=\"row\" name=\"uygulamalar\">\n"; 
echo "							<div class=\"4u 12u(medium)\">\n"; 
echo "\n"; 

echo "								<!-- IMAGES Box -->\n"; 
										$imageURL=getImageUrl120($appName1);										
echo 									'<p><center><img src="'.$imageURL.' " ></center></p>';
echo 									'<p><center>vs</center></p>';											
										$imageURL2=getImageUrl120($appName2);										
echo 									'<p><center><img src="'.$imageURL2.' "></center></p>';
echo "\n"; 
echo "							</div>\n"; 


echo "							<div class=\"8u 12u(medium)\">\n"; 

echo "\n"; 
echo "								<!-- DETAILS Box -->\n"; 
									getBoxDetailsMin($appName1);
echo "\n"; 
echo "								<!-- Box -->\n"; 
									getBoxDetailsMin2($appName2);
echo "							</section>\n"; 

echo "\n"; 
echo "							</div>\n"; 
echo "						</div>\n"; 
echo "					</div>\n"; 
echo "				</div></br>\n"; 
echo "\n"; 




echo "			<!-- Graph Benchmark-->\n"; 
echo "				<div id=\"banner-wrapper\">\n"; 
echo "					<div id=\"banner\" class=\"box container\">\n"; 
getBenchTrends($appName1,$appName2); 
echo "					</div>\n"; 
echo "				</div>\n"; 

echo "</br>\n"; 

echo "<section class=\"box feature\">\n"; 
echo "<h2 style=\"color:#4099FF;\"><center>Son 15 App Store Yorumu</center></h2>";
echo "</section>";

echo "			<!-- Graph Android-->\n"; 
echo "			<!-- Features -->\n"; 
echo "				<div id=\"features-wrapper\">\n"; 
echo "					<div class=\"container\">\n"; 
echo "						<div class=\"row\" name=\"uygulamalar\">\n"; 
echo "							<div class=\"6u 12u(medium)\">\n"; 
echo "\n"; 
echo "								<!-- Box -->\n"; 
							getIosReviewsMin($appName1);
echo "\n"; 
echo "							</div>\n"; 
echo "							<div class=\"6u 12u(medium)\">\n"; 
echo "\n"; 
echo "								<!-- Box -->\n";
							getIosReviewsMin($appName2);

echo "\n"; 
echo "							</div>\n"; 
echo "						</div>\n"; 
echo "					</div>\n"; 
echo "				</div>\n"; 
echo "\n"; 

echo "			<!-- Footer -->\n"; 
echo "				<div id=\"footer-wrapper\">\n"; 
echo "					<footer id=\"footer\" class=\"container\">\n"; 
echo "						<div class=\"row\">\n"; 
echo "							<div class=\"8u 12u(medium) 12u$(small)\">\n"; 
echo "\n"; 
echo "								<!-- Links -->\n"; 
echo "									<section class=\"widget links\">\n"; 
echo "										<h3>Appony Hakkında</h3>\n"; 
echo "										<ul class=\"style2\">\n"; 
echo "											<li>VAS Yazılım Geliştirme Kulübü tarafından, 2016 GNCYTNK Staj programı Yeni Nesil Teknolojiler dersi kapsamında geliştirilmiştir.</li>\n"; 
echo "										</ul>\n"; 
echo "									</section>\n"; 
echo "\n"; 
echo "							</div>\n"; 
echo "							<div class=\"3u 6u$(medium) 12u$(small)\">\n"; 
echo "\n"; 
echo "								<!-- Contact -->\n"; 
echo "									<section class=\"widget contact last\">\n"; 
echo "										<h3>Bize Ulaşın</h3>\n"; 
echo "										<ul>\n"; 
echo "											<li><a href=\"#\" class=\"icon fa-twitter\"><span class=\"label\">Twitter</span></a></li>\n"; 
echo "											<li><a href=\"#\" class=\"icon fa-facebook\"><span class=\"label\">Facebook</span></a></li>\n"; 
echo "											<li><a href=\"#\" class=\"icon fa-instagram\"><span class=\"label\">Instagram</span></a></li>\n"; 
echo "											<li><a href=\"#\" class=\"icon fa-dribbble\"><span class=\"label\">Dribbble</span></a></li>\n"; 
echo "											<li><a href=\"#\" class=\"icon fa-pinterest\"><span class=\"label\">Pinterest</span></a></li>\n"; 
echo "										</ul>\n"; 
echo "										<p>VAS-MIS<br />\n"; 
echo "										Soğanlık Kartal<br />\n"; 
echo "										</p>\n"; 
echo "									</section>\n"; 
echo "\n"; 
echo "							</div>\n"; 
echo "						</div>\n"; 
echo "						<div class=\"row\">\n"; 
echo "							<div class=\"12u\">\n"; 
echo "								<div id=\"copyright\">\n"; 
echo "									<ul class=\"menu\">\n"; 
echo "										<li>&copy; Appony. All rights reserved</li><li>Design: HTML5 UP / Verti</li>\n"; 
echo "									</ul>\n"; 
echo "								</div>\n"; 
echo "							</div>\n"; 
echo "						</div>\n"; 
echo "					</footer>\n"; 
echo "				</div>\n"; 
echo "\n"; 
echo "			</div>\n"; 
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