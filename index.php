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
        
        <!-- Msgbox -->
        <link href="assets/msgbox/jquery-confirm.min.css" rel="stylesheet">
        <script src="assets/msgbox/jquery-confirm.min.js"></script>


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
                        if (isset($_SESSION['msg'])) {
                            ?>
                            <script type="text/javascript" charset="utf-8">
                                $.alert({
                                    title: <?php echo "'Hey!  " . $_SESSION['FBNAME'] . "'" ?>,
                                    type: 'dark',
                                    animationBounce: 2.5,
                                    animation: 'top',
                                    content: <?php echo "'" . $_SESSION['msg'] . "'" ?>
                                });
                            </script>
                            <?php
                            unset($_SESSION['msg']);
                        }


                        if (!isset($_SESSION['FBID'])) {
                            require_once 'libs/Facebook/autoload.php';
                            $permissions = ['user_photos '];
                            $loginUrl = $helper->getLoginUrl($fb_login_url, $permissions);
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
                }
                if (isset($_SESSION["googleUserName"])) {
                    $google_session_token = $_SESSION["googleUserName"];
                } else {
                    $google_session_token = "unset";
                } ?>
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

    function ajax_link(url) {
        $.confirm({
            animationBounce: 2.5,
            animation: 'top',
            content: function () {
                var self = this;
                return $.ajax({
                    url: url,
                }).done(function (response) {
                    self.setType('dark');
                    self.setContent(response);
                    self.setTitle(<?php echo "'Hey!  " . $_SESSION['FBNAME'] . "'" ?>);
                }).fail(function () {
                    self.setType('dark');
                    self.setContent('Something went wrong.');
                });
            }
        });
    }



    function moveToPicasa(selectedAlbumId, type)
    {
        var google_session_token = '<?php echo $google_session_token; ?>';

        if (google_session_token == "unset")
        {
            ajax_link("libs/Google/googleLogin.php?ajax=1&selectedAlbumId=" + selectedAlbumId + "&type=" + type);
        } else
        {
            ajax_link("libs/Google/googleLogin.php?ajax=1&selectedAlbumId=" + selectedAlbumId + "&type=" + type);
        }
    }

    function getAlbumId(id, type)
    {

        switch (type) {
            case "download":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                ajax_link("albumsAjax.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type);
                break;
            case "selectedDownload":
                var selectedAlbumId = get_selected_albums();
                if (selectedAlbumId == "") {
                    alert("NO Album Selected!");
                } else
                {
                    ajax_link("albumsAjax.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type);
                }
                break;
            case "allDownload":
                var allAlbumId = get_all_albums();
                ajax_link("albumsAjax.php?selectedAlbumId=" + allAlbumId + "&type=" + type);
                break;
            case "move":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                moveToPicasa(selectedAlbumId, type);
                break;
            case "selectedMove":
                var selectedAlbumId = get_selected_albums();
                if (selectedAlbumId == "") {
                    alert("NO Album Selected!");
                } else
                {
                    moveToPicasa(selectedAlbumId, type);
                }
                break;
            case "allMove":
                var selectedAlbumId = get_all_albums();
                moveToPicasa(selectedAlbumId, type);
                break;
            case "slideShow":
                var selectedAlbumId = $("#selectedAlbumId" + id).val();
                $.ajax({
                    type: 'GET',
                    url: "albumsAjax.php?selectedAlbumId=" + selectedAlbumId + "&type=" + type,
                    success: function (response) {
                        $('#carousel-inner').html(response);
                        $('#myModal').modal('show');
                    }
                });
                break;
            default:

        }
    }

</script>
