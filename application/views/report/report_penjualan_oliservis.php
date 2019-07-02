<?php
     $bulan= ($this->input->get('bulan'))?$this->input->get('bulan'):date("m");
     $tahuns= ($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');
     $defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
	//var_dump($dealer);exit();
	$loop_max = "";
	if($bulan%2 == 1){
		$loop_max = 31;
	} else {
		if($bulan == 2){
			if($tahuns%4 == 0){
				$loop_max = 29;
			} else {
				$loop_max = 28;
			}
		} else {
			$loop_max = 30;
		}
	}
?>
<style>
	@media print{@page {size: landscape}}
</style>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
        <div class="bar-nav pull-right">
            <!--a href="<?php //echo str_replace("report_lbb","createlbb_file",$_SERVER["REQUEST_URI"]);?>" class="btn btn-default"><i class="fa fa-download"></i> Download file .SDLBB</a-->
            <a onclick="printKw();" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
        </div>
	</div>
    <fieldset class="">
    <div class="col-lg-12 padding-left-right-10" style="display: block;">
    	<div class="panel margin-bottom-5">
    		<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> Laporan Penjualan Oli Service
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form id="frmAdd" method="get" action="<?php echo base_url("report_penjualan/penjualan_oliservis");?>">
                    <div class="row">
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer" disabled>
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    if ($dealer) {
                                      if (($dealer->totaldata > 0)) {
                                        foreach ($dealer->message as $key => $value) {
											$aktif = "";
											if($defaultDealer == $value->KD_DEALER){
												$aktif = "selected";
												$namaDealer = $value->NAMA_DEALER_ASLI;
											}
                                          echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                        }
                                      }
                                    }
                                    ?>
                                  </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Periode Bulan</label>
                                <select id="bulan" name="bulan" class="form-control">
                                    <option value="">--Pilih Bulan--</option>
                                    <?php
                                        for ($i=1;$i<=12;$i++){
                                            $pilih=($bulan==$i)?"selected":"";
                                            echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-2 col-sm-2">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select id="tahun" name="tahun" class="form-control">
                                    <option value="">--Pilih Tahun--</option>
                                    <?php
									
                                    if(isset($tahun)){
										if($tahun->totaldata>0){
                                            foreach ($tahun->message as $key => $value) {
                                                $pilih=($tahuns==$value->TAHUN)?"selected":"";
                                                echo "<option value='".$value->TAHUN."' ".$pilih.">".$value->TAHUN."</option>";
                                            }
                                        }else{
                                           echo "<option value='".date('Y')."' selected>".date('Y')."</option>";  
                                        }
                                    }
                                    ?>
                                </select>
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
    <div class="col-lg-12 padding-left-right-10" style="display: block;">
		<div class="panel margin-bottom-5">
			<div class="panel-heading">
				<i class="fa fa-list-ul"></i> Data Laporan Penjualan Oli Service
				<span class="tools pull-right">
					<a class="fa fa-chevron-down" href="javascript:;"></a>
				</span>
			</div>
			<div class="panel-body">
				<div style="overflow-y:auto;overflow-x:scroll">
				<!--div class=""-->
					<table class="table table-stripped table-hover table-bordered">
						<tbody>
							<tr style="background-color:#CCCCCC" >
								<th></th>
								<?php
								for ($i=1;$i<=$loop_max;$i++){
									echo '<th><p style="font-size:14px">'.$i.'/</br>'. $bulan.'</p></th>';
								}
								?>
								<th>Total</th>
							</tr>
							<?php
								
								$colvalue = array();
								for ($i=1;$i<=$loop_max;$i++){
									$colvalue[$i] = 0;
								}
								$n=0;$totalunit=0; $clase="";
								if(isset($list_pekerjaan)){
									if($list_pekerjaan->totaldata>0){
										foreach ($list_pekerjaan->message as $key => $value) {
											$totalrow = 0;
											?>
											<tr>
												<td><p style="font-size:14px"><?php echo$value->KD_PEKERJAAN;?></p></td>
												<?php
													for ($i=1;$i<=$loop_max;$i++){
														$cellvalue = 0;
														foreach($rekapoli->message as $key => $detail){
															if($detail->KD_PEKERJAAN == $value->KD_PEKERJAAN){
																if($detail->DAY == $i){
																	$cellvalue = $cellvalue + $detail->QTY;
																}
															}
														}
														
														echo '<td><p style="font-size:14px">'.$cellvalue.'</p></td>';
														$totalrow = $totalrow + $cellvalue;
														$temp_val = $colvalue[$i];
														$temp_val = $temp_val + $cellvalue;
														$colvalue[$i] = $temp_val;
													}
												?>
												<td><p style="font-size:14px"><?php echo $totalrow;?></p></td>
											</tr>
											<?php
										}
									}
								}	
							?>
							<tr>
								<td><p style="font-size:14px">TOTAL</p></td>
								<?php
									$totalrow = 0;
									for ($i=1;$i<=$loop_max;$i++){
										echo '<td><p style="font-size:14px">'.$colvalue[$i].'</p></td>';
										$totalrow = $totalrow + $colvalue[$i];
									}
								?>
								<td><p style="font-size:14px"><?php echo $totalrow;?></p></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    </div>
    </fieldset>
</section>
<div id="printarea" style="overflow: hidden;padding: 10px" class="onlyprint">
	<table class="table table-bordered">
		<tr border=0>
			<td align="center" colspan = "33">
				<h2>DATA PENJUALAN OLI SERVICE</h2>
				<h2><?php echo $namaDealer;?></h2><br />
				<h4>Periode : <?php echo nBulan($bulan)." ".$tahuns;?></h2>	
				<br />
			</td>
			
		</tr>
		<tr>
			<th></th>
			<?php
			for ($i=1;$i<=$loop_max;$i++){
				echo '<th><p style="font-size:14px">'.$i.'/</br>'. $bulan.'</p></th>';
			}
			?>
			<th>Total</th>
		</tr>
	
		<?php
			$colvalue = array();
			for ($i=1;$i<=$loop_max;$i++){
				$colvalue[$i] = 0;
			}
			$n=0;$totalunit=0; $clase="";
			if(isset($list_pekerjaan)){
				if($list_pekerjaan->totaldata>0){
					foreach ($list_pekerjaan->message as $key => $value) {
						$totalrow = 0;
						?>
						<tr>
							<td align="center"><?php echo$value->KD_PEKERJAAN;?></td>
							<?php
								for ($i=1;$i<=$loop_max;$i++){
									$cellvalue = 0;
									foreach($rekapoli->message as $key => $detail){
										if($detail->KD_PEKERJAAN == $value->KD_PEKERJAAN){
											if($detail->DAY == $i){
												$cellvalue = $cellvalue + $detail->QTY;
											}
										}
									}
									
									echo '<td align="center"><p style="font-size:14px">'.$cellvalue.'</p></td>';
									$totalrow = $totalrow + $cellvalue;
									$temp_val = $colvalue[$i];
									$temp_val = $temp_val + $cellvalue;
									$colvalue[$i] = $temp_val;
								}
							?>
							<td align="center"><?php echo $totalrow;?></td>
						</tr>
						<?php
					}
				}
			}	
		?>
		<tr>
			<td align="center">TOTAL</td>
			<?php
				$totalrow = 0;
				for ($i=1;$i<=$loop_max;$i++){
					echo '<td align="center"><p style="font-size:14px">'.$colvalue[$i].'</p></td>';
					$totalrow = $totalrow + $colvalue[$i];
				}
			?>
			<td align="center"><?php echo $totalrow;?></td>
		</tr>

	</table>
</div>
<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
        function printKw() {
            $('#printarea').removeClass("onlyprint");
            printJS({ 
                printable: 'printarea', 
                type: 'html', 
                honorColor: true,
				
             });
            $('#printarea').addClass("onlyprint");
         }
</script>