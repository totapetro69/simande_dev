<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");

if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_lokasidealer = $value->KD_LOKASIDEALER;
            $kd_gudang = $value->KD_GUDANG;
            $nama_gudang = $value->NAMA_GUDANG;
            $kd_dealer = $value->KD_DEALER;
            $alamat = $value->ALAMAT;
            $defaults = $value->DEFAULTS;
            $row_status = $value->ROW_STATUS;
        }
    }
    $defaultgudang=($gudang==1 && $defaults=='0')?'disabled-action':"";
}
?>
<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_gudang/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Gudang : <?php echo  $list->message[0]->NAMA_GUDANG; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row">

          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
              <label>Kode Dealer</label>
              <select class="form-control" id="kd_dealer" name="kd_dealer" readonly>
                <option value="0">--Pilih Dealer--</option>
                  <?php
                  if ($dealer) {
                    if (is_array($dealer->message)) {
                      foreach ($dealer->message as $key => $value) {
                        $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                        $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                        echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                      }
                    }
                  }
                ?> 
              </select>
            </div>

            <div class="form-group">
                <label>Kode Lokasi Dealer</label>
                <select class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
                    <option value="0">--Pilih Lokasi Dealer--</option>
                       <?php
                          if ($lokasidealer) {
                            if (is_array($lokasidealer->message)) {
                              foreach ($lokasidealer->message as $key => $value) {
                                $aktif = ($this->input->get("kd_lokasidealer") == $value->KD_LOKASI) ? "selected" :"";
                                $aktif = ($kd_lokasidealer == $value->KD_LOKASI) ? "selected" :  $aktif;
                                 echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                              }
                            }
                          }
                      ?>  
                </select>
            </div>

            <div class="form-group">
              <label>Kode Gudang</label>
              <input type="text" name="kd_gudang" id="kd_gudang" class="form-control disabled-action" value="<?php echo $kd_gudang; ?>" readonly maxlength="5" required>
            </div>

            <div class="form-group">
              <label>Nama Gudang</label>
              <input type="text" name="nama_gudang" id="nama_gudang" class="form-control" value="<?php echo $nama_gudang; ?>" required>
            </div>

            <div class="form-group">
              <label>Alamat</label>
              <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat"><?php echo $alamat; ?></textarea>
            </div>

            <div class="form-group">
              <label>Jenis Gudang</label>
              <select name="jenis_gudang" class="form-control">
                <option value="<?php echo $list->message[0]->JENIS_GUDANG;?>"> <?php if($list->message[0]->JENIS_GUDANG == 'Part'){echo "PART"; }else{ echo "UNIT"; }?> </option>
                <option value="Part">PART</option>
                <option value="Unit">UNIT</option>
              </select>
            </div>
              <div class="form-group">
                <label>Status Gudang</label>
                <select id="row_status" name="row_status" class="form-control">
                  <option value="0" <?php echo ($row_status=="0")?"selected":"";?>>Aktif</option>
                  <option value="-1" <?php echo ((int)$row_status<0)?"selected":"";?>>Tidak Aktif</option>
                </select>
              </div>
              <div class="form-group">
                  <label>Sebagai Gudang Default</label>
                  <select class="form-control <?php echo $defaultgudang;?>" id="defaults" name="defaults">
                      <option value="0" <?php echo ($defaults=="0")?"selected":"";?>>Tidak</option>
                      <option value="1" <?php echo ($defaults=="1")?"selected":"";?>>Ya</option>
                  </select>
              </div>
       
          </div>

        </div>

    </div>

  </div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
</div>

</form>
