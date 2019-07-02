<html>
<head>


<title>Sistem Informasi Management Dealer | SiMANDE</title>
<link rel="shortcut icon" href="<?php echo base_url('assets/images/icon.png');?>" type="image/x-icon" />

<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" >
<style type="text/css">
    
body {
    background: url(../assets/images/bg-tm.jpg);
    background-repeat: no-repeat;
    background-size: cover;
}
</style>

</head>

<body>
    
<div class="container" style="padding: 10px 0;">

    <div class="row">
        <div class="col-xs-2">
            <img class="img-responsive" src="<?php echo base_url().'assets/images/logo_tm.png';?>">
        </div>

        <div class="col-lg-7 text-center">
            <h1><strong>ANTRIAN SERVICE</strong></h1>
        </div>

        <div class="col-xs-3 padding-left-right-10">


            <div class="bar-nav pull-right ">

                <div class="btn-group" style="margin-top: 20px;">

    			<span id='ct' class="label label-warning" style="font-size: 100%;">Primary</span>
        <!-- <span id='ct' >test</span> -->

                </div>

            </div>

        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-12 padding-left-right-10">
    </div>



        <table class="table table-striped table-bordered b-t b-light" style="opacity: 0.8;">

            <thead>
                <tr style="background: #E92030; color: #fff">
                    <th>No. Antri</th>
                    <th>No. Polisi</th>
                    <th>No. Pit</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu</th>
                    <th>Waktu Selesai</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody id="antrian">

            		<?php echo $antrian; ?>

            </tbody>

        </table>




    </div>
    <?php echo loading_proses(); ?>
</section>

<script src="<?php echo base_url('assets/js/jquery2.0.3.min.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery-ui.js?v=').date('YmdHis'); ?>"></script>

<script src="<?php echo base_url('assets/js/bootstrap.js?v=').date('YmdHis'); ?>"></script>


<script type="text/javascript"> 

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];



display_ct();



function display_c(){
  var refresh=1000; // Refresh rate in milli seconds
  mytime=setTimeout('display_ct()',refresh);
}

function display_ct() {
  var strcount;
  var x = new Date();
  var Y = x.getFullYear();
  var M = x.getMonth()+1;
  var D = x.getDate();
  var h = x.getHours();
  var m = x.getMinutes();
  var s = x.getSeconds();
  // add a zero in front of numbers<10
/*  h = (h < 10? '0'+h:h);
  m = (m < 10? '0'+m:m);
  s = (s < 10? '0'+s:s);*/

  $("#ct").html(pad(D)+'/'+pad(M)+'/'+Y+' '+pad(h)+':'+pad(m)+':'+pad(s));

  $.getJSON(http+"/pkb/antrian_service/true", function(data, status){

    if(status == 'success'){

      $('#antrian').html(data.antrian);

    }

  });

  // document.getElementById('ct').innerHTML = x;
  tt=display_c();
}


function pad(num) {
  if(num < 10) {
    return "0" + num;
  } else {
    return "" + num;
  }
}
</script>

</body>
</html>