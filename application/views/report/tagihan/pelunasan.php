<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cog"></i> Pembayaran Tagihan</h4>
</div>
<?php
   	$kd_dealer="";$alamat_dealer="";$fincoy="";$telp="";$nama_dealer="";
   	$no_trans="";$jumlah=0;$keterangan=""; $no_mesin="";$no_rangka="";$nama_kota;
   	$harga_otr=0; $uang_muka=0; $dibuat_oleh=""; $nama_customer="";$telp="";$fax=""; 
   	$tampil="hidden";$tgl_trans=""; $kd_maindealer = $this->session->userdata("kd_maindealer");
   	$gatampil="";$no_reff="";$jatuh_tempo="";$kd_piutang="";
   	$jenis_print=(isset($jenis))? $jenis:"";
   	if(isset($leasing)){
      if($leasing->totaldata >0){
         foreach ($leasing->message as $key => $value) {
            $kd_dealer  = $value->KD_DEALER;
            $fincoy     = $value->KD_FINCOY;
            $no_trans   = $value->NO_TRANS;
            $no_mesin   = $value->NO_MESIN;
            $no_rangka  = $value->NO_RANGKA;
            $no_reff 	= $value->NO_KWITANSI;
            $harga_otr  = $value->HARGA_OTR;
            $uang_muka  = ($value->JML_DIBAYAR+$value->SUBSIDI);
            $jumlah     = $value->SISA_TAGIHAN;
            $keterangan = str_replace("\/n","",str_replace("\/r"," ",$value->URAIAN_TRANSAKSI));
            $nama_customer = $value->NAMA_CUSTOMER;
            $tgl_trans = tglFromSql($value->TGL_TRANS);
            $jatuh_tempo = tglFromSql($value->JATUH_TEMPO);
            $kd_piutang = $value->KD_PIUTANG;
         }
      }
   	}
   	if(isset($program)){
      if($program->totaldata >0){
         foreach ($program->message as $key => $value) {
            $kd_dealer  = $value->KD_DEALER;
            $fincoy     = $value->TAGIHANKE;
            $no_trans   = $value->NO_TRANS;
            $no_mesin   = $value->TAGIHANKE;
            $no_rangka  = tglFromSql($value->END_CLAIM);
            $no_reff 	= $value->NO_TRANS."-".$value->TAGIHANKE;
            //$harga_otr  = $value->HARGA_OTR;
            //$uang_muka  = ($value->JML_DIBAYAR+$value->SUBSIDI);
            $jumlah     = $value->SISA_TAGIHAN;
            $keterangan = str_replace("\/n","",str_replace("\/r"," ",$value->URAIAN_TRANSAKSI));
            $nama_customer = $value->NAMA_CUSTOMER;
            $tgl_trans = tglFromSql($value->TGL_TRANS);
            $jatuh_tempo = tglFromSql($value->END_DATE);
            $kd_piutang = $value->KD_PIUTANG;
         }
      }
   	}
