<?php
	$jtrans=(isset($no_trans))?$no_trans:"";
	$usergroup=$this->session->userdata("kd_group");
	$defaultDealer=$this->session->userdata("kd_dealer");
	$no_jurnal="";$tgl_jurnal=date('d/m/Y');$status_jurnal="0";
	$deskripsi_jurnal="";$type_jurnal="JUM";$source="";$kd_trans="";
	if(isset($jurnal_h)){
		if($jurnal_h->totaldata >0){
			foreach ($jurnal_h->message as $key => $value) {
				$defaultDealer = $value->KD_DEALER;
				$no_jurnal	=( $value->NO_JURNAL=='NULL')?'': $value->NO_JURNAL;
				$tgl_jurnal	= TglFromSql($value->TGL_JURNAL);
				$deskripsi_jurnal = $value->DESKRIPSI_JURNAL;
				$type_jurnal = $value->TYPE_JURNAL;
				$status_jurnal = $value->CLOSING_STATUS;
				$source = $value->SOURCE_JURNAL;
				$kd_trans = $value->KD_TRANS;
			}
		}
	}
	$reproses=($no_jurnal!='' && $status_jurnal=="0" && $source!='')?"":"hidden";
	$additems =($status_jurnal=="0" && $deskripsi_jurnal!='')?"":"disabled-action";
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('finance/jurnal_simpan');?>" method="post">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <h4 class="modal-title" id="myModalLabel">Transaksi Jurnal </h4>
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-xs-6 col-sm-4 col-md-4">
				<div class="form-group">
					<label>Nama Dealer</label>
    				<select name="kd_dealer" id="kd_dealer" class="form-control" <?php echo($usergroup!=='0')?" disabled='disabled'":""?>">
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
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class="form-group">
	        		<label><span id="tpm">Tanggal</span></label>
	        		<div class="input-group append-group date">
                        <input type="text" class="form-control" id="tgl_jurnal" name="tgl_jurnal" value="<?php echo $tgl_jurnal;?>">
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span> </span>
                    </div>
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class="form-group">
	        		<label>Nomor Jurnal</label>
	        		<input type="text" name="no_jurnal" value='<?php echo $no_jurnal;?>' class="form-control disabled-action" id="no_jurnal" placeholder="Autogenerate">
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-2 col-sm-2">
	        	<div class="form-group">
	        		<br>
	        		<span id="ldg-repost" class="hidden"><i class="fa fa-spinner fa-spin" style="color:red"></i></span>
	        		<button type="button" onclick="__repost('<?php echo $source;?>','<?php echo $no_jurnal;?>','<?php echo $kd_trans;?>');" class="btn btn-default pull-right <?php echo $reproses;?>"><i class="fa fa-cog"></i> Re Proses</button>
	        	</div>
	        </div>
	        <div class="col-xs-12 col-md-6 col-sm-6">
	        	<div class="form-group">
	        		<label>Deskripsi Jurnal</label>
	        		<textarea class="form-control" name="deskripsi_jurnal" id="deskripsi_jurnal" placeholder="Deskripsi Jurnal" rows="2"><?php echo $deskripsi_jurnal;?></textarea>
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class='form-group'>
	        		<label>Type Jurnal</label>
	        		<div class="radio">
		        		<label>
		        			<input type="radio" value='JUM' <?php echo ($type_jurnal=='JUM')?"checked='true'":"";?> name="type_jurnal" id="type_jurnal_jum">Jurnal Umum (JUM)
		        		</label>
		        		<label>
		        			<input type="radio" value='JME' <?php echo ($type_jurnal=='JME')?"checked='true'":"";?> name="type_jurnal" id="type_jurnal_jme">Jurnal Memorial (JME)
		        		</label>
		        	</div>
	        	</div>
	        </div>
	        <div class="col-xs-6 col-md-3 col-sm-3">
	        	<div class="form-group">
	        		<br>
	        		
	        		<button type="button" id="tambah" class="btn btn-default pull-right <?php echo $additems;?>"><i class="fa fa-plus"></i> Add Item Jurnal</button>
	        	</div>
	        </div>
	    </div>
	    <div class="clearfix"></div>
	    
	    <!-- <hr> -->
	    <div class="row">
	    	<div class="table-responsive h250 col-xs-12 col-md-12 col-sm-12">
		    	<table class="table table-hover table-stripped table-bordered" id="lst">
		    		<thead>
		    			<tr>
		    				<th>No</th>
		    				<th>&nbsp;</th>
		    				<th>Kode Perkiraan</th>
		    				<th>Nama Perkiraan</th>
		    				<th>Debet</th>
		    				<th>Kredit</th>
		    				<th>&nbsp;</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			<?php
		    			$n=0;
		    				if(isset($jurnal_d)){
	                			if($jurnal_d->totaldata > 0){
	                				foreach ($jurnal_d->message as $key => $value) {
	                					$n++;
	                					$disabled=($status_jurnal==0)?"":"disabled-action";
	                					if($value->TP_TRANS=='1'){
		                					?>
		                						<tr>
		                							<td class="text-center table-nowarp"><?php echo $n;?></td>
		                							<td>
		                								<a onclick="__hapus_jurnal_detail('<?php echo $value->ID;?>','<?php echo $value->NO_JURNAL;?>')" id="xls_<?php echo $value->ID;?>" class="<?php echo $disabled;?>"><i class="fa fa-trash"></i></a>
		                							</td>
		                							<td class='text-left table-nowarp'><?php echo ($value->KD_AKUN);?></td>
		                							<td class='tb-overflow-50' title="<?php echo $value->KETERANGAN_JURNAL;?>"><?php echo $value->KETERANGAN_JURNAL;?></td>
		                							<td class='text-right table-nowarp'><?php echo ($value->DEBET==0)?'': number_format($value->DEBET,0);?></td>
		                							<td class='text-right table-nowarp'><?php echo ($value->KREDIT==0)?'':number_format($value->KREDIT,0);?></td>
		                							<td>&nbsp;</td>
		                						</tr>
		                					<?php
		                				}else{ ?>
		                						<tr class="total" style="font-weight: bold !important">
		                							<td colspan="4" class="text-right"><?php echo $value->KETERANGAN_JURNAL;?></td>
		                							<td class='text-right table-nowarp'><?php echo ($value->DEBET==0)?'':number_format($value->DEBET,0);?></td>
		                							<td class='text-right table-nowarp'><?php echo ($value->KREDIT==0)?'':number_format($value->KREDIT,0);?></td>
		                							<td>&nbsp;</td>
		                						</tr>
		                						<tr style="font-style: italic;">
		                							<td colspan="5" class="text-right"><em>Balance</em></td>
		                							<td class="text-right table-nowarp info"><?php echo ($value->BALANCE==0)?'':number_format($value->BALANCE,0);?></td>
		                							<td class="info"></td>
		                						</tr>
		                					<?php
		                				}
	                				}
	                			}
	                		}
		    			?>
		    		</tbody>
		    	</table>
		    </div>
	</div>
	<div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
	    <button id="simpan" type="button" class="btn btn-danger hidden"><i class='fa fa-save'></i> Simpan</button>
	</div>
	<div class="modalx" id="jurnal_item">
		<div class="modal-contentxx">
			<div class="modal-header">
				<h4 class="modal-title"><i class="fa fa-list-ul"></i> Add Item Jurnal</h4>
			</div>
			<div class="modal-body">
				<div class="row">
			        <div class="col-xs-6 col-md-12 col-sm-12">
			        	<div class="form-group">
			        		<label>No.Perkiraan</label>
			        		<input type="text" name="kd_akun" id="kd_akun" class="form-control">
			        		<input type="hidden" name="kd_akun_1" id="kd_akun_1" class="form-control">
			        		<input type="hidden" name="sub_akun" id="sub_akun" class="form-control">
			        	</div>
			        </div>
			        <div class="col-xs-12 col-md-12 col-sm-12">
			        	<div class="form-group">
			        		<label>Nama Perkiraan</label>
			        		<input type="text" name="nama_akun" id="nama_akun" class="form-control">
			        	</div>
			        </div>
			        <div class="col-xs-12 col-md-6 col-sm-6">
			        	<div class="form-group">
			        		<label>Jumlah</label>
			        		<input type="text" name="jml" id="jml" class="form-control">
			        	</div>
			        </div>
			        <div class="col-xs-12 col-md-6 col-sm-6">
			        	<div class="form-group">
			        		<label>Posisi</label>
			        		<select name="type_akun" class="form-control">
			        			<option value='D'>Debet</option>
			        			<option value="K">Kredit</option>
			        		</select>
			        	</div>
			        </div>
			        <div class="col-xs-12 col-sm-12 col-md-12">
			        	<div class="form-group">
			        		
      
			        	</div>
			        </div>

			    </div>
			</div>
			<br>
			<div class="modal-footer">
				<span id="ldg" class="hidden"><i class="fa fa-spinner fa-spin" style="font-size:36px;color:red"></i></span>
				<a class="btn btn-default" id="batal" onclick="keluar()" role="button"><i class="fa fa-close"></i> Batal</a>
				<a class="btn btn-info" id="simpan" onclick="__simpanItem();"  role="button"><i class="fa fa-save"></i> Simpan</a>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/perkiraan.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript">
