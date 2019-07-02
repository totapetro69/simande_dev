<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<script type="text/javascript">

    function __getdata_warna(kd_item) {
        return true;
    }
</script>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('motor/update_grup_motor/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Grup : <?php echo  $list->message[0]->NAMA_GROUPMOTOR; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group">
            <label>Kode Grup Motor</label>
            <input type="text" name="kd_groupmotor" id="kd_groupmotor" class="form-control" value="<?php echo  $list->message[0]->KD_GROUPMOTOR; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Grup Motor</label>
            <input type="text" name="nama_groupmotor" id="nama_groupmotor" class="form-control" value="<?php echo  $list->message[0]->NAMA_GROUPMOTOR; ?>" required>
        </div>

        <div class="form-group">
            <label>Kode Tipe Motor</label>

<?php echo DropDownMotor(true, $list->message[0]->KD_TYPEMOTOR); ?>

        </div>

        <div class="form-group">
            <label>Kategori Motor</label>
            <select name="kd_category" class="form-control" required>
                <option value="">- Pilih Kategori Motor -</option>
                <?php
                if ($categories):

                    foreach ($categories->message as $key => $category) :
                        ?>
                        <option value="<?php echo  $category->KD_CATEGORY; ?>" <?php echo ($category->KD_CATEGORY == $list->message[0]->CATEGORY_MOTOR ? "selected" : ""); ?> ><?php echo  $category->NAMA_CATEGORY; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Segmen Motor</label>
            <select name="kd_segmen" class="form-control" required>
                <option value="">- Pilih Segmen Motor -</option>
                <?php
                if ($segment):
                    foreach ($segment->message as $key => $segmen) :
                        ?>
                        <option value="<?php echo  $segmen->KD_SEGMEN; ?>" <?php echo ($segmen->KD_SEGMEN == $list->message[0]->SEMBILAN_SEGMEN ? "selected" : ""); ?> ><?php echo  $segmen->NAMA_SEGMEN; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Series</label>
            <select name="kd_series" class="form-control" required>
                <option value="" >- Pilih Series Motor -</option>
                <?php
                if ($seriest):
                    foreach ($seriest->message as $key => $series) :
                        ?>
                        <option value="<?php echo  $series->KD_SERIES; ?>" <?php echo ($series->KD_SERIES == $list->message[0]->SERIES ? "selected" : ""); ?> ><?php echo  $series->NAMA_SERIES; ?></option>
                        <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>

