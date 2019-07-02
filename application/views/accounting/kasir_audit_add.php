<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$no_trans=$this->input->get("n");
$tgl_trans=date('d/m/Y');
$k100=0;$k50=0;$k20=0;$k10=0;$k5=0;$k2=0;$k1=0;
$l1000=0;$l500=0;$l200=0;$l100=0;$l50=0;
$k100v=0;$k50v=0;$k20v=0;$k10v=0;$k5v=0;$k2v=0;$k1v=0;
$l1000v=0;$l500v=0;$l200v=0;$l100v=0;$l50v=0;
$selisih=0;$keterangan="";
$total_kertas=0; $total_koin=0;
$total_kertas_v=0; $total_koin_v=0;
$total_kas=0;$total_hasil=0; $status_audit="";
if(isset($list)){
	if($list->totaldata >0){
		foreach ($list->message as $key => $value) {
			$k100 = $value->JUMLAH_K100;
			$k50 = $value->JUMLAH_K50;
			$k20 = $value->JUMLAH_K20;
			$k10 = $value->JUMLAH_K10;
			$k5 = $value->JUMLAH_K5;
			$k2 = $value->JUMLAH_K2;
			$k1 = $value->JUMLAH_K1;
			$l1000= $value->JUMLAH_L1000;
			$l500= $value->JUMLAH_L500;
			$l200= $value->JUMLAH_L200;
			$l100= $value->JUMLAH_L100;
			$l50= $value->JUMLAH_L50;
			$k100v = $value->JUMLAH_K100*100000;
			$k50v = $value->JUMLAH_K50*50000;
			$k20v = $value->JUMLAH_K20*20000;
			$k10v = $value->JUMLAH_K10*10000;
			$k5v = $value->JUMLAH_K5*5000;
			$k2v = $value->JUMLAH_K2*2000;
			$k1v = $value->JUMLAH_K1*1000;
			$l1000v = $value->JUMLAH_L1000*1000;
			$l500v = $value->JUMLAH_L500*500;
			$l200v = $value->JUMLAH_L200*200;
			$l100v = $value->JUMLAH_L100*100;
			$l50v = $value->JUMLAH_L50*50;
			$selisih = $value->SELISIH;
			$keterangan = $value->KETERANGAN;
			$total_kertas = ($k100+$k50+$k20+$k10+$k5+$k2+$k1);
			$total_koin =($l1000+$l500+$l200+$l100+$l50);
			$total_kertas_v = ($k100v+$k50v+$k20v+$k10v+$k5v+$k2v+$k1v);
			$total_koin_v =($l1000v+$l500v+$l200v+$l100v+$l50v);
			$total_kas = $value->JUMLAH_KAS;
			$total_hasil = $value->JUMLAH_TOTAL;
			$tgl_trans =TglFromSql($value->TGL_TRANS);
			$status_audit = $value->STATUS_AUDIT;

		}
	}
}
if(isset($kas_skrng)){
	$total_kas=$kas_skrng;
}
$show =($no_trans)?"":"hidden";
$show =($status_audit=="0")?"":"hidden";
$hidden =($no_trans)?"hidden":"";
$apv=(isApproval('CSHOP'))?'':'disabled-action';
$level =(isApproval('CSHOP'))?isApproval('CSHOP')[0]->APP_LEVEL:"0";
$hidden =($level=="0")?'':$hidden;
$show =($level=="0")?'hidden':$show;
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('cashier/cashopname_simpan');?><?php echo ($level=="0")?"":"/".$level;?>" method="post">
  	<div class="modal-header">
	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  	<h4 class="modal-title" id="myModalLabel">Detail Cash Opname</h4>
	</div>
	<div class="modal-body">
		<div class="row">
		    <div class="col-xs-12 col-sm-6 col-md-6">
		    	<div class="col-xs-12 col-sm-12 col-md-12">
			      	<div class="form-group ">
			      		<label>Dealer</label>
			      		<select class="form-control" name="kd_dealer" tabindex="40">
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
		  	</div>
		  	<div class="col-xs-12 col-md-6 col-sm-6">
		  		<div class="col-xs-12 col-sm-12 col-md-12">
			  		<div class="form-group">
			  			<label>Tanggal</label>
			  			<div class="input-group input-append date" id="date">
		                    <input type="text" tabindex="41" class="form-control" id="tgl_trans" required="true" name="tgl_trans" value="<?php echo $tgl_trans;?>" placeholder="dd/mm/yyyy" />
		                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		                </div>
		            </div>
		        </div>
	        </div>
	        <input type="hidden" name="no_trans" value="<?php echo $no_trans;?>">
		</div>
		<div class="row <?php echo ($level=='0')?'':'disabled-action';?><?php echo $status_e;?><?php echo $status_c;?>">
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="alert alert-success" role='alert'><h4>Uang Kertas</h4></div>
				</div>
				<!-- <hr> -->
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 100.000</label>
						<div class="input-group">
							<input type="text" tabindex="1" class="form-control k" id="jumlah_k100000" name="jumlah_k100" placeholder="Jumlah Pecahan 100.000" value="<?php echo number_format($k100,0);?>">
							<span class='input-group-addon'><span id='k_100' class="kp"><?php echo number_format($k100v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 50.000</label>
						<div class="input-group">
							<input type="text" tabindex="2" class="form-control k" id="jumlah_k50000" name="jumlah_k50" placeholder="Jumlah Pecahan 50.000" value="<?php echo number_format($k50,0);?>">
							<span class='input-group-addon'><span id='k_50' class="kp"><?php echo number_format($k50v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 20.000</label>
						<div class="input-group">
							<input type="text" tabindex="3" class="form-control k" id="jumlah_k20000" name="jumlah_k20" placeholder="Jumlah Pecahan 20.000" value="<?php echo number_format($k20,0);?>">
							<span class="input-group-addon"><span id="k_20" class="kp"><?php echo number_format($k20v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 10.000</label>
						<div class="input-group">
							<input type="text" tabindex="4" class="form-control k" id="jumlah_k10000" name="jumlah_k10" placeholder="Jumlah Pecahan 10.000" value="<?php echo number_format($k10,0);?>">
							<span class="input-group-addon"><span id="k_10" class="kp"><?php echo number_format($k10v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 5.000</label>
						<div class="input-group">
							<input type="text" tabindex="5" class="form-control k" id="jumlah_k5000" name="jumlah_k5" placeholder="Jumlah Pecahan 5.000" value="<?php echo number_format($k5,0);?>">
							<span class="input-group-addon"><span id="k_5" class="kp"><?php echo number_format($k5v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 2.000</label>
						<div class="input-group">
							<input type="text" tabindex="6" class="form-control k" id="jumlah_k2000" name="jumlah_k2" placeholder="Jumlah Pecahan 2.000" value="<?php echo number_format($k2,0);?>">
							<span class="input-group-addon"><span id="k_2" class="kp"><?php echo number_format($k2v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 1.000</label>
						<div class="input-group">
							<input type="text" tabindex="7" class="form-control k" id="jumlah_k1000" name="jumlah_k1" placeholder="Jumlah Pecahan 1.000" value="<?php echo number_format($k1,0);?>">
							<span class="input-group-addon"><span id="k_1" class="kp"><?php echo number_format($k1v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Total Pecahan Kertas</label>
						<div class="input-group">
							<input type="text" class="form-control warning disabled-action" id="jumlah_pk" name="jumlah_pk" placeholder="Jumlah Pecahan kertas" value="<?php echo number_format($total_kertas,0);?>">
							<span class="input-group-addon"><span id="k_pk"><?php echo number_format($total_kertas_v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12 <?php //echo $show;?>">
					<div class="form-group">
						<label>Keterangan Audit</label>
						<textarea class="form-control" rows="1"></textarea>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="alert alert-warning"><h4>Uang Logam</h4></div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 1.000</label>
						<div class="input-group">
							<input type="text" tabindex="8" class="form-control l" id="jumlah_l1000" name="jumlah_l1000" placeholder="Jumlah Pecahan 1.000" value="<?php echo number_format($l1000,0);?>">
							<span class="input-group-addon"><span id="l_1000" class="nl"><?php echo number_format($l1000v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 500</label>
						<div class="input-group">
							<input type="text" tabindex="9" class="form-control l" id="jumlah_l500" name="jumlah_l500" placeholder="Jumlah Pecahan 500" value="<?php echo number_format($l500,0);?>">
							<span class="input-group-addon"><span id="l_500" class="nl"><?php echo number_format($l500v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 200</label>
						<div class="input-group">
							<input type="text" tabindex="10" class="form-control l" id="jumlah_l200" name="jumlah_l200" placeholder="Jumlah Pecahan 200" value="<?php echo number_format($l200,0);?>">
							<span class="input-group-addon"><span id="l_200" class="nl"><?php echo number_format($l200v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 100</label>
						<div class="input-group">
							<input type="text" tabindex="11" class="form-control l" id="jumlah_l100" name="jumlah_l100" placeholder="Jumlah Pecahan 100" value="<?php echo number_format($l100,0);?>">
							<span class="input-group-addon"><span id="l_100" class="nl"><?php echo number_format($l100v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Pecahan 50</label>
						<div class="input-group">
							<input type="text" tabindex="12" class="form-control l" id="jumlah_l50" name="jumlah_l50" placeholder="Jumlah Pecahan 50" value="<?php echo number_format($l50,0);?>">
							<span class="input-group-addon"><span id="l_50" class="nl"><?php echo number_format($l50v,0);?></span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label>Total Pecahan Koin</label>
						<div class="input-group">
							<input type="text" class="form-control warning disabled-action" id="jumlah_l" name="jumlah_l" placeholder="Total Pecahan Koin" value="<?php echo number_format($total_koin,0);?>">
							<span class="input-group-addon"><span id="l_pl"><?php echo number_format($total_koin_v,0);?>0</span></span>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label><b>Total Kas Hari ini</b></label>
						<input type="text" class="form-control bold text-right disabled-action" id="total_kas" name="total_kas" placeholder="Total" value="<?php echo number_format($total_kas,0);?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label><b>Total Hasil Opname</b></label>
						<input type="text" class="form-control bold text-right disabled-action" id="total_hasil" name="total_hasil" placeholder="Total" value="<?php echo number_format($total_hasil,0);?>">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label><b>Balance</b></label>
						<input type="text" class="form-control bold text-right disabled-action" id="selisih" name="selisih" placeholder="Total" value="<?php echo number_format($selisih,0);?>">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
	  	<button type="button" class="btn btn-default" data-dismiss="modal"><i class='fa fa-close'></i> Batal</button>
	  	<button id="submit-btn" onclick="addData();" tabindex="13" type="button" class="btn btn-danger submit-btn<?php echo $status_c." ".$status_e;?> <?php echo $hidden;?>"><i class='fa fa-save'></i> Simpan</button>
	  	<button id="submit-btn" onclick="addData();" tabindex="13" type="button" class="btn btn-danger submit-btn <?php echo $show ." ".$apv;?>"><i class='fa fa-cogs'></i> Approval</button>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function(){
		$('#input .form-control').ForceNumericOnly();
		$('[tabindex=1]').focus().select();
		$('#submit-btn').addClass('disabled-action');

	})
	$(document).on('keyup','input',function(e){
		var id= $(this).attr('id');
		var nilai =0;
			nilai = $(this).val();
		var tipe = id.split('_')[1];
			tipex = tipe.split('')[0];
		var nom  = tipe.substr(1,tipe.length-1);
		console.log(id+','+tipex+','+nom+','+(nilai*nom));
		if(tipex=='k'){
			$('#'+tipex+'_'+(nom/1000)).html((nilai*nom).toLocaleString());
		}else{
			$('#'+tipex+'_'+(nom)).html((nilai*nom).toLocaleString());
		}

		var sum_t =0; var sum_tl=0;
		var sum =0; var suml=0;
		$("input.k").each(function(){
			sum += parseInt($(this).val());
		})
		$('.kp').each(function(){
			var tt=$(this).html().replace(/,/g,'');
			sum_t += parseFloat(tt);
		})
		$("input.l").each(function(){
			suml += parseInt($(this).val());
		})
		$('.nl').each(function(){
			var ttl=$(this).html().replace(/,/g,'');
			sum_tl += parseFloat(ttl);
		})
		$('#jumlah_pk').val(sum);
		$('#k_pk').html((sum_t).toLocaleString())
		$('#jumlah_l').val(suml);
		$('#l_pl').html((sum_tl).toLocaleString());
		$('#total_hasil').val((sum_t + sum_tl).toLocaleString());
		var saldo_kas =$('#total_kas').val().replace(/,/g,'');
		$('#selisih').val(((sum_t + sum_tl)-parseFloat(saldo_kas)).toLocaleString());
		var bal =$('#selisih').val().replace(/,/g,'');
		if(parseFloat(bal)!=0){ $('#submit-btn').removeClass('disabled-action');}
	})
	$(document).on('keypress','input',function(e){
		var sum =0;
			sum = $(this).attr('tabindex');
			//console.log(sum);
			if(e.which===13){
				//$('input :not(.date)').attr('tabindex',parseInt(sum)+1).focus();
				$('[tabindex='+(parseInt(sum)+1)+']').focus().select();
			}
	})
</script>
