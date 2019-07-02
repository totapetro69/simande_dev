<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Jenis Kelamin : <?php echo $list->message[0]->NAMA_GENDER; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/update_gender/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode Kelamin</label>
            <input type="text" name="kd_gender" id="kd_gender" class="form-control" value="<?php echo  $list->message[0]->KD_GENDER; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Jenis Kelamin</label>
            <input type="text" name="nama_gender" id="nama_gender" class="form-control" value="<?php echo  $list->message[0]->NAMA_GENDER; ?>" >
        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>