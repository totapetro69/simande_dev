<!doctype html>


<html lang="en">
<head>
    <!-- Latest compiled and minified JavaScript -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/icon.png');?>" type="image/x-icon" />
    
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" >


    <!-- font-simande icons -->
    <link href="<?php echo base_url('assets/css/font-awesome.css'); ?>" rel="stylesheet"> 
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-simande.css'); ?>" type="text/css"/>

    <style type="text/css">

        html, body {
            font-family: 'Roboto', sans-serif;
            font-size: 100%;
            overflow-x: hidden;
            background: #444;
        }

        .shadow{
            -webkit-box-shadow: 0 12px 15px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);
            -moz-box-shadow: 0 12px 15px 0 rgba(0, 0, 0, 0.24), 0 17px 50px 0 rgba(0, 0, 0, 0.19);

            /*box-shadow: 0 12px 15px 0 rgba(0, 0, 0, 0.9), 0 17px 50px 0 rgba(0, 0, 0, 0.19);*/
                box-shadow: 0 5px 15px rgba(0,0,0,0.9);
            border-left: 10px solid #E92030;
            border-right: 10px solid #E92030;

            background-color: #fff;

            overflow:hidden;

            padding: 15px 15px 30px 15px;

                border-radius: 6px;
        }

        .shadow h2
        {
            font-weight: 600;
            color: #444;
        }

        .shadow .logo
        {
            color: #E92030;
            vertical-align: middle;
            font-size: 55px;
        }

        .btn-color1{
            background-color:#E92030;
            color:white;
        }

        .btn-color1:hover, .btn-default.disabled, .btn-default.disabled.active, .btn-default.disabled.focus, .btn-default.disabled:active, .btn-default.disabled:focus, .btn-default.disabled:hover, .btn-default[disabled], .btn-default[disabled].active, .btn-default[disabled].focus, .btn-default[disabled]:active, .btn-default[disabled]:focus, .btn-default[disabled]:hover, fieldset[disabled] .btn-default, fieldset[disabled] .btn-default.active, fieldset[disabled] .btn-default.focus, fieldset[disabled] .btn-default:active, fieldset[disabled] .btn-default:focus, fieldset[disabled] .btn-default:hover{

            background-color:white;
            color:#E92030;
            border: 1px solid #E92030;
        }

        .input-group-addon {
            background:#ffc107;
            color: white;
        }

        .main-content
        {
            margin-top:10%;
        }



        /*notif
        ===============================================================================================*/


        .alert-message
        {
            -webkit-background-size: 40px 40px;
            -moz-background-size: 40px 40px;
            background-size: 40px 40px;     
            
            /*background-image: -webkit-gradient(linear, left top, right bottom,
                        color-stop(.25, rgba(255, 255, 255, .05)), color-stop(.25, transparent),
                        color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .05)),
                        color-stop(.75, rgba(255, 255, 255, .05)), color-stop(.75, transparent),
                        to(transparent));
            background-image: -webkit-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
                      transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
                      transparent 75%, transparent);
            background-image: -moz-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
                      transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
                      transparent 75%, transparent);
            background-image: -ms-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
                      transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
                      transparent 75%, transparent);
            background-image: -o-linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
                      transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
                      transparent 75%, transparent);
            background-image: linear-gradient(135deg, rgba(255, 255, 255, .05) 25%, transparent 25%,
                      transparent 50%, rgba(255, 255, 255, .05) 50%, rgba(255, 255, 255, .05) 75%,
                      transparent 75%, transparent);*/
                        
             -moz-box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
             -webkit-box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);   
             box-shadow: inset 0 -1px 0 rgba(255,255,255,.4);
             width: 100%;
             border: 1px solid;
             color: #fff;
             padding: 15px;
             position: fixed;
             _position: absolute;
             text-shadow: 0 1px 0 rgba(0,0,0,.5);
            font-size: 12px;
            font-weight: 800;
             -webkit-animation: animate-bg 5s linear infinite;
             -moz-animation: animate-bg 5s linear infinite;
             z-index: 10052;
            display: none;
        }

        .info
        {
             background-color: #4ea5cd;
             border-color: #3b8eb5;
        }

        .error
        {
             background-color: #de4343;
             border-color: #c43d3d;
        }
             
        .warning
        {
             background-color: #eaaf51;
             border-color: #d99a36;
        }

        .success
        {
             background-color: #61b832;
             border-color: #55a12c;
        }

        .message h3
        {
             margin: 0 0 5px 0;                          
        }

        .message p
        {
             margin: 0;                          
        }



        /*page load
        ===============================================================================================*/


                
        .loadpage-preloader{
            background-color:#fbfbfb;
            height: 100%;
            padding-top: 200px;
            position: fixed;
            width: 100%;
            z-index: 20000;
        }



    </style>


