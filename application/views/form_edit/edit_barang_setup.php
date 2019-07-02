<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Barang : <?php echo $list->message[0]->KD_BARANG; ?></h4>
</div>

<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/update_barang_setup/' . $list->message[0]->ID); ?>">
      <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
        
    <div class="form-group">
      <label>Dealer</label>
      <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
        <option value="<?php echo $defaultDealer; ?>"><?php echo $dealer->message[0]->NAMA_DEALER;?></option>
        <!-- <?php
        if ($dealer) {
          if (is_array($dealer->message)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
              echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
            }
          }
        }
        ?>  -->
      </select>
    </div>
        <div class="form-group" id="kd_barang">
        <label>Barang</label>
        <select name="kd_barang" class="form-control" disabled="disabled">
          <option value="">- Pilih Barang -</option>
          <?php if($barang && (is_array($barang->message) || is_object($barang->message))): foreach ($barang->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_BARANG;?>" <?php echo ($value->KD_BARANG == $list->message[0]->KD_BARANG ? "selected" : "");?>><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?> - <?php echo $value->KATEGORI;?></option>
          <?php endforeach; endif;?>
        </select>
      </div>
     <div class="form-group">
        <label>Default Qty SJ</label>
        <input id="qty_default" type="text" name="qty_default" class="form-control" value="<?php echo $list->message[0]->QTY_DEFAULT;?>">
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

        $('#qty_default')
        .focusout(function(){

        })
        .ForceNumericOnly()

    });

</script>