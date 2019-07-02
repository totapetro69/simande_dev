<?php
	$jtrans=(isset($no_trans))?$no_trans:"";
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('finance/jno_simpan');?>" method="post">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <h4 class="modal-title" id="myModalLabel">Setup Jurnal To Transaksi </h4>
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6">
				<div class="form-group">
					<label>Jenis Transaksi</label>
					<select name="kd_transaksi" id="kd_transaksi" class="form-control">
	            		<option value="">--Pilih Jenis Transaksi--</option>
	            		<?php
	            			if(isset($trans)){
	            				if($trans->totaldata > 0){
	            					foreach ($trans->message as $key => $value) {
	            						$select =($jtrans==$value->KD_TRANS)?"selected":"";
	            						echo "<option value='".$value->KD_TRANS."' ".$select.">[".strtoupper($value->KD_TRANS)."] ".strtoupper($value->NAMA_TRANS)."</option>";
	            					}
	            				}
	            			}
	            		?>
	            	</select>
	            </div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class="form-group">
	        		<label><span id="tpm">Type Transaksi</span></label>
	        		<select name="tp_transaksi" id="tp_transaksi" class="form-control">
	        			<option value="" class="xx">--Pilih Type Transaksi</option>
	        			<option value='KAS' id="all" class='pj hidden'>KAS</option>
	        			<option value='DPP' class='pj hidden'>DPP Unit</option>
	        			<option value='PPN' class='pj hidden'>PPN Unit</option>
	        			<option value='LSH' class='pj hidden'>Piutang Leasing</option>
	        			<option value='SP' class='pj hidden'>Potongan Sales Program</option>
	        			<option value='STNK' class='pj hidden'>Piutang STNK</option>
	        			<option value='BPKB' class='pj hidden'>Piutang BPKB</option>
	        			<option value='STNKB' class='pj hidden'>Piutang Pengurusan STNK Lainnya</option>
	        			<option value='SK' class='pj hidden'>Sales Kupon</option>
	        			<option value='UNIT' class='pb hidden'>Unit</option>
	        			<option value='PPN' class='pb hidden'>PPN</option>
	        			<option value='SP' class='sp hidden'>Spare Part</option>
	        			<option value='OLI' class='sp hidden'>Olie</option>
	        			<!-- <option value='CLAIM' class='sp hidden'>Claim</option> -->
	        			<option value='PPN' class='sp hidden'>PPN</option>

	        			<option value='STNK' class='pjm hidden'>Pengurusn STNK</option>
	        			<option value='BPKB' class='pjm hidden'>Pengurusn BPKB</option>
	        			<option value='STNKB' class='pjm hidden'>Pengurusan Lainnya</option>
	        			<option value='KPB' class='svr hidden'>KPB</option>
	        			<option value='REGULER' class='svr hidden'>REGULER</option>

	        		</select>
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class="form-group">
	        		<label><span id="tpm_1">Sub Type</span></label>
	        		<select name="tp_trans" id="tp_trans" class="form-control">
	        			<option value="" class="xx">--Pilih Sub Type</option>
	        			<option value='AHM' class='sp hidden'>AHM</option>
	        			<option value='MD' class='sp hidden'>MD</option>
	        			<option value='SD' class='sp hidden'>SD</option>
	        			<option value='LSH' class='sp hidden'>Leasing</option>
	        			<option value='IN' class='ppn hidden'>PPN Masukan</option>
	        			<option value='OUT' class='ppn hidden'>PPN Keluaran</option>
	        			<option value='JP' class='sk hidden'>Joint Promo

	        			<option value='OTH' class='dpp hidden'>SMH</option>
	        			<option value='AT' class='dpp hidden'>AT</option>
	        			<option value='CUB' class='dpp hidden'>CUB</option>
	        			<option value='SPORT' class='dpp hidden'>SPORT</option>

	        			<option value='OTH' class='unit hidden'>SMH</option>
	        			<option value='AT' class='unit hidden'>AT</option>
	        			<option value='CUB' class='unit hidden'>CUB</option>
	        			<option value='SPORT' class='unit hidden'>SPORT</option>

	        			<option value='BENGKEL' class='sp oli hidden'>Bengkel</option>
	        			<option value='COUNTER' class='sp oli hidden'>Counter</option>
	        			<option value='CLAIM' class='sp oli hidden'>Claim</option>

	        			<option value='JASA' class='kpb reguler hidden'>Jasa</option>
	        			<option value='PART' class='kpb reguler hidden'>Part</option>
	        			<option value='OLI' class='kpb reguler hidden'>Oli</option>
	        		</select>
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm3 akun">
	        	<div class="form-group">
	        		<label>No.Perkiraan</label>
	        		<input type="text" name="kd_akun" id="kd_akun" class="form-control">
	        		<input type="hidden" name="kd_akun_1" id="kd_akun_1" class="form-control">
	        		<input type="hidden" name="sub_akun" id="sub_akun" class="form-control">
	        	</div>
	        </div>
	        <div class="col-xs-8 col-md-6 col-sm6 akun">
	        	<div class="form-group">
	        		<label>Nama Perkiraan</label>
	        		<input type="text" name="nama_akun" id="nama_akun" class="form-control">
	        	</div>
	        </div>
	        <div class="col-xs-4 col-md-2 col-sm2">
	        	<div class="form-group">
	        		<label>Posisi</label>
	        		<select name="type_akun" class="form-control">
	        			<option value='D'>Debet</option>
	        			<option value="K">Kredit</option>
	        		</select>
	        	</div>
	        </div>
	        <div class="col-xs-12 col-sm-1 col-md-1">
	        	<div class="form-group"><br>
	        		<a class="btn btn-primary pull-right" role="button" onclick="__simpan();"><i class="fa fa-plus"></i> Add</a>
	        	</div>
	        </div>
	    </div>
	    <hr>
	    <div class="row">
	    	<div class="table-responsive h250 col-xs-12 col-md-12 col-sm-12">
		    	<table class="table table-hover table-stripped" id="lst">
		    		<thead>
		    			<tr>
		    				<th>No</th>
		    				<th>&nbsp;</th>
		    				<th>Jenis</th>
		    				<th>Tipe</th>
		    				<th>No.Perkiraan</th>
		    				<th>Nama Perkiraan</th>
		    				<th>Posisi</th>
		    				<th>Keterangan</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			
		    		</tbody>
		    	</table>
		    </div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
	    <button id="simpan" type="buttom" class="btn btn-danger hidden"><i class='fa fa-save'></i> Simpan</button>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function () {
	var jtr="<?php echo $jtrans;?>"
	if(jtr){
		__get_list(jtr);
	}
	$('#kd_transaksi').on('change',function(){
		__get_list($(this).val());
	})
	__getKDAkun('');

	$('#tp_transaksi').on('change',function(){
		var kdt=$(this).val();
		kdt=(kdt)?kdt.toLowerCase():kdt;
		console.log(kdt);
		//$('div.akun input').val('');
		if(kdt){
			$('#tp_trans option.'+kdt).removeClass("hidden");
			$('#tp_trans option:not(.'+kdt+')').addClass("hidden");
			$('#tp_trans option.xx').removeClass("hidden");
		 }
	})
})

</script>