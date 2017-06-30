<?php
require_once('config.php');
require_once('function.php');
checkinstall($config);
require_once('dbcon.php');
if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
require_once('header.php');



$query1 = "SELECT * FROM `".$config['db']['pre']."userdata` where id = '".$_SESSION['id']."'";
$result1 = $con->query($query1);
$row1 = mysqli_fetch_assoc($result1);
$row1['username'];
$sesuserpic = $row1['picname'];

if($sesuserpic == "")
    $sesuserpic = "avatar_default.png";
?>


    <!-- .chat-row -->
    <div class="chat-main-box">

        <!-- .chat-left-panel -->
        <div class="chat-left-aside left">
            <div class="open-panel"><i class="ti-angle-right"></i></div>
            <div class="chat-left-inner">

                <div class="form-material"><input class="form-control p-20 live-search-box" type="text" placeholder="Search Contact"></div>
                <ul class="chatonline style-none live-search-list" id="userScroll">
                    <?php
                    $query = "SELECT * FROM `".$config['db']['pre']."userdata` where id != '".$_SESSION['id']."' order by online = 0 , online";
                    $result = $con->query($query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $username = $row['username'];
                        $picname = $row['picname'];
                        if($picname == "")
                            $picname = "avatar_default.png";
                        else{
                            $picname = "small".$picname;
                        }
                        $res = mysqli_query($con, "SELECT * FROM `".$config['db']['pre']."userdata` WHERE id='$id' AND TIMESTAMPDIFF(MINUTE, last_active_timestamp, NOW()) > 1;");
                        if($res === FALSE) {
                            die(mysqli_error($con)); // TODO: better error handling
                        }
                        $num = mysqli_num_rows($res);
                        if($num == "0")
                            $onofst = "Online";
                        else
                            $onofst = "Offline";
                        ?>
                        <li class="person chatboxhead" id="chatbox1_<?php echo $username ?>" data-chat="person_<?php echo $id ?>" href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $username ?>','<?php echo $id ?>','<?php echo $sesuserpic; ?>','<?php echo $onofst ?>')">
                            <a href="javascript:void(0)">
                                <span class="userimage"><img src="storage/user_image/<?php echo $picname; ?>" alt="<?php echo $username ?>" class="img-circle bg-theme"></span>
                                    <span>
                                        <span class="bname personName"><?php echo $username ?></span>
                                        <span class="personStatus"><span class="time <?php echo $onofst ?>"><i class="fa fa-circle" aria-hidden="true"></i></span></span>
                                        <small class="preview"><span class="<?php echo $onofst ?>"><?php echo $onofst ?></span></small>
                                    </span>
                            </a>
                            <span class="hidecontent">
                                <input id="to_id" name="to_id" value="<?php echo $id ?>" type="hidden">
                                <input id="to_uname" name="to_uname" value="<?php echo $username ?>" type="hidden">
                                <input id="from_uname" name="from_uname" value="<?php echo $row1['username']; ?>" type="hidden">
                            </span>
                        </li>
                    <?php } ?>
                    <li class="p-20"></li>
                </ul>
            </div>
        </div>
        <!-- .chat-left-panel -->
        <!-- .chat-right-panel -->
        <div class="chat-right-aside right" id="right">
            <div class="chat-main-header">
                <div class="b-b">
                    <div class="p-4 chat-head top pull-left" style="width: 77%">
                        <div class="userimage"><img alt="male" src="plugins/images/users/ritesh.jpg"></div>
                        <h3 class="project box-title personName"></h3>
                        <div class="preview personStatus">Online</div>
                    </div>
                    <div class="pull-left" style="width: 20%;padding-top: 17px;">
                        <div class="pull-right p-l-20 right-side-toggle" style="padding-top: 2px"> <a href="javascript:void(0)"><i class="ti-settings"></i></a></div>
                        <!-- top right panel -->
                        <ul>
                            <!-- .dropdown -->
                            <li class="dropdown pull-right"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="ti-more font-24"></span></a>
                                <ul class="dropdown-menu dropdown-user animated flipInY">
                                    <li><a href="profile.php"><i class="ti-user"></i> My Profile</a></li>
                                    <li><a href="edit_profile.php"><i class="ti-wallet"></i> Edit Profile</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                                <!-- /.dropdown-user -->
                            </li>
                            <!-- /.dropdown -->
                        </ul>
                        <!-- top right panel -->

                    </div>

                    <div class="clear"></div>

                </div>
            </div>

            <!-- Here chating messages content will be show by inbox.JS-->
            <div class="chat-list slimscroll p-t-30" id="resultchat">

            </div>
            <div id="chatFrom">

            </div>


        </div>
        <!-- .chat-right-panel -->
    </div>
    <!-- /.chat-row -->


<?php
require_once('footer.php');
?>