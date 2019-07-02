<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_perusahaan_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Perusahaan Baru</h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Dealer</label>
            <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
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
            <label>Kode Perusahaan</label>
            <input type="text" name="kd_company" id="kd_company" class="form-control" placeholder="Masukkan Kode Perusahaan">
        </div>

        <div class="form-group">
            <label>Nama Perusahaan</label>
            <input type="text" name="nama_company" id="nama_company" class="form-control" placeholder="Masukkan Nama Perusahaan" required>
        </div>

        <div class="form-group">
            <label>Pimpinan Dealer</label>
            <select class="form-control" id="nik" name="nik" required>
                <option value="">- Pilih Pimpinan Dealer -</option>
                    <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
                      <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA;?></option>
                    <?php endforeach; endif;?>
                  </select>
        </div>


        <div class="form-group">
            <label>Kepala Gudang</label>
            <select class="form-control" id="nama" name="nama" required>
                <option value="" >- Pilih Kepala Gudang -</option>
                    <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
                      <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA;?></option>
                    <?php endforeach; endif;?>
            </select>
        </div>
        
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>