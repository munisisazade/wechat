<?php require_once('config.php'); ?>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Wchat responsive php ajax inbox messaging app</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <link rel="stylesheet" href="livedemo/css/style.css">
    <script type="text/javascript" src="livedemo/js/jquery.min.js.download"></script>
    <script type="text/javascript">//<![CDATA[
        $(function() {

            $('.web').click(function(e){
                e.preventDefault();
                //$("#bg").attr('src',"template_preview/images/2x2.gif");
                $("#device").attr('style',"margin: 0px auto;text-align: center;");
                $("#hw").attr('class',"web");
                $("#hw").attr('height',"100%");
                $("#hw").attr('width',"100%");
            });
            $('.Ipad-vertical').click(function(e){
                e.preventDefault();
                ///$("#bg").attr('src',"template_preview/images/ipad-landscape-big.png");
                $("#device").attr('style',"margin: 1em auto;text-align: center;");
                $("#hw").attr('class',"ipad-frame-land");
                // $("#hw").attr('style',"position: absolute;	left: 7.3%;	top: 55px;");
                $("#hw").attr('height',"680");
                $("#hw").attr('width',"1024");

            });
            $('.Ipad-hori').click(function(e){
                e.preventDefault();
                //$("#bg").attr('src',"template_preview/images/ipad-vertical-big.png");
                $("#device").attr('style',"margin: 1em auto;text-align: center;");
                //$("#hw").attr('style',"left:50px;top:135px");
                $("#hw").attr('class',"ipad-frame-ver");
                // $("#hw").attr('style',"	position: absolute;	left: 7.3%;	top: 75px;");
                $("#hw").attr('height',"1024");
                $("#hw").attr('width',"768");

            });



            $('.Iphone-vertical').click(function(e){
                e.preventDefault();
                ///$("#bg").attr('src',"template_preview/images/ipad-landscape-big.png");
                $("#device").attr('style',"margin: 1em auto;text-align: center;");
                $("#hw").attr('class',"smart-frame-var");
                // $("#hw").attr('style',"position: absolute;	left: 7.3%;	top: 55px;");
                $("#hw").attr('height',"320");
                $("#hw").attr('width',"480");

            });
            $('.Iphone-hori').click(function(e){
                e.preventDefault();
                //$("#bg").attr('src',"template_preview/images/ipad-vertical-big.png");
                $("#device").attr('style',"margin: 1em auto;text-align: center;");
                //$("#hw").attr('style',"left:50px;top:135px");
                $("#hw").attr('class',"smart-frame-land");
                // $("#hw").attr('style',"	position: absolute;	left: 7.3%;	top: 75px;");
                $("#hw").attr('height',"480");
                $("#hw").attr('width',"320");
            });

            $('.mobile').click(function(e){
                e.preventDefault();
                //$("#bg").attr('src',"template_preview/images/ipad-vertical-big.png");
                $("#device").attr('style',"margin: 1em auto;text-align: center;");
                //$("#hw").attr('style',"left:50px;top:135px");
                $("#hw").attr('class',"mobile-frame");
                // $("#hw").attr('style',"	position: absolute;	left: 7.3%;	top: 75px;");
                $("#hw").attr('height',"500");
                $("#hw").attr('width',"240");

            });


        });

    </script>
</head>
<body>
<div id="wrap" style="margin-top: 0px; position: relative;">
    <!-- Navbar -->
    <div id="topBar">
        <div class="wrap">
            <div class="logo"><a href="<?php echo $config['site_url']; ?>index.php"><img src="livedemo/images/logo.png" alt="Wchat php chat script"></a></div>
            <div class="right">
                <div class="devices">
                    <ul>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="web"><img src="livedemo/images/monitor.png" alt=""></a></li>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="Ipad-hori"><img src="livedemo/images/ipad-icon.png" alt=""></a></li>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="Ipad-vertical"><img src="livedemo/images/ipad-landscape.png" alt=""></a></li>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="Iphone-vertical"><img src="livedemo/images/iphone-icon.png" alt=""></a></li>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="Iphone-hori"><img src="livedemo/images/iphone-landscape.png" alt=""></a></li>
                        <li><a href="<?php echo $config['site_url']; ?>index.php" class="mobile"><img src="livedemo/images/nokia-icon.png" alt=""></a></li>
                    </ul>
                </div>
                <!--<div class="social">
                    <ul>
                    <li>Share on </li>
                    <li><a href="mobile_demo.html"><img src="images/fb.png" alt=""></a></li>
                    <li><a href="http://bylancer.com"><img src="images/tw.png" /></a></li>
                    <li><a href="mobile_demo.html"><img src="images/gp.png" alt=""></a></li>
                    <li><a href="mobile_demo.html"><img src="images/pin.png" alt=""></a></li>
                    </ul>
                </div>-->
                <div class="go-back">
                    <a href="<?php echo $config['site_url']; ?>index.php">Buynow</a>
                </div>
                <a id="close-button" title="Remove Frame" class="closeMe" href="<?php echo $config['site_url']; ?>index.php" target="_blank"></a>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>
<div style="text-align: center;" id="device">
    <iframe id="hw" class="web" src="<?php echo $config['site_url']; ?>index.php" name="livePreviewFrame" width="100%" height="90%" frameborder="0" noresize="noresize" style=" min-height: 100%;" __idm_id__="675841"> </iframe>
</div>
<script>
    var adjustFrame = function() {
        var headerDimensions = $('#topBar').height();
        $('#livePreviewFrame').height($(window).height() - headerDimensions);
    }
    $(document).ready(function() {
        adjustFrame();
        $('.closeMe').mouseover(function() {
            $('.closeMe').addClass('active');
        }).mouseout(function() {
            $('.closeMe').removeClass('active');
        });
    });

    $(window).resize(function() {
        adjustFrame();
    }).load(function() {
        adjustFrame();
    });
</script>
<script type="text/javascript">
    //		function to fix height of iframe!
    var calcHeight = function() {
        var headerDimensions = $('#headerlivedemo').height();
        var selector = '#iframelive';
        if($('#wrap').hasClass('closed')) {
            $(selector).height($(window).height());
        } else {
            $(selector).height($(window).height() - headerDimensions);
        }
    }
    $(document).ready(function() {
        calcHeight();
    });
    $(window).resize(function() {
        calcHeight();
    }).load(function() {
        calcHeight();
    });
</script>

</body></html>