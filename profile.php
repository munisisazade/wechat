<?php
require_once('config.php');
require_once('dbcon.php');
if(!isset($_GET['uname']))
{
    if(!isset($_SESSION['id'])) {
        echo "Error in Url";
        exit();
    }
    else{
        $_GET['uname'] = $_SESSION['username'];
    }
}

$query1 = "SELECT * FROM userdata where username = '".$_GET['uname']."'";
$result1 = $con->query($query1);
if(mysqli_num_rows($result1)==0){
    //and we send 0 to the ajax request
    echo "Error: USERID not available";
    exit();
}
$row1 = mysqli_fetch_assoc($result1);

$res1 = mysqli_query($con, "SELECT * FROM `userdata` WHERE id='".$row1['id']."' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;");
if($res1 === FALSE) {
    die(mysqli_error($con)); // TODO: better error handling
}
$num1 = mysqli_num_rows($res1);
if($num1 == "0")
    $onofst1 = "Online";
else
    $onofst1 = "Offline";


$string = $row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "")
    $sesuserpic = "avatar_default.png";

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title><?php echo $row1['name'];?> - Wchat Profile</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="php chat script, php ajax Chat,facebook similar chat, php mysql chat, chat script, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox, facebook similar inbox, facebook like chat" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <meta name="author" content="Wchat - Codentheme.com">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/profile.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body>


<div class="entry-board J_entryBoard col-lg-12">
    <div class="col-lg-12" align="center">
        <div class="pull-left">
            <a href="index.php">Chat Page</a>
        </div>
        <div class="entry pull-left">
            <a href="profile.php">My Profile</a>
        </div> <div class="entry  pull-left">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

<!-- ******HEADER****** -->
<header class="header">
    <div class="container">
        <div class="col-lg-12 " align="center">
            <div class="profile-picture medium-profile-picture mpp XxGreen mnkLeft">
                <img width="169px" style="min-height:170px;" src="storage/user_image/<?php echo $sesuserpic; ?>" alt="<?php echo $_SESSION['username'];?>">
            </div>
            <div class="profile-content pull-left" align="left" >
                <h1 class="name"><?php echo $row1['name'];?></h1>
                <h2 class="desc">#<?php echo $row1['username'];?></h2>
                <ul class="social list-inline">
                    <?php if(!empty($row1['facebook'])){?>
                        <li><a href="<?php echo $row1['facebook'];?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    <?php } ?>
                    <?php if(!empty($row1['twitter'])){?>
                        <li><a href="<?php echo $row1['twitter'];?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                    <?php } ?>
                    <?php if(!empty($row1['googleplus'])){?>
                        <li><a href="<?php echo $row1['googleplus'];?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                    <?php } ?>
                    <?php if(!empty($row1['instagram'])){?>
                        <li><a href="<?php echo $row1['instagram'];?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                    <?php } ?>
                </ul>
            </div><!--//profile-->
            <?php
            if($_GET['uname'] == $_SESSION['username']){ ?>
                <a class="btn btn-cta-primary pull-right" href="edit_profile.php"><i class="fa fa-paper-plane"></i>Edit Profile</a>
            <?php } ?>
        </div>
    </div><!--//container-->
</header><!--//header-->


<div class="container sections-wrapper">
    <div class="row">
        <div class="primary col-md-8 col-sm-12 col-xs-12">
            <section class="about section">
                <div class="section-inner">
                    <h2 class="heading">About Me</h2>
                    <div class="content">
                        <?php echo $row1['about'];?>

                    </div><!--//content-->
                </div><!--//section-inner-->
            </section><!--//section-->

        </div><!--//primary-->
        <div class="secondary col-md-4 col-sm-12 col-xs-12">
            <aside class="info aside section">
                <div class="section-inner">
                    <h2 class="heading sr-only">Basic Information</h2>
                    <div class="content">
                        <ul class="list-unstyled">
                            <?php
                            if(!empty($row1['sex']))
                            {
                                if($row1['sex'] == "male")
                                {
                                    ?><li><i class="fa fa-mars"></i><span class="sr-only">Sex:</span>Male</li><?php
                                }
                                else{
                                    ?><li><i class="fa fa-venus"></i><span class="sr-only">Sex:</span>Female</li><?php
                                }
                            }
                            ?>


                            <li><i class="fa fa-birthday-cake"></i><span class="sr-only">DOB:</span><?php echo $row1['dob'];?></li>
                            <li><i class="fa fa-map-marker"></i><span class="sr-only">Location:</span><?php echo $row1['country'];?></li>
                            <li><i class="fa fa-envelope-o"></i><span class="sr-only">Email:</span><a href="#"><?php echo $row1['email'];?></a></li>
                            <li><i class="fa fa-skype"></i><span class="sr-only">Skype ID:</span><a href="#"><?php echo $row1['skype'];?></a></li>
                        </ul>
                    </div><!--//content-->
                </div><!--//section-inner-->
            </aside><!--//aside-->



        </div><!--//secondary-->
    </div><!--//row-->
</div><!--//masonry-->

<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Developed with <i class="fa fa-heart"></i> by <a href="http://www.byweb.online" target="_blank">Deven Katariya</a> for developers</small>
    </div><!--//container-->
</footer><!--//footer-->

<!-- Javascript -->
<script type="text/javascript" src="assets/plugins/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-rss/dist/jquery.rss.min.js"></script>

<!-- custom js -->
<script type="text/javascript" src="assets/js/main.js"></script>








</body>
</html>

