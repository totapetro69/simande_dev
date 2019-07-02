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
if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_lokasi = $value->KD_LOKASI;
            $nama_lokasi = $value->NAMA_LOKASI;
            $kd_dealer = $value->KD_DEALER;
            $kd_maindealer = $value->KD_MAINDEALER;
            $alamat = $value->ALAMAT;
            $chanel = $value->CHANEL;
            $defaults = $value->DEFAULTS;
        }
    }
}
?>
<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_lokasi_dealer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Lokasi Dealer : <?php echo  $list->message[0]->NAMA_LOKASI; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Main Dealer</label>
                    <select class="form-control disabled-action" id="kd_maindealer" name="kd_maindealer" readonly>
                        <option value="0">- Pilih Kode Main Dealer -</option>
                        <?php
                        if ($maindealers) {
                            if (is_array($maindealers->message)) {
                                foreach ($maindealers->message as $key => $value) {
                                    $aktif = ($defaultMainDealer == $value->KD_MAINDEALER) ? "selected" : "";
                                    $aktif = ($this->input->get("kd_maindealer") == $value->KD_MAINDEALER) ? "selected" : $aktif;
                                    echo "<option value='" . $value->KD_MAINDEALER . "' " . $aktif . ">" . $value->KD_MAINDEALER . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kode Dealer</label>
                    <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
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
                    <label>Kode Lokasi</label>
                    <input type="text" name="kd_lokasi" id="kd_lokasi" class="form-control disabled-action" value="<?php echo  $list->message[0]->KD_LOKASI; ?>" readonly maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" value="<?php echo  $list->message[0]->NAMA_LOKASI; ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" ><?php echo  $list->message[0]->ALAMAT; ?></textarea>
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Channel Dealer</label><br>
                        <input type="radio" name="chanel" class="radio-inline" <?php echo ($chanel == '1') ? 'checked' : '' ?> value="1" /> Channel 
                        <br>
                        <input type="radio" name="chanel" class="radio-inline" <?php echo ($chanel == '0') ? 'checked' : '' ?> value="0" /> Non Channel
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="row_status" class="form-control">
                        <option value="<?php echo  $list->message[0]->ROW_STATUS; ?>"> <?php
                            if ($list->message[0]->ROW_STATUS == 0) {
                                echo "Aktif";
                            } else {
                                echo "Tidak Aktif";
                            }
                            ?> </option>
                        <?php
                        if ($list->message[0]->ROW_STATUS == 0) {
                            ?>
                            <option value="-1">Tidak Aktif</option>
                            <?php
                        } else {
                            ?>
                            <option value="0">Aktif</option>
                            <?php
                        }
                        ?>
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
