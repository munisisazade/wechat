<?php
require_once('../config.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
$install_version = '1.0';


if(isset($_GET['lang']))
{
	$_POST['lang'] = $_GET['lang'];
}

if(isset($_POST['lang']))
{
	require_once('lang/lang_'.$_POST['lang'].'.php');
}

// Check to see if the script is already installed
if(isset($config['installed']))
{
	if($config['version'] == $install_version)
	{
		// Exit the script
		exit('Wchat is already installed.');
	}
	else
	{
		header('Location: upgrade_'.$config['version'].'.php');
		exit;
	}
}

$error = '';

// Check that their config file is writtable
if(is_writable('../config.php'))
{
	if(!isset($_POST['lang']))
	{
		$step = 2;
	}
	else
	{
		if(!isset($_POST['DBHost']))
		{
			$step = 3;
		}
		else
		{
			// Test the connection
            //$conLink = new mysqli($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass'], $_POST['DBName']);
            //$con = mysqli_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']);
            if(mysqli_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']))
            {
                if($conLink = mysqli_select_db(mysqli_connect($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass']), $_POST['DBName']))
				{
					if(isset($_POST['adminuser']))
					{
						if(trim($_POST['adminuser']) == '')
						{
							$step = 4;
						}
						else
						{
							//$site_path = str_replace('\\','/',preg_replace('install', '', dirname(__FILE__)));
                            //$site_url = "http://" . $_SERVER['HTTP_HOST'] . ereg_replace ("index.php", "", ereg_replace ("install/", "", $_SERVER['PHP_SELF']));
									
							// Content that will be written to the config file
							$content = "<?php\n";
							$content.= "\$config['db']['host'] = '".addslashes($_POST['DBHost'])."';\n";
							$content.= "\$config['db']['name'] = '".addslashes($_POST['DBName'])."';\n";
							$content.= "\$config['db']['user'] = '".addslashes($_POST['DBUser'])."';\n";
							$content.= "\$config['db']['pass'] = '".addslashes($_POST['DBPass'])."';\n";
							$content.= "\$config['db']['pre'] = '".addslashes($_POST['DBPre'])."';\n";
							$content.= "\n";
							$content.= "\$config['site_title'] = 'Wchat';\n";
							$content.= "\$config['site_url'] = '".addslashes($_POST['site_url'])."';\n";
                            $content.= "\n";
							$content.= "\$config['version'] = '".$config['version']."';\n";
							$content.= "\$config['installed'] = '1';\n";
							$content.= "?>";
						
							// Open the includes/config.php for writting
							$handle = fopen('../config.php', 'w');
							// Write the config file
							fwrite($handle, $content);
							// Close the file
							fclose($handle);

                            // Create connection in MYsqli
                            $con = new mysqli($_POST['DBHost'], $_POST['DBUser'], $_POST['DBPass'], $_POST['DBName']);
                            // Check connection
                            if ($con->connect_error) {
                                die("Connection failed: " . $con->connect_error);
                            }

// Create USER Table
                            $table_userdata = "CREATE TABLE `".addslashes($_POST['DBPre'])."userdata` (
                            `id` int(11) unsigned NOT NULL auto_increment,
                            `username` varchar(40) NOT NULL default '',
                            `password` varchar(50) NOT NULL default '',
                            `email` varchar(225) NOT NULL default '',
                            `name` varchar(40) NOT NULL default '',
                            `country` text NOT NULL default '',
                            `about` longtext NOT NULL default '',
                            `sex` varchar(40) NOT NULL default '',
                            `dob` text NOT NULL default '',
                            `skype` varchar(40) NOT NULL default '',
                            `facebook` varchar(40) NOT NULL default '',
                            `twitter` varchar(40) NOT NULL default '',
                            `googleplus` varchar(40) NOT NULL default '',
                            `instagram` varchar(40) NOT NULL default '',
                            `picname` varchar(225) NOT NULL default '',
                            `online` tinyint(1) unsigned NOT NULL default '0',
                            `last_active_timestamp` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
                            `joined` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
                             PRIMARY KEY  (`id`))";

// Create MESSAGES Table
                            $table_messages = "CREATE TABLE `".addslashes($_POST['DBPre'])."messages` (
                            `message_id` int(11) unsigned NOT NULL auto_increment,
                            `from_id` varchar(40) NOT NULL default '',
                            `to_id` varchar(50) NOT NULL default '',
                            `from_uname` varchar(225) NOT NULL default '',
                            `to_uname` varchar(255) NOT NULL default '',
                            `message_content` longtext NOT NULL default '',
                            `message_date` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
                            `recd` TINYINT( 1 ) NOT NULL DEFAULT  '0',
                            `message_type` varchar(255) NOT NULL default '',  PRIMARY KEY  (`message_id`))";


                            $createTablemsg = array();

                            if ($con->query($table_userdata) === TRUE) {
                                $createTablemsg[] = "<span style='color: #43dc43;'>Table ".$_POST['DBPre']."userdata created successfully </span></br>";
                            } else {
                                $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table: " . $con->error ." </span></br>";
                            }
                            if ($con->query($table_messages) === TRUE) {
                                $createTablemsg[] = "<span style='color: #43dc43;'>Table ".$_POST['DBPre']."messages created successfully </span></br>";
                            } else {
                                $createTablemsg[] = "<span style='color: #dc4366;'>Error creating table: " . $con->error." </span></br>";
                            }

                            $con->close();

                            $step = 5;
						}
					}
					else
					{
						$step = 4;
					}
				}
				else
				{

					$error_number = mysqli_connect_errno();
				
					if($error_number == '1044')
					{
						$error = $lang['ERROR1044'];
					}
					elseif($error_number == '1046')
					{
						$error = $lang['ERROR1046'];
					}
					elseif($error_number = '1049')
					{
						$error = $lang['ERROR1049'];
					}
					else
					{
						$error = mysqli_connect_error().' - '.$error_number;
					}
					$step = 3;
				}
			}
			else
			{
				$error_number = mysqli_connect_error();
			
				if($error_number == '1045')
				{
					$error = $lang['ERROR1045'];
				}
				elseif($error_number == '2005')
				{
					$error = $lang['ERROR2005'];
				}
				else
				{
					$error = mysqli_connect_error().' - '.$error_number;
				}
				$step = 3;
			}
		}
	}
}
else
{
	$step = 1;
	$error = 'Could not write to your config.php file.<br><br>Please check that you have set the chmod/permisions to 0777';
}

if($step == 1)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wchat Installation</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }
.error { color:#FF0000;}
-->
</style></head>

<body>
<table width="500"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="500%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><span class="style1">Wchat Installation</span></td>
        <td align="right" valign="bottom">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><br></td>
  </tr>
  <tr>
    <td>
	<br><br>
	<span class="error"><?php echo $error;?></span><br><br><br>
	<a href="index.php">Click here</a> once you have corrected this.<br><br><br><br><bR>
    </td>
  </tr>
  <tr>
    <td><div align="center"><span class="style5">&copy; 2008 <a>Byweb.online</a></span></div></td>
  </tr>
</table>
</body>
</html>
<?php
}
elseif($step == 2)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wchat Installation</title>
<style type="text/css">

body {
    background: #fff;
}
body {
    background: #f6f9fb;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    overflow-x: hidden;
    color: #686868;
    font-weight: 300;
}
.container {
    width: 1170px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
input[type=text],input[type=password] {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #545658;
    border: 2px solid #C9C9C9 !important;
    padding: 8px;
    font-size: 14px;
    border-radius: 2px 2px 2px 2px;
}
input, textarea {
    font: 14px/24px Helvetica, Arial, sans-serif;
    color: #666;width: 300px;
}
    td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
    font-weight: 700;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
	text-align: center;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }

.byMsg {
    border: 2px solid #7d7d7d;
    border-radius: 4px;
    margin-bottom: 24px;
    padding: 10px 10px 10px 25px;
    position: relative;
    font-size: 13px;
}
.byMsgError {
    border: 2px solid #d50000 !important;
    color: #d50000;
}
.coffe.button {
    background: #5bbc2e;
}
.coffe.button:hover {
    background: #008329;
}
.button {
    width: auto;
    min-width: 100px;
    font-weight: 600;
    font-size: 12px;
    font-family: 'Ubuntu',sans-serif;
    color: #fff;
    text-transform: uppercase;
    line-height: 35px;
    border: none;
    border-radius: 2px;
    -webkit-transition: background .3s;
    transition: background .3s;
    text-decoration: none;
}
button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    border: none;
}
</style>

</head>

<body>
<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Wchat Installation</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>Please select the language you would like Wchat to use:<br><small style="color:#FF0000;">*Some parts of the installation may not be in your chosen language</small><Br><br>

                <table  border="0" cellspacing="0" cellpadding="10">
                    <tr>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=english"><img src="images/flag_en.gif" alt="English" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=english">English</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=german"><img src="images/flag_german.gif" alt="Deutsch" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=german">Deutsch</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=french"><img src="images/flag_french.gif" alt="French" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=french">Fran&ccedil;ais</a></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=spanish"><img src="images/flag_spanish.gif" alt="Espanol" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=spanish">Espa&ntilde;ol</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left">
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center"><a href="index.php?lang=italian"><img src="images/flag_italian.gif" alt="Italian" width="130" height="87" vspace="2" border="0"></a><br><a href="index.php?lang=italian">Italian</a></td>
                                </tr>
                            </table>
                        </td>
                        <td width="33%" height="140" align="left"></td>
                    </tr>
                </table>
            <br>
            <br>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Byweb.online</a></span></div></td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
}
elseif($step == 3)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Step 2 - Wchat Installation</title>
<style type="text/css">

