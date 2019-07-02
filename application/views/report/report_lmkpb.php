<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");

$dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));

$bulan= ($this->input->get('bulan'))?$this->input->get('bulan'):date("m");

$tahuns= ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');


$KPB_JATUHTEMPO = $kpb_jatuhtempo;
$PHONE_JATUHTEMPO = $phone_jatuhtempo;


// METODE 1
$METODE1_SERVICED = 0;
$METODE1_NOT_SERVICE = 0;
$METODE1_SERVICED_PERCENT = '0%';
$METODE1_NOT_SERVICE_PERCENT = '0%';

$METODE1_SMS_SUCCESS = 0;
$METODE1_SMS_FAILED = 0;
$METODE1_SMS_SUCCESS_PERCENT = "0%";
$METODE1_SMS_FAILED_PERCENT = "0%";

$METODE1_CALL_SUCCESS = 0;
$METODE1_CALL_FAILED = 0;
$METODE1_CALL_STATUS_FAILED = 0;
$METODE1_CALL_STATUS_UNREACHABLE = 0;
$METODE1_CALL_STATUS_REJECTED = 0;
$METODE1_CALL_STATUS_WORKLOAD = 0;
$METODE1_CALL_SUCCESS_PERCENT = "0%";
$METODE1_CALL_STATUS_FAILED_PERCENT = "0%";
$METODE1_CALL_STATUS_UNREACHABLE_PERCENT = "0%";
$METODE1_CALL_STATUS_REJECTED_PERCENT = "0%";
$METODE1_CALL_STATUS_WORKLOAD_PERCENT = "0%";

// METODE 2
$METODE2_SERVICED = 0;
$METODE2_NOT_SERVICE = 0;
$METODE2_FAR = 0;
$METODE2_NOTIME = 0;
$METODE2_FORGET = 0;
$METODE2_SERVICED_PERCENT = '0%';
$METODE2_NOT_SERVICE_PERCENT = '0%';
$METODE2_FAR_PERCENT = '0%';
$METODE2_NOTIME_PERCENT = '0%';
$METODE2_FORGET_PERCENT = '0%';

$METODE2_SMS_SUCCESS = 0;
$METODE2_SMS_FAILED = 0;
$METODE2_SMS_SUCCESS_PERCENT = "0%";
$METODE2_SMS_FAILED_PERCENT = "0%";

$METODE2_CALL_SUCCESS = 0;
$METODE2_CALL_FAILED = 0;
$METODE2_CALL_STATUS_FAILED = 0;
$METODE2_CALL_STATUS_UNREACHABLE = 0;
$METODE2_CALL_STATUS_REJECTED = 0;
$METODE2_CALL_STATUS_WORKLOAD = 0;
$METODE2_CALL_SUCCESS_PERCENT = "0%";
$METODE2_CALL_STATUS_FAILED_PERCENT = "0%";
$METODE2_CALL_STATUS_UNREACHABLE_PERCENT = "0%";
$METODE2_CALL_STATUS_REJECTED_PERCENT = "0%";
$METODE2_CALL_STATUS_WORKLOAD_PERCENT = "0%";


$TOTAL_SERVICE = 0;

