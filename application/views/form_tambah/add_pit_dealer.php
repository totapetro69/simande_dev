<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_pit_dealer_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Pit Dealer Baru</h4>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Nama Dealer</label>
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
                    <label>Kode Pit</label>
                    <input type="text" name="kd_pit" id="kd_pit" class="form-control" placeholder="Masukkan Kode Pit" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label>Jenis Pit</label>
                    <select name="jenis_pit" class="form-control">
                      <option value="">- Pilih Jenis Pit -
                      </option>
                      <?php if($jenispit && (is_array($jenispit->message) || is_object($jenispit->message))): foreach ($jenispit->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_JENISPIT;?>"><?php echo $value->KD_JENISPIT;?> - <?php echo $value->NAMA_JENISPIT;?>
                        </option>
                    <?php endforeach; endif;?>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Pit</label>
                <input type="text" name="nama_pit" id="nama_pit" class="form-control" placeholder="Masukkan Nama Pit" required>
            </div>
            <div class="form-group">
                <label>Urutan</label>
                <input id="urutan" type="text" name="urutan" class="form-control">
              </div>

        </div>

    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn ">Simpan</button>
</div>

</form>

