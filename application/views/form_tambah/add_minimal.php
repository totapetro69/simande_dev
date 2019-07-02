<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Minimal Value</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_minimal_simpan');?>" method="post">
    <div class="form-group">
      <label>Nama Dealer</label>
      <select class="form-control" id="kd_dealer" name="kd_dealer">
        <option value="0">--Pilih Dealer--</option>
        <?php
        if ($dealer) {
          if (($dealer->totaldata > 0)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
            }
          }
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label>Kode Transaksi</label>
            <select class="form-control" name="kd_trans" id="kd_trans">
                <option value="" >- Pilih Kode Transaksi -</option>
         <?php if($transaksi && (is_array($transaksi->message) || is_object($transaksi->message))): foreach ($transaksi->message as $key => $value) : ?>
           <option value="<?php echo $value->KD_TRANS;?>"><?php echo $value->KD_TRANS;?> - <?php echo $value->NAMA_TRANS;?></option>
       <?php endforeach; endif;?>
   </select>
    </div>
   <div class="form-group">
    <label>Min Value</label>
    <input id="min_value" type="text" name="min_value" class="form-control" placeholder="0">
  </div>
  <!-- <div class="form-group">
    <label>Max Value</label>
    <input id="max_value" type="text" name="max_value" class="form-control" placeholder="0">
  </div> -->
  <div class="form-group">
      <label>Keterangan</label>
      <input id="keterangan" type="text" name="keterangan" class="form-control" placeholder="Masukkan nama diskon">
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


