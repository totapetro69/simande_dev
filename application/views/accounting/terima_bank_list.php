<?php if(!isBolehAkses()){ /*redirect(base_url().'auth/error_auth');*/}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$defaultDealer = $this->input->get('kd_dealer')?$this->input->get('kd_dealer'): $this->session->userdata("kd_dealer");
$tgl_trans = $this->input->get('frd')?$this->input->get('frd'):date('d/m/Y',strtotime("-5 days"));
$to_tgl = $this->input->get('tod')?$this->input->get('tod'):date('d/m/Y');
$kd_bank =$this->input->get("n");
?>
<section class="wrapper">
	<div class="breadcrumb margin-bottom-10">
		<?php echo breadcrumb();?>
		<div class="bar-nav pull-right ">
			<a class="btn btn-default" href="<?php echo base_url("angsuran/terima_bank");?>"><i class='fa fa-open'></i> Add Transaksi Bank</a>
		</div>
	</div>
	<form id="terimbankForm" action="<?php echo base_url('angsuran/terimabank_list');?>" class="bucket-form" method="get">
		<input class="form-control" id="akhir" name="akhir" type="hidden" value="<?php //echo $saldo_akhir;?>" />
		<div class="col-lg-12 padding-left-right-10">
			<div class="panel margin-bottom-10">
				<div class="panel-heading panel-custom">
					<i class="fa fa-list fa-fw"></i> List Transaksi Bank
					<span class="tools pull-right">
							<a class="fa fa-chevron-down" href="javascript:;"></a>
					</span>
				</div>
				<div class="panel-body panel-body-border" style="display: show;">
					<div class="row">
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label>Dealer</label>
								<select name="kd_dealer" class="form-control">
									<option value="">--Pilih Dealer--</option>
									<?php
										if(isset($dealer)){
											if($dealer->totaldata>0){
												foreach ($dealer->message as $key => $value) {
													$pilih = ($defaultDealer == $value->KD_DEALER)?'selected':'';
													?>
													<option value="<?php echo $value->KD_DEALER;?>" <?php echo $pilih;?>><?php echo $value->NAMA_DEALER;?></option>
													<?php
												}
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 no-margin-r">
							<div class="form-group">
								<label class="control-label" for="date">Dari Tanggal</label>
								<div class="input-group input-append date">
									<input class="form-control" id="tgl_trans" name="frd" placeholder="DD/MM/YYYY" value="<?php echo $tgl_trans; ?>" type="text" required/>
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-2 col-md-2 no-margin-r">
							<div class="form-group">
								<label class="control-label" for="date">Sampai Tanggal</label>
								<div class="input-group input-append date">
									<input class="form-control" id="tgl_trans2" name="tod" placeholder="DD/MM/YYYY" value="<?php echo $to_tgl; ?>" type="text" required/>
									<span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
						</div>
						<div class="col-xs-1 col-sm-1 col-md-1">
							<br>
							<button class="btn btn-info" type="submit"><i class="fa fa-search"></i> Preview</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="col-lg-12 padding-left-right-10">
		<div id="table_data" class="panel panel-default">
			<div class="table-responsive h250">
				<table id="list_data" class="table table-striped table-bordered">
					<thead>
						<tr class="no-hover"><th colspan="11" ><i class="fa fa-list fa-fw"></i> Data Terima Bank</th></tr>
						<tr>
						<th>No</th>
						<th>Bank</th>
						<th>Tanggal</th>
						<th>Tran</th>
						<th>Keterangan</th>
						<th>Tipe</th>
						<th>Awal</th>
						<th>Debet</th>
						<th>Kredit</th>
						<th>Akhir</th>
						<th>No tran</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$n=0;
							if(isset($terimabank)){
								if($terimabank->totaldata >0){
									foreach ($terimabank->message as $key => $value) {
										$n++;
										?>
											<tr id="l_<?php echo $n;?>">
												<td class='text-center'><?php echo $n;?></td>
												<td class='text-left'><?php echo NamaBank($value->KD_DEALER,$value->KD_BANK);?></td>
												<td class='table-nowarp'><?php echo tglFromSql($value->TGL_TRANS);?></td>
												<td class='table-nowarp'><?php echo $value->KD_TRANS;?></td>
												<td class='td-overflow' title="<?php echo $value->KETERANGAN;?>"><?php echo $value->KETERANGAN;?></td>
												<td class='table-nowarp text-center'><?php echo $value->TIPE_TRANS;?></td>
												<td class='table-nowarp text-right'><?php echo number_format($value->SALDO_AWAL,0);?></td>
												<td class='table-nowarp text-right'><?php echo number_format($value->DEBET,0);?></td>
												<td class='table-nowarp text-right'><?php echo number_format($value->KREDIT,0);?></td>
												<td class='table-nowarp text-right'><?php echo number_format($value->SALDO_AKHIR,0);?></td>
												<td class='table-nowarp text-right'><?php echo $value->NO_TRANS;?></td>
											</tr>
										<?php
									}
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
	$('#jumlah').mask("#,##0",{reverse: true});
	$('#jumlah').on("keypress",function(e){
		if(e.which===13){
			$('#store-btn').trigger('click');
		}
	})
	$('#keterangan').on('keypress',function(e){
		if(e.which===13){
			$('#jumlah').focus().select();
		}
	})
	$("#store-btnx").click(function(){
		//$("#terimbankForm").valid();
		$("#terimbankForm").validate({
			focusInvalid: false,
			invalidHandler: function(form, validator) {
				if (!validator.numberOfInvalids()) {return;}
				$('html, body').animate({
						scrollTop: $(validator.errorList[0].element).offset().top
				}, 2000);
			}
			/*submitHandler: function(form){
				form.submit();
			}*/
		});
		if ($("#terimbankForm").valid()) {
			//storePengajuan();
		}
	})
	__perkiraan();
	__getKDAkun("<?php echo $kd_bank;?>");
})
/**
 * [__getKDAkun description]addForm
 * @param  {[type]} jenisakun [description]
 * @return {[type]}           [description]
 */
function __openDetail(){
	var kd_bank = $("#kd_bank").val();
	if(kd_bank != ''){
		$("#detail-panel").removeAttr('style');
		$("#store-btn").removeAttr('disabled');
	}
	else{
		$('.error').animate({ top: "0" }, 500).fadeIn();
		$('.error').html("Tidak ada bank yg dipilih.");
		$("#store-btn").attr('disabled', 'disabled');
		setTimeout(function () {
				hideAllMessages();
		}, 4000);
		$("#detail-panel").css('display','none');
	}
}
function  __getKDAkun(kd_bank){
	$.getJSON(http+"/angsuran/terima_bank/true",function(result){
		console.log(result);
		$('#kd_bank').val(kd_bank);
		var datax=[];
		$.each(result.nama_bank.message,function(index,d){
			datax.push({
				'KD BANK':d.KD_BANK,
				'NAMA BANK':d.NAMA_BANK,
				'NO REKENING':d.NO_REKENING,
				'KD_AKUN':d.KD_AKUN
			})
		})
		var datrans=[];
		$.each(result.transaksi.message,function(index,d){
			datrans.push({
				'KD TRANS':d.KD_TRANS,
				'NAMA TRANS':d.NAMA_TRANS,
				'TIPE TRANS':d.TIPE_TRANS
			})
		})
		$('#kd_bank').inputpicker({
			data:datax,
			fields:['KD BANK','NAMA BANK','NO REKENING'],
			headShow:true,
			fieldValue:'KD BANK',
			fieldText:'NAMA BANK',
			filterOpen:true
		}).change(function(e){
			e.preventDefault();
			var dx=datax.findIndex(obj => obj['KD BANK'] === $(this).val());
			//$('#jml').focus().select();
			if(dx>-1){
				$('#nama_bank').val(datax[dx]['NAMA BANK'])
				$('#no_rekening').val(datax[dx]['NO REKENING'])
				$('#kd_akun').val(datax[dx]["KD_AKUN"]);
				//$('#keterangan').focus().select();
			}
		})
		$('#kd_bank').val(kd_bank).trigger("change");
		$('#kd_trans').inputpicker({
			data:datrans,
			fields:['KD TRANS','NAMA TRANS','TIPE TRANS'],
			headShow:true,
			fieldValue:'KD TRANS',
			fieldText:'NAMA TRANS',
			filterOpen:true
		}).change(function(e){
			e.preventDefault();
			var dt=datrans.findIndex(obj => obj['KD TRANS'] === $(this).val());
			$('#keterangan').focus().select();
			console.log(dt);
			if(dt>-1){
				var tipe_trans = datrans[dt]['TIPE TRANS'];
				var nama_trans = datrans[dt]['NAMA TRANS'];
				var Keterngan_tipe = datrans[dt]['TIPE TRANS'] == 'D'?'Debit':'Kredit';
				$('#nama_trans').val(nama_trans);
				$('#tipe_trans').val(tipe_trans).select();
				$('#keterangan').val(nama_trans);
				$('#keterangan').focus().select();
			}
		})
	})
}
function storePengajuan(){
	var defaultBtn = $("#store-btn").html();
	if(!$("#terimbankForm").valid()){ return ;}
	$("#store-btn").addClass("disabled");
	$("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
	var formData = $("#terimbankForm").serialize();
	 $("#terimbankForm").attr('action');
	$.ajax({
		url:"<?php echo base_url('angsuran/terimabank_simpan');?>",
		type:"POST",
		dataType: "json",
		data:formData,
		success:function(result){
			if (result.status == true) 
			{
				$('.success').animate({ top: "0" }, 500).fadeIn();
				$('.success').html(result.message);
				setTimeout(function(){
						document.location.href=result.location;
				}, 2000);
			}else{
				$('.error').animate({ top: "0" }, 500).fadeIn();
				$('.error').html(result.message);
				setTimeout(function () {
						hideAllMessages();
						$("#store-btn").removeClass("disabled");
						$("#store-btn").html(defaultBtn);
				}, 2000);
			}
		}
	});
}
function __perkiraan(kd_akun){
	var jenisakun="";
	//console.log(kd_akun);
	$.getJSON(http+"/cashier/kodeakun/"+jenisakun,{'id':jenisakun},function(result){
		//console.log(result);
		$('#kd_akun').text('');
		var datax=[];
		$.each(result,function(index,d){
			datax.push({
				'KD_AKUN':d.KD_AKUN,
				'NAMA_AKUN':d.NAMA_AKUN
			})
		})
		//$('#kd_akun').text('');
		/*$('#kd_akun').inputpicker({
			data:datax,
			fields:['KD_AKUN','NAMA_AKUN'],
			headShow:true,
			fieldText:'KD_AKUN',
			fieldValue:'KD_AKUN',
			filterOpen:true
		})*/
		
	});
}

function __hapus(id,baris){
	if(confirm("Yakin transaksi ini akan dihapus?")){
		$('tr#l_'+id+' > td:eq(1) > a').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
		$.getJSON("<?php echo base_url('angsuran/terimabank_hps');?>",{'id':id},function(result){
			console.log(result);
			if(result.status){
				$('tr#l_'+baris).remove();
			}else{
				$('tr#l_'+baris+' > td:eq(1) > a').html("<i class='fa fa-trash'></i>")
			}
		})
	}
	
}
</script>