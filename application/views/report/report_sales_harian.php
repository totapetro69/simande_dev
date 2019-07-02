<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("tgl"))?$this->input->get("tgl"):date("d/m/Y",strtotime('-1 days'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url("report/salesharian_part/true");?>?<?php echo $_SERVER["QUERY_STRING"];?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i> Print</a>
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Report Sales Akumulasi Harian
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
	        <div class="panel-body panel-body-border panel-body-10" style="display: block;">
	        	<form id="filterForms" method="GET" action="<?php echo base_url("report/salesharian_part");?>">
	        		<div class="row">
		        		<div class="col-xs-6 col-md-3 col-sm-3">
		        			<div class="form-group">
			        			<label>Nama Dealer</label>
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
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
			                	<label>Periode Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="tgl" name="tgl" value="<?php echo $dari_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
                        <div class="col-xs-6 col-md-3 col-sm-3 hidden">
                            <div class="form-group">
                            	<label>Filter By</label>
                            	<select id="filter" name="filter" class="form-control">
                            		<option value="">Total Stock</option>
                            		<option value="GD">Gudang</option>
                            		<option value="SIM">SIM PART</option>
                            		<option value="SG">Sales Group</option>
                            	</select>
                            </div>
                        </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
		                		<br>
		                		<button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Preview</button>
		                	</div>
		                </div>
		            </div>
	        	</form>
	        </div>
	    </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
    	<div class="printarea">
    		<div id="head" class='hidden'>
    			<table class="table">
    				<tbody>
    					<?php echo (isset($judul))?$judul:"";?>
    				</tbody>
    			</table>
    		</div>
	    	<div class="panel panel-default">
	    		<div class="table-responsive">
	    			<table class="table table-hover table-striped table-bordered">
	    				<thead>
	    					<tr>
	    						<th rowspan="3" style="text-align: center !important">No</th>
	    						<th rowspan="3" style="text-align: center !important">No.Faktur</th>
	    						<th rowspan="3" style="text-align: center !important">Tgl Trans</th>
	    						<th rowspan="3" style="text-align: center !important">Part Number</th>
	    						<th rowspan="3" style="text-align: center !important">Deskripsi</th>
	    						<th rowspan="3" style="text-align: center !important">Qty Sales</th>
	    						<th colspan="4" style="text-align: center !important">Per PCS</th>
	    						<th rowspan="3" style="text-align: center !important">Amount Sales</th>
	    						<th rowspan="3">Src</th>
	    					</tr>
	    					<tr>
	    						<th rowspan="2" style="text-align: center !important">HET</th>
	    						<th colspan="2" style="text-align: center !important">Diskon</th>
	    						<th rowspan="2" style="text-align: center !important">Harga Jual</th>
	    					</tr>
	    					<tr>
	    						<th style="text-align: center !important">%</th>
	    						<th style="text-align: center !important">Rp</th>
	    					</tr>
	    				</thead>
	    				<tbody>
	    					<?php
	    					$jml=0;$tharga=0;
	    						$n=0;$part=array();
	    						if(isset($list)){
	    							if($list->totaldata >0){
	    								
	    								foreach ($list->message as $key => $value) {
	    									$n++;
	    									//$part=explode("-",$value->URAIAN_TRANSAKSI,2);
	    									?>
	    										<tr>
	    											<td class='text-center table-nowarp'><?php echo $n;?></td>
	    											<td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
	    											<td class='text-center table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
	    											<td class='table-nowarp'><?php echo $value->PART_NUMBER;?></td>
	    											<td class='td-overflow' title="<?php echo $value->PART_DESKRIPSI;?>"><?php echo $value->PART_DESKRIPSI;?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format($value->JUMLAH_ORDER,0);?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format($value->HET,0);?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format($value->PROSEN,0);?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format($value->DISKON,0);?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format(($value->HARGA_JUAL),0);?></td>
	    											<td class='text-right table-nowarp'><?php echo number_format($value->TOTAL_HARGA,0);?></td>
	    											<td class='text-center table-nowarp'><?php echo substr($value->TIPE,0,2);?></td>
	    										</tr>
	    									<?php
	    									$jml +=$value->JUMLAH_ORDER;
	    									$tharga += ($value->TOTAL_HARGA);
	    								}
	    							}else{
	    								echo belumAdaData(12);
	    							}
	    						}else{
	    							echo belumAdaData(12);
	    						}
	    					?>
	    				</tbody>
	    				<tfoot>
	    					<tr class='total'>
	    						<td colspan="5" class='text-right' style="padding-right: 10px">TOTAL</td>
	    						<td class="text-right table-nowarp"><?php echo number_format($jml,0);?></td>
	    						<td colspan="4">&nbsp;</td>
	    						<td class="text-right table-nowarp"><?php echo number_format($tharga,0);?></td>
	    						<td colspan="1">&nbsp;</td>
	    					</tr>
	    				</tfoot>
	    			</table>
	    		</div>
	    	</div>
	    </div>
    </div>
</section>
<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
	function printKw() {
	     printJS('printarea','html');
	 }
</script>