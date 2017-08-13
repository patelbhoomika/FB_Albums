<?php

include_once './appConfig.php';

use Facebook\FacebookRequest;

if (isset($_GET['selectedAlbumId']) && $_GET['selectedAlbumId'] <> "") {
    $selectedAlbumId = $_GET['selectedAlbumId'];
    if ($_GET['type'] == "slideShow") {
        $selectedAlbumData = explode('$', $selectedAlbumId);
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumData[0]);
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
        $album_download_directory = createZip($_GET['selectedAlbumId']);
        require_once('libs/zipper.php');
        $zipper = new zipper();
        echo $zipper->get_zip($album_download_directory);
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
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumData[0]);
        $album_directory = $album_download_directory . $selectedAlbumData[1];
        if (!file_exists($album_directory)) {
            mkdir($album_directory, 0777);
        }
        $j = 1;
        foreach ($albumPhotographObject['data'] as $album_photo) {
            $album_photo = (array) $album_photo;
            file_put_contents($album_directory . '/' . $j . ".jpg", fopen($album_photo['source'], 'r'));
            $j++;
        }
    }
    return $album_download_directory;
}
