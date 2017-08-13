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

        if ((!isset($_SESSION["googleUserName"])) && (!isset($_GET['code']))) {
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
            if (isset($_GET['code'])) {
                getCredentials($_GET['code'], $authUrl);
            }

            $_SESSION["googleUserName"] = $_SESSION["userInfo"]["name"];
            // $userEmail = $_SESSION["userInfo"]["email"];
            // Init the variables
            $driveInfo = "";
            $folderName = "";

            // Get the client Google credentials
            $credentials = $_COOKIE["credentials"];

            // Get your app info from JSON downloaded from google dev console
            $json = json_decode(file_get_contents("./conf/GoogleClientId.json"), true);
            $CLIENT_ID = $json['web']['client_id'];
            $CLIENT_SECRET = $json['web']['client_secret'];
            $REDIRECT_URI = $json['web']['redirect_uris'][0];

            // Create a new Client
            $client = new Google_Client();
            $client->setClientId($CLIENT_ID);
            $client->setClientSecret($CLIENT_SECRET);
            $client->setRedirectUri($REDIRECT_URI);
            $client->addScope(
                    "https://www.googleapis.com/auth/drive", "https://www.googleapis.com/auth/drive.appfolder");

            // Refresh the user token and grand the privileges
            $client->setAccessToken($credentials);
            $service = new Google_Service_Drive($client);

            // Set the file metadata for drive

            $parentFolderName = $_SESSION['FBNAME'];
            $selectedAlbum = explode(',', $_SESSION["selectedAlbumId"]);
            $albumNameAndPhoto = array();

            for ($i = 0; $i < count($selectedAlbum); $i++) {
                if ($selectedAlbum[$i] <> "") {
                    $selectedAlbumData = explode('$', $selectedAlbum[$i]);
                }

                $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumData[0]);
                foreach ($albumPhotographObject['data'] as $album_photo) {
                    $file_tmp_name[] = $album_photo['source'];
                }

                $albumNameAndPhoto[] = array($selectedAlbumData[1] => $file_tmp_name);
                $file_tmp_name = "";
            }

            $driveInfo = insertFile($service, $albumNameAndPhoto, $parentFolderName);
        }
        ?>    
    </body>
</html>