$(document).ready(function () {
	
	$('#tambah').click(function(){
		$('#jurnal_item').attr("style","display:block")
	})
	__getKDAkun('');
	$('#jml').mask("#,##0",{reverse: true});
	$('#deskripsi_jurnal').on('focusout',function(){
		var isi = $(this).val();
		if(isi.length >0){
			$('#tambah').removeClass('disabled-action');
		}else{
			$('#tambah').addClass('disabled-action');
		}
	})
})

</script>
<script type="text/javascript">
	var path = window.location.pathname.split('/');
	var http = window.location.origin + '/' + path[1];
	  var modal = document.getElementById('jurnal_item');
	  // Get the button that opens the modal
	  //var btn = document.getElementById("myBtn");
	  // Get the <span> element that closes the modal
	  //var span = document.getElementsByClassName("closex")[0];
	  // When the user clicks on the button, open the modal 
	  // btn.onclick = function() {
	  //     modal.style.display = "block";
	  //     //document.getElementById('fade').style.display='block'
	  // }

	  // When the user clicks on <span> (x), close the modal
	  /*span.onclick = function() {
	      modal.style.display = "none";
	  }*/

	  function keluar(){
	    //$('#kd_fincom').prop('selectedIndex','0');
	    modal.style.display = "none";
	  }
	  

</script>