body {
    background: #fff;
}
body {
    background: #f6f9fb;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    overflow-x: hidden;
    color: #686868;
    font-weight: 300;
}
.container {
    width: 1170px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
input[type=text],input[type=password] {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #545658;
    border: 2px solid #C9C9C9 !important;
    padding: 8px;
    font-size: 14px;
    border-radius: 2px 2px 2px 2px;
}
input, textarea {
    font: 14px/24px Helvetica, Arial, sans-serif;
    color: #666;width: 300px;
}
    td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
    font-weight: 700;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
	text-align: center;
	padding-top: 20px;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }

.byMsg {
    border: 2px solid #7d7d7d;
    border-radius: 4px;
    margin-bottom: 24px;
    padding: 10px 10px 10px 25px;
    position: relative;
    font-size: 13px;
}
.byMsgError {
    border: 2px solid #d50000 !important;
    color: #d50000;
}
.coffe.button {
    background: #5bbc2e;
}
.coffe.button:hover {
    background: #008329;
}
.button {
    width: auto;
    min-width: 100px;
    font-weight: 600;
    font-size: 12px;
    font-family: 'Ubuntu',sans-serif;
    color: #fff;
    text-transform: uppercase;
    line-height: 35px;
    border: none;
    border-radius: 2px;
    -webkit-transition: background .3s;
    transition: background .3s;
    text-decoration: none;
}
button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    border: none;
}
</style>
</head>

