<?php 
include_once './appConfig.php';
$basicFunctionObj->remove_directory('albums/photo/'.$_SESSION['FBID']);
    unset($_SESSION['FBID'] );
    unset($_SESSION['FBNAME'] );
    unset($_SESSION['ACCESSTOKEN']);
    unset($_SESSION['ALBUMS']);
    unset($_SESSION["selectedAlbumId"]);
    unset($_SESSION['credentials']);
    
header("Location: index.php");
