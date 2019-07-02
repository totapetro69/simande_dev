<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$defaultDealer = ($this->session->userdata("kd_dealer"));
//$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <div class="btn-group">
                <!-- <a class="btn btn-default"  role="button" href='<?php echo base_url('pkb/antrian_service_fullscreen'); ?>' target="blank">
                    <i class="fa fa-eye fa-fw"></i> Full Screen
                </a> -->

			<span id='ct' class="label label-warning">Primary</span>
    <!-- <span id='ct' >test</span> -->

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">

            <div class="table-responsive">

                <table class="table table-striped b-t b-light">

                    <thead>
                        <tr>
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

        </div>


    </div>
    <?php echo loading_proses(); ?>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/pkb.js");?>"></script>

<script type="text/javascript"> 

display_ct();


var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];


var popupScreenParameters = [ 'height='+screen.height, 'width='+screen.width, 'fullscreen=yes' ].join(','); var windowVariable = window.open(http+'/pkb/antrian_service_fullscreen',"popupName",popupScreenParameters); windowVariable .moveTo(0,0); 



</script>