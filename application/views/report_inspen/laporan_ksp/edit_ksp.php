<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('report_inspen/update_ksp/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Insentif KSP NIK : <?php echo $list->message[0]->NIK; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>No. Trans</label>
            <input type="text" name="no_trans" id="no_trans" class="form-control" value="<?php echo $list->message[0]->NO_TRANS; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Kode Maindealer</label>
            <input type="text" name="kd_maindealer" id="kd_maindealer" class="form-control" value="<?php echo $list->message[0]->KD_MAINDEALER; ?>" readonly=>
        </div>

        <div class="form-group">
            <label>Kode Dealer</label>
            <input type="text" name="kd_dealer" id="kd_dealer" class="form-control" value="<?php echo $list->message[0]->KD_DEALER; ?>" readonly>
        </div>

        <div class="form-group">
            <label>NIK</label>
            <input type="text" name="nik" id="nik" class="form-control" value="<?php echo $list->message[0]->NIK; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Periode Awal</label>
            <div class="input-group input-append date" id="datex">
                <input type="text" class="form-control" name="periode_awal" id="periode_awal" placeholder="dd/mm/yyyy" value="<?php echo tglfromSql($list->message[0]->PERIODE_AWAL); ?>" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Periode Akhir</label>
            <div class="input-group input-append date" id="datey">
                <input type="text" class="form-control" name="periode_akhir" id="periode_akhir" placeholder="dd/mm/yyyy" value="<?php echo tglfromSql($list->message[0]->PERIODE_AKHIR); ?>" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Tanggal Pengajuan</label>
            <div class="input-group input-append date" id="datez">
                <input type="text" class="form-control" name="tgl_pengajuan" id="tgl_pengajuan" placeholder="dd/mm/yyyy" value="<?php echo tglToSql($list->message[0]->TGL_PENGAJUAN); ?>" /> 
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group">
            <label>Total Sales</label>
            <input type="text" name="total_sales" id="total_sales" class="form-control" value="<?php echo $list->message[0]->TOTAL_SALES; ?>">
        </div>

        <div class="form-group">
            <label>Sales Tambah</label>
            <input type="text" name="sales_tambah" id="sales_tambah" class="form-control" value="<?php echo $list->message[0]->SALES_TAMBAH; ?>">
        </div>

        <div class="form-group">
            <label>Sales Kurang</label>
            <input type="text" name="sales_kurang" id="sales_kurang" class="form-control" value="<?php echo $list->message[0]->SALES_KURANG; ?>">
        </div>

        <div class="form-group">
            <label>RPK</label>
            <input type="text" name="rpk" id="rpk" class="form-control" value="<?php echo $list->message[0]->RPK; ?>">
        </div>

        <div class="form-group">
            <label>Margin Unit</label>
            <input type="text" name="margin_unit" id="margin_unit" class="form-control" value="<?php echo $list->message[0]->MARGIN_UNIT; ?>">
        </div>

        <div class="form-group">
            <label>Insentif Unit</label>
            <input type="text" name="insentif_unit" id="insentif_unit" class="form-control" value="<?php echo $list->message[0]->INSENTIF_UNIT; ?>">
        </div>

        <div class="form-group">
            <label>Total Insentif</label>
            <input type="text" name="total_insentif" id="total_insentif" class="form-control" value="<?php echo $list->message[0]->TOTAL_INSENTIF; ?>">
        </div>

        <div class="form-group">
            <label>Penalty</label>
            <input type="text" name="penalty" id="penalty" class="form-control" value="<?php echo $list->message[0]->PENALTY; ?>">
        </div>

        <div class="form-group">
            <label>PPH21</label>
            <input type="text" name="pph21" id="pph21" class="form-control" value="<?php $list->message[0]->PPH21; ?>">
        </div>

        <div class="form-group">
            <label>Insentif Terima</label>
            <input type="text" name="insentif_terima" id="insentif_terima" class="form-control" value="<?php $list->message[0]->INSENTIF_TERIMA; ?>">            
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