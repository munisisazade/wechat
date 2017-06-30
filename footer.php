<!-- .right-sidebar -->
<div class="right-sidebar">
    <div class="slimscrollright">
        <div class="rpanel-title"> Theme Modify<span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body">
            <ul>
                <li><b>Layout Options</b></li>

            </ul>
            <ul id="mainthemecolors" class="m-t-20">
                <li><b>Theme (Light/Dark)</b></li>
                <li><a href="javascript:void(0)" maintheme="style-light" class="light-theme working">1</a></li>
                <li><a href="javascript:void(0)" maintheme="style-dark" class="dark-theme">2</a></li>
            </ul>
            <ul id="themecolors" class="m-t-20">
                <li><b>With Light sidebar</b></li>
                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                <li><a href="javascript:void(0)" theme="blue" class="blue-theme">4</a></li>
                <li><a href="javascript:void(0)" theme="purple" class="purple-theme working">5</a></li>
                <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
            </ul>

        </div>
    </div>
</div>
<!-- /.right-sidebar -->
<!-- jQuery -->
<script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="assets/bootstrap/dist/js/bootstrap.min.js"></script>
<!--slimscroll JavaScript -->
<script src="assets/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<!--<script src="js/waves.js"></script>-->
<!-- Custom Theme JavaScript -->
<script src="assets/js/custom.js"></script>
<!--Style Switcher -->
<script src="plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
<!--Style Switcher -->

<!--ChatJs -->
<script>
    var siteurl = '<?php echo $config['site_url']; ?>';
</script>
<script type="text/javascript" src="chatjs/lightbox.js"></script>
<script type="text/javascript" src="chatjs/inbox.js"></script>
<script type="text/javascript" src="chatjs/custom.js"></script>
<!--ChatJs-->
<!--This div for modal light box chat box image-->
<div id="lightbox" style="display: none;">
    <p>
        <img src="https://www.itroteam.com/wp-content/plugins/itro-wordpress-marketing/images/close-icon-white.png"
            width="30px" style="cursor: pointer"/>
    </p>
    <div id="content">
        <img src="#" />
    </div>
</div>
<!--This div for modal light box chat box image-->

</body>
</html>