<body>
<div class="container">
    <table  border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Wchat Installation</span></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
                    <table border="0" cellspacing="10" cellpadding="3" align="center">
                        <tr>
                            <td align="center"><?php echo $lang['MYSQLFILL']; ?></td>
                        <tr/>
                        <tr>
                            <td align="center">
                                <?php
                                if($error != '')
                                {
                                    echo '<span class="byMsg byMsgError">! '.$error.'</span><br><Br>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table border="0" cellspacing="0" cellpadding="3" align="center">
                        <tr>
                            <td><span class="style12">Wchat URL: </span></td>
                            <td><input name="site_url" type="text" id="site_url" value="<?php if(isset($_POST['site_url'])){ echo $_POST['site_url']; } else { echo 'http://www.example.com/wchat/'; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('Write the exact path of wchat on your domain. Its Must to add / at the end of url');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLHOST'];?>: </span></td>
                            <td><input name="DBHost" type="text" id="DBHost" value="<?php if(isset($_POST['DBHost'])){ echo $_POST['DBHost']; } else { echo 'localhost'; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['HOSTHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLUSER'];?>:</span></td>
                            <td><input name="DBUser" type="text" id="DBUser" value="<?php if(isset($_POST['DBUser'])){ echo $_POST['DBUser']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['USERHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLPASS'];?>:</span></td>
                            <td><input name="DBPass" type="password" id="DBPass" value="<?php if(isset($_POST['DBPass'])){ echo $_POST['DBPass']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PASSHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLNAME'];?>: </span></td>
                            <td><input name="DBName" type="text" id="DBName" value="<?php if(isset($_POST['DBName'])){ echo $_POST['DBName']; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['NAMEHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td><span class="style12"><?php echo $lang['MYSQLPRE'];?>: </span></td>
                            <td><input name="DBPre" type="text" id="DBPre" value="<?php if(isset($_POST['DBPre'])){ echo $_POST['DBPre']; } else { echo 'lance_'; } ?>"></td>
                            <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['PREHELP'];?>');">(?)</a> </span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input class="coffe button" name="Submit" type="submit" value="Next &gt;&gt;"></td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <br><br><br>
                    <input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
                </form>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Byweb.online</a></span></div></td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
}
elseif($step == '4')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wchat Installation</title>
<style type="text/css">

