<?php 
require_once('config.php');
require_once('dbcon.php');
$query = mysqli_query($con, "update `".$config['db']['pre']."userdata` set online = 0 where id = '".$_SESSION['id']."'");
session_unset($_SESSION['id']);

echo '<script>window.location="login.php"</script>';



?>

