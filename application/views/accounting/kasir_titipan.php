<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $defaultDealer=$this->session->userdata("kd_dealer");
  $defaultGroup=$this->session->userdata("nama_group");
  $tgl_trans=date('d/m/Y');
  ?>
  <form id="addForm" class="bucket-form" action="<?php echo base_url('cashier/kwitansi');?>" method="post">
  	<div class="modal-header">
  		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  		<h4 class="modal-title" id="myModalLabel">Input Pembayaran Titipan</h4>
  	</div>
  	<div class="modal-body">
  		<div class="row">
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Nama Dealer</label>
					<select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required="true">
						<option value="">--Pilih Dealer--</option>
						<?php
							if($dealer){
								if(is_array($dealer->message)){
									foreach ($dealer->message as $key => $value) {
										$select=($defaultDealer==$value->KD_DEALER)?"selected":"";
										echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
									}
								}
							}
						?>
					</select>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Tanggal Transaksi</label>
	  				<div class="input-group append-group date">
						<input type="text" class="form-control" id="tgl_transaksi" name="tgl_transaksi" value="<?php echo ($tgl_trans);?>">
						 <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
					</div>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Nama Customer</label>
	  				<select class="form-control" name="nama_customer" id="nama_customer">
	  					<option>--Pilih Customer--</option>
	  					<?php
	  						if($guest){
	  							if($guest->totaldata>0){
	  								foreach ($guest->message as $key => $value) {
	  									echo "<option value='".$value->ID."'>".$value->NAMA_CUSTOMER."</option>";
	  								}
	  							}
	  						}
	  					?>
	  				</select>
	  				<input type="hidden" id="no_ref" name="no_ref">
	  				<input type='hidden' id="kd_customer" name="kd_customer">
	  				<input type="hidden" id="jenis_pembayaran" name="jenis_pembayaran" value="Titipan-100.12000">
	  				<input type="hidden" id="tgl_spk" name="tgl_spk">
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Alamat Customer</label>
	  				<textarea class="form-control" name="alamat_customer" id="alamat_customer"></textarea>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>No. KTP</label>
	  				<input type='text' id="no_ktp" name="no_ktp" class="form-control" required="true">
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>No. Telp</label>
	  				<input type='text' id="no_telp" name="no_telp" class="form-control">
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-12 col-sm-12">
	  			<div class="form-group">
	  				<label>Type Motor</label>
	  				<?php echo Dropdownmotor(false);?>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Transaksi</label>
	  				<select id="cara_bayar" name="cara_bayar" class="form-control">
	  					<option >&nbsp;</option>
	  					<option value='CASH'>CASH</option>
	  					<option value='CREDIT'>CREDIT</option>
	  				</select>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Jumlah Titipan</label>
	  				<input type='text' id="uangmuka_1" name="uangmuka_1" class="form-control" required="true">
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-6 col-sm-6">
	  			<div class="form-group">
	  				<label>Leasing Company</label>
	  				<select id="kd_fincom" name="kd_fincom" class="form-control" disabled="disabled">
	  					<option value="">--Pilih Leasing--</option>
	  					<?php
	  					if($fincom){
	  						if($fincom->totaldata>0){
	  							foreach ($fincom->message as $key => $value) {
	  								echo "<option value='".$value->KD_LEASING."'>".$value->NAMA_LEASING."</option>";
	  							}
	  						}
	  					}
	  					?>
	  				</select>
	  			</div>
	  		</div>
	  		<div class="col-xs-12 col-md-12 col-sm-12">
	  			<div class="form-group">
	  				<label></label>
	  				<input type="text" id="ket_1" name="ket_1" class="form-control" value="">
	  				<input type="hidden" id="jml_1" name="jml_1" value="1">
	  				<input type="hidden" id="source" name="source" value="TRANS_GUESTBOOK.ID">
	  			</div>
	  		</div>
	  	</div>
  	</div>
  	<div class="modal-footer">
	    <button type="button" class="btn btn-default" onclick="batal();">Batal</button>
	    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_c ?> submit-btn">Simpan</button>
	</div>
  </form>
<script type="text/javascript">
	$(document).ready(function(){
		$('#myModalLg').on('hidden.bs.modal',function(){
			document.location.href="<?php echo base_url('cashier/kasir');?>";
		})
		$('#myModalLg').draggable({
		    handle: ".modal-header"
		});

		$('#myModalLg #nama_customer').change(function(){
				__getDetailCustomer();
		})
		$('#myModalLg #addForm').on('submit',function(){
			if(!$('#myModalLg #addForm').valid()){return;}

		})
		$('#myModalLg #uangmuka_1').on('focusout',function(){
			$('#myModalLg #ket_1').val('Pembayaran Titipan untuk pembelian Unit Motor \n'+$('#myModalLg #kd_item2').val())
		})
	})
	function batal(){
		document.location.href="<?php echo base_url('cashier/kasir');?>";
	}
	function __getDetailCustomer(){
		var kd_guest=$('#myModalLg #nama_customer').val();
		
		var alamat ="";
		if(kd_guest==''){return false;}
		$('#loadpage').removeClass("hidden");
		$.ajax({
			type: 'GET',
			url:'<?php echo base_url("spk/detailguest");?>',
			data: {'id': kd_guest},
			dataType: 'json',
			success:function(result){
				if(result.length>0){
					$.each(result,function(e,d){
						alamat =d.ALAMAT_SURAT +' '
						alamat +=d.NAMA_DESA + ', KEC. '
						alamat +=d.NAMA_KECAMATAN +'\n'
						alamat +=d.NAMA_KABUPATEN +'\n'+d.NAMA_PROPINSI + '\n'+ d.KODE_POS
						$('#myModalLg #alamat_customer').val(alamat);
						$('#myModalLg #no_ktp').val(d.NO_KTP);
						$('#myModalLg #no_telp').val(d.NO_HP);
						$('#myModalLg #kd_item').val(d.KD_ITEM);
						$('#myModalLg #kd_item2').val(d.KD_ITEM +' [ '+d.NAMA_ITEM+' ]');
						$('#myModalLg #cara_bayar').val(d.CARA_BAYAR).select();
						$('#myModalLg #kd_customer').val(d.KD_CUSTOMER);
						$('#myModalLg #no_ref').val(d.ID)
						$('#myModalLg #tgl_spk').val(convertDate(d.TANGGAL));
						$('#loadpage').addClass("hidden");
						if(d.CARA_BAYAR=='CREDIT'){
							$('#myModalLg #kd_fincom').attr('required',true).removeAttr('disabled');
						}else{
							$('#myModalLg #kd_fincom').attr({'required':false,'disabled':'disabled'}).
						}

						$('#myModalLg #uangmuka_1').focus().select();
					})
				}
			}
		})
	}

</script>
<!-- <script type="text/javascript" src="<?php echo base_url("assets/js/style.js");?>"></script> -->