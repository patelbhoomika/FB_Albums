<?php
require_once("functions.php");
require_once 'google-api-php-client/src/Google/Client.php';
require_once 'google-api-php-client/src/Google/Service/Oauth2.php';
require_once 'google-api-php-client/src/Google/Service/Drive.php';
require_once '../../appConfig.php';
header('Content-Type: text/html; charset=utf-8');

$authUrl = getAuthorizationUrl("", "");
?>
<!DOCTYPE html>
<html lang="fi">
    <head>
        <title>Google Drive Login and Upload</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php
        ini_set('max_execution_time', 300);
        if (isset($_GET['selectedAlbumId']) != "") {
            $_SESSION["selectedAlbumId"] = $_GET['selectedAlbumId'];
        }

        if ((!isset($_SESSION["credentials"])) && (!isset($_GET['code']))) {
            ?>
            <a href=<?php echo "'" . $authUrl . "'" ?>>Sign in with Google</a>
            <?php
        } else {
            global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
            $client = new Google_Client();
            $client->setClientId($CLIENT_ID);
            $client->setClientSecret($CLIENT_SECRET);
            $client->setRedirectUri($REDIRECT_URI);
            $client->setScopes('email');
            $authUrl = $client->createAuthUrl();
            //echo $authUrl;exit;
            if (isset($_GET['code'])) {
                getCredentials($_GET['code'], $authUrl);
            }
            // Init the variables
            $driveInfo = "";
            $folderName = "";

            // Get the client Google credentials
            $credentials = $_SESSION["credentials"];
         

            // Refresh the user token and grand the privileges
            $client->setAccessToken($credentials);
            $service = new Google_Service_Drive($client);

            // Set the file metadata for drive

            $parentFolderName = $_SESSION['FBNAME'];
            $selectedAlbum = explode(',', $_SESSION["selectedAlbumId"]);
            $albumNameAndPhoto = array();
            $file_tmp_name = array();

            for ($i = 0; $i < count($selectedAlbum); $i++) {
                if ($selectedAlbum[$i] <> "") {
                    $selectedAlbumData = explode('$', $selectedAlbum[$i]);
                }

                $albumPhotographObject = $basicFunctionObj->getAlnumsPhoto_From_FB($selectedAlbumData[0]);
                foreach ($albumPhotographObject['data'] as $album_photo) {
                    $file_tmp_name[] = $album_photo['source'];
                }


                $albumNameAndPhoto[] = array($selectedAlbumData[1] => $file_tmp_name);
                unset($file_tmp_name);
            }

            $driveInfo = insertFile($service, $albumNameAndPhoto, $parentFolderName);
        }
        ?>    
    </body>
</html>