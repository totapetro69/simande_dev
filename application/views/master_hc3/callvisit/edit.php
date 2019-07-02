<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Status</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_hc3/update_callvisit/' . $list->message[0]->ID); ?>">
    	<input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
            <label>Kategori</label>
          <select name="kategori" class="form-control">
          	<option value="<?php echo $list->message[0]->KATEGORI;?>"> <?php echo $list->message[0]->KATEGORI;?> </option>
             <option value="SMS">SMS</option>
             <option value="CALL">CALL</option>
             <option value="Visit">Visit</option>
             <option value="Direct Touch">Direct Touch</option>
         </select>
        </div>
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" id="status" class="form-control" value="<?php echo  $list->message[0]->STATUS; ?>" >
        </div>
        <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo  $list->message[0]->KETERANGAN; ?>">
        </div>
        <div class="form-group">
            <label>Klasifikasi</label>
            <input type="text" name="klasifikasi" id="klasifikasi" class="form-control" value="<?php echo  $list->message[0]->KLASIFIKASI; ?>">
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