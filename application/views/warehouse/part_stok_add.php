<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>
<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part1/simpan_part_stok'); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambahkan Part Stock</h4>
    </div>

    <div class="modal-body">

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
            <label>Nomor Part <span id="fd"></span></label>
            <input id="part_number" type="text" name="part_number" class="form-control" autocomplete="off" placeholder="Masukkan Nomor Part Atau Nama Part">
        </div>

        <div class="form-group">
            <label>Kode Gudang</label>
            <select class="form-control" id="kd_gudang" name="kd_gudang">
                <option value="">- Pilih Kode Gudang-</option>
                <?php
                if ($gudang) {
                    if (is_array($gudang->message)) {
                        foreach ($gudang->message as $key => $value) {
                            echo "<option value='" . $value->KD_GUDANG . "'>" . $value->KD_GUDANG . "</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Rak</label>
            <select class="form-control" id="kd_rak" name="kd_rak">
                <option value="">- Pilih Rak-</option>
                <?php
                if ($rakbin) {
                    if (is_array($rakbin->message)) {
                        foreach ($rakbin->message as $key => $value) {
                            echo "<option value='" . $value->KD_RAK . "'>" . $value->KD_RAK . "</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Binbox</label>
            <select class="form-control" id="kd_binbox" name="kd_binbox">
                <option value="">- Pilih Binbox-</option>
                <?php
                if ($rakbin) {
                    if (is_array($rakbin->message)) {
                        foreach ($rakbin->message as $key => $value) {
                            echo "<option value='" . $value->KD_BINBOX . "'>" . $value->KD_BINBOX . "</option>";
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" id="stok" class="form-control input-number" min="0" placeholder="Masukkan Stock" required>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
    </div>

</form>
<script type="text/javascript">
    $(document).ready(function (e) {

        $("#part_number").typeahead({
            source: function (query, process) {
                $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                return $.get('<?php echo base_url("Part1/part_typeahead"); ?>', {keyword: query}, function (data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    $('#fd').html('');
                    return process(data.keyword);
                })
            },
            minLength: 3,
            limit: 20
        });

    });

</script>