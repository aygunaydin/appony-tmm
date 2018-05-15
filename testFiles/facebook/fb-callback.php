<?php
if(!session_id()) {
    session_start();
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';

$appid='315132298832432';
$appsecret='5aee6f3d86c139fd7198239b82445ade';

$fb = new Facebook\Facebook([
  'app_id' => '315132298832432',
  'app_secret' => '5aee6f3d86c139fd7198239b82445ade',
  'default_graph_version' => 'v2.5'
]);



$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) {
  echo '<br>TOKEN IS OK</br>';

  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
//echo '<h3>Metadata</h3>';
//var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($appid); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

$access_token=$_SESSION['fb_access_token'];
// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');

//echo '</br>session created'.$_SESSION['fb_access_token'];

try {
  // Returns a `Facebook\FacebookResponse` object
//  $response = $fb->get('/me?fields=id,name', $access_token);

  $response = $fb->get('/me', $access_token);

} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();

echo '</br> response: '.$user;

echo '</br>Login User Name: ' . $user['name'];

echo '</br>---------------</br>';


try {
    $posts_request = $fb->get('/me/posts?limit=500',$accessToken);
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
  $total_posts = array();
  $posts_response = $posts_request->getGraphEdge();
  if($fb->next($posts_response)) {
    $response_array = $posts_response->asArray();
    $total_posts = array_merge($total_posts, $response_array);
    while ($posts_response = $fb->next($posts_response)) {  
      $response_array = $posts_response->asArray();
      $total_posts = array_merge($total_posts, $response_array);  
    }
    print_r($total_posts);
  } else {
    $posts_response = $posts_request->getGraphEdge()->asArray();
    print_r($posts_response);
  }






?>
