<?php
ob_start();
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
require_once 'libs/Facebook/autoload.php';

session_start();

if ( !defined('RELATIVEPATH') )
{
    $relativeUrl="http://".$_SERVER["SERVER_NAME"]."/FB_Albums/";
    define('RELATIVEPATH',$relativeUrl);
}
if ( !defined('ABSPATH') )
{
   $absPath=$_SERVER["DOCUMENT_ROOT"]."/FB_Albums/";
   define('ABSPATH',$absPath); 
}
$app_id='1396276800489356';// Replace {app-id} with your app id
$app_secret="df11b88e20b9f16a5685f820ac3561ef";// Replace {app_secret} with your app id

$fb = new Facebook\Facebook([
  'app_id' => $app_id, 
  'app_secret' => $app_secret, 
  'default_graph_version' => 'v2.9',
  'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();

$fbApp = new Facebook\FacebookApp($app_id, $app_secret);

$fb_login_url = RELATIVEPATH.'fbCallback.php';// 'your login url or index url where the response is come'; 




class BasicFunction
{

 function getAlnumsPhoto_From_FB($selectedAlbumId)
{
    if(isset($_SESSION['ACCESSTOKEN']))
    {
        
    global $fbApp, $fb;

    $albumPhotoReq = new FacebookRequest($fbApp, $_SESSION['ACCESSTOKEN'], 'GET', '/' . $selectedAlbumId . '/photos?fields=source&type=album');
    try {
        $albumPhotoRes = $fb->getClient()->sendRequest($albumPhotoReq);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $albumPhotographObject = $albumPhotoRes->getDecodedBody();
    $all_photos=array();
    $all_photos = array_merge( $all_photos, $albumPhotographObject['data']);
    //print_r($albumPhotographObject['paging']);exit;
    while(isset($albumPhotographObject['paging']['next'])<>null)
    {
        //print_r($albumPhotographObject['paging']['cursors']['next']);
        //echo "dsfs";exit;
        //print_r($albumPhotographObject['paging']['cursors']);exit;
        $paging = $albumPhotographObject['paging'];
        $after = $paging['cursors']['after'];
        //echo $albumPhotographObject['paging']['next'];exit;
        $albumPhotoReq = new FacebookRequest($fbApp, $_SESSION['ACCESSTOKEN'], 'GET', '/' . $selectedAlbumId . '/photos?fields=source&type=album&limit=100',
                     array('limit' => '25','after'  => $after ));
        $albumPhotoRes = $fb->getClient()->sendRequest($albumPhotoReq);
        $albumPhotographObject = $albumPhotoRes->getDecodedBody();
        $all_photos = array_merge( $all_photos, $albumPhotographObject['data']);
    }
    
    
    
    
//   
//        while($albumPhotographObject['paging']<>null)
//        {
//            $all_photos = array_merge( $all_photos, $albumPhotographObject['data']);
//            $paging = $albumPhotographObject['paging'];
//            
//            $next = $paging['next'];
//            $query = parse_url($next, PHP_URL_QUERY);
//            parse_str($query, $par); 
//             $albumPhotoReq1 = new FacebookRequest($fbApp, $_SESSION['ACCESSTOKEN'], 'GET', '/' . $selectedAlbumId . '/photos?fields=source',
//                     array('limit' => $par['limit'],'after'  => $par['after'] ));
//             
//             $albumPhotoRes1 = $fb->getClient()->sendRequest($albumPhotoReq1);
//            // print_r($albumPhotographObject);exit;
//             $albumPhotographObject = $albumPhotoRes1->getDecodedBody();
//            
////            $albumPhotographObject = $facebook->api(
////                $user_id."/photos", 'GET', array(
////                    'limit' => $par['limit'],
////                    'until'  => $par['until'] ));
//        }
   
    
    return $all_photos;
    }
}
function createZip($selectedAlbumId)
{
    $selectedAlbum = explode(',', $_GET['selectedAlbumId']);

    $zip_folder = "";
    $album_download_directory = 'albums/' . uniqid() . '/';
    mkdir($album_download_directory, 0777);

    for ($i = 0; $i < count($selectedAlbum); $i++) {
        if ($selectedAlbum[$i] <> "") {
            $selectedAlbumData = explode('$', $selectedAlbum[$i]);
        }
        $albumPhotographObject = $this->getAlnumsPhoto_From_FB($selectedAlbumData[0]);
        $album_directory = $album_download_directory . $selectedAlbumData[1];
        if (!file_exists($album_directory)) {
            mkdir($album_directory, 0777);
        }
        $j = 1;
        //print_r($albumPhotographObject);exit;
        foreach ($albumPhotographObject as $album_photo) {
            file_put_contents($album_directory . '/' . $j . ".jpg", fopen($album_photo['source'], 'r'));
            $j++;
        }
    }
    return $album_download_directory;
    
}
 function remove_directory($directory)
    {
        if (isset($directory)) {
            foreach (glob("{$directory}/*") as $file) {
                if (is_dir($file)) {
                    $this->remove_directory($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($directory);
        }
    }
    
}  

$basicFunctionObj=new BasicFunction();


