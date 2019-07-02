<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Input Budget</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/add_budget_simpan');?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Kode Event</label>
      <input type="text" name="kd_event" id="kd_event" class="form-control" value="<?php echo $list->message[0]->KD_EVENT; ?>" readonly>
    </div>
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
    <div class="form-group">
      <label>Kode Budget Event</label>
      <input type="text" style="text-transform:uppercase" name="kd_budget" id="kd_budget" class="form-control" placeholder="Kode Budget">
    </div>
    
    <div class="form-group">
      <label>Nama Budget Event</label>
      <input type="text" name="nama_budget" id="nama_budget" class="form-control" placeholder="Nama Budget">
    </div>
    
    <div class="form-group">
      <label>Jumlah Budget Event</label>
      <input type="number" name="jumlah_budget" id="jumlah_budget" class="form-control" placeholder="Jumlah Budget">
    </div>
    
    <div class="form-group">
      <label>Keterangan Budget Event</label>
      <input type="text" name="keterangan_budget" id="keterangan_budget" class="form-control" placeholder="Keterangan Budget">
    </div>

  </form>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
