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
  <h4 class="modal-title" id="myModalLabel">Edit Target SF</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('motor/update_typemotorm/'.$list->message[0]->ID);?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
        <label>Kode Tipe Motor Marketing</label>
        <input id="type_marketing" type="text" name="type_marketing" class="form-control" placeholder="Masukkan kode tipe motor marketing" maxlength="5" value="<?php echo $list->message[0]->TYPE_MARKETING;?>" required>
      </div>

      <div class="form-group">
        <label>Deskrpisi/ Varian</label>
        <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi/ varian" value="<?php echo $list->message[0]->DESKRIPSI;?>" required>
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

<script type="text/javascript">
	
	$(document).ready(function(e){
		
		$('#amount')
   .focusout(function(){

   })
   .ForceNumericOnly()
			/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po bulan ini'
		});*/
		
		//unsetSession(-1);
	});

</script>

