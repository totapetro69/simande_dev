<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('motor/update_segmen_motor/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Segmen Motor : <?php echo $list->message[0]->NAMA_SEGMEN; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Segmen Motor</label>
            <input type="text" name="kd_segmen" id="kd_segmen" class="form-control" value="<?php echo  $list->message[0]->KD_SEGMEN; ?>" readonly maxlength="5" required>
        </div>

        <div class="form-group">
            <label>Nama Segmen Motor</label>
            <input type="text" name="nama_segmen" id="nama_segmen" class="form-control" value="<?php echo  $list->message[0]->NAMA_SEGMEN; ?>" required>
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
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>