<?php

include_once './appConfig.php';

use Facebook\FacebookRequest;

if (isset($_GET['selectedAlbumId']) && $_GET['selectedAlbumId'] <> "") {
    $selectedAlbumId = $_GET['selectedAlbumId'];
    if ($_GET['type'] == "selectedDownload" || $_GET['type'] == "allDownload" || $_GET['type'] == "allMove") {
        $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumId);
        //        if (isset($_GET['move'])) {
        //            $folderName=array("userAlbum1","userAlbums2");
        //            $parentFolderName="parentFolder";
        //            $file_tmp_name = array('https://scontent.xx.fbcdn.net/v/t1.0-9/19148900_751316498379430_3735414432256686222_n.jpg?oh=5c60b933b6d1aaa8dffc15a5048979d1&oe=59F7ECA3','https://scontent.xx.fbcdn.net/v/t1.0-9/19149019_751306368380443_4693862114607348708_n.jpg?oh=ac57b628d5b95667344a2b144ff8e87c&oe=59F9DA72');
        //            header('location:libs/Google/googleLogin.php?folderName=' . $folderName.'&parentFolderName='.$parentFolderName.'&file_tmp_name='.$file_tmp_name);
        //        } else {
        $album_download_directory = createZip($_GET['selectedAlbumId']);
        require_once('libs/zipper.php');
        $zipper = new zipper();
        echo $zipper->get_zip($album_download_directory);
        //        }
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
