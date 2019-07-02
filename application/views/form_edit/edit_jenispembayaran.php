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
    <h4 class="modal-title" id="myModalLabel">Edit Jenis Pembayaran : <?php echo $list->message[0]->NAMA_JENISPEMBAYARAN; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/update_jenispembayaran/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode Jenis Pembayaran</label>
            <input type="text" name="kd_jenispembayaran" id="kd_jenispembayaran" class="form-control" value="<?php echo  $list->message[0]->KD_JENISPEMBAYARAN; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Jenis Pembayaran</label>
            <input type="text" name="nama_jenispembayaran" id="nama_jenispembayaran" class="form-control" value="<?php echo  $list->message[0]->NAMA_JENISPEMBAYARAN; ?>" >
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
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>