<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('master_service/add_absensi_mekanik_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Input Absensi</h4>
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
    <label>NIK</label>
    <select name="nik" class="form-control" required>
    <option value="" >- Pilih Karyawan -</option>
      <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
      <option value="<?php echo $value->NIK;?>"><?php echo $value->NIK;?> - <?php echo $value->NAMA_MEKANIK;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
  <div class="form-group">
    <label class="control-label" for="date">Tanggal</label>
    <div class="input-group input-append date">
      <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
  </div>
  <div class="form-group">
    <label>Status</label>
    <select name="status_karyawan" id="status_karyawan" class="form-control" required>
      <option value="" >- Pilih Status -</option>
      <?php if(isset($status)){
        if($status->totaldata>0){
          foreach ($status->message as $key => $value) {
            echo "<option value='".$value->KD_STATUSABSENSI."'>".$value->KD_STATUSABSENSI." - ".$value->NAMA_STATUSABSENSI."</option>";
          }
        }
      }
      ?>
    </select>
  </div>
  <div class="form-group" style='display:none;' id="jam_masuk">
    <label>Jam Masuk</label>
    <div class="input-group input-append datetime-mulai" id="datetime">
        <input class="form-control" id="jam_masuk" name="jam_masuk" placeholder="HH:MM:" type="text">
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
    </div>
  </div>

  <div class="form-group" style='display:none;' id="jam_pulang">
    <label>Jam Pulang</label>
    <div class="input-group input-append datetime-selesai" id="datetime">
        <input class="form-control" id="jam_pulang" name="jam_pulang" placeholder="HH:MM:" type="text">
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
    </div>
  </div>

  <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan"></textarea>
  </div>
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>

<script type="text/javascript">

    var date = new Date();
    date.setDate(date.getDate());
    
    $('.datetime-mulai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

     $('.datetime-selesai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

    $('#status_karyawan').on('change', function() {
    if ( this.value == 'M')
      {
        $("#jam_masuk").show();
        $("#jam_pulang").hide();
      }else{
        $("#jam_masuk").hide();
        $("#jam_pulang").hide();
      }
  });

    /*$('.datetime').datetimepicker({
      format:'dd/MM/yyyy',
      autoclose: true,
      startDate: date,
      minView: 2,
      maxView: 4
    });*/

    /*$(".date").datetimepicker({
        format:'dd/MM/yyyy',
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });*/

</script>








