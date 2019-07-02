<?php
$defaultDealer = $this->session->userdata("kd_dealer");
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('dealer/update_activity/' . $list->message[0]->ID); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Aktivitas : <?php echo  $list->message[0]->NAMA_ACTIVITY; ?></h4>
    </div>

    <div class="modal-body">

      <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">

          <div class="form-group">
            <label>Dealer</label>
            <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
              <option value="0">--Pilih Dealer--</option>
              <?php
                if ($dealer) {
                  if (is_array($dealer->message)) {
                    foreach ($dealer->message as $key => $value) {
                      $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                      $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                      echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                    }
                  }
                }
              ?> 
            </select>
          </div>

          <div class="form-group">
            <label>Kode Activitas</label>
            <input type="text" name="kd_activity" id="kd_activity" class="form-control" value="<?php echo  $list->message[0]->KD_ACTIVITY; ?>" readonly maxlength="5" required>
          </div>

          <div class="form-group">
            <label>Nama Aktivitas</label>
            <input type="text" name="nama_activity" id="nama_activity" class="form-control" value="<?php echo  $list->message[0]->NAMA_ACTIVITY; ?>" required>
          </div>

          <div class="form-group">
            <label>Jenis Activity</label>
            <input type="text" name="jenis_activity" name="jenis_activity" class="form-control" value="<?php echo  $list->message[0]->JENIS_ACTIVITY; ?>" required>
          </div>

          <div class="form-group">
            <label>Tanggal Mulai</label>
              <div class="input-group input-append date" id="date">
                <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>

          <div class="form-group">
            <label>Tanggal Berakhir</label>
              <div class="input-group input-append date" id="date">
                <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>

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

    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
      <button id="submit-btn" type="submit" class="btn btn-danger <?php echo  $status_e ?> submit-btn">Simpan</button>
    </div>

</form>
