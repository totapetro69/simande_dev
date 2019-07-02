<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
?>

<style type="text/css">
    table {
   
    font-size: 13px;
   
</style>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
			<a id="modal-button" class="btn btn-info" href="<?php echo base_url('motor/mutasiunit_add'); ?>");' role="button">
                <i class="fa fa-file-o"></i> Input Mutasi
            </a>
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> List Mutasi Unit
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
	        <div class="panel-body panel-body-border panel-body-10" style="display: block;">
	        	<form id="frmCriteran" method="GET" action="<?php echo base_url("motor/mutasiunit_list");?>">
	        		<div class="row">
		        		<div class="col-xs-6 col-md-5 col-sm-5">
		        			<div class="form-group">
			        			<label>Nama Dealer</label>
		                        <select class="form-control " id="kd_dealer" name="kd_dealer">
		                            <option value="">--Pilih Dealer--</option>
		                            <?php
		                            if (isset($dealer)) {
		                                if ($dealer->totaldata>0) {
		                                    foreach ($dealer->message as $key => $value) {
		                                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
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
			                	<label>Periode dari Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
		                <div class="col-xs-6 col-md-3 col-sm-3">
		                	<div class="form-group">
			                	<label>Sampai Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
		            </div>
		            <div class="row">
		            	<div class="col-xs-8 col-md-6 col-sm-6">
		            		<div class="form-group">
		            			<label>Search Criteria</label>
		            			<input type="text" id="keyword" name="keyword" autocomplete="off" class="form-control" placeholder=" find by No. Rangka">
		            		</div>
		            	</div>
		            	<div class="col-xs-4 col-sm-1 col-md-1">
		                	<label style="color: white">Preview</label>
		                	<button class="btn btn-info" type="submit"> <i class="fa fa-search"></i> Preview</button>
		                </div>
		            </div>
	        	</form>
	        </div>
	    </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
    	<div class="panel panel-default">
    		<div class="table-responsive">
    			<table class="table table-hover table-striped">
    				<thead>
    					<tr>
    						<th style="width:65px">No</th>
    						<th>No. Trans</th>
    						<th>Tanggal</th>
    						<th>No. Rangka</th>
    						<th>Keterangan</th>
    						<th>Jenis Mutasi</th>
    						<th>Lokasi Asal</th>
    						<th>Lokasi Tujuan</th>
    						<th>Status</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php 
    						$n=0;$approval=0;
    						if(isset($list)){
    							if($list->totaldata>0){
    								foreach ($list->message as $key => $value) {
    									$n++;
                                        $approval = ($value->APPROVAL_STATUS)?$value->APPROVAL_STATUS:$approval;
                                        $status = ($approval >0)?'Approve':'Open';
                                        $status = ($value->STATUS_MUTASI)?'Close':$status;
    									?>
    										<tr>
    											<td><?php echo $n;?>
    												<span class="pull-right">
    													<a id="modal-button-1" class="" href="<?php echo base_url('motor/mutasiunit_add'); ?>/<?php echo $value->NO_TRANS;?>");" role="button"><i class="fa fa-edit"></i></a>
    													<a class="hidden"><i class="fa fa-trash"></i></a>
    												</span>
    											</td>
    											<td class='text-center table-nowarp'><?php echo $value->NO_TRANS;?></td>
    											<td class='text-center'><?php echo tglFromSql($value->TGL_TRANS);?></td>
    											<td class='text-center td-overflow-50' title="<?php echo $value->PART_NUMBER;?>"><?php echo $value->PART_NUMBER;?></td>
    											<td class='td-overflow-50' title="<?php echo $value->KETERANGAN;?>"><?php echo $value->KETERANGAN;?></td>
    											<td class='table-nowarp'><?php echo $value->JENIS_TRANS;?></td>
    											<td class='table-nowarp'><?php echo $value->KD_GUDANG_ASAL;?></td>
    											<td class='table-nowarp' title="<?php echo $value->NAMA_DEALER;?>" ><?php echo($value->JENIS_TRANS=='Antar Gudang')? $value->KD_GUDANG_TUJUAN:$value->KD_DEALER_TUJUAN;?></td>
    											<td class='table-nowarp'><?php echo $status;?></td>
    										</tr>
    									<?php
    								}
    							}else{
    								belumAdaData(9);
    							}
    						}else{
    							belumAdaData(9);
    						}
    					?>	
    				</tbody>
    				<tfoot>
    					
    				</tfoot>
    			</table>
    		</div>
    		<footer class="panel-footer">
                <div class="row">
                    <div class="col-sm-5">
                        <small class="text-muted inline m-t-sm m-b-sm"> 
                            <?php echo (isset($totaldata)) ? ($totaldata == '0' ? "" : "<i>Total Data " . $totaldata . " items</i>") : '' ?>
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        <?php echo isset($pagination)?$pagination:""; ?>
                    </div>
                </div>
            </footer>
    	</div>
    </div>
    <?php echo loading_proses();?>
</section>