if($metode1 && (is_array($metode1->message) || is_object($metode1->message))): 

    foreach ($metode1->message as $key => $value):

    $METODE1_SUCCESS = $value->METODE1_SUCCESS == ''?0:$value->METODE1_SUCCESS;
    $METODE1_FAILED = $value->METODE1_FAILED == ''?0:$value->METODE1_FAILED;

    $METODE1_STATUS_FAILED = $value->METODE1_STATUS_FAILED == ''?0:$value->METODE1_STATUS_FAILED;
    $METODE1_STATUS_UNREACHABLE = $value->METODE1_STATUS_UNREACHABLE == ''?0:$value->METODE1_STATUS_UNREACHABLE;
    $METODE1_STATUS_REJECTED = $value->METODE1_STATUS_REJECTED == ''?0:$value->METODE1_STATUS_REJECTED;
    $METODE1_STATUS_WORKLOAD = $value->METODE1_STATUS_WORKLOAD == ''?0:$value->METODE1_STATUS_WORKLOAD;

    $METODE1_SERVICED = $METODE1_SERVICED + $value->METODE1_SERVICE;
    $METODE1_NOT_SERVICE = $METODE1_NOT_SERVICE + $value->METODE1_NOSERVICE;

    $METODE1_SERVICED_PERCENT = round(($METODE1_SERVICED/$KPB_JATUHTEMPO)*100).'%';
    $METODE1_NOT_SERVICE_PERCENT = round(($METODE1_NOT_SERVICE/$KPB_JATUHTEMPO)*100).'%';



    if($value->NAMA_METODEFU == 'SMS'):

        $METODE1_SMS_SUCCESS = $METODE1_SUCCESS;
        $METODE1_SMS_FAILED = $METODE1_FAILED;
        $METODE1_SMS_SUCCESS_PERCENT = round(($METODE1_SUCCESS/$KPB_JATUHTEMPO)*100).'%';
        $METODE1_SMS_FAILED_PERCENT = round(($METODE1_FAILED/$KPB_JATUHTEMPO)*100).'%';

    elseif($value->NAMA_METODEFU == 'CALL'):

        $METODE1_CALL_SUCCESS = $METODE1_SUCCESS;
        $METODE1_CALL_FAILED = $METODE1_FAILED;
        $METODE1_CALL_STATUS_FAILED = $METODE1_STATUS_FAILED;
        $METODE1_CALL_STATUS_UNREACHABLE = $METODE1_STATUS_UNREACHABLE;
        $METODE1_CALL_STATUS_REJECTED = $METODE1_STATUS_REJECTED;
        $METODE1_CALL_STATUS_WORKLOAD = $METODE1_STATUS_WORKLOAD;
        $METODE1_CALL_SUCCESS_PERCENT = round(($METODE1_SUCCESS/$KPB_JATUHTEMPO)*100).'%';
        $METODE1_CALL_STATUS_FAILED_PERCENT = round(($METODE1_STATUS_FAILED/$KPB_JATUHTEMPO)*100).'%';
        $METODE1_CALL_STATUS_UNREACHABLE_PERCENT = round(($METODE1_STATUS_UNREACHABLE/$KPB_JATUHTEMPO)*100).'%';
        $METODE1_CALL_STATUS_REJECTED_PERCENT = round(($METODE1_STATUS_REJECTED/$KPB_JATUHTEMPO)*100).'%';
        $METODE1_CALL_STATUS_WORKLOAD_PERCENT = round(($METODE1_STATUS_WORKLOAD/$KPB_JATUHTEMPO)*100).'%';

    endif; endforeach; 
endif;


