<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Barang</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/add_barang_setup_simpan'); ?>">
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
        <div class="form-group">
          <label>Barang</label>
          <select name="kd_barang" class="form-control" >
            <option value="">- Pilih Barang -</option>
            <?php if($barang && (is_array($barang->message) || is_object($barang->message))): foreach ($barang->message as $key => $value) : ?>
              <option value="<?php echo $value->KD_BARANG;?>"><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
       <div class="form-group">
        <label>Default Qty SJ</label>
        <input id="qty_default" type="text" name="qty_default" class="form-control">
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