?>
<div class="modal-body">
	<div class="row">
		<div class="col-lg-12 padding-left-right-5">
			<div class="panel margin-bottom-5">
				<div class="panel-heading">
					<i class="fa fa-list-ul"></i> Detail Tagihan
					<span class="tools pull-right"><a class="fa fa-chevron-down" href="javascript:;"></a></span>
				</div>
				<div class="panel-body panel-body-border panel-body-10">
					<form id="frmBayar" class="bucket-form" method="post" action="">
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label>Kode Leasing</label>
								<select class="form-control disabled-action" id="kd_fincoy" name="kd_fincoy">
									<option value="">-- Pilih Leasing --</option>
									<?php 
										if(($jenis_print=='')){
											if(isset($finco)){
												if($finco->totaldata >0){
													foreach ($finco->message as $key => $value) {
														$pilih=($fincoy == $value->KD_LEASING)?'selected':'';
														echo "<option value='".$value->KD_LEASING."' $pilih>".$value->NAMA_LEASING."</option>";
													}
												}
											}
										}else if($jenis_print=="program"){
											
											$pilih1=($fincoy =='AHM')?'selected':'';
											$pilih2=($fincoy =='MAINDEALER')?'selected':'';
											$pilih3=($fincoy =='FINANCE')?'selected':'';
											echo "<option value='AHM' $pilih1>AHM</option>";
											echo "<option value='MAINDEALER' $pilih2>MAINDEALER</option>";
											echo "<option value='FINANCE' $pilih3>FINANCE</option>";
										}
										//}
									?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 col-md-3 col-sm-3">
							<div class="form-group">
								<label>No. Tagihan</label>
								<input type="text" name="no_trans" id="no_trans" class="form-control" value="<?php echo $no_trans;?>">
							</div>
						</div>
						<div class="col-xs-6 col-md-2 col-sm-2">
							<div class="form-group">
								<label>Tgl Tagihan</label>
								<input type="text" name="tgl_trans" id="tgl_trans" class="form-control" value="<?php echo $tgl_trans;?>">
							</div>
						</div>
						<div class="col-xs-6 col-md-4 col-sm-4">
							<div class="form-group">
								<label>No. Reff</label>
								<input type="text" name="no_reff" id="no_reff" class="form-control" value="<?php echo $no_reff;?>">
							</div>
						</div>
						<div class="col-xs-8 col-md-7 col-sm-7">
							<div class="form-group">
								<label>Uraian Tagihan</label>
								<input type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo $keterangan;?>">
							</div>
						</div>
						<div class="col-xs-2 col-md-3 col-sm-3">
							<div class="form-group">
								<label>Jumlah Tagihan</label>
								<input type="text" name="jml_tagihan" id="jml_tagihan" class="form-control text-right" value="<?php echo number_format($jumlah,0);?>">
							</div>
						</div>
						<div class="col-xs-2 col-md-2 col-sm-2">
							<div class="form-group">
								<label>Nama Customer</label>
								<input type="text" name="nama_customer" id="nama_customer" class="form-control" value="<?php echo $nama_customer;?>">
							</div>
						</div>
						<div class="col-xs-4 col-md-2 col-sm-2">
							<div class="form-group">
								<label>Cara Bayar</label>
								<select class="form-control" id="cbayar" name="cbayar">
									<option value=''>--Pilih Cara Bayar--</option>
									<option value='CASH'>CASH</option>
									<option value='BANK'>BANK</option>
									<!-- <option value='KU'>KU</option> -->
								</select>
							</div>
						</div>
						<div class="tunai col-xs-4 col-md-3 col-sm-3">
							<div class="form-group">
								<label>Jumlah Tagihan</label>
								<input type="text" name="jml_bayar" id="jml_bayar_t" class="form-control text-right" value="<?php echo number_format($jumlah,0);?>" data-mask='#,##0' data-mask-reverse='true'/>
							</div>
						</div>
						<div class="nontunai col-xs-4 col-md-6 col-sm-6 hidden">
							<div class="form-group">
								<label>Reff. Penerimaan bank <span id="ldg" class="hidden" style="color:red"><i class="fa fa-spinner fa-spin"></i></span></label>
								<input type="text" name="no_reff_bank" id="no_reff_bank" class="form-control" value=""/>
							</div>
						</div>
						<div class="nontunai col-xs-4 col-md-3 col-sm-3 hidden">
							<div class="form-group">
								<label>Jumlah Bayar<span id="jmb"></span></label>
								<input type="text" name="jml_bayar" id="jml_bayar" class="form-control text-right" value="<?php echo number_format($jumlah,0);?>" data-mask="#,##0" data-mask-reverse="true"/>
								<input type="hidden" id="tgl_bayar" name="tgl_bayar" value="<?php echo date("d/m/Y");?>">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-12 padding-left-right-5 hidden">
			<div class="panel margin-bottom-5">
				<div class="panel-heading">
					<i class="fa fa-list-ul"></i> Data Penerimaan Bank
					<span class="tools pull-right"><a class="fa fa-chevron-down" href="javascript:;"></a></span>
				</div>
				<div class="panel-body panel-body-border panel-body-10">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" id="submit-btn" onclick="" class="btn btn-info disabled-action"><i class='fa fa-save xd'></i> Simpan</button>