if($metode2 && (is_array($metode2->message) || is_object($metode2->message))): 

    foreach ($metode2->message as $key => $m2):

    $METODE2_SUCCESS = $m2->METODE2_SUCCESS == ''?0:$m2->METODE2_SUCCESS;
    $METODE2_FAILED = $m2->METODE2_FAILED == ''?0:$m2->METODE2_FAILED;

    $METODE2_STATUS_FAILED = $m2->METODE2_STATUS_FAILED == ''?0:$m2->METODE2_STATUS_FAILED;
    $METODE2_STATUS_UNREACHABLE = $m2->METODE2_STATUS_UNREACHABLE == ''?0:$m2->METODE2_STATUS_UNREACHABLE;
    $METODE2_STATUS_REJECTED = $m2->METODE2_STATUS_REJECTED == ''?0:$m2->METODE2_STATUS_REJECTED;
    $METODE2_STATUS_WORKLOAD = $m2->METODE2_STATUS_WORKLOAD == ''?0:$m2->METODE2_STATUS_WORKLOAD;

    $METODE2_SERVICED = $METODE2_SERVICED + $m2->METODE2_SERVICE;
    $METODE2_NOT_SERVICE = $METODE2_NOT_SERVICE + $m2->METODE2_NOSERVICE;
    $METODE2_FAR = $METODE2_FAR + $m2->METODE2_FAR;
    $METODE2_NOTIME = $METODE2_NOTIME + $m2->METODE2_NOTIME;
    $METODE2_FORGET = $METODE2_FORGET + $m2->METODE2_FORGET;

    $METODE2_SERVICED_PERCENT = round(($METODE2_SERVICED/$KPB_JATUHTEMPO)*100).'%';
    $METODE2_NOT_SERVICE_PERCENT = round(($METODE2_NOT_SERVICE/$KPB_JATUHTEMPO)*100).'%';
    $METODE2_FAR_PERCENT = round(($METODE2_FAR/$KPB_JATUHTEMPO)*100).'%';
    $METODE2_NOTIME_PERCENT = round(($METODE2_NOTIME/$KPB_JATUHTEMPO)*100).'%';
    $METODE2_FORGET_PERCENT = round(($METODE2_FORGET/$KPB_JATUHTEMPO)*100).'%';


    if($m2->NAMA_METODEFU2 == 'SMS'):

        $METODE2_SMS_SUCCESS = $METODE2_SUCCESS;
        $METODE2_SMS_FAILED = $METODE2_FAILED;
        $METODE2_SMS_SUCCESS_PERCENT = round(($METODE2_SUCCESS/$KPB_JATUHTEMPO)*100).'%';
        $METODE2_SMS_FAILED_PERCENT = round(($METODE2_FAILED/$KPB_JATUHTEMPO)*100).'%';

    elseif($m2->NAMA_METODEFU2 == 'CALL'):

        $METODE2_CALL_SUCCESS = $METODE2_SUCCESS;
        $METODE2_CALL_FAILED = $METODE2_FAILED;
        $METODE2_CALL_STATUS_FAILED = $METODE2_STATUS_FAILED;
        $METODE2_CALL_STATUS_UNREACHABLE = $METODE2_STATUS_UNREACHABLE;
        $METODE2_CALL_STATUS_REJECTED = $METODE2_STATUS_REJECTED;
        $METODE2_CALL_STATUS_WORKLOAD = $METODE2_STATUS_WORKLOAD;
        $METODE2_CALL_SUCCESS_PERCENT = round(($METODE2_SUCCESS/$KPB_JATUHTEMPO)*100).'%';
        $METODE2_CALL_STATUS_FAILED_PERCENT = round(($METODE2_STATUS_FAILED/$KPB_JATUHTEMPO)*100).'%';
        $METODE2_CALL_STATUS_UNREACHABLE_PERCENT = round(($METODE2_STATUS_UNREACHABLE/$KPB_JATUHTEMPO)*100).'%';
        $METODE2_CALL_STATUS_REJECTED_PERCENT = round(($METODE2_STATUS_REJECTED/$KPB_JATUHTEMPO)*100).'%';
        $METODE2_CALL_STATUS_WORKLOAD_PERCENT = round(($METODE2_STATUS_WORKLOAD/$KPB_JATUHTEMPO)*100).'%';

    endif; endforeach; 

endif;


$TOTAL_SERVICE = $METODE1_SERVICED + $METODE2_SERVICED;
$TOTAL_SERVICE_PERCENT = $KPB_JATUHTEMPO == 0? '0%' : round(($TOTAL_SERVICE/$KPB_JATUHTEMPO)*100).'%';

