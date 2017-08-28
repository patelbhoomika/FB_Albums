Working Demo : http://ratneshsharma.com/fb_albums/

# How It Work…

##### STEP 1 :
=> User Login using Facebook credentials. Ask user to give permission to access of email,cover_photo,name and photos. Application fetches user’s all Albums form facebook acccount.

#### STEP 2 :
=> Albums are displayed with a Thumbnail, Album Name. When a user clicks on view more all photos for that album are displayed in screen slideshow.

=> A Down aero image work as "Download" link is displayed for each album. When user clicks on "Download" link, jquery(Ajax) processes PHP script to collect photos for that album, Zip them and prompts "Download Zip Folder" Link to user for download.

=> An checkbox is displayed for each album.A "Download Selected" link is displayed at bottom. When user clicks on "Download Selected " link, jquery(Ajax) processes PHP script to collect photos for all checked albums, Zip them and prompts "Download Zip Folder" Link to user for download.

=> A "Download All" link is displayed at bottom. When user clicks on "Download All" link, jquery(Ajax) processes PHP script to collect photos for all albums, Zip them and prompts "Download Zip Folder" Link to user for download.

=> All the time while albums are download and processed into zip, a loading spinner is showing.

#### STEP 3 :
=> NOTE : At first time if user is not login to google account then it sends to login page and asks to grant access from user.

=> A Up aero image work as "Move" link is displayed for each album. When user clicks on "Move" link, jquery(Ajax) processes PHP script to collect photos for that album and upload on Google drive.

=> An checkbox is displayed for each album. A "Move Selected" link is displayed at bottom. When user clicks on "Move Selected" link, jquery(Ajax) processes PHP script to collect photos for all checked albums and upload on Google drive .

=> A "Move All" link is displayed at bottom. When user clicks on "Move All" link, jquery(Ajax) processes PHP script to collect photos for all albums and upload into on Google drive.

=> All the time while albums are processed to move, a loading spinner is showing.


# Library Used...

### Facebook PHP SDK
 The Facebook SDK for PHP provides developers with a modern, native library for accessing the Graph API and taking advantage of Facebook Login. Usually this means you're developing with PHP for a Facebook Canvas app, building your own website, or adding server-side functionality to an app. More information and examples: https://developers.facebook.com/docs/reference/php/

### Google APIs Client Library
 The Google API Client Library enables you to work with Google APIs such as Google+, Drive on your server. More information and examples: https://developers.google.com/drive/v3/web/about-sdk ,  https://travis-ci.org/google/google-api-php-client

### Animate.css
 animate.css is a bunch of cool, fun, and cross-browser animations for you to use in your projects. Great for emphasis, home pages, sliders, and general just-add-water-awesomeness.More information and examples: https://daneden.github.io/animate.css/

### Bootstrap
 Twitter Bootstrap Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web. More information and examples: http://getbootstrap.com/

### Jquery-Confirm Msg Box
 A jqury plugin that provides great set of features like auto-close,ajax-loading,themes,animation and more. More information and examples: https://craftpip.github.io/jquery-confirm/


# How To use

#### STEP 1:For FACEBOOK

=> First of all Go on https://developers.facebook.com/=> From menu select Apps->Add a New App->WWW->Give Name an create new app id. => Test version of another -> select No => Choose your category -> clicks Create App ID. => Right-Top corner select skip quick start.

=> After that in your app go to Settings Add: -> Namespace -> Contact Email

=> In settings +Add Platform-> Select Website Add: -> Site Url -> Domain NOTE : even if localhost url also works.

=> NOTE: if you want the all photos permission of users then you need to approve your facebook app first.

=> Download our app from github => put this in root directory(Wamp => www, xampp => htdocs) => unzip it. => go to appConfig.php Set: $app_id = 'your-fb-app-key'; $app_secret = 'your-fb-app-secret-key';
	
        //fb_login_url is same url which is added in facebook app->settings.
	$fb_login_url = 'your login url or index url where the response is come'; 

#### STEP 2:For GOOGLE

(1)Creating a Google API Console project and client ID follw step from https://developers.google.com/identity/sign-in/web/devconsole-project 

(2) In https://console.developers.google.com select your created project and download json add downloaded json file in google->conf->as googleClientId.json file in unzipped code.

(3)In https://console.developers.google.com select your created project ->go in Library menu->in G Suit APIs select Drive API and enable it.

ENJOY…
