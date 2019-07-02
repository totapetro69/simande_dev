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
    <h4 class="modal-title" id="myModalLabel">Detail Kelurahan / Desa : </h4>
</div>

<div class="modal-body">

    <form class="bucket-form" method="get">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Kecamatan</label>
                    <input type="text"  name="kd_kecamatan" value="<?php echo $list->message[0]->KD_KECAMATAN; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kode Desa</label>
                    <input type="text" name="kd_desa" value="<?php echo $list->message[0]->KD_DESA; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Nama Desa</label>
                    <input type="text" name="nama_desa" value="<?php echo $list->message[0]->NAMA_DESA; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Kode Desa AHM</label>
                    <input type="text" name="kd_desaahm" value="<?php echo $list->message[0]->KD_DESAAHM; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

                <div class="form-group">
                    <label>Nama Desa AHM</label>
                    <input type="text" name="desaahm" value="<?php echo $list->message[0]->DESAAHM; ?>" readonly="true" required class="form-control" placeholder="-" >
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger <?php echo  $status_e ?> hidden">Simpan</button>
</div>