</div>
<script type="text/javascript">
	var jenis ="<?php echo $jenis_print;?>";
	$(document).ready(function(){
		$('#cbayar').on('change',function(){
			if($(this).val()=='CASH'){
				$('.tunai').removeClass('hidden');
				$('.nontunai').addClass('hidden');
				$('#submit-btn').removeClass("disabled-action");
			}else{
				$('.tunai').addClass('hidden');
				$('.nontunai').removeClass('hidden');
				__loadTerimaBank();
			}
		})
		$('#jml_bayar').on("change",function(){
			$('#submit-btn').removeClass("disabled-action");
		})
		$('#myModalLg').on("hidden.bs.modal",function(){
	  		var tab="2";
	  		switch(jenis){
	  			case "kupon":tab="3";break;
	  			case "program":tab="4";break;
	  			case "promo":tab="5";break;
	  			default: tab="2";break;
	  		}
      		window.location.href="<?php echo base_url('report/view_ar?t=');?>"+tab;
      	})
      	$('#submit-btn').on("click",function(){
      		simpanbayar();
      	})
	})
	function __loadTerimaBank(){
		var datax=[];
		$('#ldg').removeClass("hidden");
		$.getJSON("<?php echo base_url("angsuran/terimabank_list/1");?>",function(result){
			if(result.status){
				$.each(result.message,function(e,d){
					datax.push({
						'NO_TRANS':d.NO_TRANS,
						'KETERANGAN': d.KETERANGAN,
						'TANGGAL': d.TANGGAL,
						'JUMLAH':parseFloat(d.SISA_SALDO).toLocaleString(),
						'text'	: d.NO_TRANS +' - '+d.KETERANGAN
					});
				})
				$('#ldg').addClass("hidden");
			}
			$('#no_reff_bank').inputpicker({
				data : datax,
				fields: ['NO_TRANS','JUMLAH','KETERANGAN','TANGGAL'],
				fieldValue :"NO_TRANS",
				fieldText : 'text',
				filterOpen: false,
				headShow: true
			}).on("change", function(e) {
				e.preventDefault();
				var dx = datax.findIndex(obj => obj['NO_TRANS'] === $(this).val());
				if (dx > -1) {
					var jml_bayar = $("#frmBayar input#jml_bayar").val().replace(/,/g,'');
					var jml_dbyar = datax[dx]["JUMLAH"].replace(/,/g,'');
					var kurang=(parseFloat(jml_bayar) > parseFloat(jml_dbyar));
					if(kurang){
						var dbyr=(parseFloat(jml_dbyar).toLocaleString())
						//$('#jmb').html(jml_dbyar);
						$("#frmBayar input#jml_bayar").val(dbyr);
					}else{
						$("#frmBayar input#jml_bayar").val(parseFloat(jml_bayar).toLocaleString());
					}
					$('#tgl_bayar').val(datax[dx]["TANGGAL"])
					$('#submit-btn').removeClass("disabled-action");
				}
			})
		})
	}
	function simpanbayar(){
  		$("i.xd").removeClass("fa-save").addClass("fa fa-spinner fa-spin");
  		$('#loadpage').removeClass("hidden");
  		var datax=[];
		datax={
			'kd_maindealer':"<?php echo $this->session->userdata('user_id');?>",
			'kd_dealer':"<?php echo $kd_dealer;?>",
			'no_trans' :"<?php echo $no_trans;?>",
			'tgl_bayar':$.trim($('#tgl_bayar').val()),
			'jumlah_bayar': ($('#cbayar').val()=='CASH')?$('#jml_bayar_t').val():$("#frmBayar input#jml_bayar").val(),
			'reff_bayar' : $('#no_reff_bank').val(),
			'keterangan' : $('#keterangan').val(),
			'sisa_bayar' : $("#jml_bayar").val(),
			'rencana_bayar':"<?php echo $jatuh_tempo;?>",
			'no_kwitansi' :$('#no_reff').val(),
			'kd_piutang': "<?php echo $kd_piutang;?>",
			'tagihanke'	: (jenis=='program')?"<?php echo $no_mesin;?>":''	
		};
		
		//console.log(datax);return;
		$.ajax({
			type :'POST',
			url : "<?php echo base_url('report/piutang_bayar');?>",
			data: (datax),
			dataType :'json',
			//async : false,
			success : function(result){
				console.log(result);
				if(result.status){
					//update trans_piutang status=2 ( di bayar)
					$("i.xd").removeClass("fa fa-spinner fa-spin").addClass("fa-save")
					$('#myModalLg').modal('hide');
					$('#loadpage').addClass("hidden");
				}
			}
		})
	}
</script>