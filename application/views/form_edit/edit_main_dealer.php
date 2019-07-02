<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<?php
$kd_propinsi = "";
$kd_kabupaten = "";

if ($list) {
    if (is_array($list->message)) {
        foreach ($list->message as $key => $value) {
            $kd_propinsi = $value->KD_PROPINSI;
            $kd_kabupaten = $value->KD_KABUPATEN;
            
        }
    }
}
?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_main_dealer/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Main Dealer : <?php echo  $list->message[0]->NAMA_MAINDEALER; ?></h4>
    </div>

    <div class="modal-body">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12">

                <div class="form-group">
                    <label>Kode Main Dealer</label>
                    <input type="text" name="kd_maindealer" id="kd_maindealer" class="form-control" value="<?php echo  $list->message[0]->KD_MAINDEALER; ?>" readonly maxlength="5" required>
                </div>

                <div class="form-group">
                    <label>Nama Main Dealer</label>
                    <input type="text" name="nama_maindealer" id="nama_maindealer" class="form-control" value="<?php echo  $list->message[0]->NAMA_MAINDEALER; ?>" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea type="text" name="alamat" id="alamat" class="form-control" value="<?php echo  $list->message[0]->ALAMAT; ?>" required><?php echo  $list->message[0]->ALAMAT; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Propinsi</label>
                    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                        <option value="0">--Pilih Propinsi--</option>
                        <?php
                        /* var_dump($propinsi);
                          exit(); */
                        if ($propinsi) {
                            if (is_array($propinsi->message)) {
                                foreach ($propinsi->message as $key => $value) {
                                    $select = ($kd_propinsi == $value->KD_PROPINSI) ? "selected" : "";
                                    echo "<option value='" . $value->KD_PROPINSI . "' " . $select . ">" . $value->NAMA_PROPINSI . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                        <option value="0">--Pilih Kabupaten--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="text" name="telepon" id="telepon" class="form-control" value="<?php echo  $list->message[0]->TELEPON; ?>" required>
                </div>
				<div class="form-group">
			<label>Status</label>
			<select name="row_status" class="form-control">
			  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> </option>
			  <?php
			  if($list->message[0]->ROW_STATUS == 0){
			  ?>
			  <option value="-1">Tidak Aktif</option>
			  <?php
			  }else{
			  ?>
			  <option value="0">Aktif</option>
			  <?php
			  }
			  ?>
			</select>
		</div>

            </div>

        </div>

    </div>

    <script>
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
        })
        $('#kd_kabupaten').on('change', function () {
            loadData('kd_kecamatan', $(this).val(), '0')
        })
        loadData('kd_kabupaten', '<?php echo $kd_propinsi; ?>', '<?php echo $kd_kabupaten; ?>');
        
        function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>customer/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled','disabled');
        $.ajax({
            type: 'GET',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').empty();
                $('#' + id + '').html(result);
                $('#' + id + '').val(select).select();
                $('#l_' + param + '').html('');
                $('#' + id + '').removeAttr('disabled');
            }
        });
    }
    </script>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button id="submit-btn" type="submit" class="btn btn-danger  <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>
