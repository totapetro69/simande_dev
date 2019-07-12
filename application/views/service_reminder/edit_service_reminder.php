<?php

if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action');
$status_e = (isBolehAkses('e') ? '' : 'disabled-action');
$status_v = (isBolehAkses('v') ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? '' : 'disabled-action');

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Service Reminder Schedule</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('service_reminder/update_service_reminder/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>">

    <div class="form-group">
      <label>Tgl Reminder</label>
      <input id="tgl_reminder" type="text" name="tgl_reminder" class="form-control" value="<?php echo tglfromSql($list->message[0]->TGL_REMINDER); ?>" >
    </div>    

    <div class="form-group">
      <label>Kode Dealer</label>
      <input id="kd_dealer" type="text" name="kd_dealer" class="form-control" value="<?php echo $defaultDealer = $this->session->userdata("kd_dealer"); ?>" placeholder="Kode dealer" readonly>
    </div>

    <div class="form-group">
      <label>Nama Customer</label>
      <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo $list->message[0]->NAMA_CUSTOMER; ?>" readonly>
    </div>

    <div class="form-group">
      <label>Kode Customer</label>
      <input id="kd_customer" type="text" name="kd_customer" class="form-control" value="<?php echo $list->message[0]->KD_CUSTOMER; ?>" readonly>
    </div>

    <div class="form-group">
      <label>No HP</label>
      <input id="no_hp" type="text" name="no_hp" class="form-control" value="<?php echo $list->message[0]->NO_HP; ?>">
    </div>

    <div class="form-group">
      <label>No Polisi</label>
      <input id="no_polisi" type="text" name="no_polisi" class="form-control" value="<?php echo $list->message[0]->NO_POLISI; ?>">
    </div>

    <div class="form-group">
      <label>Tipe Unit</label>
      <input id="kd_typemotor" type="text" name="kd_typemotor" class="form-control" value="<?php echo $list->message[0]->KD_TYPEMOTOR; ?>" readonly>
    </div>
    
    <div class="form-group">
      <label>No Mesin</label>
      <input id="no_mesin" type="text" name="no_mesin" class="form-control" value="<?php echo $list->message[0]->NO_MESIN; ?>" readonly>
    </div>

    <div class="form-group">
      <label>Tgl Service Terakhir</label>
      <div class="input-group input-append date" id="datepicker">
        <input id="tgl_lastservice" type="text" name="tgl_lastservice" class="form-control" required value="<?php echo  tglfromSql($list->message[0]->TGL_LASTSERVICE); ?>" readonly>
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>

    <div class="form-group">
      <label>Type Service Sebelumnya</label>
      <select name="type_lastservice" class="form-control" readonly>
        <option value="<?php echo $list->message[0]->TYPE_LASTSERVICE; ?>"> <?php echo $list->message[0]->TYPE_LASTSERVICE; ?> </option>
        <option value="KPB1">KPB1</option>
        <option value="KPB2">KPB2</option>
        <option value="KPB3">KPB3</option>
        <option value="KPB4">KPB4</option>
        <option value="NONKPB">NONKPB</option>
      </select>
    </div>

    <div class="form-group">
      <label>Tgl Service Berikutnya</label>
      <div class="input-group input-append date" id="datepicker">
        <input id="tgl_nextservice" type="text" name="tgl_nextservice" class="form-control" required value="<?php echo  tglfromSql($list->message[0]->TGL_NEXTSERVICE); ?>">
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>

    <div class="form-group">
      <label>Type Service Berikutnya</label>
      <select name="type_nextservice" class="form-control">
        <option value="<?php echo $list->message[0]->TYPE_NEXTSERVICE; ?>"> <?php echo $list->message[0]->TYPE_NEXTSERVICE; ?> </option>
        <option value="KPB1">KPB1</option>
        <option value="KPB2">KPB2</option>
        <option value="KPB3">KPB3</option>
        <option value="KPB4">KPB4</option>
        <option value="NONKPB">NONKPB</option>
      </select>
    </div>

    <div class="form-group">
      <label>Status SMS </label>
      <select id="status_sms" name="status_sms" class="form-control">
        <option value="<?php echo $list->message[0]->STATUS_SMS; ?>"> <?php echo $list->message[0]->STATUS_SMS; ?> </option>
        <option value="Tidak Terkirim">Tidak Terkirim</option>
        <option value="Terkirim">Terkirim</option>
      </select>
    </div>

    <div class="form-group">
      <label>Status Call </label>
      <select id="status_call" name="status_call" class="form-control">
        <option value="<?php echo $list->message[0]->STATUS_CALL; ?>"> <?php echo $list->message[0]->STATUS_CALL; ?> </option>
        <option value="Terhubung">Terhubung</option>
        <option value="Salah Sambung">Salah Sambung</option>
        <option value="Nomor Salah">Nomor Salah</option>
        <option value="Nomor Tidak Terdaftar">Nomor Tidak Terdaftar</option>
        <option value="Telepon Ditolak">Telepon Ditolak</option>
        <option value="Telepon Tidak diangkat">Telepon Tidak diangkat</option>
        <option value="Dialihkan">Dialihkan</option>
        <option value="Tidak Aktif">Tidak Aktif</option>
        <option value="Di Luar Jangkauan">Di Luar Jangkauan</option>
      </select>
    </div>

    <div class="form-group">
      <label>Booking Status </label>
      <select id="booking_status" name="booking_status" class="form-control">
        <option value="<?php echo $list->message[0]->BOOKING_STATUS; ?>"> <?php echo $list->message[0]->BOOKING_STATUS; ?> </option>
        <option value="No">No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>

    <div class="form-group">
      <label>Alasan TIdak Booking</label>
      <input id="alasan" type="text" name="alasan" class="form-control" value="<?php echo $list->message[0]->ALASAN; ?>">
    </div>

    <div class="form-group">
      <label>Reschedule</label>
      <div class="input-group input-append date" id="datepicker">
        <input id="reschedule" type="text" name="reschedule" class="form-control" required value="<?php echo  tglfromSql($list->message[0]->RESCHEDULE); ?>">
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>


  </form>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">

    $(function() {
        $('#tgl_lastservice').datepicker({
            format: 'dd/mm/yyyy'
        });
        $('#tgl_nextservice').datepicker();
        $('#tgl_reminder').datepicker();
    });

</script>