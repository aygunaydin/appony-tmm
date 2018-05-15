<?php
if(!session_id()) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';


$fb = new Facebook\Facebook([
  'app_id' => '315132298832432',
  'app_secret' => '5aee6f3d86c139fd7198239b82445ade',
  'default_graph_version' => 'v2.5'
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://turkcell.ga/facebook/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';


?>
