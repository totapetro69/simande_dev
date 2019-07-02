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
  <h4 class="modal-title" id="myModalLabel">Edit Minimal Value</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_minimal/'.$list->message[0]->ID);?>" method="post">
    <div class="form-group">
      <label>Nama Dealer</label>
      <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
        <option value="<?php echo $list->message[0]->KD_DEALER;?>"><?php echo $list->message[0]->NAMA_DEALER;?></option>
      </select>
    </div>
	<div class="form-group">
     <label>Kode Transaksi</label>
            <select name="kd_trans" class="form-control disabled-action" readonly>
         <option value="" >- Pilih Kode Transaksi -</option>
         <?php if($transaksi && (is_array($transaksi->message) || is_object($transaksi->message))): foreach ($transaksi->message as $key => $value) : ?>
           <option value="<?php echo $value->KD_TRANS;?>" <?php echo ($value->KD_TRANS == $list->message[0]->KD_TRANS ? "selected" : "");?>><?php echo $value->KD_TRANS;?> - <?php echo $value->NAMA_TRANS;?></option>
       <?php endforeach; endif;?>
   </select>
  </div>
  
  <div class="form-group">
      <label>Minimal Value</label>
      <input id="min_value" type="text" name="min_value" class="form-control" value="<?php echo round($list->message[0]->MIN_VALUE,0); ?>">
  </div>

    <!-- <div class="form-group">
      <label>Max Value</label>
      <input id="max_value" type="text" name="max_value" class="form-control" value="<?php echo round($list->message[0]->MAX_VALUE,0); ?>">
  </div> -->

    <div class="form-group">
      <label>Keterangan</label>
      <input id="keterangan" type="text" name="keterangan" class="form-control" value="<?php echo $list->message[0]->KETERANGAN;?>">
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
		
    $('#min_value')
   .focusout(function(){

   })
   .ForceNumericOnly()
   $('#max_value')
   .focusout(function(){

   })
   .ForceNumericOnly()
  });

</script>

