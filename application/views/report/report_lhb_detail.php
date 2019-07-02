<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Detail Laporan : <?php echo $list->message[0]->NO_PKB; ?></h4>
</div>

<div class="modal-body">

    <form class="bucket-form" method="get">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>NO. PKB</label>
                    <input type="text"  name="no_pkb" value="<?php echo $list->message[0]->NO_PKB; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Kode SA</label>
                    <input type="text"  name="kd_sa" value="<?php echo $list->message[0]->KD_SA; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Tipe</label>
                    <input type="text"  name="kd_tipepkb" value="<?php echo $list->message[0]->KD_TIPEPKB; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>NO. Polisi</label>
                    <input type="text"  name="no_polisi" value="<?php echo $list->message[0]->NO_POLISI; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Tipe Motor</label>
                    <input type="text"  name="kd_typemotor" value="<?php echo $list->message[0]->NAMA_TYPEMOTOR; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Nama Customer</label>
                    <input type="text"  name="nama_comingcustomer" value="<?php echo $list->message[0]->NAMA_COMINGCUSTOMER; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Nama Mekanik</label>
                    <input type="text"  name="nama" value="<?php echo $list->message[0]->NAMA; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Jenis Pit</label>
                    <input type="text"  name="jenis_pit" value="<?php echo $list->message[0]->JENIS_PIT; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text"  name="kd_typemotor" value="<?php echo $list->message[0]->KETERANGAN; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <input type="text"  name="kd_typecomingcustomer" value="<?php echo $list->message[0]->KD_TYPECOMINGCUSTOMER; ?>" readonly="true" required class="form-control" placeholder="" >
                </div>

            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
</div>
