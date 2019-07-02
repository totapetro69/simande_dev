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

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('motor/update_bundling/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Bundling : <?php echo $list->message[0]->NAMA_BUNDLING; ?></h4>
    </div>

    <div class="modal-body">
		<div class="form-group">
				<label>Dealer</label>
				<select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
					<?php if($dealer && (is_array($dealer->message) || is_object($dealer->message))): foreach ($dealer->message as $key => $value) : ?>
					  <option value="<?php echo $value->KD_DEALER;?>" <?php echo ($value->KD_DEALER == $list->message[0]->KD_DEALER ? "selected" : "");?>><?php echo $value->NAMA_DEALER;?></option>
					  <?php endforeach; endif;?>
					</select>
		</div>

        <div class="form-group">
            <label>Kode Bundling</label>
            <input type="text" name="kd_bundling" id="kd_bundling" class="form-control" value="<?php echo $list->message[0]->KD_BUNDLING; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Bundling</label>
            <input type="text" name="nama_bundling" id="nama_bundling" class="form-control" value="<?php echo $list->message[0]->NAMA_BUNDLING; ?>" required>
        </div>
		<div class="form-group">
			   <label>Tanggal Mulai</label>
			   <div class="input-group input-append date" id="date">
				   <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
				   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			   </div>
		</div>
		  <div class="form-group">
			   <label>Tanggal Selesai</label>
			   <div class="input-group input-append date" id="date">
				   <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
				   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
			   </div>
		   </div>
		   <div class="form-group">
			<label>Status</label>
			<select name="row_status" class="form-control">
			  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
			  <?php
			  if($list->message[0]->ROW_STATUS == -1){
			  ?>
			  <option value="0">Aktif</option>
			  <?php
			  }else{
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

