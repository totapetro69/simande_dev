<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$print_ufu = $crm->totaldata > 0 ? $status_p : 'disabled-action';

$dari_tgl =($this->input->get("tgl_trans"))?$this->input->get("tgl_trans"):date("d/m/Y",strtotime("-1 Days"));

$bulan= ($this->input->get('bulan'))?$this->input->get('bulan'):date("m");

$tahuns= ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');

 
// H1 
$H1_TOTAL_GUEST = 0;
$H1_TOTAL_DATA = 0;
$H1_METODE_SMS_SUCCESS = 0;
$H1_METODE_SMS_FAILED = 0;
$H1_METODE_CALL_STATUS_SUCCESS = 0;
$H1_METODE_CALL_STATUS_FAILED = 0;
$H1_METODE_CALL_STATUS_UNREACHABLE = 0;
$H1_METODE_CALL_STATUS_REJECTED = 0;
$H1_METODE_CALL_STATUS_WORKLOAD = 0;
$H1_METODE_VISIT_SUCCESS = 0;
$H1_METODE_VISIT_FAILED = 0;
$H1_METODE_VISIT_STATUS_FAILED = 0;
$H1_METODE_VISIT_STATUS_UNREACHABLE = 0;
$H1_METODE_VISIT_STATUS_REJECTED = 0;
$H1_METODE_DT_DEAL = 0;
$H1_METODE_DT_LOWPROSPECT = 0;
$H1_METODE_DT_HOTPROSPECT = 0;
$H1_METODE_DT_NOTDEAL = 0;

$H1_WORKLOAD = 0;
$H1_THIS_MONTH_BY_DT = 0;
$H1_THIS_MONTH = 0;
$H1_NOT_PROSPECT = 0;
$H1_M_MIN1 = 0;
$H1_M_MIN2 = 0;
$H1_M_MIN1_NOTDEAL = 0;
$H1_M_MIN2_NOTDEAL = 0;

// H1H2
$H1H2_TOTAL_GUEST = 0;
$H1H2_TOTAL_DATA = 0;
$H1H2_METODE_SMS_SUCCESS = 0;
$H1H2_METODE_SMS_FAILED = 0;
$H1H2_METODE_CALL_STATUS_SUCCESS = 0;
$H1H2_METODE_CALL_STATUS_FAILED = 0;
$H1H2_METODE_CALL_STATUS_UNREACHABLE = 0;
$H1H2_METODE_CALL_STATUS_REJECTED = 0;
$H1H2_METODE_CALL_STATUS_WORKLOAD = 0;
$H1H2_METODE_VISIT_SUCCESS = 0;
$H1H2_METODE_VISIT_FAILED = 0;
$H1H2_METODE_VISIT_STATUS_FAILED = 0;
$H1H2_METODE_VISIT_STATUS_UNREACHABLE = 0;
$H1H2_METODE_VISIT_STATUS_REJECTED = 0;
$H1H2_METODE_DT_DEAL = 0;
$H1H2_METODE_DT_LOWPROSPECT = 0;
$H1H2_METODE_DT_HOTPROSPECT = 0;
$H1H2_METODE_DT_NOTDEAL = 0;

$H1H2_WORKLOAD = 0;
$H1H2_THIS_MONTH_BY_DT = 0;
$H1H2_THIS_MONTH = 0;
$H1H2_NOT_PROSPECT = 0;
$H1H2_M_MIN1 = 0;
$H1H2_M_MIN2 = 0;
$H1H2_M_MIN1_NOTDEAL = 0;
$H1H2_M_MIN2_NOTDEAL = 0;

// H2
$H2_TOTAL_GUEST = 0;
$H2_TOTAL_DATA = 0;
$H2_METODE_SMS_SUCCESS = 0;
$H2_METODE_SMS_FAILED = 0;
$H2_METODE_CALL_STATUS_SUCCESS = 0;
$H2_METODE_CALL_STATUS_FAILED = 0;
$H2_METODE_CALL_STATUS_UNREACHABLE = 0;
$H2_METODE_CALL_STATUS_REJECTED = 0;
$H2_METODE_CALL_STATUS_WORKLOAD = 0;
$H2_METODE_VISIT_SUCCESS = 0;
$H2_METODE_VISIT_FAILED = 0;
$H2_METODE_VISIT_STATUS_FAILED = 0;
$H2_METODE_VISIT_STATUS_UNREACHABLE = 0;
$H2_METODE_VISIT_STATUS_REJECTED = 0;
$H2_METODE_DT_DEAL = 0;
$H2_METODE_DT_LOWPROSPECT = 0;
$H2_METODE_DT_HOTPROSPECT = 0;
$H2_METODE_DT_NOTDEAL = 0;

