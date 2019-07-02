<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('company/update_mobil/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Mobil</h4>
</div>

<div class="modal-body">
	<input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
  <div class="form-group">
    <label>Nomor Polisi</label>
    <input id="no_polisi" type="text" name="no_polisi" class="form-control" value="<?php echo $list->message[0]->NO_POLISI;?>" readonly>
  </div>

  <div class="form-group">
    <label>Merek</label>
    <input type="text" name="merek" id="merek" class="form-control" value="<?php echo $list->message[0]->MEREK;?>"  required>
  </div>
  <div class="form-group">
			<label>Status</label>
			<select name="row_status" class="form-control">
			  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
			  <?php
			  if($list->message[0]->ROW_STATUS == -1){
			  ?>
			  <option value="0">Aktif</option>
			  <?php
			  }else{
			  ?>
			  <option value="-1">Tidak Aktif</option>
			  <?php
			  }
			  ?>
			</select>
		</div>        
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn">Simpan</button>
</div>

</form>
