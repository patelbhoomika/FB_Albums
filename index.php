<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>Facebook Albums</title>

        <!-- Google fonts -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Josefin+Sans:600' rel='stylesheet' type='text/css'>

        <!-- font awesome -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

        <!-- jquery -->
        <script src="assets/jquery.js"></script>

        <!-- bootstrap -->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
        <script src="assets/bootstrap/js/bootstrap.js" type="text/javascript" ></script>

        <!-- animate.css -->
        <link rel="stylesheet" href="assets/animate/animate.css" />
        <link rel="stylesheet" href="assets/animate/set.css" />

        <!-- favicon -->
        <link rel="icon" href="images/favicon.jpg" type="image/x-icon">

        <!-- custome css -->
        <link rel="stylesheet" href="assets/style.css">

        <!-- Spinner  -->
        <link href="assets/spinner/jquery-loading.css" rel="stylesheet">
        <script src="assets/spinner/jquery-loading.js"></script>


    </head>
    <?php require_once './appConfig.php'; ?>
    <body>
        <!-- Slider Starts -->
        <div class="banner container" >
            <img src="images/back.jpg" alt="banner" class="img-responsive">
            <div class="caption">
                <div class="caption-wrapper">
                    <div class="caption-info"> 
                        <?php
                        if (!isset($_SESSION['FBID'])) {
                            require_once 'libs/Facebook/autoload.php';
                            $permissions = ['user_photos '];
                            $loginUrl = $helper->getLoginUrl('http://localhost:99/FB_Albums/fb-callback.php', $permissions);
                            echo ' <p class="animated bounceInLeft">Show Download and Move your FACEBOOK Album. </p>';
                            echo '<div class="animated bounceInDown"><a class="btn btn-default " href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a></div>';
                        } else {
                            ?>
                            <p class="animated bounceInLeft">Show Download and Move your FACEBOOK Album. </p>
                            <div class="animated bounceInDown"><a href="logout.php" class="btn btn-default ">LOGOUT</a></div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- #Slider Ends -->
        <!-- works -->
        <div id="works"  class=" clearfix grid">
            <?php
            if (isset($_SESSION['FBID'])) {
                $google_session_token = "";
                if (isset($_SESSION['google_session_token'])) {
                    $google_session_token = $_SESSION['google_session_token'];
                }
                for ($i = 1; $i < count($_SESSION['ALBUMS']); $i++) {
                    ?>
                    <figure class="effect-oscar  wowload fadeInUp">
                        <input type="hidden" value="<?php echo $_SESSION['ALBUMS'][$i]['id'] . '$' . $_SESSION['ALBUMS'][$i]['name']; ?>" id="<?php echo "selectedAlbumId" . $i; ?>">
                        <img src="<?php echo $_SESSION['ALBUMS'][$i]['picture']['data']['url']; ?>"> 
                        <figcaption>
                            <h5><?php echo $_SESSION['ALBUMS'][$i]['name']; ?></h5>
                            <p>
                                <input type="checkbox" style="width:20px;height:16px;" value="<?php echo $_SESSION['ALBUMS'][$i]['id'] . '$' . $_SESSION['ALBUMS'][$i]['name']; ?>" id="selectedAlbumIdByChk[]" name="selectedAlbumIdByChk" >
                                <a onclick="getAlbumId(<?php echo $i; ?>, 'slideShow');" id="abc" >View more</a>
                                <img src="images/download.png" class="download-img" style="min-width:30px !important;display:inline;   max-width: 30px;min-height:30px !important;max-height: 30px;" onclick="getAlbumId(<?php echo $i; ?>, 'download');" />
                                <img src="images/move.png" class="download-img" style="min-width:30px !important;display:inline;   max-width: 30px;min-height:30px !important;max-height: 30px;" onclick="getAlbumId(<?php echo $i; ?>, 'move');" />
                            </p>  
                        </figcaption>
                    </figure>
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner" id="carousel-inner">
                                                <div class="item active">
                                                     <img src="<?php echo $_SESSION['ALBUMS'][$i]['picture']['data']['url']; ?>" class="img-responsive"> 
                                                </div>
                                            </div>

                                            <!-- Left and right controls -->
                                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                            </a>
                                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                            </a>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>

                            </div>
                        </div>

                    <?php
                } ?>
            </div>
         <div class="alert alert-success" id="success-alert" style="display: block;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong id="msg">Success! </strong>

            </div>
            <input type="button" value="Download Selected" class="btn success" onclick="getAlbumId('', 'selectedDownload');" />
            <input type="button" value="Download All" class="btn success" onclick="getAlbumId('', 'allDownload');" />
            <input type="button" value="Move Selected" class="btn success" onclick="getAlbumId('', 'selectedMove');" />
            <input type="button" value="Move All" class="btn success" onclick="getAlbumId('', 'allMove');" />
            <!-- works Ends-->
            <?php
            }
        ?>
    </body>
</html>
<script type="text/javascript" charset="utf-8">
    //$(document).ready (function(){
    function get_selected_albums() {
        var selectedAlbumIdByChk = [];
        $("input:checkbox[name=selectedAlbumIdByChk]:checked").each(function () {
            selectedAlbumIdByChk.push($(this).val());
        });
        return selectedAlbumIdByChk;
    }
    function get_all_albums() {
        var allAlbumIdByChk = [];
        $("input:checkbox[name=selectedAlbumIdByChk]").each(function () {
            allAlbumIdByChk.push($(this).val());
        });
        return allAlbumIdByChk;
    }

    $("#success-alert").hide();



    function ajax_link(url) {
        
         $.ajax({
                    type: 'GET',
                    url: url,
                     success: function (response) {
                         alert(response);
                       // return response;
                    }
                    
                });
        
        

//        $('.container').loading({
//
//            // add an overlay background
//            overlay: false,
//
//            // set fixed width to loading indicator, otherwise calculated relative to element
//            width: null,
//
//            // html template
//            indicatorHtml: "<div class='js-loading-indicator' style='display: none;'></div>",
//            overlayHtml: "<div class='js-loading-overlay' style='display: none;'></div>",
//
//            // indicator's width/height relative to element
//            base: 0.9,
//
//            // number of indicator circles: maximum is 3
//            circles: 3,
//
//            // position options
//            top: null,
//            left: null,
//
//            // hide the indicator of the current element
//            hide: false,
//
//            //remove the indicator from the DOM
//            destroy: false
//
//        });


//        $.ajax({
//            url: url,
//            success: function (result) {
//               // $("#msg").html(result);
//                // spinner.stop();
//                //$("#success-alert").alert();
//                window.setTimeout(function () {
//                    $("#success-alert").alert('close');
//                }, 2000);
//            }
//        });

    }

    // });
    function getAlbumId(id, type)
    {
       
        switch (type) {
            case "download":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                window.location.href = "albumsFunction.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type;
                break;
            case "selectedDownload":
                var selectedAlbumId = get_selected_albums();
                if (selectedAlbumId == "") {
                    alert("NO Album Selected!");
                } else
                {
                    window.location.href = "albumsFunction.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type;
                }
                break;
            case "allDownload":
                var allAlbumId = get_all_albums();
                window.location.href = "albumsFunction.php?selectedAlbumId=" + allAlbumId + "&type=" + type;
                break;
            case "move":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                window.location.href = "libs/Google/googleLogin.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type;
                break;
            case "selectedMove":
                var selectedAlbumId = get_selected_albums();
                if (selectedAlbumId == "") {
                    alert("NO Album Selected!");
                } else
                {
                    window.location.href = "libs/Google/googleLogin.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type;
                }
                break;
            case "allMove":
                var allAlbumId = get_all_albums();
                   window.location.href = "libs/Google/googleLogin.php?selectedAlbumId=" + allAlbumId + "&type=" + type; 
                break;
            case "slideShow":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                $.ajax({
                    type: 'GET',
                    url: "albumsFunction.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type,
                    success: function (response) {
                        //console.log(response);
                        // alert(response);
                        $('#carousel-inner').html(response);
                        $('#myModal').modal('show');
                    }
                });
                break;
            default:

        }
    }

//    function getAlbumId(id, type)
//    {
//
//    var selectedAlbumId = $("#selectedAlbumId" + id).val();
//    if (type == "allDownload")
//    {
//        var selectedAlbumIdByChk = [];
//        $("input:checkbox[name=selectedAlbumIdByChk]").each(function () {
//        selectedAlbumIdByChk.push($(this).val());
//        });
//        window.location.href = "albumsPhoto.php?selectedAlbumId=" + selectedAlbumIdByChk + "&type=" + type;
//    } else if (type == "selectedDownload")
//    {
//        var selectedAlbumIdByChk = [];
//        $("input:checkbox[name=selectedAlbumIdByChk]:checked").each(function () {
//        selectedAlbumIdByChk.push($(this).val());
//        });
//        if (selectedAlbumIdByChk == "")
//        {
//        alert("NO Album Selected!");
//        } else
//        {
//        window.location.href = "albumsPhoto.php?selectedAlbumId=" + selectedAlbumIdByChk + "&type=" + type;
//        }
//    } else if (type == "download")
//    {
//    window.location.href = "albumsPhoto.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type;
//    }
//    //else work for slideshow
//    else
//    {
//    $.ajax({
//    type: 'POST',
//            url: 'albumsPhoto.php',
//            data: {'selectedAlbumId': selectedAlbumId, 'type': type},
//            success: function (response) {
//            $('#carousel-inner').html(response);
//            $('#myModal').modal('show');
//            }
//    });
//    }
//    }

</script>
