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

<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/update_budget');?>" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Create Event</h4>
  </div>

  <div class="modal-body">
    <!-- 1 -->
    <div class="row">

      <!-- <div class="col-sm-3">
        <div class="form-group">
          <label>Kode Event</label>
          <input type="text" name="kd_event" id="kd_event" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" readonly>
        </div>
      </div> -->

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
          <label>Kode Budget Event</label>
          <input type="text" style="text-transform:uppercase" name="kd_budget" id="kd_budget" class="form-control" placeholder="Kode Budget">
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Nama Budget</label>
          <input type="text" name="nama_budget" id="nama_budget" class="form-control" placeholder="Nama Budget">
        </div>
      </div>
    </div>


    <!-- 2 -->
    <div class="row">
      
      <div class="col-sm-3">
        <div class="form-group">
          <label>Jumlah Budget</label>
          <input type="text" name="jumlah_budget" id="jumlah_budget" class="form-control" placeholder="0" >
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label>Keterangan Budget</label>
          <textarea type="text" rows="1" name="keterangan_budget" id="keterangan_budget" class="form-control" placeholder="Masukkan Keterangan"></textarea>
        </div>
      </div>

    </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>

</form>

<script type="text/javascript">
   
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
</script>