<?php
if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
	$defautlDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
	$tahune=($this->input->get("tahun"))?$this->input->get("tahun"):date('Y');
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">
             <a id="modal-button" class="btn btn-default <?php echo $status_c?>" onclick='addForm("<?php echo base_url('setup/leasing_komposisi'); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-file-o fa-fw"></i> Setup Komposisi
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading">
                List Sales Order
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <div class="panel-body panel-body-border" style="display: block;">
                <form method="get" action="<?php echo base_url("setup/list_komposisi");?>">
                	<div class="col-xs-12 col-sm4 col-md-4">
                		<div class="form-group">
                			<label>Dealer</label>
                			<select id="kd_dealer" name="kd_dealer" class="form-control">
                				<option value="">--Pilih Dealer--</option>
                				<?php
                					if(($dealer)){
                						if($dealer->totaldata>0){
                							foreach ($dealer->message as $key => $value) {
                								$selected=($defautlDealer==$value->KD_DEALER)?"selected":"";
                								echo "<option value='".$value->KD_DEALER."' ".$selected.">".$value->NAMA_DEALER."</option>";
                							}
                						}
                					}
                				?>
                			</select>
                		</div>
                	</div>
                	<div class="col-xs-12 col-md-2 col-sm-2">
                		<div class="form-group">
                			<label>Tahun</label>
                			<select class="form-control" id="tahun" name="tahun">
                				<?php
                					if(($listtahun)){
                						if($listtahun->totaldata>0){
                							foreach ($listtahun->message as $key => $value) {
                								$selected=($tahune==$value->TAHUN)?"selected":"";
                								echo "<option value='".$value->TAHUN."' ". $selected.">".$value->TAHUN."</option>";
                							}
                						}
                					}
                				?>
                			</select>
                		</div>
                	</div>
                	<div class="col-xs-4 col-md-1 col-sm1">
                		<br>
	                	<button class="btn btn-info" type="submit"><i class='fa fa-search'></i> Preview</button>
	                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
        	<div class="table-responsive h350">
	        	<table class="table table-striped table-hover">
	        		<thead>
	        			<tr>
	        				<th>No.</th>
	        				<th>KODE</th>
	        				<th>NAMA LEASING </th>
	        				<th>%</th>
	        				<th>TAHUN</th>
	        				<th>KETERANGAN</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<?php 
	        				if(isset($list)){ $n=0;
	        					if($list->totaldata>0){
	        						foreach ($list->message as $key => $value) {
	        							$n++;
	        							?>
	        							<tr class="info">
	        								<td class='text-center'><?php echo $n;;?></td>
	        								<td class='center'><?php echo $value->KD_DEALER;?></td>
	        								<td colspan="4"><?php echo $value->NAMA_DEALER;?></td>
	        							</tr>
	        							<?php
	        							if(isset($listd[$value->KD_DEALER])){
	        								$data=$listd[$value->KD_DEALER];
	        								if($data->totaldata>0){
	        									foreach ($data->message as $key => $val) {
	        										?>
	        											<tr>
	        												<td class="text-right"><?php echo $val->RANGKING_LEASING;?></td>
	        												<td class='text-center'><?php echo $val->KD_LEASING;?></td>
	        												<td><?php echo $val->NAMA_LEASING;?></td>
	        												<td class='text-center'><?php echo number_format(($val->TARGET_LEASING*100),0);?></td>
	        												<td class='text-center'><?php echo $val->TAHUN;?></td>
	        												<td>&nbsp;</td>
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
                            
                        </small>
                    </div>
                    <div class="col-sm-7 text-right text-center-xs">                
                        
                    </div>
                </div>
            </footer>
        </div>
	</div>
</section>