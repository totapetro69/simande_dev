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
    <h4 class="modal-title" id="myModalLabel">Edit Setup Reminder</h4>
</div>

<div class="modal-body">
    
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('service_reminder/update_setupreminder/' . $list->message[0]->ID); ?>">
    	<input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
            <label>Type Service</label>
         <select name="type_srv_next" class="form-control">
            <option value="<?php echo $list->message[0]->TYPE_SRV_NEXT;?>"> <?php echo $list->message[0]->TYPE_SRV_NEXT;?> </option>
             <option value="KPB1">KPB1</option>
             <option value="KPB2">KPB2</option>
             <option value="KPB3">KPB3</option>
             <option value="KPB4">KPB4</option>
             <option value="NONKPB">NONKPB</option>
         </select>
        </div>
        <div class="form-group">
            <label>Hari -x reminder</label>
            <input type="text" name="tgl_srv_reminder" id="tgl_srv_reminder" class="form-control" value="<?php echo  $list->message[0]->TGL_SRV_REMINDER; ?>" >
        </div>
        <div class="form-group">
            <label>Hari Jatuh tempo Service</label>
            <input type="text" name="tgl_srv_next" id="tgl_srv_next" class="form-control" value="<?php echo  $list->message[0]->TGL_SRV_NEXT; ?>">
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