?>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">
            
            <a onclick="print();" class="btn btn-default"><i class="fa fa-print"></i> Print </a>

            <!-- <a id="modal-button" class="btn btn-default" href="<?php echo base_url('customer/add_customer'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Tambah Customer
            </a> -->
        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                 Laporan Mingguan Reminder KPB
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border">

                <form id="" action="<?php echo base_url('report_kpb/laporan_reminder_kpb') ?>" class="bucket-form" method="get">

                    <div id="ajax-url" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>

                    <div class="row">

                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                      if (($dealer->totaldata > 0)) {
                                        foreach ($dealer->message as $key => $value) {
                                          $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                          //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                                          echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                        }
                                      }
                                    }
                                    ?>
                                  </select>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <div class="form-group">
                                <label>Jenis KPB</label>
                                <select id="jenis_kpb" name="jenis_kpb" class="form-control">
                                    <option value="KPB1" <?php echo $this->input->get('jenis_kpb') == 'KPB1'?'selected':''; ?>>KPB1</option>
                                    <option value="KPB2" <?php echo $this->input->get('jenis_kpb') == 'KPB2'?'selected':''; ?>>KPB2</option>
                                    <option value="KPB3" <?php echo $this->input->get('jenis_kpb') == 'KPB3'?'selected':''; ?>>KPB3</option>
                                    <option value="KPB4" <?php echo $this->input->get('jenis_kpb') == 'KPB4'?'selected':''; ?>>KPB4</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-3 col-md-2">
                            <div class="form-group">
                                <label>Periode</label>

                                <div class="input-group input-append date" id="datepicker">
                                  <input id="tgl_awal" type="text" name="tgl_awal" class="form-control" value="<?php echo $this->input->get('tgl_awal'); ?>" placeholder="DD/MM/YYYY">
                                  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>

                            </div>
                        </div>


                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label></label>

                                <input id="periode" type="text" name="periode" class="form-control disabled-action" value="<?php echo $date['start_date'].' - '.$date['end_date']; ?>" placeholder="DD/MM/YYYY" style="text-align: center;background: #ffc107;color: white;font-weight: 800;">


                            </div>
                        </div>


                        <div class="col-xs-3 col-sm-1 col-md-1">
                            <div class="form-group">
                                <br>
                                <button type="submit" class='btn btn-info'><i class="fa fa-search"></i> Preview</button>
                            </div>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">

            <!-- <div class="table-responsive"> -->

                <table id="printarea" class="table table-bordered b-t b-light text-center">

                    <tbody>
                        <tr style="background: #ffc107; color: #fff;">
                            <td style="width: 10%;"><strong>Service Type</strong></td>
                            <td><strong>Month</strong></td>
                            <td colspan="5"><strong><?php echo $date['month'];?></strong></td>
                        </tr>
                        <tr>
                            <td rowspan="34" style="background: #eeeeee; vertical-align: middle;">
                                <strong><?php echo $this->input->get('jenis_kpb')?$this->input->get('jenis_kpb'):'KPB1';?></strong>
                            </td>
                            <td>Total data source</td>
                            <td colspan="5"><?php echo $KPB_JATUHTEMPO;?></td>
                        </tr>

                        <tr>
                            <td>Phone No Available</td>
                            <td colspan="5"><?php echo $PHONE_JATUHTEMPO;?></td>
                        </tr>



                        <!-- metode 1 ========================================================================= -->

                        <tr>
                            <td rowspan="10">Atention by</td>
                            <td colspan="5" style="background: #eeeeee;">SMS</td>
                        </tr>


                    <!-- ================================== SMS ========================================= -->
                        <tr>
                            <td>Sent</td>
                            <td colspan="4">Failed</td>

                        </tr>
                        <tr>
                            <td><?php echo $METODE1_SMS_SUCCESS;?></td>
                            <td colspan="4"><?php echo $METODE1_SMS_FAILED;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE1_SMS_SUCCESS_PERCENT;?></td>
                            <td colspan="4"><?php echo $METODE1_SMS_FAILED_PERCENT;?></td>
                        </tr>



                    <!-- ================================== CALL ========================================= -->

                        <tr>
                            <td colspan="5" style="background: #eeeeee;">Call</td>
                        </tr>

                        <tr>
                            <td>Contacted</td>
                            <td colspan="4">Not Contacted</td>
                        </tr>
                        <tr>
                            <td rowspan="3"><?php echo $METODE1_CALL_SUCCESS;?></td>
                            <td colspan="4"><?php echo $METODE1_CALL_FAILED;?></td>
                        </tr>
                        <tr>
                            <td>Failed</td>
                            <td>Unreachable</td>
                            <td>Rejected</td>
                            <td>Workload</td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE1_CALL_STATUS_FAILED;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_UNREACHABLE;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_REJECTED;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_WORKLOAD;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE1_CALL_SUCCESS_PERCENT;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_FAILED_PERCENT;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_UNREACHABLE_PERCENT;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_REJECTED_PERCENT;?></td>
                            <td><?php echo $METODE1_CALL_STATUS_WORKLOAD_PERCENT;?></td>
                        </tr>




                        <!-- akhir metode 1 -->



                        <tr>
                            <td rowspan="3">1<sup>st</sup> result service visitor</td>
                            <td>Serviced</td>
                            <td colspan="4">Not yet service</td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE1_SERVICED;?></td>
                            <td colspan="4"><?php echo $METODE1_NOT_SERVICE;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE1_SERVICED_PERCENT;?></td>
                            <td colspan="4"><?php echo $METODE1_NOT_SERVICE_PERCENT;?></td>
                        </tr>


                        <!-- metode 2 ========================================================================= -->


                        <tr>
                            <td rowspan="10">Follow Up by</td>
                            <td colspan="5" style="background: #eeeeee;">SMS</td>
                        </tr>




                    <!-- ================================== SMS ========================================= -->
                        <tr>
                            <td>Sent</td>
                            <td colspan="4">Failed</td>

                        </tr>
                        <tr>
                            <td><?php echo $METODE2_SMS_SUCCESS;?></td>
                            <td colspan="4"><?php echo $METODE2_SMS_FAILED;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE2_SMS_SUCCESS_PERCENT;?></td>
                            <td colspan="4"><?php echo $METODE2_SMS_FAILED_PERCENT;?></td>
                        </tr>



                    <!-- ================================== CALL ========================================= -->

                        <tr>
                            <td colspan="5" style="background: #eeeeee;">Call</td>
                        </tr>

                        <tr>
                            <td>Contacted</td>
                            <td colspan="4">Not Contacted</td>
                        </tr>
                        <tr>
                            <td rowspan="3"><?php echo $METODE2_CALL_SUCCESS;?></td>
                            <td colspan="4"><?php echo $METODE2_CALL_FAILED;?></td>
                        </tr>
                        <tr>
                            <td>Failed</td>
                            <td>Unreachable</td>
                            <td>Rejected</td>
                            <td>Workload</td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE2_CALL_STATUS_FAILED;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_UNREACHABLE;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_REJECTED;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_WORKLOAD;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE2_CALL_SUCCESS_PERCENT;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_FAILED_PERCENT;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_UNREACHABLE_PERCENT;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_REJECTED_PERCENT;?></td>
                            <td><?php echo $METODE2_CALL_STATUS_WORKLOAD_PERCENT;?></td>
                        </tr>
                        <!-- akhir metode 2 -->


                        <tr>
                            <td rowspan="5">2<sup>nd</sup> result service visitor</td>
                            <td>Serviced</td>
                            <td colspan="4">Not yet service</td>
                        </tr>
                        <tr>
                            <td rowspan="3"><?php echo $METODE2_SERVICED;?></td>
                            <td colspan="4"><?php echo $METODE2_NOT_SERVICE;?></td>
                        </tr>
                        <tr>
                            <td>Too far (drop)</td>
                            <td colspan="2">No time</td>
                            <td>Forget</td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE2_FAR;?></td>
                            <td colspan="2"><?php echo $METODE2_NOTIME;?></td>
                            <td><?php echo $METODE2_FORGET;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $METODE2_SERVICED_PERCENT;?></td>
                            <td><?php echo $METODE2_FAR_PERCENT;?></td>
                            <td colspan="2"><?php echo $METODE2_NOTIME_PERCENT;?></td>
                            <td><?php echo $METODE2_FORGET_PERCENT;?></td>
                        </tr>

                        <tr>
                            <td rowspan="2">Total visitor <?php echo $this->input->get('jenis_kpb')?$this->input->get('jenis_kpb'):'KPB1';?> this month by CRM based on sales data</td>
                            <td colspan="5"><?php echo $TOTAL_SERVICE;?></td>
                        </tr>
                        <tr>
                            <td colspan="5"><?php echo $TOTAL_SERVICE_PERCENT;?></td>
                        </tr>


                    </tbody>

                    <tbody>

                    </tbody>

                </table>
            <!-- </div> -->

        </div>

    </div>

</section>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    var date = new Date();
    
    date.setDate(date.getDate());

    $('.date').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true
    });

    
    $('.datetimes').datetimepicker({
        format: 'DD/MM/YYYY',
        daysOfWeekDisabled: [2,3,4,5,6]
    });
});


function print() {
    printJS({ 
        printable: 'printarea', 
        type: 'html', 
        honorColor: true,
     });
 }

</script>