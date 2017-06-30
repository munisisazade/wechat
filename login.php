<?php
require_once('config.php');
require_once('function.php');
checkinstall($config);
require_once('dbcon.php');

if(isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
function getLocationInfoByIp(){
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country'=>'', 'city'=>'');
    if(filter_var($client, FILTER_VALIDATE_IP)){
        $ip = $client;
    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
        $ip = $forward;
    }else{
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    if($ip_data && $ip_data->geoplugin_countryName != null){
        $result['code'] = $ip_data->geoplugin_countryCode;
        $result['country'] = $ip_data->geoplugin_countryName;
        $result['city'] = $ip_data->geoplugin_city;
    }
    return $result;
}
/*$countryIP = getLocationInfoByIp();
$countrycode = $countryIP['code'];
$countryname = $countryIP['country'];*/

$countrycode = "IN";
$errors = array();

if(isset($_POST['signup']))
{
    function validStrLen($str, $min, $max, $con, $config){
        $len = strlen($str);
        if($len < $min){
            return "Username is too short, minimum is $min characters ($max max)";
        }
        elseif($len > $max){
            return "Username is too long, maximum is $max characters ($min min).";
        }
        elseif(!preg_match("/^[a-zA-Z0-9]+$/", $str))
        {
            return "Only use numbers and letters please";
        }
        else{
            //get the username
            $username = mysqli_real_escape_string($con, $_POST['username']);

            //mysql query to select field username if it's equal to the username that we check '
            $result = mysqli_query($con, "select username from `".$config['db']['pre']."userdata` where username = '".$username."'");

            //if number of rows fields is bigger them 0 that means it's NOT available '
            if(mysqli_num_rows($result)>0){
                //and we send 0 to the ajax request
                return "Error: Username not available";
            }
        }
        return TRUE;
    }

    $errors['username'] = validStrLen($_POST['username'], 4, 10, $con, $config);




    if($errors['username'] == 1)
    {

            /*$time = date('Y-m-d H:i:s', time());*/
            $query = "insert into `".$config['db']['pre']."userdata` set name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] . "', password='" . $_POST['password'] . "', joined = NOW(), country='$countrycode', last_active_timestamp = NOW() ";
            $query_result = $con->query($query);

            $user_id = $con->insert_id;
            $username = $_POST['username'];
            if (isset($user_id)) {
                $_SESSION['id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: responsive.php");
                exit;
            } else {
                $error = "Error: Username & Password do not match";
            }

    }

}
if(isset($_POST['login']))
{
    $query = "SELECT id,username,password FROM `".$config['db']['pre']."userdata` WHERE username='" . $_POST['username'] . "' AND password='" . $_POST['password'] . "' LIMIT 1";
    $query_result = $con->query($query);
    $info = mysqli_fetch_array($query_result);
    $user_id = $info['id'];
    $username = $info['username'];

    if(isset($user_id))
    {
        $_SESSION['id'] = $user_id;
        $_SESSION['username'] = $username;
        $query2 = mysqli_query($con, "update `".$config['db']['pre']."userdata` set online = 1 where id = '".$_SESSION['id']."'");

        $res = mysqli_query($con, "UPDATE `".$config['db']['pre']."userdata` SET online=1, last_active_timestamp = NOW() WHERE id = {$_SESSION['id']};");

        header("Location: responsive.php");
        exit;
    }
    else
    {
        $error = "Error: Username & Password do not match";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Wchat - Login</title>
    <!-- Bootstrap Core CSS -->
    <link href="assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- Animation CSS -->
    <link href="assets/css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style-light.css" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <!-- We have chosen the skin-blue (blue.css) for this starter
              page. However, you can choose any other skin from folder css / colors .
    -->
    <link href="assets/css/colors/blue.css" id="theme"  rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
    <div class="login-box">
        <div class="white-box">
            <form class="form-horizontal form-material" id="loginform" method="post" action="#">
                <h3 class="box-title m-b-20">Sign In</h3>
                <span style="color:#df6c6e;">
                    <?php
                    if(!empty($error)){
                        echo '<div class="byMsg byMsgError">! '.$error.'</div>';
                    }
                    ?>
                </span>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" required="" placeholder="Username" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="password" required="" placeholder="Password" name="password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary pull-left p-t-0">
                            <input id="checkbox-signup" type="checkbox">
                            <label for="checkbox-signup"> Remember me </label>
                        </div>
                        <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" name="login" type="submit">Log In</button>
                    </div>
                </div>
                <!--<div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                        <div class="social"><a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"  title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a> <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"  title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a> </div>
                    </div>
                </div>-->
                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <p>Don't have an account? <a href="register.php" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                    </div>
                </div>
            </form>
            <form class="form-horizontal" id="recoverform" action="login.php">
                <div class="form-group ">
                    <div class="col-xs-12">
                        <h3>Recover Password</h3>
                        <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" required="" placeholder="Email">
                    </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- jQuery -->
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<!--<script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>-->
<!--slimscroll JavaScript -->
<script src="assets/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<!--<script src="js/waves.js"></script>-->
<!-- Custom Theme JavaScript -->
<script src="assets/js/custom.js"></script>
<!--Style Switcher -->
<script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
<!--Style Switcher -->
</body>
</html>
