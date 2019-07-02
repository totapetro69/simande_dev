<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<form id="addFormedit" class="bucket-form" method="post" action="<?php echo base_url('pkb/update_pkb_detail/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit <?php echo $list->message[0]->KD_PEKERJAAN; ?></h4>
    </div>

    <div class="modal-body">

        <div class="form-group" hidden>
            <label>ID</label>
            <input type="text" name="id" id="id" class="form-control " value="<?php echo  $list->message[0]->ID; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Uraian Pekerjaan</label>
            <input type="text" name="kd_pekerjaan" id="kd_pekerjaan" class="form-control" value="<?php echo  $list->message[0]->KD_PEKERJAAN; ?>" readonly>
        </div>
        
        <div class="form-group">
            <label>No. PKB</label>
            <input type="text" name="no_pkb" id="no_pkb" class="form-control" value="<?php echo  $list->message[0]->NO_PKB; ?>" readonly>
        </div>
        
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo  $list->message[0]->KATEGORI; ?>">
        </div>
        
        <div class="form-group">
            <label>QTY</label>
            <input type="number" name="qty" id="qty" class="form-control" min="1" value="<?php echo  $list->message[0]->QTY; ?>" >
        </div>
        
        <div class="form-group">
            <label>Harga Satuan</label>
            <input type="text" name="harga_satuan" id="harga_satuan" class="form-control qurency" value="<?php echo number_format($list->message[0]->HARGA_SATUAN,0); ?>" >
        </div>
        
       
		
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>

<script type="text/javascript">
    $(document).ready(function () {

        $('.qurency').mask('000.000.000.000.000', {reverse: true});

        
})
</script>