<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$end_date=date('d/m/Y',strtotime('Last day of this month'));
$start_date=date('d/m/Y');
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/master_event_update/' . $list->message[0]->ID); ?>">
  <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Ubah Event</h4>
  </div>

  <div class="modal-body">
    <!-- 1 -->
    <div class="row">
      <div class="col-sm-3">
      <div class="form-group">
          <label>Dealer</label>
          <select class="form-control" id="kd_dealer" name="kd_dealer" disabled>
            <option value="0">--Pilih Dealer--</option>
            <?php
            if ($dealer) {
              if (($dealer->totaldata > 0)) {
                foreach ($dealer->message as $key => $value) {
                  $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                  echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Event ID</label>
          <select class="form-control" id="id_event" name="id_event" required="true"  disabled="true">
            <option value="" >- Pilih Event ID -</option>
            <?php
            if (isset($event)) {
              if (($event->totaldata)) {
                foreach ($event->message as $key => $value) {
                  $select=($list->message[0]->ID_EVENT == $value->ID_EVENT)?"selected":"";
                  echo "<option value='" . $value->ID_EVENT . "' ".$select.">" . $value->ID_EVENT . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">  
        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" name="nama_event" id="nama_event" class="form-control" value="<?php echo  $list->message[0]->NAMA_EVENT; ?>" >
        </div>
      </div>

    </div>

    <!-- 2 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Jenis Event</label>
          <select class="form-control" id="jenis_event" name="jenis_event" required="true" >
            <option value="" >- Pilih Jenis Event -</option>
            <?php
            if (isset($jevent)) {
              if (($jevent->totaldata)) {
                foreach ($jevent->message as $key => $value) {
                  $select=($list->message[0]->JENIS_EVENT == $value->NAMA_JENIS)?"selected":"";
                  echo "<option value='" . $value->NAMA_JENIS . "' ".$select.">" . $value->NAMA_JENIS . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Unit Target</label>
          <input type="text" name="unit_target" id="unit_target" class="form-control" value="<?php echo  $list->message[0]->UNIT_TARGET; ?>" >
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Revenue Target</label>
          <input type="text" name="revenue_target" id="revenue_target" class="form-control" value="<?php echo  $list->message[0]->REVENUE_TARGET; ?>" >
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Budget Event</label>
          <input type="text" name="budget_event" id="budget_event" class="form-control" value="<?php echo  $list->message[0]->BUDGET_EVENT; ?>" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Lokasi</label>
          <input type="text" name="loc_event" id="loc_event" class="form-control" value="<?php echo  $list->message[0]->LOC_EVENT; ?>" >
        </div>
      </div>

    </div>

    <!-- 3 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Tanggal Mulai</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE)?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Tanggal Selesai</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE)?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="form-group">
          <label>Deskripsi Event</label>
          <textarea type="text" rows="1" name="desc_event" id="desc_event" class="form-control " placeholder="Masukkan Deskripsi" ><?php echo $list->message[0]->DESC_EVENT; ?></textarea>
        </div>
      </div>

    </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>
</form>