$H2_WORKLOAD = 0;
$H2_THIS_MONTH_BY_DT = 0;
$H2_THIS_MONTH = 0;
$H2_NOT_PROSPECT = 0;
$H2_M_MIN1 = 0;
$H2_M_MIN2 = 0;
$H2_M_MIN1_NOTDEAL = 0;
$H2_M_MIN2_NOTDEAL = 0;

if($crm && (is_array($crm->message) || is_object($crm->message))): 
    foreach ($crm->message as $key => $value):

    if($value->TIPE == 'H1'):	
    	// H1 
		$H1_TOTAL_GUEST = $value->JUMLAH;
		$H1_TOTAL_DATA = $value->JUMLAH;

		$H1_METODE_SMS_SUCCESS = $value->SMS_SENT;
		$H1_METODE_SMS_FAILED = $value->SMS_FAILED;
		$H1_METODE_CALL_STATUS_SUCCESS = $value->CALL_CONTACTED;
		$H1_METODE_CALL_STATUS_FAILED = $value->CALL_FAILED;
		$H1_METODE_CALL_STATUS_UNREACHABLE = $value->CALL_REJECTED;
		$H1_METODE_CALL_STATUS_REJECTED = $value->CALL_UNREACHABLE;
		$H1_METODE_CALL_STATUS_WORKLOAD = $value->CALL_WORKLOAD;
		$H1_METODE_DT_DEAL = $value->DIRECTTOUCH_DEAL;
		$H1_METODE_DT_LOWPROSPECT = $value->DIRECTTOUCH_LOWPROSPECT;
		$H1_METODE_DT_HOTPROSPECT = $value->DIRECTTOUCH_HOTPROSPECT;
		$H1_METODE_DT_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;

		$H1_THIS_MONTH = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$H1_NOT_PROSPECT = $value->DIRECTTOUCH_NOTDEAL;
		/*$H1_WORKLOAD = $value->;
		$H1_M_MIN1 = $value->;
		$H1_M_MIN2 = $value->;
		$H1_M_MIN1_NOTDEAL = $value->;
		$H1_M_MIN2_NOTDEAL = $value->;*/

	elseif ($value->TIPE == 'H1H2'):
		// H1H2
		$H1H2_TOTAL_GUEST = $value->JUMLAH;
		$H1H2_TOTAL_DATA = $value->JUMLAH;

		$H1H2_METODE_SMS_SUCCESS = $value->SMS_SENT;
		$H1H2_METODE_SMS_FAILED = $value->SMS_FAILED;
		$H1H2_METODE_CALL_STATUS_SUCCESS = $value->CALL_CONTACTED;
		$H1H2_METODE_CALL_STATUS_FAILED = $value->CALL_FAILED;
		$H1H2_METODE_CALL_STATUS_UNREACHABLE = $value->CALL_REJECTED;
		$H1H2_METODE_CALL_STATUS_REJECTED = $value->CALL_UNREACHABLE;
		$H1H2_METODE_CALL_STATUS_WORKLOAD = $value->CALL_WORKLOAD;
		$H1H2_METODE_DT_DEAL = $value->DIRECTTOUCH_DEAL;
		$H1H2_METODE_DT_LOWPROSPECT = $value->DIRECTTOUCH_LOWPROSPECT;
		$H1H2_METODE_DT_HOTPROSPECT = $value->DIRECTTOUCH_HOTPROSPECT;
		$H1H2_METODE_DT_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;

		$H1H2_THIS_MONTH = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$H1H2_NOT_PROSPECT = $value->DIRECTTOUCH_NOTDEAL;
	elseif ($value->TIPE == 'H2'):
		// H2
		$H2_TOTAL_GUEST = $value->JUMLAH;
		$H2_TOTAL_DATA = $value->JUMLAH;

		$H2_METODE_SMS_SUCCESS = $value->SMS_SENT;
		$H2_METODE_SMS_FAILED = $value->SMS_FAILED;
		$H2_METODE_CALL_STATUS_SUCCESS = $value->CALL_CONTACTED;
		$H2_METODE_CALL_STATUS_FAILED = $value->CALL_FAILED;
		$H2_METODE_CALL_STATUS_UNREACHABLE = $value->CALL_REJECTED;
		$H2_METODE_CALL_STATUS_REJECTED = $value->CALL_UNREACHABLE;
		$H2_METODE_CALL_STATUS_WORKLOAD = $value->CALL_WORKLOAD;
		$H2_METODE_DT_DEAL = $value->DIRECTTOUCH_DEAL;
		$H2_METODE_DT_LOWPROSPECT = $value->DIRECTTOUCH_LOWPROSPECT;
		$H2_METODE_DT_HOTPROSPECT = $value->DIRECTTOUCH_HOTPROSPECT;
		$H2_METODE_DT_NOTDEAL = $value->DIRECTTOUCH_NOTDEAL;

		$H2_THIS_MONTH = $value->DIRECTTOUCH_DEAL + $value->DIRECTTOUCH_LOWPROSPECT + $value->DIRECTTOUCH_HOTPROSPECT;
		$H2_NOT_PROSPECT = $value->DIRECTTOUCH_NOTDEAL;
	endif;

    endforeach;

	/*foreach ($metode_thismonth->message as $key => $valueS):

		$THIS_MONTH = $valueS->DIRECTTOUCH_DEAL + $valueS->DIRECTTOUCH_LOWPROSPECT + $valueS->DIRECTTOUCH_HOTPROSPECT;
		$NOT_PROSPECT = $valueS->DIRECTTOUCH_NOTDEAL;
	endforeach;*/