body {
    background: #fff;
}
body {
    background: #f6f9fb;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    overflow-x: hidden;
    color: #686868;
    font-weight: 300;
}
.container {
    width: 1170px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
input[type=text],input[type=password] {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #545658;
    border: 2px solid #C9C9C9 !important;
    padding: 8px;
    font-size: 14px;
    border-radius: 2px 2px 2px 2px;
}
input, textarea {
    font: 14px/24px Helvetica, Arial, sans-serif;
    color: #666;width: 300px;
}
    td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
    font-weight: 700;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
	text-align: center;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }

.byMsg {
    border: 2px solid #7d7d7d;
    border-radius: 4px;
    margin-bottom: 24px;
    padding: 10px 10px 10px 25px;
    position: relative;
    font-size: 13px;
}
.byMsgError {
    border: 2px solid #d50000 !important;
    color: #d50000;
}
.coffe.button {
    background: #5bbc2e;
}
.coffe.button:hover {
    background: #008329;
}
.button {
    width: auto;
    min-width: 100px;
    font-weight: 600;
    font-size: 12px;
    font-family: 'Ubuntu',sans-serif;
    color: #fff;
    text-transform: uppercase;
    line-height: 35px;
    border: none;
    border-radius: 2px;
    -webkit-transition: background .3s;
    transition: background .3s;
    text-decoration: none;
}
button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    border: none;
}
</style>


</head>

