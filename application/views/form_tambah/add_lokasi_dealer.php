<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_lokasi_dealer_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Lokasi Dealer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Main Dealer</label>
                    <select class="form-control" id="kd_maindealer" name="kd_maindealer">
                        <option value="0">- Pilih Kode Main Dealer -</option>
                        <?php
                        if ($maindealers) {
                            if (is_array($maindealers->message)) {
                                foreach ($maindealers->message as $key => $value) {
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
                    <label>Kode Dealer</label>
                    <select class="form-control " id="kd_dealer" name="kd_dealer" <?php echo $status_n; ?>>
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
                    <input type="text" name="kd_lokasi" id="kd_lokasi" class="form-control" placeholder="Masukkan Kode Lokasi" maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Lokasi</label>
                    <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" placeholder="Masukkan Nama Lokasi" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" ></textarea>
                </div>

            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="col-xs-12 col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>Channel Dealer</label><br>
                        <input type="radio" name="chanel" class="radio-inline" value="1" /> Channel 
                        <br>
                        <input type="radio" name="chanel" class="radio-inline" value="0" /> Non Channel
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>
