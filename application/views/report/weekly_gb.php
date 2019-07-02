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
 
 
$TOTAL_GUEST = 0;
$TOTAL_DATA = 0;
 
$METODE_SMS_SUCCESS = 0;
$METODE_SMS_FAILED = 0;
$METODE_SMS_SUCCESS_PERCENT = "0%";
$METODE_SMS_FAILED_PERCENT = "0%";
 
$METODE_CALL_STATUS_SUCCESS = 0;
$METODE_CALL_STATUS_FAILED = 0;
$METODE_CALL_STATUS_UNREACHABLE = 0;
$METODE_CALL_STATUS_REJECTED = 0;
$METODE_CALL_STATUS_WORKLOAD = 0;
$METODE_CALL_STATUS_SUCCESS_PERCENT = "0%";
$METODE_CALL_STATUS_FAILED_PERCENT = "0%";
$METODE_CALL_STATUS_UNREACHABLE_PERCENT = "0%";
$METODE_CALL_STATUS_REJECTED_PERCENT = "0%";
$METODE_CALL_STATUS_WORKLOAD_PERCENT = "0%";

$METODE_VISIT_SUCCESS = 0;
$METODE_VISIT_FAILED = 0;
$METODE_VISIT_STATUS_FAILED = 0;
$METODE_VISIT_STATUS_UNREACHABLE = 0;
$METODE_VISIT_STATUS_REJECTED = 0;
$METODE_VISIT_SUCCESS_PERCENT = "0%";
$METODE_VISIT_FAILED_PERCENT = "0%";
$METODE_VISIT_STATUS_FAILED_PERCENT = "0%";
$METODE_VISIT_STATUS_UNREACHABLE_PERCENT = "0%";
$METODE_VISIT_STATUS_REJECTED_PERCENT = "0%";

$METODE_DT_DEAL = 0;
$METODE_DT_LOWPROSPECT = 0;
$METODE_DT_HOTPROSPECT = 0;
$METODE_DT_NOTDEAL = 0;
$METODE_DT_DEAL_PERCENT = "0%";
$METODE_DT_LOWPROSPECT_PERCENT = "0%";
$METODE_DT_HOTPROSPECT_PERCENT = "0%";
$METODE_DT_NOTDEAL_PERCENT = "0%";

$WORKLOAD = 0;
$THIS_MONTH_BY_DT = 0;

$THIS_MONTH = 0;
$THIS_MONTH_PERCENT = "0%";
$NOT_PROSPECT = 0;
$M_MIN1 = 0;
$M_MIN2 = 0;
$M_MIN1_NOTDEAL = 0;
$M_MIN2_NOTDEAL = 0;

