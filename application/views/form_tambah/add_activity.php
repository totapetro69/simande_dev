<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_activity_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Aktivitas</h4>
</div>

<div class="modal-body">
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

  <div class="form-group">
    <label>Kode Aktivitas</label>
    <input id="kd_activity" type="text" name="kd_activity" class="form-control" placeholder="Masukkan Kode Aktivitas" maxlength="5" required=>
  </div>

  <div class="form-group">
    <label>Nama Aktivitas</label>
    <input type="text" name="nama_activity" id="nama_activity" class="form-control" placeholder="Masukkan Nama Aktivitas" required>
  </div>

  <div class="form-group">
    <label>Jenis Aktivitas</label>
    <input type="text" name="jenis_activity" id="jenis_activity" class="form-control" placeholder="Masukkan Jenis Aktivitas" required>
  </div>

  <div class="form-group">
    <label class="control-label" for="date">Tanggal Mulai</label>
    <div class="input-group input-append date">
      <input class="form-control" name="start_date" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
  </div>


  <div class="form-group">
    <label class="control-label" for="date">Tanggal Berakhir</label>
    <div class="input-group input-append date">
      <input class="form-control" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
  </div>
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>


