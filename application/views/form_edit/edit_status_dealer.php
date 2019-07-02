<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_status_dealer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Jenis Dealer : <?php echo $list->message[0]->NAMA_STATUSDEALER; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Jenis Dealer</label>
                    <input type="text" name="kd_statusdealer" id="kd_statusdealer" class="form-control" value="<?php echo  $list->message[0]->KD_STATUSDEALER; ?>" readonly maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Jenis Dealer</label>
                    <input type="text" name="nama_statusdealer" id="nama_statusdealer" class="form-control" value="<?php echo  $list->message[0]->NAMA_STATUSDEALER; ?>" required>
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