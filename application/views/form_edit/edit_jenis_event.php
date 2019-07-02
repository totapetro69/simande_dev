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
<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/jenis_event_update/' . $list->message[0]->ID); ?>">
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
          <label>Kode Jenis</label>
          <input type="text" style="text-transform:uppercase" name="kd_jenis_event" id="kd_jenis_event" class="form-control" value="<?php echo  $list->message[0]->KD_JENIS_EVENT; ?>" >
        </div>
      </div>

      <div class="col-sm-3">  
        <div class="form-group">
          <label>Nama Jenis Event</label>
          <input type="text" name="nama_jenis_event" id="nama_jenis_event" class="form-control" value="<?php echo  $list->message[0]->NAMA_JENIS_EVENT; ?>" >
        </div>
      </div>

    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>
</form>