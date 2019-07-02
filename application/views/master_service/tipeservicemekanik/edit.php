<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Tipe Service Mekanik : <?php echo $list->message[0]->NAMA_TIPESERVICEMEKANIK; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_tipeservicemekanik/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode </label>
            <input type="text" name="kd_tipeservicemekanik" id="kd_tipeservicemekanik" class="form-control" value="<?php echo  $list->message[0]->KD_TIPESERVICEMEKANIK; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_tipeservicemekanik" id="nama_tipeservicemekanik" class="form-control" value="<?php echo  $list->message[0]->NAMA_TIPESERVICEMEKANIK; ?>" >
        </div>
		<div class="form-group">
			<label>Status</label>
			<select name="row_status" class="form-control">
				  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> </option>
				  <?php
				  if($list->message[0]->ROW_STATUS == 0){
				  ?>
				  <option value="-1">Tidak Aktif</option>
				  <?php
				  }else{
				  ?>
				  <option value="0">Aktif</option>
				  <?php
				  }
				  ?>
			</select>
		</div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>