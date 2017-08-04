<?php 
session_start();
session_unset();
    $_SESSION['FBID'] = NULL;
    $_SESSION['FBNAME'] = NULL;
    $_SESSION['ACCESSTOKEN']=  NULL;
    $_SESSION['ALBUMS']=  NULL;
    $_SESSION['google_session_token']=NULL;
header("Location: index.php"); 
?>