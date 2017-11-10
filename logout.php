<?php 
include_once './appConfig.php';
$logoutUrl = 'https://www.facebook.com/logout.php?next=' . RELATIVEPATH .
  '&access_token='.$_SESSION['ACCESSTOKEN'];
//$logoutUrl  = $helper->getLogoutUrl($_SESSION['ACCESSTOKEN'], RELATIVEPATH);
$basicFunctionObj->remove_directory('albums/photo/'.$_SESSION['FBID']);

session_destroy();
    unset($_SESSION['FBID'] );
    unset($_SESSION['FBNAME'] );
    unset($_SESSION['ACCESSTOKEN']);
    unset($_SESSION['ALBUMS']);
    unset($_SESSION["selectedAlbumId"]);
    unset($_SESSION['credentials']);
    
header("Location: index.php");
    
//header('Location: '.$url);
