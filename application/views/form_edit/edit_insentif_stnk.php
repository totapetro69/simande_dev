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
  <h4 class="modal-title" id="myModalLabel">Edit Master Insentif PIC STNK</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_insentif_stnk/'.$list->message[0]->ID);?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
	<input type="hidden" name="kd_config" id="kd_config" class="form-control" value="<?php echo  $list->message[0]->KD_CONFIG; ?>" >
   <div class="form-group">
    <label>Nama</label>
    <input id="name" readonly type="text" name="name" class="form-control" value="<?php echo $list->message[0]->NAMA_CONFIG;?>">
  </div>
  <div class="form-group">
    <label>Value</label>
    <input id="value_config" type="text" name="value_config" class="form-control" value="<?php echo $list->message[0]->VALUE_CONFIG;?>">
  </div>
 
  
</form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(e){

    $('#value')
    .focusout(function(){

    })
    .ForceNumericOnly()
   

  });

</script>