<?php

include_once './appConfig.php';
include_once 'libs/smart_resize_image.function.php';

use Facebook\FacebookRequest;

function resizeImage($album_photo) {
    $album_photoWithOutHttp = substr($album_photo, -8);
    if(!file_exists('albums/photo'))
    {
         mkdir('albums/photo', 0777);
    }
    $user_album_photo_dir = 'albums/photo/' . $_SESSION['FBID'];
    if (!file_exists($user_album_photo_dir)) {
        mkdir($user_album_photo_dir, 0777);
    }
    $album_resized_photo = $user_album_photo_dir . "/" . $album_photoWithOutHttp . '.jpg';

    if (!file_exists($album_resized_photo)) {
        smart_resize_image($album_photo, 400, 400, false, $album_resized_photo, false, false);
    }
    return $album_resized_photo;
}

if (isset($_GET['selectedAlbumId']) && $_GET['selectedAlbumId'] <> "") {
    $selectedAlbumId = $_GET['selectedAlbumId'];
    if ($_GET['type'] == "slideShow") {
        $selectedAlbumData = explode('$', $selectedAlbumId);
        $albumPhotographObject = $basicFunctionObj->getAlnumsPhoto_From_FB($selectedAlbumData[0]);
        $album_resized_photo = resizeImage($albumPhotographObject[0]["source"]);
        $data = ' <div class="item active">';
        $data .= ' <img src=' . $album_resized_photo . ' class="img-responsive">';
        $data .= ' </div>';
        for ($i = 1; $i < count($albumPhotographObject); $i++) {
            $album_resized_photos = resizeImage($albumPhotographObject[$i]["source"]);
            $data .= '<div class="item">';
            $data .= ' <img src=' . $album_resized_photos . ' class="img-responsive">';
            $data .= ' </div>';
        }
        echo $data;
    } else {
        $album_download_directory = $basicFunctionObj->createZip($_GET['selectedAlbumId']);
        require_once('libs/zipper.php');
        $zipper = new zipper();
        echo $zipper->get_zip($album_download_directory);
    }
}