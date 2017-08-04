<?php
require_once 'libs/Facebook/autoload.php';

use Facebook\PersistentData\PersistentDataInterface;
use Facebook\Helpers\FacebookRedirectLoginHelper;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSession;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\GraphNodes\GraphAlbum;

session_start();


$fb = new Facebook\Facebook([
  'app_id' => '669957856547801', // Replace {app-id} with your app id
  'app_secret' => 'dbe135803c63cfeced65c9c656a8c82e',
  'default_graph_version' => 'v2.9',
  'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();

$fbApp = new Facebook\FacebookApp('669957856547801', 'dbe135803c63cfeced65c9c656a8c82e');