if($metode && (is_array($metode->message) || is_object($metode->message))): 
    foreach ($metode->message as $key => $value):

    	$TOTAL_GUEST = $value->TOTAL_GUEST;
    	$TOTAL_DATA = $value->TOTAL_DATA;

    	// var_dump($value->TOTAL_DATA);exit;

        $METODE_SMS_SUCCESS = $value->SMS_SENT;
        $METODE_SMS_FAILED = $value->SMS_FAILED;
        $METODE_SMS_SUCCESS_PERCENT = round(($METODE_SMS_SUCCESS/$TOTAL_DATA)*100).'%';
        $METODE_SMS_FAILED_PERCENT = round(($METODE_SMS_FAILED/$TOTAL_DATA)*100).'%';

        $METODE_CALL_STATUS_SUCCESS = $value->CALL_CONTACTED;
        $METODE_CALL_STATUS_FAILED = $value->CALL_FAILED;
        $METODE_CALL_STATUS_UNREACHABLE = $value->CALL_UNREACHABLE;
        $METODE_CALL_STATUS_REJECTED = $value->CALL_REJECTED;
        $METODE_CALL_STATUS_WORKLOAD = $value->CALL_WORKLOAD;
        $METODE_CALL_STATUS_SUCCESS_PERCENT = round(($METODE_CALL_STATUS_SUCCESS/$TOTAL_DATA)*100).'%';
        $METODE_CALL_STATUS_FAILED_PERCENT = round(($METODE_CALL_STATUS_FAILED/$TOTAL_DATA)*100).'%';
        $METODE_CALL_STATUS_UNREACHABLE_PERCENT = round(($METODE_CALL_STATUS_UNREACHABLE/$TOTAL_DATA)*100).'%';
        $METODE_CALL_STATUS_REJECTED_PERCENT = round(($METODE_CALL_STATUS_REJECTED/$TOTAL_DATA)*100).'%';
        $METODE_CALL_STATUS_WORKLOAD_PERCENT = round(($METODE_CALL_STATUS_WORKLOAD/$TOTAL_DATA)*100).'%';


		$METODE_DT_DEAL = $value->DIRECTTOUCH_DEAL;
		$METODE_DT_LOWPROSPECT = $value->DIRECTTOUCH_LOWPROSPECT;
		$METODE_DT_HOTPROSPECT = $value->DIRECTTOUCH_HOTPROSPECT;
		$METODE_DT_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;
		$METODE_DT_DEAL_PERCENT = round(($METODE_DT_DEAL/$TOTAL_DATA)*100).'%';
		$METODE_DT_LOWPROSPECT_PERCENT = round(($METODE_DT_LOWPROSPECT/$TOTAL_DATA)*100).'%';
		$METODE_DT_HOTPROSPECT_PERCENT = round(($METODE_DT_HOTPROSPECT/$TOTAL_DATA)*100).'%';
		$METODE_DT_NOTDEAL_PERCENT = round(($METODE_DT_NOTDEAL/$TOTAL_DATA)*100).'%';

		$THIS_MONTH_BY_DT = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;

    endforeach;

	foreach ($metode_thismonth->message as $key => $valueS) {

		$THIS_MONTH = $valueS->DIRECTTOUCH_DEAL + $valueS->DIRECTTOUCH_LOWPROSPECT + $valueS->DIRECTTOUCH_HOTPROSPECT;
		$THIS_MONTH_PERCENT = $TOTAL_DATA == 0? '0%' : round(($THIS_MONTH/$TOTAL_DATA)*100).'%';
		$NOT_PROSPECT = $valueS->DIRECTTOUCH_NOTDEAL;
	}
endif; 
/*

if($metode_thismonth && (is_array($metode_thismonth->message) || is_object($metode_thismonth->message))):
	foreach ($metode_thismonth->message as $key => $value) {

		$THIS_MONTH = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$THIS_MONTH_PERCENT = $TOTAL_DATA == 0? '0%' : round(($THIS_MONTH/$TOTAL_DATA)*100).'%';
		$NOT_PROSPECT = $value->DIRECTTOUCH_NOTDEAL;
	}

endif;*/

if($metode_min1 && (is_array($metode_min1->message) || is_object($metode_min1->message))):
	foreach ($metode_min1->message as $key => $value) {
		$WORKLOAD = $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;

		$M_MIN1 = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$M_MIN1_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;
	}

endif;


if($metode_min2 && (is_array($metode_min2->message) || is_object($metode_min2->message))):
	foreach ($metode_min2->message as $key => $value) {
		$M_MIN2 = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$M_MIN2_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;
	}

endif;
?>

