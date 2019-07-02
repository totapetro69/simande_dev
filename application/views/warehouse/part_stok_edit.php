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

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part1/update_part_stok/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Part Number : <?php echo $list->message[0]->PART_NUMBER; ?></h4>
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

        <div class="form-group" hidden>
            <label>ID</label>
            <input type="text" name="id" id="id" class="form-control" value="<?php echo $list->message[0]->ID; ?>" >
        </div>

        <div class="form-group">
            <label>Part Number</label>
            <input type="text" name="part_number" id="part_number" class="form-control" value="<?php echo $list->message[0]->PART_NUMBER; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Kode Gudang</label>
            <select class="form-control" id="kd_gudang" name="kd_gudang">
                <option value="">- Pilih Kode Gudang-</option>
                <?php
                if ($gudang):
                    foreach ($gudang->message as $key => $value) :
                        ?>
                        <option value="<?php echo $value->KD_GUDANG; ?>" <?php echo ($value->KD_GUDANG == $list->message[0]->KD_GUDANG ? "selected" : ""); ?> ><?php echo $value->KD_GUDANG; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Rak</label>
            <select class="form-control" id="kd_rak" name="kd_rak">
                <option value="">- Pilih Rak-</option>
                <?php
                if ($rakbin):
                    foreach ($rakbin->message as $key => $value) :
                        ?>
                        <option value="<?php echo $value->KD_RAK; ?>" <?php echo ($value->KD_RAK == $list->message[0]->KD_RAK ? "selected" : ""); ?> ><?php echo $value->KD_RAK; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kode Binbox</label>
            <select class="form-control" id="kd_binbox" name="kd_binbox">
                <option value="">- Pilih Binbox-</option>
                <?php
                if ($rakbin):
                    foreach ($rakbin->message as $key => $value) :
                        ?>
                        <option value="<?php echo $value->KD_BINBOX; ?>" <?php echo ($value->KD_BINBOX == $list->message[0]->KD_BINBOX ? "selected" : ""); ?> ><?php echo $value->KD_BINBOX; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>               

            </select>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" id="stok" class="form-control input-number" min="0" value="<?php echo $list->message[0]->STOK; ?>" placeholder="Masukkan Stock" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="row_status" class="form-control">
                <option value="<?php echo $list->message[0]->ROW_STATUS; ?>"> <?php
                    if ($list->message[0]->ROW_STATUS == 0) {
                        echo "Aktif";
                    } ELSE {
                        echo "Tidak Aktif";
                    }
                    ?> </option>
                <?php
                if ($list->message[0]->ROW_STATUS == -1) {
                    ?>
                    <option value="0">Aktif</option>
                    <?php
                } else {
                    ?>
                    <option value="-1">Tidak Aktif</option>
                    <?php
                }
                ?>
            </select>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e ?> submit-btn">Simpan</button>
    </div>

</form>