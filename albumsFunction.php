<?php

include_once './appConfig.php';

use Facebook\FacebookRequest;

if (isset($_GET['selectedAlbumId']) && $_GET['selectedAlbumId'] <> "") {
    $selectedAlbumId = $_GET['selectedAlbumId'];
    if ($_GET['type'] == "selectedDownload" || $_GET['type'] == "allDownload") {
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumId);
        $album_download_directory = createZip($_GET['selectedAlbumId']);
        if (isset($_GET['move'])) {
            header('location:libs/move_to_picasa.php?ajax=1&album_download_directory=' . $album_download_directory);
        } else {
            require_once('libs/zipper.php');
            $zipper = new zipper();
            echo $zipper->get_zip($album_download_directory);
        }
    } elseif ($_GET['type'] == "download") {
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumId);
        for ($i = 0; $i < count($albumPhotographObject['data']); $i++) {
            $files[] = $albumPhotographObject["data"][$i]["source"];
        }
        # create new zip opbject
        $zip = new ZipArchive();

        # create a temp file & open it
        $tmp_file = tempnam('.', '');
        $zip->open($tmp_file, ZipArchive::CREATE);

        # loop through each file
        $i = 1;
        foreach ($files as $file) {

            # download file
            $download_file = file_get_contents($file);

            #add it to the zip
            $zip->addFromString($i . ".png", $download_file);
            $i++;
        }

        # close zip
        $zip->close();

        # send the file to the browser as a download
        header('Content-disposition: attachment; filename=fb_album.zip');
        header('Content-type: application/zip');
        readfile($tmp_file);
        unlink($tmp_file);
    } else {
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumId);
        //print_r($albumPhotographObject);
        //exit;
        $data = ' <div class="item active">';
        $data .= ' <img src=' . $albumPhotographObject["data"][0]["source"] . ' class="img-responsive">';
        $data .= ' </div>';
        for ($i = 1; $i < count($albumPhotographObject['data']); $i++) {
            $data .= '<div class="item">';
            $data .= ' <img src=' . $albumPhotographObject["data"][$i]["source"] . ' class="img-responsive">';
            $data .= ' </div>';
        }
        echo $data;
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

function getAlnumsPhoto_From_FB($selectedAlbumId)
{
    global $fbApp, $fb;

    $albumPhotoReq = new FacebookRequest($fbApp, $_SESSION['ACCESSTOKEN'], 'GET', '/' . $selectedAlbumId . '/photos?fields=source');
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
    return $albumPhotographObject;
}
