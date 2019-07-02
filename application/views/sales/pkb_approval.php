<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('pkb/update_pkb_approval/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Approval No. PKB: <?php echo $list->message[0]->NO_PKB; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group" hidden>
            <label>ID</label>
            <input type="text" name="id" id="id" class="form-control" value="<?= $list->message[0]->ID; ?>" >
        </div>

        <div class="form-group">
            <label>Approve PKB ?</label>
            <select name="status_approval" id="status_approval" class="form-control">
                <option <?php echo ($list->message[0]->STATUS_APPROVAL == 0 ? "selected" : ""); ?> value="0">-- Pilih Approval PKB --</option>
                <option <?php echo ($list->message[0]->STATUS_APPROVAL == 1 ? "selected" : ""); ?> value="1">Ya</option>
                <option <?php echo ($list->message[0]->STATUS_APPROVAL == 2 ? "selected" : ""); ?> value="2">Tidak</option>
            </select>
        </div>
        
        <div class="form-group hidden">
            <input type="text" name="status_pkb" id="status_pkb" class="form-control" placeholder="" value="<?= $list->message[0]->STATUS_PKB; ?>">
            <input type="text" name="final_confirmation" id="final_confirmation" class="form-control" placeholder="" value="<?= $list->message[0]->FINAL_CONFIRMATION; ?>">
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e ?> submit-btn">Simpan</button>
    </div>

</form>