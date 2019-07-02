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

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Mekanik</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/add_mekanik_simpan'); ?>">

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
        <?php
        if($user->message[0]->KD_JENISDEALER == 'Y'){
            ?>
            <div class="form-group">
              <label>NIK</label>
              <select name="nik" class="form-control">
                <option value="">- Pilih Mekanik -</option>
                <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
                  <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA;?></option>
              <?php endforeach; endif;?>
          </select>
      </div>
      <?php
  }else{
    ?>
    <div class="form-group">
        <label>NIK</label>
        <input type="text" name="nik" id="nik" class="form-control" placeholder="Masukkan NIK" >
    </div>

    <div class="form-group">
        <label>Nama Mekanin</label>
        <input type="text" name="nama_mekanik" id="nama_mekanin" class="form-control" placeholder="Masukkan nama mekanik" >
    </div>        

    <?php
}
?>
<div class="form-group">
    <label>Honda ID</label>
    <input type="text" name="honda_id" id="honda_id" class="form-control">
</div>
<div class="form-group">
    <label>Tipe PKB</label><br>
    <?php if($tipe_pkb && (is_array($tipe_pkb->message) || is_object($tipe_pkb->message))): foreach ($tipe_pkb->message as $key => $value) : ?>
      <input type="checkbox" name="tipe_pkb[]" value="<?php echo $value->KD_TIPEPKB;?>"><?php echo $value->KD_TIPEPKB;?> - <?php echo $value->NAMA_TIPEPKB;?><br>
  <?php endforeach; endif;?>
</div>
</form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>