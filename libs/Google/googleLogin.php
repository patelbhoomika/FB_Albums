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
//echo $_SESSION["userInfo"]["name"];exit;
ini_set('max_execution_time', 300);
if (isset($_GET['selectedAlbumId']) != "") {
    $_SESSION["selectedAlbumId"]=$_GET['selectedAlbumId'];
}



if ((!isset($_SESSION["userInfo"]["name"])) && (!isset($_GET['code']))) {
    ?>
    <a href=<?php echo "'" . $authUrl . "'" ?>>Login</a>
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
        // echo $_SESSION['code'];
        //exit;
        

        $userName = $_SESSION["userInfo"]["name"];
        $userEmail = $_SESSION["userInfo"]["email"];
       
        
        // Init the variables
        $driveInfo = "";
        $folderName = "";
        $folderDesc = "";

        // Get the file path from the variable
        //$file_tmp_name = array('https://scontent.xx.fbcdn.net/v/t1.0-9/19148900_751316498379430_3735414432256686222_n.jpg?oh=5c60b933b6d1aaa8dffc15a5048979d1&oe=59F7ECA3','https://scontent.xx.fbcdn.net/v/t1.0-9/19149019_751306368380443_4693862114607348708_n.jpg?oh=ac57b628d5b95667344a2b144ff8e87c&oe=59F9DA72');
        //  $file_tmp_name=$_GET['file_tmp_name'];
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
    "https://www.googleapis.com/auth/drive",
    "https://www.googleapis.com/auth/drive.appfolder");

        // Refresh the user token and grand the privileges
        $client->setAccessToken($credentials);
        $service = new Google_Service_Drive($client);

        // Set the file metadata for drive
        //$mimeType = "image/jpeg";
        //echo $mimeType;exit;
        //  $title = "a";
        // $description = "Uploaded from your very first google drive application!";

        // Get the folder metadata
        // if (!empty($_POST["folderName"])) {
        //$folderName = $_POST["folderName"];
        
        // $folderName=$_GET['folderName'];
        $parentFolderName=$_SESSION['FBNAME'];
        $selectedAlbum = explode(',', $_SESSION["selectedAlbumId"]);
        // $folderName=array();
        $albumNameAndPhoto=array();
        //$file_tmp_name=array();
        for ($i = 0; $i < count($selectedAlbum); $i++) {
            if ($selectedAlbum[$i] <> "") {
                $selectedAlbumData = explode('$', $selectedAlbum[$i]);
            }
            //array_push($folderName, $selectedAlbumData[1]);
            $albumPhotographObject = getAlnumsPhoto_From_FB($selectedAlbumData[0]);
            foreach ($albumPhotographObject['data'] as $album_photo) {
                $file_tmp_name[]=$album_photo['source'];
                //  echo $file_tmp_name."</br>";
            }
            // echo $selectedAlbumData[1];
            //print_r($file_tmp);
            //exit;
            $albumNameAndPhoto[]=array($selectedAlbumData[1]=>$file_tmp_name);
            $file_tmp_name="";
            //array_push($albumNameAndPhoto,$selectedAlbumData[1]=>$file_tmp_name);
        }
        
        //$driveInfo = insertFile($service, $albumNameAndPhoto, $parentFolderName);
        // echo '</pre>';
        // print_r($albumNameAndPhoto);
        //exit;
        $driveInfo = insertFile($service, $albumNameAndPhoto, $parentFolderName);
        
        
        
        // $folderName=array('userAlbum1','userAlbum2');
        //}
        //if (!empty($_POST["folderDesc"])) {
        //$folderDesc = $_POST["folderDesc"];
        // $folderDesc="new folder";
        
        //$parentFolderName="parentFolder";
        // Call the insert function with parameters listed below
       // $driveInfo = insertFile($service, $file_tmp_name, $parentFolderName, $folderName);

        

        
        // echo "<br>Link to file: " . $driveInfo["alternateLink"];?>
<?php
    }
     
    ?>    
</body>
</html>