<section class="wrapper">
 	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <!-- <a href="<?php echo str_replace("report_lbb","createlbb_file",$_SERVER["REQUEST_URI"]);?>" class="btn btn-default"><i class="fa fa-download"></i> Download file</a> -->
            <a onclick="printKw();" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>

    <div class="col-lg-12 padding-left-right-10" style="display: block;">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class=""></i> LAPORAN MINGGUAN GUESTBOOK (WEEKLY )
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="frmAdd" method="get" action="<?php echo base_url("report/weekly_gb");?>">
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
                                <label>Data Source</label>
                                <select id="gb_source" name="gb_source" class="form-control">
                                    <option value="Walk In" <?php echo $this->input->get('gb_source') == 'Walk In'?'selected':''; ?>>Walk In</option>
                                    <option value="Gathering" <?php echo $this->input->get('gb_source') == 'Gathering'?'selected':''; ?>>Gathering</option>
                                    <option value="Exhibition" <?php echo $this->input->get('gb_source') == 'Exhibition'?'selected':''; ?>>Exhibition</option>
                                    <option value="Canvassing" <?php echo $this->input->get('gb_source') == 'Canvassing'?'selected':''; ?>>Canvassing</option>
                                    <option value="Roadshow" <?php echo $this->input->get('gb_source') == 'Roadshow'?'selected':''; ?>>Roadshow</option>
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
    <div class="col-lg-12 padding-left-right-10" style="display: block;  padding: 10px;width:100%" >
         <div class="panel panel-default">
            
            <table id="printarea" class="table table-stripped table-bordered" style="font-size: 12px; text-align: center;">
			
			<tbody>	


				<tr>
					<th>MONTH</th>
					<th colspan="5"><?php echo $date['month'];?></th>
				</tr>
				
				<tr>
					<th>Data Source Guestbook</th>
					<th colspan="5"><?php echo $this->input->get('gb_source')?$this->input->get('gb_source'):'Walk In';?></th>
				</tr>
				
				<tr>
					<th>Total Data Source</th>
					<td colspan="5"><?php echo $TOTAL_GUEST;?></td>
				</tr>
				
				<tr>
					<th>Analysis By </th>
					<th colspan="5"> >2 Tahun-Wilayah JP-habis masa tenor</th>
				</tr>
				
				<tr>
					<th>Total Data Based On Analysis Result</th>
					<td colspan="5"><?php echo $TOTAL_DATA;?></td>
				</tr>
				
				<tr>
					<th rowspan="4">Attention by</th>
					<th colspan="5" style="background: #eeeeee;">SMS</th>
				</tr>

				<!-- ================================== SMS ========================================= -->

				<tr>
					
					<th>Sent</th>
					<th colspan="4">Failed</th>
				</tr>

				<tr>
					<td><?php echo $METODE_SMS_SUCCESS;?></td>
                    <td colspan="4"><?php echo $METODE_SMS_FAILED;?></td>
				</tr>
				
				<tr>
					<td><?php echo $METODE_SMS_SUCCESS_PERCENT;?></td>
                    <td colspan="4"><?php echo $METODE_SMS_FAILED_PERCENT;?></td>
				</tr>


				<tr>
					<th>Workoad from (M-1)</th>
					<td colspan="5"><?php echo $WORKLOAD;?></td>
				</tr>
				
				<tr>
					<th>Total data that must be followed up by phone</th>
					<td colspan="5"><?php echo $TOTAL_DATA;?></td>
				</tr>

				<!-- ================================== CALL========================================= -->

				<tr>
					<th rowspan="5">Follow Up by </th>
					<th colspan="5" style="background: #eeeeee;">CALL</th>
				</tr>


				<tr>
					<th rowspan="2"> Contacted</th>
					<th colspan="4">Not Contacted </th>
				</tr>
				
				<tr>
					<th>Failed</th>
					<th>Unreachable</th>
					<th>Rejected</th>
					<th>Workload</th>
				</tr>
				
				<tr>
					<td><?php echo $METODE_CALL_STATUS_SUCCESS;?></td>
					<td><?php echo $METODE_CALL_STATUS_FAILED;?></td>
					<td><?php echo $METODE_CALL_STATUS_UNREACHABLE;?></td>
                    <td><?php echo $METODE_CALL_STATUS_REJECTED;?></td>
                    <td><?php echo $METODE_CALL_STATUS_WORKLOAD;?></td>
				</tr>
				
				<tr>
					<td><?php echo $METODE_CALL_STATUS_SUCCESS_PERCENT;?></td>
					<td><?php echo $METODE_CALL_STATUS_FAILED_PERCENT;?></td>
                    <td><?php echo $METODE_CALL_STATUS_UNREACHABLE_PERCENT;?></td>
                    <td><?php echo $METODE_CALL_STATUS_REJECTED_PERCENT;?></td>
                    <td><?php echo $METODE_CALL_STATUS_WORKLOAD_PERCENT;?></td>
				</tr>
				
				<tr>
					<th rowspan="6">Total Prospect</th>
					<th colspan="3">Prospect</th>
					<th colspan="2" rowspan="2"> Not Prospect</th>
				</tr>
				
				<tr>
					<th>(M-2)</th>
					<th> (M-1)</th>
					<th> (M)</th>
				</tr>
				
				<tr>
					<td><?php echo $M_MIN2;?></td>
					<td><?php echo $M_MIN1;?></td>
					<td><?php echo $THIS_MONTH;?></td>
					<td colspan="2"><?php echo $NOT_PROSPECT;?></td>
				</tr>
				
				<tr>
					<th colspan="5">Total Prospect</th>
				</tr>
				
				<tr>
					<td colspan="5"><?php echo $THIS_MONTH;?></td>
				</tr>
				
				<tr>
					<td colspan="5"><?php echo $THIS_MONTH_PERCENT;?></td>
				</tr>
				
				<tr>
					<th rowspan="3">Total Customer Result from direct touch</th>
					<th>Deal </th>
					<th>Hot Prospect</th>
					<th>Low Prospect </th>
					<th colspan="2">Not Deal</th>
				</tr>
				
				<tr>
					<td><?php echo $METODE_DT_DEAL;?></td>
					<td><?php echo $METODE_DT_LOWPROSPECT;?></td>
					<td><?php echo $METODE_DT_HOTPROSPECT;?></td>
					<td colspan="2"><?php echo $METODE_DT_NOTDEAL;?></td>
				</tr>
				
				<tr>
					<td><?php echo $METODE_DT_DEAL_PERCENT;?></td>
					<td><?php echo $METODE_DT_LOWPROSPECT_PERCENT;?></td>
					<td><?php echo $METODE_DT_HOTPROSPECT_PERCENT;?></td>
					<td colspan="2"><?php echo $METODE_DT_NOTDEAL_PERCENT;?></td>
				</tr>
				
				<tr>
					<th rowspan="3">Total Unit Sold Result From Direct Touch</th>
					<th>Deal</th>
					<th colspan="4" rowspan="3"></th>
				</tr>
				
				<tr>
					<td><?php echo $METODE_DT_DEAL;?></td>
				</tr>
				
				<tr>
					<td><?php echo $METODE_DT_DEAL_PERCENT;?></td>
				</tr>
				
				
				<tr>
					<th rowspan="2">Tracking Data Pending </th>
					<th> (M-2)</th>
					<th> (M-1)</th>
					<th> (M)</th>
					<td colspan="2" rowspan="7"></td>
				</tr>
				
				<tr>
					<td><?php echo $M_MIN2;?></td>
					<td><?php echo $M_MIN1;?></td>
					<td><?php echo $THIS_MONTH;?></td>
				</tr>
				
				<tr>
					<th>Contacted by Direct Touch</th>
					<td></td>
					<td></td>
					<td><?php echo $THIS_MONTH_BY_DT;?></td>
				</tr>
				
				<tr>
					<th>Deal</th>
					<td></td>
					<td></td>
					<td><?php echo $METODE_DT_DEAL;?></td>
				</tr>
				
				<tr>
					<th>Hot Prospect</th>
					<td></td>
					<td></td>
					<td><?php echo $METODE_DT_LOWPROSPECT;?></td>
				</tr>
				
				<tr>
					<th>Low Prospect</th>
					<td></td>
					<td></td>
					<td><?php echo $METODE_DT_HOTPROSPECT;?></td>
				</tr>
				
				<tr>
					<th>Not Deal </th>
					<td><?php echo $M_MIN1_NOTDEAL;?></td>
					<td><?php echo $M_MIN2_NOTDEAL;?></td>
					<td><?php echo $METODE_DT_NOTDEAL;?></td>
				</tr>
			</tbody>	
            </table>
        </div>
    </div>
</section>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printKw() {
            // $('#printarea').addClass("onlyprint");
            printJS({ 
                printable: 'printarea', 
                type: 'html', 
                honorColor: true,
             });
            // $('#printarea').removeClass("onlyprint");
            
         }
</script>