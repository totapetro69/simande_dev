<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_area_dealer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Area Dealer : <?php echo $list->message[0]->NAMA_AREADEALER; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Dealer</label>
            <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled">
                <option value="0">--Pilih Dealer--</option>
                <?php
                if ($dealer) {
                    if (is_array($dealer->message)) {
                        foreach ($dealer->message as $key => $value) {
                            $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                            $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                            echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->KD_DEALER . "</option>";
                        }
                    }
                }
                ?> 
            </select>
        </div>

        <div class="form-group">
            <label>Kode Area Dealer</label>
            <input type="text" name="kd_areadealer" id="kd_areadealer" class="form-control" value="<?php echo $list->message[0]->KD_AREADEALER; ?>" readonly maxlength="5" required>
        </div>

        <div class="form-group">
            <label>Nama Area Dealer</label>
            <input type="text" name="nama_areadealer" id="nama_areadealer" class="form-control" value="<?php echo $list->message[0]->NAMA_AREADEALER; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Status</label>
            <select name="row_status" class="form-control">
                <option value="<?php echo  $list->message[0]->ROW_STATUS; ?>"> <?php if ($list->message[0]->ROW_STATUS == 0) {
                    echo "Aktif";
                } else {
                    echo "Tidak Aktif";
                } ?> </option>
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

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e ?> submit-btn">Simpan</button>
    </div>

</form>