<?php

if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}
$defaultDealer = $this->session->userdata("kd_dealer");

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");

?>

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_absensi_mekanik/' . $list->message[0]->ID); ?>">
  <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Absensi Mekanik : <?php echo $list->message[0]->NIK; ?></h4>
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
    <label>ID Customer</label>
    <input type="text" class="form-control" id="id_customer" placeholder="ID Customer">
  </div>

  <div class="form-group">
    <label for="nama_customer">Nama Customer</label>
    <input type="text" class="form-control" id="nama_customer" placeholder="Nama Customer">
  </div>

  <div class="form-group">
    <label for="nama_customer">No HP</label>
    <input type="text" class="form-control" id="no_hp" placeholder="No HP">
  </div>

  <div class="form-group">
      <label>Kode Type Unit</label>
      <select id="row_status" name="row_status" class="form-control">
          <option value="0">--Pilih Type Unit--</option>
          <option value="1">Unit 1</option>
          <option value="1">Unit 2</option>
          <option value="1">Unit 3</option>
          <option value="1">Unit 4</option>
      </select>
  </div> 

  <div class="form-group">
    <label for="no_polisi">No Polisi</label>
    <input type="text" class="form-control" id="no_polisi" placeholder="No Polisi">
  </div>

  <div class="form-group">
      <label>Type Service Terakhir</label>
      <select id="row_status" name="row_status" class="form-control">
          <option value="0">--Pilih Type Service--</option>
          <option value="1">KPB1</option>
          <option value="1">KPB2</option>
          <option value="1">KPB3</option>
          <option value="1">KPB4</option>
      </select>
  </div>  

  <div class="form-group">
    <label class="control-label" for="date">Tanggal Service Terakhir</label>
    <div class="input-group input-append date">
      <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
  </div>

  <div class="form-group">
      <label>Type Service Berikutnya</label>
      <select id="row_status" name="row_status" class="form-control">
          <option value="0">--Pilih Type Service--</option>
          <option value="1">KPB1</option>
          <option value="1">KPB2</option>
          <option value="1">KPB3</option>
          <option value="1">KPB4</option>
      </select>
  </div>  

  <div class="form-group">
    <label class="control-label" for="date">Tanggal Service Berikutnya</label>
    <div class="input-group input-append date">
      <input class="form-control" name="tanggal" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
    </div>
  </div>

  <div class="form-group">
      <label>Status SMS</label>
      <select id="row_status" name="row_status" class="form-control">
          <option value="0">--Pilih Status SMS--</option>
          <option value="1">Tidak Terkirim</option>
          <option value="1">Terkirim</option>
      </select>
  </div>

  <div class="form-group">
      <label>Status Call</label>
      <select id="row_status" name="row_status" class="form-control">
          <option value="0">--Pilih Status Call--</option>
          <option value="1">Terhubung</option>
          <option value="2">Salah Sambung</option>
          <option value="3">Nomor Salah</option>
          <option value="4">Nomor Tidak terdaftar</option>
          <option value="5">Telepon ditolak</option>
          <option value="6">Telepon tidak diangkat</option>
          <option value="7">Dialihkan</option>
          <option value="8">Tidak Aktif</option>
          <option value="9">Diluar jangkauan</option>
      </select>
  </div>
 
</div>


  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn">Simpan</button>
  </div>
</form>

<script type="text/javascript">

  $('.datetime-mulai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

  $('.datetime-pulang').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

  $('#status_karyawan').on('change', function() {
    if ( this.value == 'M')
    {
      $("#jam_masuk").show();
      $("#jam_pulang").show();
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

