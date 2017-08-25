<?php

include_once './appConfig.php';

use Facebook\FacebookRequest;


if (isset($_GET['selectedAlbumId']) && $_GET['selectedAlbumId'] <> "") {
    $selectedAlbumId = $_GET['selectedAlbumId'];
    if ($_GET['type'] == "slideShow") {
        $selectedAlbumData = explode('$', $selectedAlbumId);
        $albumPhotographObject = $basicFunctionObj->getAlnumsPhoto_From_FB($selectedAlbumData[0]);
        $data = ' <div class="item active">';
        $data .= ' <img src=' . $albumPhotographObject["data"][0]["source"] . ' class="img-responsive">';
        $data .= ' </div>';
        for ($i = 1; $i < count($albumPhotographObject['data']); $i++) {
            $data .= '<div class="item">';
            $data .= ' <img src=' . $albumPhotographObject["data"][$i]["source"] . ' class="img-responsive">';
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