$(document).ready(function() {
    $(".embtn").click(function(event){
        var client = $('.chat.active-chat').attr('client');

        var prevMsg = $('#chatFrom .chatboxtextarea').val();
        var emotiText = $(event.target).attr("alt");

        $('#chatFrom .chatboxtextarea').val(prevMsg+' '+emotiText);
        $('#chatFrom .chatboxtextarea').focus();
    });

    $(".chat-head .personName").click(function(){
        var personName = $(this).text()
        window.location.href = siteurl+'profile.php?uname='+personName;
    });

});

function chatemoji() {
    $(".target-emoji").toggle( 'fast', function(){
        if ($(".target-emoji").css('display') == 'block') {
            $('.chat-list').css({'height':(($(window).height())-240)+'px'});
        } else {
            $('.chat-list').css({'height':(($(window).height())-132)+'px'});
        }
    });
    var heit = $('#resultchat').css('max-height');
}

/*Get get on scroll*/
$("#resultchat").scrollTop($("#resultchat")[0].scrollHeight);
// Assign scroll function to chatBox DIV
$('#resultchat').scroll(function(){
    if ($('#resultchat').scrollTop() == 0){

        var client = $('.chat.active-chat').attr('client');

        if($("#chatbox_"+client+" .pagenum:first").val() != $("#chatbox_"+client+" .total-page").val()) {

            $('#loader').show();
            var pagenum = parseInt($("#chatbox_"+client+" .pagenum:first").val()) + 1;

            var URL = siteurl+'chat.php?page='+pagenum+'&action=get_all_msg&client='+client;

            get_all_msg(URL);                                       // Calling get_all_msg function

            $('#loader').hide();									// Hide loader on success

            if(pagenum != $("#chatbox_"+client+" .total-page").val()) {
                setTimeout(function () {										//Simulate server delay;

                    $('#resultchat').scrollTop(100);							// Reset scroll
                }, 458);
            }
        }

    }
});
/*Get get on scroll*/
//Inbox User search
$(document).ready(function(){
    $('.live-search-list li').each(function(){
        $(this).attr('data-search-term', $(this).text().toLowerCase());
    });

    $('.live-search-box').on('keyup', function(){
        var searchTerm = $(this).val().toLowerCase();
        $('.live-search-list li').each(function(){

            if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
$(document).ready(function(){
    $('.person:first').trigger('click');
    var personName = $('.person:first').find('.personName').text();
    $('.right .top .personName').html(personName);
    var userImage = $('.person:first').find('.userimage').html();
    $('.right .top .userimage').html(userImage);
    var personStatus = $('.person:first').find('.personStatus').html();
    $('.right .top .personStatus').html(personStatus);
    var hideContent = $('.person:first').find('.hidecontent').html();
    $('.right .hidecontent').html(hideContent);
});
$('.left .person').mousedown(function(){
    if ($(this).hasClass('.active')) {
        return false;
    } else {
        var findChat = $(this).attr('data-chat');
        var personName = $(this).find('.personName').text();
        $('.right .top .personName').html(personName);
        var userImage = $(this).find('.userimage').html();
        $('.right .top .userimage').html(userImage);
        var personStatus = $(this).find('.personStatus').html();
        $('.right .top .personStatus').html(personStatus);
        var hideContent = $(this).find('.hidecontent').html();
        $('.right .hidecontent').html(hideContent);
        $('.chat').removeClass('active-chat');
        $('.left .person').removeClass('active');
        $(this).addClass('active');
        $('.chat[data-chat = '+findChat+']').addClass('active-chat');
    }
});
//Uploading Image And files
function uploadimage(touname) {


    var file_name=$("#imageInput").val();
    var fileName = $("#imageInput").val();
    var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);

    var toid = $("#chatbox_"+touname+" #to_id").val();
    var tun = $("#chatbox_"+touname+" #to_uname").val();
    var fun = $("#chatbox_"+touname+" #from_uname").val();

    var base_url = siteurl+'process.php?toid='+toid+'&tun='+tun+'&fun='+fun;
alert(base_url);
    var file_data=$("#imageInput").prop("files")[0];
    //alert(touname+' '+base_url);
    var form_data=new FormData();
    form_data.append("file",file_data);

    $('#loadmsg').show();
    var wtf    = $('#resultchat');
    var height = wtf[0].scrollHeight;
    wtf.scrollTop(height);

    $.ajax({
        type:"POST",
        url: base_url,
        cache:false,
        contentType:false,
        processData:false,
        data:form_data,
        success:function(data){

            $.each(data.items, function(i,item){
                if (item)	{ // fix strange ie bug

                    chatboxtitle = item.chatboxtitle;
                    filename = item.filename;
                    path = item.path;

                    $('#loadmsg').hide();

                    var message_content = "<a url='"+path+"' onclick='trigq(this)' style='cursor: pointer;'><img src='"+filename+"' height='100'/></a>";
                    $("#chatbox_"+chatboxtitle).append('<div class="col-xs-12 p-b-10"><div class="chat-image"> <img alt="male" src="'+siteurl+'storage/user_image/'+item.simg+'"> </div><div class="chat-body"><div class="chat-text"><h4>'+item.sender+'</h4><p>'+message_content+'</p><b>Just Now</b> </div></div></div>');

                    var wtf    = $('#resultchat');
                    var height = wtf[0].scrollHeight;
                    wtf.scrollTop(height);
                }
            });
        },
        error:function(){
            //----------
        }
    });
}