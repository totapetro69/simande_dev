<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");

?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right ">
			<a class="btn btn-default modal-button" id="opn" role="button" title="Open Transaksi" url="<?php echo base_url("cashier/cashopname_add");?>" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-bookmark'></i><span class='hidden-xs hidden-sm'> Input Opname</span></a>
		</div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel margin-bottom-10">
			<div class="panel-heading">
                <i class="fa fa-list fa-fw"></i> List Cash Opname
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
             <div class="panel-body panel-body-border" style="display: block;">
				<form class='form' method="get" action="">
					<div class='row'>
						<div class="col-xs-12 col-md-4 col-sm-4">
							<div class="form-group">
								<label>Dealer</label>
								<select class="form-control" name="kd_dealer">
					      			<option value="">--Pilih Dealer-</option>
					      			<?php 
					      				if(isset($dealer)){
					      					if($dealer->totaldata > 0){
					      						foreach ($dealer->message as $key => $value) {
					      							$pilih=($defaultDealer==$value->KD_DEALER)?"selected":"";
					      							echo "<option value=".$value->KD_DEALER." ".$pilih.">".$value->NAMA_DEALER."</option>";
					      						}
					      					}
					      				}
					      			?>
					      		</select>
					      	</div>
				      	</div>
				      	<div class="col-xs-6 col-md-3 col-sm-3">
				      		<div class="form-group">
				      			<label>Periode Bulan</label>
				      			<select name="bulan" id="bulan" class="form-control">
				      				<option value="">--Pilih Bulan--</option>
				      				<?php
				      					for($i=0;$i<12;$i++){
				      						$pilih =(date('m')==$i)?'selected':'';
				      						echo "<option value=".$i." ".$pilih.">".nBulan($i)."</option>";
				      					}
				      				?>
				      			</select>
				      		</div>
				      	</div>
				      	<div class="col-xs-6 col-md-2 col-sm-2">
				      		<div class="form-group">
				      			<label>Tahun</label>
				      			<select name="bulan" id="bulan" class="form-control">
				      				<option value="">--Pilih Tahun--</option>
				      				<?php
				      					if(isset($tahun)){
				      						if($tahun->totaldata >0){
				      							foreach ($tahun->message as $key => $value) {
				      								$pilih =(date('Y')==$value->TAHUN)?'selected':'';
				      								echo "<option value=".$value->TAHUN." ".$pilih.">".$value->TAHUN."</option>";
				      							}
				      						}else{
				      							echo "<option value=".date('Y')." selected>".date('Y')."</option>";
				      						}
				      					}
				      				?>
				      			</select>
				      		</div>
				      	</div>
			        </div>
		        </form>
		    </div>
		</div>
	</div>
	<div class="col-lg-12 padding-left-right-10">
		<div class="panel panel-default">
			<div class="table-responsive h350">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>No</th>
							<th>&nbsp;</th>
							<th>No. Transaksi</th>
							<th>Tanggal</th>
							<th>Saldo Kas</th>
							<th>Cash Opname</th>
							<th>Selisih</th>
							<th>Status</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$n=0;
							if(isset($list)){
								if($list->totaldata >0){
									foreach ($list->message as $key => $value) {
										$n++;
										$status=($value->STATUS_AUDIT=="0")?"Open":"Approve";
										?>
											<tr>
												<td class='text-center'><?php echo $n;?></td>
												<td class="table-nowarp">
													<a id="modal-button" role="button" title="Open Transaksi" onclick="addForm('<?php echo base_url("cashier/cashopname_add?n=");?><?php echo $value->NO_TRANS;?>')" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-edit"></i></a>
													<a id="modal-button-1" role="button"onclick="addForm('<?php echo base_url("cashier/cashopname_add/true?n=");?><?php echo $value->NO_TRANS;?>')" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-print"></i></a>
												</td>
												<td class='table-nowarp'><?php echo $value->NO_TRANS;?></td>
												<td class="table-nowarp"><?php echo tglFromSql($value->TGL_TRANS);?></td>
												<td class="table-nowarp"><?php echo number_format($value->JUMLAH_KAS,0);?></td>
												<td class="table-nowarp"><?php echo number_format($value->JUMLAH_TOTAL,0);?></td>
												<td class="table-nowarp"><?php echo number_format($value->SELISIH,0);?></td>
												<td class=""><?php echo $status;?></td>
												<td class="td-overflow-50" title="?php echo $value->KETERANGAN;?>"><?php echo $value->KETERANGAN;?></td>
											</tr>
										<?
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<footer class="panel-footer">
        <div class="row">
        	<div class="col-sm-5">
                <small class="text-muted inline m-t-sm m-b-sm"> 
                    <?php echo isset($list->totaldata) ? "" : "<i>Total Data " . $list->totaldata . " items</i>" ?>
                </small>
            </div>
            <div class="col-sm-7 text-right text-center-xs">                
                <?php echo $pagination; ?>
            </div>
        </div>
    </footer>
</section>