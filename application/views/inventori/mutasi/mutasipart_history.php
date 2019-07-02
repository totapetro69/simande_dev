<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
  
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$dari_tanggal=($this->input->get("dari_tanggal"))?$this->input->get("dari_tanggal"):date("d/m/Y",strtotime('first day of this month'));
$sampai_tanggal=($this->input->get("sampai_tanggal"))?$this->input->get("sampai_tanggal"):date("d/m/Y");
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
		<div class="bar-nav pull-right">
		</div>

    </div>

    <div class="col-lg-12 padding-left-right-5">
        <div class="panel margin-bottom-5">
            <div class="panel-heading">
               <i class="fa fa-list-ul fa-fw"></i> Stock Movement Part
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </div>
        
	        <div class="panel-body panel-body-border panel-body-10" style="display: block;">
	        	<form id="filterForm" method="GET" action="<?php echo base_url("part/partstock_mvt");?>">
	        		<div class="row">
		        		<div class="col-xs-6 col-md-2 col-sm-2">
		        			<div class="form-group">
			        			<label>Nama Dealer</label>
		                        <select class="form-control" id="kd_dealer" name="kd_dealer">
		                            <option value="">--Pilih Dealer--</option>
		                            <?php
		                            if (isset($dealer)) {
		                                if ($dealer->totaldata>0) {
		                                    foreach ($dealer->message as $key => $value) {
		                                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
		                                        $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
		                                        echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
		                                    }
		                                }
		                            }
		                            ?> 
		                        </select>
		                    </div>
		                </div>
                        <div class="col-xs-8 col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Search Part Number</label>

                                <div id="ajax-url" url="<?php echo base_url('part/moving_typeahead/PART/true');?>"></div>

                                <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="find by Part Number" autocomplete="off">

                            </div>
                        </div>
		                <div class="col-xs-6 col-md-2 col-sm-2">
		                	<div class="form-group">
			                	<label>Periode dari Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="dari_tanggal" name="dari_tanggal" value="<?php echo $dari_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
		                <div class="col-xs-6 col-md-2 col-sm-2">
		                	<div class="form-group">
			                	<label>Sampai Tanggal</label>
			                	<div class="input-group input-append date" id="date">
			                        <input class="form-control" id="sampai_tanggal" name="sampai_tanggal" value="<?php echo $sampai_tanggal;?>">
			                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			                    </div>
			                </div>
		                </div>
                        <div class="col-xs-3 col-md-1 col-sm-1">
                            <br>
                            <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> Preview</button>
                        </div>
		            </div><!-- 
		            <div class="row">
		            	<div class="col-xs-4 col-sm-1 col-md-1">
		                	<label style="color: white">Preview</label>
		                	<button class="btn btn-info" type="submit"> <i class="fa fa-search"></i> Preview</button>
		                </div>
		            </div> -->
	        	</form>
	        </div>
	    </div>
    </div>
    <div class="col-lg-12 padding-left-right-5">
    	<div class="panel panel-default">
    		<div class="table-responsive">
    			<table class="table table-hover table-striped table-bordered">
    				<thead>
    					<tr>
                            <th>No</th>
    						<th>No. Trans</th>
    						<th>Tanggal</th>
    						<th>Jenis Mutasi</th>
    						<th>Lokasi Asal</th>
    						<th>Lokasi Tujuan</th>
                            <th>Jumlah</th>
    						<th>Keterangan</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php
    						if(isset($list)){
    							$n=0;
    							if($list->totaldata>0){
    								foreach ($list->message as $key => $value) {
    									$n++;
    									?>
    									<tr class='info'>
    										<td class='text-center'><?php echo $n;?></td>
    										<td colspan="2"><?php echo $value->PART_NUMBER;?></td>
    										<td colspan="5"><?php echo $value->PART_DESKRIPSI;?></td>
    									</tr>
    									<?php
    									if(isset($listd)){
    										if($listd[$value->PART_NUMBER]->totaldata>0){
    											foreach ($listd[$value->PART_NUMBER]->message as $key => $val) {
    												?>
    													<tr>
    														<td>&nbsp;</td>
    														<td class='text-center table-nowarp'><?php echo $val->NO_TRANS;?></td>
    														<td class='text-center'><?php echo TglFromSql($val->TGL_TRANS);?></td>
    														<td><?php echo $val->JENIS_MOVE;?></td>
    														<td class="table-nowarp"><?php echo $val->KD_GUDANG;?>&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>&nbsp;&nbsp;<?php echo $val->KD_RAKBIN;?></td>
    														<?php 
    														if($val->KETERANGAN=='Antar Dealer'){
    															?>
    															<td class="table-nowarp"><?php echo $val->KD_DEALER_TUJUAN;?> [ <?php echo NamaDealer($val->KD_DEALER_TUJUAN);?>]
    															</td>
    															<?php
    														}else{
    															?>
    															<td class="table-nowarp">
    																<?php if($val->JENIS_MOVE=='Mutasi'){
    																  echo $val->GD_TUJUAN;?> &nbsp;&nbsp;<i class="fa fa-arrow-right"></i> &nbsp;&nbsp;<?php echo strtoupper($val->RAKBIN_TUJUAN);
    																}
    																?>
    															</td>
    															<?php
    														}
    														?>
    														
    														<td class="text-right"><?php echo number_format($val->JUMLAH);?></td>
    														<td class=''><?php echo $val->KETERANGAN;?></td>
    													</tr>
    												<?php
    											}
    										}
    									}
    								}
    							}
    						}
    					?>
    				</tbody>
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
</section>