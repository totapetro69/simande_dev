<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Bundling</h4>
</div>

<div class="modal-body">

	<form id="addForm" class="bucket-form" action="<?php echo base_url('motor/add_bundling_simpan');?>" method="post">
	  <div class="form-group">
				<label>Dealer</label>
				<select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
					<option value="0">--Pilih Dealer--</option>
					<?php
						if ($dealer) {
						 if (is_array($dealer->message)) {
							foreach ($dealer->message as $key => $value) {
							 $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
							 $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
						echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
									}
								}
							}
						?> 
					</select>
		</div>
	  <div class="form-group">
		<label>Kode</label>
		<input id="kd_bundling" type="text" name="kd_bundling" class="form-control" placeholder="Masukkan kode bundling" >
	  </div>

	  <div class="form-group">
		  <label>Nama Bundling</label>
		  <input id="nama_bundling" type="text" name="nama_bundling" class="form-control" placeholder="Masukkan nama bundling">
	  </div>
	  
	  <div class="form-group">
		   <label>Tanggal Mulai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
		</div>
	  <div class="form-group">
		   <label>Tanggal Selesai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
	   </div>
	   
	</form>


</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
