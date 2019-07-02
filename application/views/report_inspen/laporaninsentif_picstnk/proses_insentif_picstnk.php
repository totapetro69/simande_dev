<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$kd_dealer=$this->session->userdata("kd_dealer");
//var_dump($list);exit();
?>
<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default <?php echo $status_p ?>" id="modal-button" role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" onclick="checkData()">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Proses Insentif" ></i> Proses Insentif
            </a>    
			<input id = "urlModal" type="hidden" value="<?php echo base_url('report_inspen/modal_proses_insentif_picstnk'); ?>" />
        </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading">
                Dealer
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>

            <div class="panel-body panel-body-border" style="display: block;">

                <form id="penerimaanForm" action="<?php echo base_url('report_inspen/laporaninsentif_picstnk') ?>" class="bucket-form" method="get">

                    <div class="row">

                        <div class="col-xs-12 col-sm-3 col-md-3">

                            <div class="form-group">
                                <label>Dealer</label>
                                <select disabled name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                                    <option value="">- Pilih Dealer -</option>
                                   <?php
                                        if(isset($dealer)){
                                            if($dealer->totaldata >0){
                                                foreach ($dealer->message as $key => $value) {
													$dlr_temp = $this->input->get('kd_dealer') ? $this->input->get('kd_dealer') : $this->session->userdata("kd_dealer");
                                                    $select=($dlr_temp==$value->KD_DEALER)?"selected":"";
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->KD_DEALER." - ".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                        </div>
						
						
                        <div class="col-xs-12 col-sm-2 col-md-2">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Periode Awal</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
 
                        <div class="col-xs-12 col-sm-2 col-md-2">
 
                            <div class="form-group">
 
                                <label class="control-label" for="date">Periode Akhir</label>
                                <div class="input-group input-append date">
                                    <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
 
                            </div>
 
                        </div>
                        
                        <div class="col-xs-12 col-sm-1 col-md-1">

                            <div class="form-group">

                                <br />
                                <button id="submit-btn" onclick="" class="btn btn-primary"><i class="fa fa-search"></i> Preview</button>

                            </div>

                        </div>  

                    </div>

                </form>

            </div>

        </div>

    </div>
	
    <div class="col-lg-12 padding-left-right-10">

        <div class="panel panel-default">

            <div class="table-responsive">

                <table class="table table-striped b-t b-light">

                    <thead>

                        <tr>
                            <th style="width:40px;">No.</th>
                            <th>NIK</th>
                            <th>Nama Pengurus</th>
                            <th>No. Transaksi</th>
                            <th>Jumlah</th>
                            <th>Insentif/Unit</th>
                            <th>Jumlah Insentif</th>
							<th>Tgl. Selesai Pengurusan</th>
                        </tr>
                       
                    </thead>
					<tbody>
                        <?php
						$valuedata = "empty";
                        $no = $this->input->get('page');
						//var_dump($list);exit;
                        if ($list):
                            if (is_array($list->message)):
								$datadetail = array();
								$datadetail = $list->message;
								$_SESSION["array_detail"] = $datadetail;
								//var_dump($datadetail);exit;
								$TOTAL_SELURUHNYA = 0;
								$totalunit = 0;
								$birojasa = "";
                                foreach ($list->message as $key => $row):
                                    $no ++;
									$TOTAL_SELURUHNYA = $TOTAL_SELURUHNYA + $row->JUMLAH_INSENTIF;
									$totalunit = $totalunit + $row->JUMLAH;
									if($no < $list->totaldata){
										$next = $list->message[$no];
										$birojasa = $next->KD_BIROJASA;
									}
									?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo $row->KD_BIROJASA; ?></td>
                                        <td><?php echo $row->NAMA_PENGURUS; ?></td>
                                        <td><?php echo $row->NO_TRANS; ?></td>
                                        <td><?php echo $row->JUMLAH; ?></td>
                                        <td><?php echo $row->INSENTIF_PERUNIT; ?></td>
										<td><?php echo $row->JUMLAH_INSENTIF; ?></td>
										<td><?php echo $row->TGLSELESAI_PENGURUSAN; ?></td>
                                    </tr>
									<?php 
									if(($birojasa != "" && $next->KD_BIROJASA != $row->KD_BIROJASA) || $no == $list->totaldata){
									?>
									<tr>
                                        <td colspan = '2'>TOTAL INSENTIF</td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $totalunit ?></td>
										<td></td>
                                        <td><?php echo $TOTAL_SELURUHNYA; ?></td>
                                    </tr>
                                    <?php
									$totalunit = 0;
									$TOTAL_SELURUHNYA = 0;
									}
									//$birojasa = $row->KD_BIROJASA;
                                endforeach;
								$valuedata = "Notempty";
                            else:
                                ?>

                                <tr>
                                    <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                                    <td colspan="40"><b><?php echo ($list->message); ?></b></td>
                                </tr>

                            <?php
                            endif;
                        else:
                            echo belumAdaData(40);
                        endif;
                        ?>
                    </tbody>
                    

                </table>

            </div>
			<input type="hidden" id="statusData" value="<?php echo $valuedata?>"/>
            <footer class="panel-footer">
                <div class="row">

                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo $pagination; ?>
                    </div>
                    
                </div>
                
            </footer>

        </div>
        
    </div>
    
</section>

<script type="text/javascript">

    $(document).ready(function (e) {
        
    });
    function checkData(){
		var statusData = document.getElementById("statusData").value;
		if (statusData == "empty"){
			alert("Data Kosong!");
			window.location.replace(window.location.href);
		} else {
			var url = document.getElementById("urlModal").value;
			addForm(url);
		}
	}
 

</script>
