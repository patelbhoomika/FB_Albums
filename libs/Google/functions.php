<?php

require_once 'google-api-php-client/src/Google/Client.php';
require_once "google-api-php-client/src/Google/Service/Oauth2.php";
header('Content-Type: text/html; charset=utf-8');

// Get your app info from JSON downloaded from google dev console
$json = json_decode(file_get_contents("./conf/GoogleClientId.json"), true);

$CLIENT_ID = $json['web']['client_id'];
$CLIENT_SECRET = $json['web']['client_secret'];
$REDIRECT_URI = $json['web']['redirect_uris'][0];
//$REDIRECT_URI = 'http://localhost/FB_Albums/libs/Google/googleLogin.php';
// Set the scopes you need
$SCOPES = array(
    'https://www.googleapis.com/auth/drive.file',
    'https://www.googleapis.com/auth/userinfo.email',
    'https://www.googleapis.com/auth/userinfo.profile');

/**
 * Lets first get an authorization URL to our client, it will forward the client to Google's Concent window
 * @param String $emailAddress
 * @param String $state
 * @return String URL to Google Concent screen
 */
function getAuthorizationUrl($emailAddress, $state) {
    global $CLIENT_ID, $REDIRECT_URI, $SCOPES;
    $client = new Google_Client();

    $client->setClientId($CLIENT_ID);
    $client->setRedirectUri($REDIRECT_URI);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('auto');
    $client->setState($state);
    $client->setScopes($SCOPES);
    $tmpUrl = parse_url($client->createAuthUrl());
    $query = explode('&', $tmpUrl['query']);
    $query[] = 'user_id=' . urlencode($emailAddress);

    return
            $tmpUrl['scheme'] . '://' . $tmpUrl['host'] .
            $tmpUrl['path'] . '?' . implode('&', $query);
}

/**
 * Exchange an authorization code for OAuth 2.0 credentials.
 *
 * @param String $authorizationCode Authorization code to exchange for OAuth 2.0
 *                                  credentials.
 * @return String Json representation of the OAuth 2.0 credentials.
 * @throws An error occurred. And prints the error message
 */
function exchangeCode($authorizationCode) {
    try {
        global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
        $client = new Google_Client();
        $client->setClientId($CLIENT_ID);
        $client->setClientSecret($CLIENT_SECRET);
        $client->setRedirectUri($REDIRECT_URI);
        return $client->authenticate($authorizationCode);
    } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
    }
}

/**
 * Retrieve credentials using the provided authorization code.
 *
 * @param String authorizationCode Authorization code to use to retrieve an access token.
 * @param String state State to set to the authorization URL in case of error.
 * @return String Json representation of the OAuth 2.0 credentials.
 */
function getCredentials($authorizationCode, $state) {
    $emailAddress = '';
    try {
        $credentials = exchangeCode($authorizationCode);
        $_SESSION['credentials'] = $credentials;
        return $credentials;
    } catch (CodeExchangeException $e) {
        print 'An error occurred during code exchange.';
        // Drive apps should try to retrieve the user and credentials for the current
        // session.
        // If none is available, redirect the user to the authorization URL.
        $e->setAuthorizationUrl(getAuthorizationUrl($emailAddress, $state));
        throw $e;
    } catch (NoUserIdException $e) {
        print 'No e-mail address could be retrieved.';
    }
    // No token has been retrieved.
    $authorizationUrl = getAuthorizationUrl($emailAddress, $state);
}

/**
 * Send a request to the UserInfo API to retrieve the user's information.
 *
 * @param String credentials OAuth 2.0 credentials to authorize the request.
 * @return Userinfo User's information.
 * @throws NoUserIdException An error occurred.
 */
function getUserInfo($credentials) {
    $apiClient = new Google_Client();
    $apiClient->setAccessToken($credentials);
    $userInfoService = new Google_Service_Oauth2($apiClient);
    try {
        $userInfo = $userInfoService->userinfo->get();

        if ($userInfo != null && $userInfo->getId() != null) {
            return $userInfo;
        } else {
            echo "No user ID";
        }
    } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
    }
}

