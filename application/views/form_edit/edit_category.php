<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('motor/update_category/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Kategori Motor : <?php echo $list->message[0]->NAMA_CATEGORY;?></h4>
</div>

<div class="modal-body">

  <div class="form-group">
    <label>Kode Kategori Motor</label>
    <input id="kd_category" type="text" name="kd_category" class="form-control" value="<?php echo $list->message[0]->KD_CATEGORY;?>" readonly>
  </div>

  <div class="form-group">
    <label>Nama Kategori Motor</label>
    <input type="text" name="nama_category" id="nama_category" class="form-control" value="<?php echo $list->message[0]->NAMA_CATEGORY;?>"  required>
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
