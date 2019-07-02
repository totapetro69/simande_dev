<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_jenis_dealer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Rule Dealer : <?php echo $list->message[0]->NAMA_JENISDEALER; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Rule Dealer</label>
                    <input type="text" name="kd_jenisdealer" id="kd_jenisdealer" class="form-control" value="<?php echo  $list->message[0]->KD_JENISDEALER; ?>" readonly maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Rule Dealer</label>
                    <input type="text" name="nama_jenisdealer" id="nama_jenisdealer" class="form-control" value="<?php echo  $list->message[0]->NAMA_JENISDEALER; ?>" required>
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

            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>