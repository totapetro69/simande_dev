<?php

$end_date=date('d/m/Y',strtotime('Last day of this month'));
$start_date=date('d/m/Y');
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Budget</h4>
</div>

<div class="modal-body">
   <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/update_budget/' . $list->message[0]->ID); ?>">
      <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" ><input type="hidden" name="kd_event" id="kd_event" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" >
      
      <!-- <div class="form-group">
        <label>Kode Event</label>
        <input type="text" name="kd_event" id="id" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" readonly>
      </div> -->

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
        <input type="text" style="text-transform:uppercase" name="kd_budget" id="kd_budget" class="form-control" value="<?php echo  $list->message[0]->KD_BUDGET; ?>" readonly>
      </div>

      <div class="form-group">
        <label>Nama Budget</label>
        <input type="text" name="nama_budget" id="nama_budget" class="form-control" value="<?php echo  $list->message[0]->NAMA_BUDGET; ?>">
      </div>

      <div class="form-group">
        <label>Jumlah Budget</label>
        <input type="number" name="jumlah_budget" id="jumlah_budget" class="form-control" value="<?php echo  $list->message[0]->JUMLAH_BUDGET; ?>" >
      </div>

      <div class="form-group">
        <label>Keterangan Budget</label>
        <textarea type="text" rows="1" name="keterangan_budget" id="keterangan_budget" class="form-control " placeholder="Masukkan Keterangan" ><?php echo $list->message[0]->KETERANGAN_BUDGET; ?></textarea>
      </div>

  </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
   
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
</script>