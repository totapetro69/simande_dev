<?php

$defaultDealer =($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Sales</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/update_people/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <input type="hidden" name="kd_event" id="kd_event" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" >


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
        <label>Kode Sales</label>
        <input type="text" name="kd_sales" id="kd_sales" class="form-control" value="<?php echo  $list->message[0]->KD_SALES; ?>" readonly>
      </div>

      <div class="form-group">
        <label>Nama Sales</label>
        <input type="text" name="nama_sales" id="nama_sales" class="form-control" value="<?php echo  $list->message[0]->NAMA_SALES; ?>" readonly>
      </div>

      <div class="form-group">
        <label>JABATAN_SALES</label>
        <select name="jabatan_sales" id="jabatan_sales" class="form-control">
          <option value="">--Pilih Jabatan--</option>
          <option value="PIC" <?php echo($list->message[0]->JABATAN_SALES=="PIC")?"selected":"";?>>PIC</option>
          <option value="Sales Jaga" <?php echo($list->message[0]->JABATAN_SALES=="Sales Jaga")?"selected":"";?>>Sales Jaga</option>
        </select>
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