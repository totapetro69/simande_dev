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
      <label>NIK</label>
      <select name="nik" class="form-control">
        <option value="" >- Pilih Karyawan -</option>
        <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
          <option value="<?php echo $value->NIK;?>" <?php echo ($value->NIK == $list->message[0]->NIK ? "selected" : "");?>><?php echo $value->NIK;?> - <?php echo $value->NAMA_MEKANIK;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>

    <div class="form-group">
      <label>Tanggal</label>
      <div class="input-group input-append date" id="date">
        <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?php echo ($list->message[0]->TANGGAL!='')?tglfromSql($list->message[0]->TANGGAL): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="status_karyawan" id="status_karyawan" class="form-control disabled-action" readonly>
        <option value="" >- Pilih Status -</option>
        <?php if($status && (is_array($status->message) || is_object($status->message))): foreach ($status->message as $key => $value) : ?>
          <option value="<?php echo $value->KD_STATUSABSENSI;?>" <?php echo ($value->KD_STATUSABSENSI == $list->message[0]->STATUS_KARYAWAN ? "selected" : "");?>><?php echo $value->KD_STATUSABSENSI;?> - <?php echo $value->NAMA_STATUSABSENSI;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>

    <?php
    if($list->message[0]->STATUS_KARYAWAN== "M"){
      ?>
      <div class="form-group">
        <label>Jam Masuk</label>
        <div class="input-group input-append datetime-mulai" id="datetime">
          <input type="text" class="form-control" id="jam_masuk" name="jam_masuk" value="<?php echo ($list->message[0]->JAM_MASUK!='')?$list->message[0]->JAM_MASUK: date('h/m/s');?>" placeholder="hh/mm/ss" />
          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
      </div>

      <div class="form-group">
        <label>Jam Pulang</label>
        <div class="input-group input-append datetime-pulang" id="datetime">
          <input type="text" class="form-control" id="jam_pulang" name="jam_pulang" value="<?php echo ($list->message[0]->JAM_PULANG!='')?$list->message[0]->JAM_PULANG: date('h/m/s');?>" placeholder="hh/mm/ss" />
          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
      </div>
      <?php
    }
    ?>

    <div class="form-group">
      <label>Keterangan</label>
      <textarea type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo $list->message[0]->KETERANGAN ; ?>" ><?php echo $list->message[0]->KETERANGAN; ?></textarea>
    </div>

    <div class="form-group">
      <label>Status</label>
      <select name="row_status" class="form-control">
        <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
        <?php
        if($list->message[0]->ROW_STATUS == -1){
          ?>
          <option value="0">Aktif</option>
          <?php
        }else{
          ?>
          <option value="-1">Tidak Aktif</option>
          <?php
        }
        ?>
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

