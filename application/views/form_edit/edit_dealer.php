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
    <h4 class="modal-title" id="myModalLabel">Detail Dealer : <?php echo $list->message[0]->NAMA_DEALER; ?></h4>
</div>

<div class="modal-body">

    <form class="bucket-form" method="get">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Dealer</label>
                    <input type="text" name="kd_dealer" value="<?php echo $list->message[0]->KD_DEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kode Dealer AHM</label>
                    <input type="text" name="kd_dealerahm" value="<?php echo $list->message[0]->KD_DEALERAHM; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Nama Dealer</label>
                    <input type="text" name="nama_dealer" value="<?php echo $list->message[0]->NAMA_DEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Tlp-1</label>
                    <input type="text" name="tlp" value="<?php echo $list->message[0]->TLP; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Tlp-2</label>
                    <input type="text" name="tlp2" value="<?php echo $list->message[0]->TLP2; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Tlp-3</label>
                    <input type="text" name="tlp3" value="<?php echo $list->message[0]->TLP3; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" name="alamat" value="<?php echo $list->message[0]->ALAMAT; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Jenis Dealer</label>
                    <input type="text" name="kd_jenisdealer" value="<?php echo $list->message[0]->KD_JENISDEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kode Status Dealer</label>
                    <input type="text" name="kd_statusdealer" value="<?php echo $list->message[0]->KD_STATUSDEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kabupaten</label>
                    <input type="text" name="kd_kabupaten" value="<?php echo $list->message[0]->KD_KABUPATEN; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Provinsi</label>
                    <input type="text" name="kd_propinsi" value="<?php echo $list->message[0]->KD_PROPINSI; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Rule Dealer</label>
                    <input type="text" name="rule_dealer" value="<?php echo $list->message[0]->RULE_DEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kode Main Dealer</label>
                    <input type="text" name="kd_maindealer" value="<?php echo $list->message[0]->KD_MAINDEALER; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Jumlah Pit</label>
                    <input type="text" name="jumlah_pit" value="<?php echo $list->message[0]->JUMLAH_PIT; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger <?php echo  $status_e ?> hidden">Simpan</button>
</div>
