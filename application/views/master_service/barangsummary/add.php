<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('master_service/simpan_barang_summary');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah</h4>
</div>


<div class="modal-body">
    <div class="form-group">
        <label>Main Dealer</label>
            <select class="form-control" id="kd_maindealer" name="kd_maindealer" disabled="disabled" required>
                <option value="0">--Pilih Main Dealer-</option>
                <?php
                if ($maindealer) {
                    if (is_array($maindealer->message)) {
                        foreach ($maindealer->message as $key => $value) {
                            $aktif = ($defaultMainDealer == $value->KD_MAINDEALER) ? "selected" : "";
                            $aktif = ($this->input->get("kd_maindealer") == $value->KD_MAINDEALER) ? "selected" : $aktif;
                            echo "<option value='" . $value->KD_MAINDEALER . "' " . $aktif . ">" . $value->NAMA_MAINDEALER . "</option>";
                        }
                    }
                }
                ?> 
            </select>
    </div>

    <div class="form-group">
        <label>Dealer</label>
        <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
            <option value="0">--Pilih Dealer--</option>
            <?php
            if ($dealer) {
            if (is_array($dealer->message)) {
                foreach ($dealer->message as $key => $value) {
                 $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                }
            }
            }
        ?> 
        </select>
    </div>

    <div class="form-group">
        <label>ID Part</label>
        <input id="id_part" type="text" name="id_part" class="form-control" placeholder="0">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input id="harga_beli" type="text" name="harga_beli" class="form-control" placeholder="0">
    </div>

    <!--<div class="form-group">
    <label>Harga Jual</label>
    <input id="harga_jual" type="text" name="harga_jual" class="form-control" placeholder="0">
  </div>-->

  <div class="form-group">
    <label>Harga Jual - Diskon</label>
     <select name="harga_jual" id="harga_jual" class="form-control">
        <option value="" >- Pilih -</option>
            <?php if($hargaparts && (is_array($hargaparts->message) || is_object($hargaparts->message))): foreach ($hargaparts->message as $key => $value) : ?>
        <option value="<?php echo $value->HARGA_JUAL;?>"><?php echo $value->HARGA_JUAL;?> - <?php echo $value->DISKON;?></option>
            <?php endforeach; endif;?>
    </select>
  </div>

    
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