</head>

<body >

<div class="loadpage-preloader" id="loadpage-preloader">
    <img src="<?php echo base_url("assets/images/loading_spinner.gif"); ?>" class="img-responsive center-block">
    <p class="text-center loadText">Loading...</p>
</div>

<div class="container main-content">
    <div class="row ">
        <div class="col-md-5 col-md-offset-3 shadow "  >
            <form id="addForm" action="<?php echo base_url();?>auth/login" method='POST'>

            <div class="row">
            <h2 class="text-center"><span class="tm tm-logo logo"></span> Form Login</h2>
            <hr>
            </div>

            <div class="form-group">
                <!-- <label for="example-text-input" class="col-md-3 col-form-label">NIK</label> -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input class="form-control" type="text" id="user_id" name="user_id" placeholder=" NIK">
                </div>
            </div>
            <!-- <div class="form-group row"> -->
            <div class="form-group">
                <!-- <label for="example-text-input" class="col-md-3 col-form-label"> Password </label> -->
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i> </span>
                    <input class="form-control" type="password"  id="password" name="password" placeholder="password">
                </div>
            </div>
                <!-- <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input ">
                        Check me out
                    </label>
                </div> -->

                
            <button id="submit-btn" type="submit" class="btn btn-default btn-block pull-right btn-color1">LogIn</button>

            </form>

            <!-- // <div class="info small" id="message"><?php echo @$message;?></div> -->
        </div>
    </div>
</div>



<!-- alert 
===============================================================================-->

<div class="info message alert-message">
     <h3>FYI, something just happened!</h3>
     <p>This is just an info notification message.</p>
</div>

<div class="error message alert-message">
     <h3>Ups, an error ocurred</h3>
     <p>This is just an error notification message.</p>

</div>

<div class="warning message alert-message">
     <h3>Wait, I must warn you!</h3>
     <p>This is just a warning notification message.</p>
</div>

<div class="success message alert-message">
     <h3>Congrats, you did it!</h3>
     <p>This is just a success notification message.</p>

</div>



<script src="<?php echo base_url('assets/js/jquery2.0.3.min.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.js'); ?>"></script>



<script type="text/javascript">


// ========================================================================================
// onload page  
// ========================================================================================
/**
 * [downloadJSAtOnload description]
 * @return {[type]} [description]
 */
function downloadJSAtOnload() {
    setTimeout(function () {
        $("#loadpage-preloader").fadeOut();
    }, 500);
}
if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;



    
// ========================================================================================
// alert 
// ========================================================================================

var myMessages = ['info', 'warning', 'error', 'success']; // define the messages types      


/**
 * [hideAllMessages description]
 * @return {[type]} [description]
 */
function hideAllMessages() {
    var messagesHeights = new Array(); // this array will store height for each

    for (i = 0; i < myMessages.length; i++) {
        messagesHeights[i] = $('.' + myMessages[i]).outerHeight();
        $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport   
    }
}



/**
 * Posting data
 */
$(document).ready(function(){

    $("#addForm").submit(function(event)
    {
        event.preventDefault();
        
        var defaultBtn = $("#submit-btn").html();

        $("#submit-btn").addClass("disabled");
        $("#submit-btn").html("<span class='fa fa-spinner fa-spin'></span> Loading");
        $(".alert-message").fadeIn();
        

        var formData = $("#addForm").serialize();
        var act = $("#addForm").attr('action');     
        
        $.ajax({
            url: act,
            type: 'POST',
            data: formData,
            dataType: "json",
            success: function (result) {

                if (result.status == true) {

                    $('.success').animate({ top: "0" }, 500);
                    $('.success').html(result.message);

                    if (result.location != null) {
                        setTimeout(function(){
                        location.replace(result.location)
                        }, 2000);
                    }
                } 
                else {

                    $('.error').animate({ top: "0" }, 500);
                    $('.error').html(result.message);

                    setTimeout(function () {
                        hideAllMessages();
                        $("#submit-btn").removeClass("disabled");
                        $("#submit-btn").html(defaultBtn);
                    }, 4000);
                }
            }
            
        });
        
        return false;

    })
});
/**
 * Post data dengan bentuk JSON
 * @param {[string]} act [url posting]
 */
$(document).ready(function () {

    // Initially, hide them all
    hideAllMessages();

    // Show message
    /*for(var i=0;i<myMessages.length;i++)
    {
     showMessage(myMessages[i]);
    }*/

    // When message is clicked, hide it
    $('.message').click(function () {
        $(this).animate({ top: -$(this).outerHeight() }, 500);
    });

});
</script>


</body>
</html>