/**
 * Insert new file in the Application Data folder.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param string $albumNameAndPhoto albumName of the file to insert and it's photolink.
 * @param string $parentFolderName ParentFolderName for create folder with user  facebook name.
 * @return Google_DriveFile The file that was inserted. NULL is returned if an API error occurred.
 */
function insertFile($service, $albumNameAndPhoto, $parentFolderName) {
    try {

        // Setup the folder you want the file in, if it is wanted in a folder
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId(getFolderExistsCreate($service, "", $parentFolderName, ""));

        for ($i = 0; $i < count($albumNameAndPhoto); $i++) {
            $folderName = array_keys($albumNameAndPhoto[$i]);
            $filename = $albumNameAndPhoto[$i][$folderName[0]];

            $childfolder = new Google_Service_Drive_ParentReference();
            $childfolder->setId(getFolderExistsCreate($service, $folderName[0], "", $parent));

            for ($j = 0; $j < count($filename); $j++) {

                // Set the metadata

                $file = new Google_Service_Drive_DriveFile();
                $file->setTitle("image" . $j);
                $file->setMimeType("image/jpeg");
                $mimeType = "image/jpeg";
                $file->setParents(array($childfolder));

                // Get the contents of the file uploaded
                $data = file_get_contents($filename[$j]);

                // Try to upload the file, you can add the parameters e.g. if you want to convert a .doc to editable google format, add 'convert' = 'true'
                $createdFile = $service->files->insert($file, array(
                    'data' => $data,
                    'mimeType' => $mimeType,
                    'uploadType' => 'multipart'
                ));
            }
        }

        if (isset($_GET['ajax']) == "1") {
            echo "Your Album Move successfully on google drive.";
        } else {
            $_SESSION['msg'] = "Your Album Move successfully on google drive.";
            header('location:../../index.php');
        }
    } catch (Exception $e) {
        if (isset($_GET['ajax']) == "1") {
            print "An error occurred: " . $e->getMessage();
        } else {
            $_SESSION['msg'] = $e->getMessage();
            //header('location:../../index.php?msg='.$e->getMessage());
            header('location:../../index.php');
        }
    }
}

/**
 * Get the folder ID if it exists, if it doesnt exist, create it and return the ID
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param String $folderName Name of the folder you want to search or create
 * @param String $folderDesc Description metadata for Drive about the folder (optional)
 * @return Google_Drivefile that was created or got. Returns NULL if an API error occured
 */
function getFolderExistsCreate($service, $folderName, $parentFolderName, $parentFolderId) {

    // List all user files (and folders) at Drive root
    $files = $service->files->listFiles();
    $found = "0";

    foreach ($files['items'] as $item) {
        if ($parentFolderName != null) {
            if ($item['title'] == $parentFolderName) {
                $found = "1";
                return $item['id'];
                break;
            }
        } else {
            if ($item['title'] == $folderName) {
                $service->files->delete($item['id']);
            }
        }
    }


    // If not, create one
    if ($found == "0") {

        //Create the Folder
        try {
            if ($parentFolderName != "") {
                $folder = new Google_Service_Drive_DriveFile();
                //Setup the folder to create
                $folder->setTitle($parentFolderName);
                $folder->setMimeType('application/vnd.google-apps.folder');
                $createdFile = $service->files->insert($folder, array(
                    'mimeType' => 'application/vnd.google-apps.folder'
                ));
            } else {
                $folder = new Google_Service_Drive_DriveFile();
                $folder->setTitle($folderName);
                $folder->setParents(array($parentFolderId));
                $folder->setMimeType('application/vnd.google-apps.folder');

                $createdFile = $service->files->insert($folder, array(
                    'mimeType' => 'application/vnd.google-apps.folder'
                ));
            }
            // Return the created folder's id
            return $createdFile->id;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }
}
