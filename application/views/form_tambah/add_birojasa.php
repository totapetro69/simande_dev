<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('birojasa/add_birojasa_simpan'); ?>" method="post">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Biro Jasa</h4>
    </div>

    <div class="modal-body">

        <input id="kd_maindealer" type="hidden" name="kd_maindealer" value="<?php echo $this->session->userdata('kd_maindealer'); ?>">

        <div class="row">

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                      <label>Dealer</label>
                      <select class="form-control" id="kd_dealer" name="kd_dealer">
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
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Kode Biro Jasa</label>
                    <input id="kd_birojasa" type="text" name="kd_birojasa" class="form-control uppercaseform" placeholder="kode biro jasa" required style="text-transform:uppercase">
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Nama Biro Jasa</label>
                    <input id="nama_birojasa" type="text" name="nama_birojasa" class="form-control uppercaseform" placeholder="nama biro jasa" required style="text-transform:uppercase" >
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <div class="form-group">
                    <label>Nama Pengurus</label>
                    <input type="text" name="nama_pengurus" id="nama_pengurus" class="form-control uppercaseform" placeholder="nama pengurus" required style="text-transform:uppercase" >
                </div>
            </div>

            <div class="col-xs-12 col-md-12">
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea rows="5" name="alamat" class="form-control" placeholder="masukan alamat" required></textarea>
                </div>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>


<script type="text/javascript">
    $(document).ready(function () {

        $(".uppercaseform").keyup(function () {
            $('.uppercaseform').val(function () {
                return this.value.toUpperCase();
            })
        });

    });
</script>

