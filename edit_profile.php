<?php
require_once('config.php');
require_once('dbcon.php');
if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$query1 = "SELECT * FROM userdata where id = '".$_SESSION['id']."'";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_assoc($result1);
$string = $row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "")
    $sesuserpic = "avatar_default.png";


$error = "";

if(isset($_POST['Submit']))
{
    if($_FILES['file']['name'] != "")
    {
        $uploaddir = 'storage/user_image/';
        $original_filename = $_FILES['file']['name'];

        $extensions = explode(".", $original_filename);
        $extension = $extensions[count($extensions) - 1];
        $uniqueName =  $string . "." . $extension;
        $uploadfile = $uploaddir . $uniqueName;

        $file_type = "file";

        if ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") {
            $file_type = "image";

            $size = filesize($_FILES['file']['tmp_name']);

            $image = $_FILES["file"]["name"];
            $uploadedfile = $_FILES['file']['tmp_name'];

            if ($image) {
                if ($extension == "jpg" || $extension == "jpeg") {
                    $uploadedfile = $_FILES['file']['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($extension == "png") {
                    $uploadedfile = $_FILES['file']['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                } else {
                    $src = imagecreatefromgif($uploadedfile);
                }

                list($width, $height) = getimagesize($uploadedfile);

                $newwidth = 225;
                $newheight = ($height / $width) * $newwidth;
                $tmp = imagecreatetruecolor($newwidth, $newheight);

                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                $filename = $uploaddir . "small" . $uniqueName;

                imagejpeg($tmp, $filename, 100);

                imagedestroy($src);
                imagedestroy($tmp);
            }


        }
        //else if it's not bigger then 0, then it's available '
        //and we send 1 to the ajax request
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
            //$time = date('Y-m-d H:i:s', time());
            $query = "Update userdata set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', about='" . $_POST['about'] . "', sex='" . $_POST['sex'] . "', dob='" . $_POST['dob'] . "', skype='" . $_POST['skype'] . "', facebook='" . $_POST['facebook'] . "', twitter='" . $_POST['twitter'] . "', googleplus='" . $_POST['googleplus'] . "', instagram='" . $_POST['instagram'] . "', picname='$uniqueName' WHERE id = {$_SESSION['id']} ";
            $query_result = $con->query($query);

            header("Location: index.php");
            exit;
        }
    }
    else{
        //$time = date('Y-m-d H:i:s', time());
        $query = "Update userdata set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', about='". addslashes($_POST['about'])."', sex='" . $_POST['sex'] . "', dob='" . $_POST['dob'] . "', skype='" . $_POST['skype'] . "', facebook='" . $_POST['facebook'] . "', twitter='" . $_POST['twitter'] . "',googleplus='" . $_POST['googleplus'] . "', instagram='" . $_POST['instagram'] . "' WHERE id = {$_SESSION['id']}";
        $query_result = $con->query($query);

        header("Location: index.php");
        exit;
    }

}


?>
<?php if(!empty($error)) {
    echo '<script type="text/javascript">alert("' . $error . '");</script>';
} ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Wchat - Responsive php ajax inbox messaging</title>
    <meta name="keywords" content="PHP inbox messaging script, php chat script, php ajax Chat,facebook similar chat, php mysql chat, chat script, facebook style chat script, gmail style chat script. fbchat, gmail chat, facebook style message inbox, facebook similar inbox, facebook like chat" />
    <meta name="description"  content="This jQuery chat module easily to integrate Gmail/Facebook style chat into your existing website." />
    <meta name="author" content="Wchat - Codentheme.com">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>


    <!-- Global CSS -->
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/profile.css">



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
                <img width="169px" style="min-height:170px;" src="storage/user_image/small<?php echo $sesuserpic; ?>" alt="<?php echo $_SESSION['username'];?>">
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
        </div>
    </div><!--//container-->
</header><!--//header-->


<div class="middle-container container">
    <div class="middle-dabba col-md-12">
        <h1>Edit Your Profile</h1>
        <div id="post-form" style="padding:10px">

            <form name="form1" method="post" action="" id="send" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="input text">
                        <label for="file">Change Profile Picture </label>
                        <img class="pull-left" src="storage/user_image/<?php echo $sesuserpic; ?>" alt="<?php echo $_SESSION['username'];?>"  style="width: 42px; border-radius: 50%"/>
                        <input type="file" name="file" style="width:70%">
                    </div>

                    <div class="input text">
                        <label for="name">Fullname </label><input type="text" name="name" value="<?php echo $row1['name'];?>">
                    </div>

                    <div class="input text">
                        <label for="dob">Date of Birth </label><input type="text" name="dob" placeholder="Format : 02-April-1992" value="<?php echo $row1['dob'];?>">
                    </div>

                    <div class="input text">
                        <label for="about">About Me </label>
                        <textarea name="about" style="width: 95%;height: 137px"><?php echo $row1['about'];?></textarea>
                    </div>

                    <div class="input text">
                        <label for="sex">Sex</label>
                        <input type="radio" name="sex" value="male" style="width: 10%" <?php if($row1['sex'] == "male") { echo "checked"; }?>> Male <br>
                        <input type="radio" name="sex" value="female" style="width: 10%" <?php if($row1['sex'] == "female") { echo "checked"; }?>> Female

                    </div>
                </div>


                <div class="col-md-6">
                    <div class="input text">
                        <label for="email">Email</label><input type="text" name="email" value="<?php echo $row1['email'];?>">
                    </div>

                    <div class="input text">
                        <label for="skype">Skype ID</label><input type="text" name="skype" value="<?php echo $row1['skype'];?>">
                    </div>

                    <div class="input text">
                        <label for="facebook">Facebook</label><input type="text" name="facebook" value="<?php echo $row1['facebook'];?>">
                    </div>

                    <div class="input text">
                        <label for="googleplus">Google Plus</label><input type="text" name="googleplus" value="<?php echo $row1['googleplus'];?>">
                    </div>

                    <div class="input text">
                        <label for="twitter">Twitter</label><input type="text" name="twitter" value="<?php echo $row1['twitter'];?>">
                    </div>

                    <div class="input text">
                        <label for="instagram">Instagram</label><input type="text" name="instagram" value="<?php echo $row1['instagram'];?>">
                    </div>
                </div>
            </div>
            <div class="col-md-12" align="center">
                <button class="btn btn-cta-theme" type="submit" name="Submit">Save</button>
            </div>

            </form>
        </div>
    </div>
</div>


<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <!--/* This template is released under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using for your own project. Thank you for your support. :) If you'd like to use the template without the attribution, you can check out other license options via our website: themes.3rdwavemedia.com */-->
        <small class="copyright">Developed with <i class="fa fa-heart"></i> by <a href="http://www.byweb.online" target="_blank">Deven Katariya</a> for developers</small>
    </div><!--//container-->
</footer><!--//footer-->



</body>
</html>