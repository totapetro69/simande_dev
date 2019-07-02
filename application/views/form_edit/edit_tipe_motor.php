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
    <h4 class="modal-title" id="myModalLabel">Detail Motor : <?php echo $list->message[0]->NAMA_ITEM; ?></h4>
</div>

<div class="modal-body">

    <form class="bucket-form" method="get">

        <div class="row">

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Tipe Motor</label>
                    <input type="text"  name="kd_typemotor" value="<?php echo $list->message[0]->KD_TYPEMOTOR; ?>" readonly="true" required class="form-control" placeholder="Masukkan Kode Tipe Motor" >
                </div>

                <div class="form-group">
                    <label>Nama Tipe Motor</label>
                    <input type="text" name="nama_typemotor" value="<?php echo $list->message[0]->NAMA_TYPEMOTOR; ?>" readonly="true" required class="form-control" placeholder="Masukkan Nama Tipe Motor" >
                </div>

                <div class="form-group">
                    <label>Kode Warna</label>
                    <input type="text" name="kd_warna" value="<?php echo $list->message[0]->KD_WARNA; ?>" readonly="true" required class="form-control" placeholder="Masukkan Kode Warna" >
                </div>

                <div class="form-group">
                    <label>Keterangan Warna Motor</label>
                    <input type="text" name="ket_warna" value="<?php echo $list->message[0]->KET_WARNA; ?>" readonly="true" required class="form-control" placeholder="Masukkan Keterangan Warna Motor" >
                </div>

                <div class="form-group">
                    <label>Nama Pasar</label>
                    <input type="text" name="nama_pasar" value="<?php echo $list->message[0]->NAMA_PASAR; ?>" readonly="true" required class="form-control" placeholder="Masukkan Nama Pasar" >
                </div>

                <div class="form-group">
                    <label>CC Motor</label>
                    <input type="text" name="cc_motor" value="<?php echo $list->message[0]->CC_MOTOR; ?>" readonly="true" required class="form-control" placeholder="Masukkan CC Motor" >
                </div>

            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">

                <div class="form-group">
                    <label>Kode Item</label>
                    <input type="text" name="kd_item" value="<?php echo $list->message[0]->KD_ITEM; ?>" readonly="true" required class="form-control" placeholder="Masukkan Kode Item" >
                </div>

                <div class="form-group">
                    <label>Nama Item</label>
                    <input type="text" name="nama_item" value="<?php echo $list->message[0]->NAMA_ITEM; ?>" readonly="true" required class="form-control" placeholder="Masukkan Nama Item" >
                </div>

                <div class="form-group">
                    <label>Jenis Motor</label>
                    <input type="text" name="jenis_motor" value="<?php echo $list->message[0]->JENIS_MOTOR; ?>" readonly="true" required class="form-control" placeholder="Masukkan Jenis Motor" >
                </div>

                <div class="form-group">
                    <label>Tgl Awal Efektif</label>
                    <input type="date" name="tgl_awaleff" value="<?php echo $list->message[0]->TGL_AWALEFF; ?>" readonly="true" required class="form-control" placeholder="Masukkan Tanggal Awal Efektif" >
                </div>

                <div class="form-group">
                    <label>Tgl Akhir Efektif</label>
                    <input type="date" name="tgl_akhireff" value="<?php echo $list->message[0]->TGL_AKHIREFF; ?>" readonly="true" required class="form-control" placeholder="Masukkan Tanggal Akhir Efektif" >
                </div>
                <div class="form-group">
                    <label>CBU</label>
                    <input type="text" name="cbu" value="<?php echo $list->message[0]->CBU; ?>" readonly="true" required class="form-control" placeholder="Tipe CBU" >
                </div>
            </div>

        </div>

    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
    <button type="button" class="btn btn-danger <?php echo  $status_e ?> hidden">Simpan</button>
</div>
