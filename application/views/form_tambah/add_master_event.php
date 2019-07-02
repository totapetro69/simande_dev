<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/add_master_event_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Event</h4>
</div>

<div class="modal-body">
<!-- 1 -->
  <div class="row">
    <div class="col-sm-3">
        <div class="form-group">
          <label>Dealer</label>
    <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
      <option value="0">--Pilih Dealer--</option>
      <?php
        if ($dealer) {
          if (is_array($dealer->message)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
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
          <input type="text" class="form-control" id="id_event" autocomplete="off" name="id_event" placeholder="AUTO NUMBER"  readonly="true">
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" id="nama_event" name="nama_event" class="form-control" placeholder="Nama Event" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Jenis Event</label>
          <select class="form-control" id="jenis_event" name="jenis_event" required="true">
            <option value="" >- Pilih Jenis Event -</option>
            <?php if($jevent && (is_array($jevent->message) || is_object($jevent->message))): foreach ($jevent->message as $key => $value) : ?>
              <option value="<?php echo $value->NAMA_JENIS;?>"><?php echo $value->NAMA_JENIS;?> - <?php echo $value->KD_JENIS;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
      </div>

    </div>

<!-- 2 -->
<div class="row">
  <div class="col-sm-3">
        <div class="form-group">
          <label>Deskripsi Event</label>
          <input type="text-area" id="desc_event" name="desc_event" class="form-control" placeholder="Deskripsi Event" >
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
            <label>Unit Target</label>
          <input type="text" id="unit_target" name="unit_target" class="form-control" placeholder="0" >
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Revenue Target</label>
          <input type="text" id="revenue_target" name="revenue_target" class="form-control" placeholder="0" >
        </div>
      </div>
      
   <div class="col-sm-3">
    <div class="form-group">
      <label class="control-label" for="date">Tanggal Mulai</label>
      <div class="input-group input-append date" id="datepicker">
        <input class="form-control" name="start_date" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>
  </div>

    </div>

<!-- 3 -->
<div class="row">
  <div class="col-sm-3">
    <div class="form-group">
      <label class="control-label" for="date">Tanggal Berakhir</label>
      <div class="input-group input-append date">
        <input class="form-control" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>
  </div>


      <div class="col-sm-3">
        <div class="form-group">
          <label>Lokasi</label>
          <input type="text" id="loc_event" name="loc_event" class="form-control" placeholder="-" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Budget Event</label>
          <input type="text" id="budget_event" name="budget_event" class="form-control" placeholder="0" >
        </div>
      </div>

      <!-- <div class="col-sm-3">
        <div class="form-group">
         <label>Unit to Display</label>
          <input type="text" id="unit_to_display" name="unit_to_display" class="form-control" placeholder="Unit to Display" >
        </div>
      </div> -->

    </div>
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>