endif; 

/*if($metode_min1 && (is_array($metode_min1->message) || is_object($metode_min1->message))):
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

endif;*/


?>

<section class="wrapper">
 	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <a href="<?php echo base_url('report/download_ufu?tgl_awal='.$this->input->get('tgl_awal'));?>" class="btn btn-default <?php echo $print_ufu;?>"><i class="fa fa-download"></i> Download file</a>
            <a onclick="printKw();" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>

	<div class="col-lg-12 padding-left-right-10" style="display: block;">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class=""></i> LAPORAN MINGGUAN CRM (WEEKLY )
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="frmAdd" method="get" action="<?php echo base_url("report/weekly_crm");?>">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select id="kd_dealer" name="kd_dealer" class="form-control">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if(isset($dealer)){
                                            if($dealer->totaldata >0){
                                                foreach ($dealer->message as $key => $value) {
                                                    $select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                                                    $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }
                                    ?>
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
    <div id="printarea" class="col-lg-12 padding-left-right-10" style="display: block;  padding: 10px;">
        <div class="panel panel-default">

			<table class="table table-stripped table-bordered" style="font-size: 12px" >

			<tbody>

				<tr>
					<th class="text-center">MONTH</th>
					<th colspan="12" class="text-center"><?php echo $date['month'];?></th>
				</tr>
				
				<tr>
					<th class="text-center">Data Source H2 to H1</th>
					<th colspan="4" class="text-center">H1 (Hanya Beli)</th>
					<th colspan="4" class="text-center">Unit Entry Bulan Des 2015 (Beli dan Service –Dealer Sendiri)</th>
					<th colspan="4" class="text-center">Unit Entry Bulan Des 2015 (Hanya Service –Dealer Lain)</th>
				</tr>
				
				<tr>
					<th class="text-center">Total Data Source</th>
					<td colspan="4" class="text-center"> <?php echo $H1_TOTAL_DATA;?></td>
					<td colspan="4" class="text-center"> <?php echo $H1H2_TOTAL_DATA;?></td>
					<td colspan="4" class="text-center"> <?php echo $H2_TOTAL_DATA;?></td>
				</tr>
				
				<tr>
					<th class="text-center">Analysis By </th>
					<td colspan="4" class="text-center"> >2 Tahun-Wilayah JP-habis masa tenor</td>
					<td colspan="4" class="text-center"> > 2 Years M/C on Dec 2015</td>
					<td colspan="4" class="text-center">  > 2 Years M/C on Dec 2015</td>
				</tr>
				
				<tr>
					<th class="text-center">Total Data Based On Analysis Result</th>
					<td colspan="4" class="text-center"><?php echo $H1_TOTAL_DATA;?></td>
					<td colspan="4" class="text-center"><?php echo $H1H2_TOTAL_DATA;?></td>
					<td colspan="4" class="text-center"><?php echo $H2_TOTAL_DATA;?></td>
				</tr>
				
				<tr>
					<th rowspan="3" class="text-center">Attention by SMS</th>

					<th class="text-center">Sent</th>
					<th colspan="3" class="text-center">Failed</th>

					<th class="text-center">Sent</th>
					<th colspan="3" class="text-center">Failed</th>

					<th class="text-center">Sent</th>
					<th colspan="3" class="text-center">Failed</th>
				</tr>
				
				<tr>
					<td class="text-center"><?php echo $H1_METODE_SMS_SUCCESS;?></td>
					<td colspan="3" class="text-center"> <?php echo $H1_METODE_SMS_FAILED;?></td>

					<td class="text-center"><?php echo $H1H2_METODE_SMS_SUCCESS;?></td>
					<td colspan="3" class="text-center"> <?php echo $H1H2_METODE_SMS_FAILED;?></td>

					<td class="text-center"><?php echo $H2_METODE_SMS_SUCCESS;?></td>
					<td colspan="3" class="text-center"> <?php echo $H2_METODE_SMS_FAILED;?></td>
				</tr>
				
				<tr>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_SMS_SUCCESS/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td colspan="3"class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_SMS_FAILED/$H1_TOTAL_DATA)*100).'%';?>
					</td>

					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_SMS_SUCCESS/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td colspan="3"class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_SMS_FAILED/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>

					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_SMS_SUCCESS/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td colspan="3"class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_SMS_FAILED/$H2_TOTAL_DATA)*100).'%';?>
					</td>
				</tr>
				
				<tr>
					<th class="text-center">Workoad from (M-1)</th>
					<td colspan="4"class="text-center">0</td>
					<td colspan="4"class="text-center">0</td>
					<td colspan="4"class="text-center">0</td>
				</tr>
				
				<tr>
					<th class="text-center">Total data that must be followed up by phone</th>
					<td colspan="4"class="text-center"><?php echo $H1_TOTAL_DATA;?></td>
					<td colspan="4"class="text-center"><?php echo $H1H2_TOTAL_DATA;?></td>
					<td colspan="4"class="text-center"><?php echo $H2_TOTAL_DATA;?></td>
				</tr>
				
				<tr>
					<th rowspan="4"class="text-center">Follow Up by Call</th>
					<th rowspan="2"class="text-center"> Contacted</th>
					<th colspan="3"class="text-center">Not Contacted </th>
					<th rowspan="2"class="text-center"> Contacted</th>
					<th colspan="3"class="text-center">Not Contacted </th>
					<th rowspan="2"class="text-center"> Contacted</th>
					<th colspan="3"class="text-center">Not Contacted </th>
				</tr>
				
				<tr>
					<th class="text-center">Unreachable</th>
					<th class="text-center">Rejected</th>
					<th class="text-center">Workload</th>
					<th class="text-center">Unreachable</th>
					<th class="text-center">Rejected</th>
					<th class="text-center">Workload</th>
					<th class="text-center">Unreachable</th>
					<th class="text-center">Rejected</th>
					<th class="text-center">Workload</th>
				</tr>
				
				<tr>
					<td class="text-center"><?php echo $H1_METODE_CALL_STATUS_SUCCESS;?></td>
					<td class="text-center"><?php echo $H1_METODE_CALL_STATUS_UNREACHABLE;?></td>
					<td class="text-center"><?php echo $H1_METODE_CALL_STATUS_REJECTED;?></td>
					<td class="text-center"><?php echo $H1_METODE_CALL_STATUS_WORKLOAD;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_CALL_STATUS_SUCCESS;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_CALL_STATUS_UNREACHABLE;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_CALL_STATUS_REJECTED;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_CALL_STATUS_WORKLOAD;?></td>
					<td class="text-center"><?php echo $H2_METODE_CALL_STATUS_SUCCESS;?></td>
					<td class="text-center"><?php echo $H2_METODE_CALL_STATUS_UNREACHABLE;?></td>
					<td class="text-center"><?php echo $H2_METODE_CALL_STATUS_REJECTED;?></td>
					<td class="text-center"><?php echo $H2_METODE_CALL_STATUS_WORKLOAD;?></td>
				</tr>
				
				<tr>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_CALL_STATUS_SUCCESS/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_CALL_STATUS_UNREACHABLE/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_CALL_STATUS_REJECTED/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_CALL_STATUS_WORKLOAD/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_CALL_STATUS_SUCCESS/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_CALL_STATUS_UNREACHABLE/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_CALL_STATUS_REJECTED/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_CALL_STATUS_WORKLOAD/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_CALL_STATUS_SUCCESS/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_CALL_STATUS_UNREACHABLE/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_CALL_STATUS_REJECTED/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_CALL_STATUS_WORKLOAD/$H2_TOTAL_DATA)*100).'%';?>
					</td>
				</tr>
				
				<tr>
					<th rowspan="6" class="text-center">Total Prospect</th>
					<th colspan="3" class="text-center">Prospect</th>
					<th rowspan="2" class="text-center"> Not Prospect</th>
					<th colspan="3" class="text-center">Prospect</th>
					<th rowspan="2" class="text-center"> Not Prospect</th>
					<th colspan="3" class="text-center">Prospect</th>
					<th rowspan="2" class="text-center"> Not Prospect</th>
				</tr>
				
				<tr>
					<td class="text-center">(M-2)</td>
					<td class="text-center"> (M-1)</td>
					<td class="text-center"> (M)</td>
					<td class="text-center">(M-2)</td>
					<td class="text-center"> (M-1)</td>
					<td class="text-center"> (M)</td>
					<td class="text-center">(M-2)</td>
					<td class="text-center"> (M-1)</td>
					<td class="text-center"> (M)</td>
				</tr>
				
				<tr>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H1_THIS_MONTH;?></td>
					<td class="text-center"><?php echo $H1_NOT_PROSPECT;?></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H1H2_THIS_MONTH;?></td>
					<td class="text-center"><?php echo $H1H2_NOT_PROSPECT;?></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H2_THIS_MONTH;?></td>
					<td class="text-center"><?php echo $H2_NOT_PROSPECT;?></td>
				</tr>
				
				<tr>
					<td colspan="4" class="text-center">Total Prospect</td>
					<td colspan="4" class="text-center">Total Prospect</td>
					<td colspan="4" class="text-center">Total Prospect</td>
				</tr>
				
				<tr>
					<td colspan="4" class="text-center">0</td>
					<td colspan="4" class="text-center">0</td>
					<td colspan="4" class="text-center">0</td>
				</tr>
				
				<tr>
					<td colspan="4" class="text-center">0%</td>
					<td colspan="4" class="text-center">0%</td>
					<td colspan="4" class="text-center">0%</td>
				</tr>
				
				<tr>
					<th rowspan="3" class="text-center">Total Customer Result from direct touch</th>
					<th class="text-center">Deal </th>
					<th class="text-center">Hot Prospect</th>
					<th class="text-center">Low Prospect </th>
					<th class="text-center">Not Deal</th>
					<th class="text-center">Deal </th>
					<th class="text-center">Hot Prospect</th>
					<th class="text-center">Low Prospect </th>
					<th class="text-center">Not Deal</th>
					<th class="text-center">Deal </th>
					<th class="text-center">Hot Prospect</th>
					<th class="text-center">Low Prospect </th>
					<th class="text-center">Not Deal</th>
				</tr>
				
				<tr>
					<td class="text-center"><?php echo $H1_METODE_DT_DEAL;?></td>
					<td class="text-center"><?php echo $H1_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"><?php echo $H1_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"><?php echo $H1_METODE_DT_NOTDEAL;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_DEAL;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_NOTDEAL;?></td>
					<td class="text-center"><?php echo $H2_METODE_DT_DEAL;?></td>
					<td class="text-center"><?php echo $H2_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"><?php echo $H2_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"><?php echo $H2_METODE_DT_NOTDEAL;?></td>
				</tr>
				
				<tr>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_DT_DEAL/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_DT_LOWPROSPECT/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_DT_HOTPROSPECT/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_DT_NOTDEAL/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_DT_DEAL/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_DT_LOWPROSPECT/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_DT_HOTPROSPECT/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_DT_NOTDEAL/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_DT_DEAL/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_DT_LOWPROSPECT/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_DT_HOTPROSPECT/$H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_DT_NOTDEAL/$H2_TOTAL_DATA)*100).'%';?>
					</td>
				</tr>
				
				<tr>
					<th rowspan="3" class="text-center">Total Unit Sold Result From Direct Touch</th>
					<td class="text-center">Deal</td>
					<td colspan="3" rowspan="3" class="text-center"></td>
					<td class="text-center">Deal</td>
					<td colspan="3" rowspan="3" class="text-center"></td>
					<td class="text-center">Deal</td>
					<td colspan="3" rowspan="3" class="text-center"></td>
				</tr>
				
				<tr>
					<td class="text-center"><?php echo $H1_METODE_DT_DEAL;?></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_DEAL;?></td>
					<td class="text-center"><?php echo $H2_METODE_DT_DEAL;?></td>
				</tr>
				
				<tr>
					<td class="text-center">
						<?php echo $H1_TOTAL_DATA == 0? '0%' : round(($H1_METODE_DT_DEAL/$H1_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H1H2_TOTAL_DATA == 0? '0%' : round(($H1H2_METODE_DT_DEAL/$H1H2_TOTAL_DATA)*100).'%';?>
					</td>
					<td class="text-center">
						<?php echo $H2_TOTAL_DATA == 0? '0%' : round(($H2_METODE_DT_DEAL/$H2_TOTAL_DATA)*100).'%';?>
					</td>
				</tr>
				
				<!-- <tr>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
				</tr> -->
				
				<tr>
					<th rowspan="2" class="text-center">Tracking Data Pending </th>
					<th class="text-center"> (M-2)</th>
					<th class="text-center"> (M-1)</th>
					<th class="text-center"> (M)</th>
					<td class="text-center"></td>
					<th class="text-center"> (M-2)</th>
					<th class="text-center"> (M-1)</th>
					<th class="text-center"> (M)</th>
					<td class="text-center"></td>
					<th class="text-center"> (M-2)</th>
					<th class="text-center"> (M-1)</th>
					<th class="text-center"> (M)</th>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H1_THIS_MONTH;?></td>
					<td class="text-center"></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H1H2_THIS_MONTH;?></td>
					<td class="text-center"></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"><?php echo $H2_THIS_MONTH;?></td>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<th class="text-center">Contacted by Direct Touch</th>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1_THIS_MONTH;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1H2_THIS_MONTH;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H2_THIS_MONTH;?></td>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<th class="text-center">Deal</th>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1_METODE_DT_DEAL;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_DEAL;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H2_METODE_DT_DEAL;?></td>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<th class="text-center">Hot Prospect</th>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H2_METODE_DT_HOTPROSPECT;?></td>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<th class="text-center">Low Prospect</th>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H1H2_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"></td>
					<td class="text-center"><?php echo $H2_METODE_DT_LOWPROSPECT;?></td>
					<td class="text-center"></td>
				</tr>
				
				<tr>
					<th class="text-center">Not Deal </th>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"></td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center">0</td>
					<td class="text-center"></td>
				</tr>
			</tbody>
            </table>
        </div>
    </div>
</section>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printKw() {
            $('#printarea').addClass("onlyprint");
            printJS({ 
                printable: 'printarea', 
                type: 'html', 
                honorColor: true,
             });
            $('#printarea').removeClass("onlyprint");
            
         }
</script>