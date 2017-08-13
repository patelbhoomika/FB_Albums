<?php 
session_start();
session_unset();
    $_SESSION['FBID'] = null;
    $_SESSION['FBNAME'] = null;
    $_SESSION['ACCESSTOKEN']=  null;
    $_SESSION['ALBUMS']=  null;
    $_SESSION["selectedAlbumId"]=null;
    $_SESSION["googleUserName"]=null;
header("Location: index.php");