<body>
<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Wchat Installation</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <form name="form1" method="post" action="index.php" style="padding:0px;margin:0px;">
                <?php echo $lang['ADMFILL'];?>
                <br><br><br>
                <table border="0" cellspacing="0" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style12"><?php echo $lang['ADMUSER'];?>: </span></td>
                        <td><input name="adminuser" type="text" id="adminuser" value="<?php if(isset($_POST['adminuser'])){ echo $_POST['adminuser']; } ?>"></td>
                        <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMUSERHELP'];?>');">(?)</a> </span></td>
                    </tr>
                    <tr>
                        <td><span class="style12"><?php echo $lang['ADMPASS'];?>: </span></td>
                        <td><input name="adminpass" type="password" id="adminpass" value="<?php if(isset($_POST['adminpass'])){ echo $_POST['adminpass']; } ?>"></td>
                        <td><span class="style12">&nbsp;<a href="javascript:alert('<?php echo $lang['ADMPASSHELP'];?>');">(?)</a> </span></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input class="coffe button" name="Submit" type="submit" value="<?php echo $lang['NEXT'];?>"></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <br><br>
                <input name="site_url" type="hidden" id="site_url" value="<?php echo $_POST['site_url'];?>">
                <input name="DBHost" type="hidden" id="DBHost" value="<?php echo $_POST['DBHost'];?>">
                <input name="DBName" type="hidden" id="DBName" value="<?php echo $_POST['DBName'];?>">
                <input name="DBUser" type="hidden" id="DBUser" value="<?php echo $_POST['DBUser'];?>">
                <input name="DBPass" type="hidden" id="DBPass" value="<?php echo $_POST['DBPass'];?>">
                <input name="DBPre" type="hidden" id="DBPre" value="<?php echo $_POST['DBPre'];?>">
                <input name="lang" type="hidden" value="<?php echo $_POST['lang'];?>">
                </form>
            </td>
        </tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Byweb.online</a></span></div></td>
        </tr>
    </table>
</div>

</body>
</html>
<?php
}
elseif($step == '5')
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Wchat Installation</title>
<style type="text/css">

body {
    background: #fff;
}
body {
    background: #f6f9fb;
    font-family: 'Poppins', sans-serif;
    margin: 0;
    overflow-x: hidden;
    color: #686868;
    font-weight: 300;
}
.container {
    width: 1170px;
}
.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
input[type=text],input[type=password] {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #545658;
    border: 2px solid #C9C9C9 !important;
    padding: 8px;
    font-size: 14px;
    border-radius: 2px 2px 2px 2px;
}
input, textarea {
    font: 14px/24px Helvetica, Arial, sans-serif;
    color: #666;width: 300px;
}
    td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
    font-weight: 700;
}
.style1 {
	font-size: 24px;
	font-weight: bold;
	text-align: center;
}
.style2 {
	color: #003366;
	font-weight: bold;
}
.style5 {font-size: 9px}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
.style15 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; }

.byMsg {
    border: 2px solid #7d7d7d;
    border-radius: 4px;
    margin-bottom: 24px;
    padding: 10px 10px 10px 25px;
    position: relative;
    font-size: 13px;
}
.byMsgError {
    border: 2px solid #d50000 !important;
    color: #d50000;
}
.coffe.button {
    background: #5bbc2e;
}
.coffe.button:hover {
    background: #008329;
}
.button {
    width: auto;
    min-width: 100px;
    font-weight: 600;
    font-size: 12px;
    font-family: 'Ubuntu',sans-serif;
    color: #fff;
    text-transform: uppercase;
    line-height: 35px;
    border: none;
    border-radius: 2px;
    -webkit-transition: background .3s;
    transition: background .3s;
    text-decoration: none;
}
button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    border: none;
}
</style>
</head>

<body>
<div class="container">
    <table border="0" align="center" cellpadding="10" cellspacing="3" align="center">
        <tr>
            <td>
                <table border="0" cellspacing="10" cellpadding="3" align="center">
                    <tr>
                        <td><span class="style1">Wchat Installation</span></td>
                        <td align="right" valign="bottom">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
            <?php
            if (is_array($createTablemsg)) {
                foreach ($createTablemsg as $value) {
                    echo $value;
                }
            }
            ?>
            </td>
        </tr>
        <tr><td>Thank you for installing Wchat, please use the links below:</td></tr>
        <tr><td>- <a href="../index.php">Front End</a> <!--<br>- <a href="../adm/">Admin</a><br>--></td></tr>
        <tr>
            <td><div align="center"><span class="style5">&copy; <?php echo date("Y"); ?> <a>Byweb.online</a></span></div></td>
        </tr>
    </table>
</div>
</body>
</html>
<?php
}
?>