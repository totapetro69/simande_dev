<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$NO_TRANS = "";$KD_CUSTOMER = "";$NO_RANGKA = "";$NO_MESIN = "";
$TGL_PEMBELIAN = "";$NAMA_CUSTOMER = "";$NO_HP = "";$ALAMAT_SURAT = "";
$KELURAHAN = "";$KECAMATAN = "";$KOTA = "";$KODE_POS = "";$PROPINSI = "";
$JENIS_KPB = "";$KD_MOTOR = "";$TGL_TRANS = "";$KD_METODEFU = "";
$NAMA_METODEFU = "";$TGL_METODEFU = "";$KD_STATUS_METODEFU = "";$STATUS_METODEFU = "";
$HASIL_METODEFU = "";$KD_METODEFU2 = "";$NAMA_METODEFU2 = "";$TGL_METODEFU2 = "";
$KD_STATUS_METODEFU2 = "";$STATUS_METODEFU2 = "";$HASIL_METODEFU2 = "";$STATUS_BOOKING = "";$ALASAN_BOOKING = ""; $STATUS_FU="";

if(isset($list)){
  if($list->totaldata >0){
    foreach ($list->message as $key => $value) {
      $NO_TRANS = $value->NO_TRANS;//
      $TGL_TRANS = tglfromSql($value->TGL_TRANS);

      $JENIS_KPB = $value->JENIS_KPB;//

      $KD_CUSTOMER = $value->KD_CUSTOMER;//
      $KD_MOTOR = $value->KD_MOTOR;//
      // $HONDA_ID = $value->;
      $NO_RANGKA = $value->NO_RANGKA;
      $NO_MESIN = $value->NO_MESIN;
      $TGL_PEMBELIAN = tglfromSql($value->TGL_BELI);
      $NAMA_CUSTOMER = ucwords($value->NAMA_STNK);
      $ALAMAT_SURAT = ucwords(strtolower($value->ALAMAT_SURAT));
      $NO_HP = $value->NO_HP;
      
      $KELURAHAN = $value->KELURAHAN_SURAT;
      $KECAMATAN = $value->KECAMATAN_SURAT;
      $KOTA = $value->KOTA_SURAT;
      $KODE_POS = $value->KODE_POS;
      $PROPINSI = $value->PROPINSI_SURAT;

      //DETAIL
      $KD_METODEFU = $value->KD_METODEFU;
      $NAMA_METODEFU = $value->NAMA_METODEFU;
      $TGL_METODEFU = tglfromSql($value->TGL_METODEFU);
      $KD_STATUS_METODEFU = $value->KD_STATUS_METODEFU;
      $STATUS_METODEFU = $value->STATUS_METODEFU;
      $HASIL_METODEFU = $value->HASIL_METODEFU;

      $KD_METODEFU2 = $value->KD_METODEFU2;
      $NAMA_METODEFU2 = $value->NAMA_METODEFU2;
      $TGL_METODEFU2 = tglfromSql($value->TGL_METODEFU2);
      $KD_STATUS_METODEFU2 = $value->KD_STATUS_METODEFU2;
      $STATUS_METODEFU2 = $value->STATUS_METODEFU2;
      $HASIL_METODEFU2 = $value->HASIL_METODEFU2;
      $STATUS_BOOKING = $value->STATUS_BOOKING;
      $ALASAN_BOOKING = $value->ALASAN_BOOKING;
      $STATUS_FU = $value->STATUS_FU;
    }
  }
}

?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('follow_up/followup_service_reminder_booking_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Followup Call Service Reminder & Service Booking</h4>
</div>

<div class="modal-body">

      <!-- header -->
      <input id="kd_customer" type="hidden" name="kd_customer" class="form-control" value="<?php echo $KD_CUSTOMER;?>" required>
      <input id="kelurahan" type="hidden" name="kelurahan" class="form-control" value="<?php echo $KELURAHAN;?>" required>
      <input id="kecamatan" type="hidden" name="kecamatan" class="form-control" value="<?php echo $KECAMATAN;?>" required>
      <input id="kota" type="hidden" name="kota" class="form-control" value="<?php echo $KOTA;?>" required>
      <input id="kode_pos" type="hidden" name="kode_pos" class="form-control" value="<?php echo $KODE_POS;?>" required>
      <input id="propinsi" type="hidden" name="propinsi" class="form-control" value="<?php echo $PROPINSI;?>" required>
      <input id="tgl_trans" type="hidden" name="tgl_trans" class="form-control" value="<?php echo $TGL_TRANS != ''? $TGL_TRANS : date('d/m/Y');?>" required>
      <input id="jenis_kpb" type="hidden" name="jenis_kpb" class="form-control" value="<?php echo $JENIS_KPB;?>" required>
      <input id="kd_motor" type="hidden" name="kd_motor" class="form-control" value="<?php echo $KD_MOTOR;?>" required>

      <!-- detail -->
  <div class='row'>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>ID FU Servis</label>
          <input id="no_trans" type="text" name="no_trans" class="form-control" value="<?php echo $NO_TRANS;?>" placeholder="Kode FU SERVICE" readonly>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <?php if($NO_TRANS == ''): ;?>
          <label>Nomor Rangka <span class="loading-fu"></span></label>
          <input id="no_rangka_service" type="text" name="no_rangka" class="form-control" value="<?php echo $NO_RANGKA;?>" placeholder="Masukan Nomor Rangka" required disabled>
          <?php else: ;?>
          <label>Nomor Rangka</label>
          <input id="no_rangka" type="text" name="no_rangka" class="form-control" value="<?php echo $NO_RANGKA;?>" placeholder="Masukan Nomor Rangka" required readonly>
          <?php endif ;?>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Nomor Mesin</label>
          <input id="no_mesin" type="text" name="no_mesin" class="form-control" value="<?php echo $NO_MESIN;?>" placeholder="Masukan Nomor Mesin" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Tanggal Pembelian</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_pembelian" type="text" name="tgl_pembelian" class="form-control" value="<?php echo $TGL_PEMBELIAN; ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Nama STNK</label>
          <input id="nama_stnk" type="text" name="nama_stnk" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" placeholder="Masukan Nama Customer" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Nomor HP</label>
          <input id="no_hp" type="text" name="no_hp" class="form-control" value="<?php echo $NO_HP;?>" placeholder="No HP" readonly>
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
          <label>Alamat</label>
          <input id="alamat_surat" type="text" name="alamat_surat" class="form-control" value="<?php echo $ALAMAT_SURAT;?>" placeholder="Masukan Alamat" required>
      </div>
    </div>
  </div>

  <div class="row total" style="background: none !important; padding-top:5px;">
    <div class="col-xs-12">
      <h3 id="jenis_kpb_title"><?php echo $JENIS_KPB != ''? $JENIS_KPB : 'KPB';?></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>ID Metode FU Pertama</label>
          <input id="kd_metodefu" type="text" name="kd_metodefu" class="form-control" value="<?php echo $KD_METODEFU;?>" placeholder="Masukan Kode Metode FU" required <?php echo $NO_TRANS == ''? '' : 'readonly';?>>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3 hidden">
      <div class="form-group">
          <label>Metode FU Pertama</label>
          <input id="nama_metodefu" type="text" name="nama_metodefu" class="form-control" value="<?php echo $NAMA_METODEFU;?>" placeholder="Masukan Nama Metode FU" required readonly>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Tanggal FU Pertama</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_fu" type="text" name="tgl_fu" class="form-control" value="<?php echo $TGL_METODEFU != ''? $TGL_METODEFU : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" required <?php echo $NO_TRANS == ''? '' : 'readonly';?>>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Status Metode Pertama</label>
          <?php if($KD_STATUS_METODEFU == ''):?>  
          <input id="status_metode" type="hidden" name="status_metode" class="form-control" value="<?php echo $STATUS_METODEFU;?>" required>
          <input id="kd_setup_statuscall" type="text" name="kd_setup_statuscall" class="form-control" value="<?php echo $KD_STATUS_METODEFU;?>" placeholder="Masukan Status Metode" required readonly>
        <?php else: ?>
          <input id="status_metode" type="text" name="status_metode" class="form-control" value="<?php echo $STATUS_METODEFU;?>" required readonly>
          <input id="kd_setup_statuscall" type="hidden" name="kd_setup_statuscall" class="form-control" value="<?php echo $KD_STATUS_METODEFU;?>" required>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Hasil Metode Pertama</label>
          <select id="hasil_metodefu" name="hasil_metodefu" class="form-control" <?php echo $NO_TRANS == ''? '' : 'readonly';?> required="">
            <option value="" disabled selected>--Pilih Hasil--</option>
            <?php 
            if($hasil_fu && (is_array($hasil_fu->message) || is_object($hasil_fu->message))): ;
              foreach ($hasil_fu->message as $key => $value):
            ?>

              <option value="<?php echo $value->KLASIFIKASI;?>" <?php echo $value->KLASIFIKASI == $HASIL_METODEFU?'selected' : '';?> ><?php echo $value->KLASIFIKASI;?></option>

            <?php endforeach; endif;?>
          </select>
      </div>
    </div>
  </div>

  <?php if($NO_TRANS != ''): ;?>
  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>ID Metode FU Kedua</label>
          <input id="kd_metodefu2" type="text" name="kd_metodefu2" class="form-control" value="<?php echo $KD_METODEFU2;?>" placeholder="Masukan Kode Metode FU" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3 hidden">
      <div class="form-group">
          <label>Metode FU Kedua</label>
          <input id="nama_metodefu2" type="text" name="nama_metodefu2" class="form-control" value="<?php echo $NAMA_METODEFU2;?>" placeholder="Masukan Nama Metode FU" required readonly>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Tanggal FU Kedua</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_metodefu2" type="text" name="tgl_metodefu2" class="form-control" value="<?php echo $TGL_METODEFU2 != ''? $TGL_METODEFU2 : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Status Metode Kedua</label>

        <?php if($KD_STATUS_METODEFU2 == ''):?>  
          <input id="status_metode2" type="hidden" name="status_metode2" class="form-control" value="<?php echo $STATUS_METODEFU2;?>" required>
          <input id="kd_status_metodefu2" type="text" name="kd_status_metodefu2" class="form-control" value="<?php echo $KD_STATUS_METODEFU2;?>" placeholder="Masukan Status Metode" required readonly>
        <?php else: ?>
          <input id="status_metode2" type="text" name="status_metode2" class="form-control" value="<?php echo $STATUS_METODEFU2;?>" required readonly>
          <input id="kd_status_metodefu2" type="hidden" name="kd_status_metodefu2" class="form-control" value="<?php echo $KD_STATUS_METODEFU2;?>" required>
        <?php endif; ?>

      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Hasil Metode Kedua</label>
          <select id="hasil_metodefu2" name="hasil_metodefu2" class="form-control" required="">
            <?php 
            if($hasil_fu && (is_array($hasil_fu->message) || is_object($hasil_fu->message))): ;
              foreach ($hasil_fu->message as $key => $value):
            ?>

              <option value="<?php echo $value->KLASIFIKASI;?>" <?php echo $value->KLASIFIKASI == $HASIL_METODEFU2?'selected' : '';?> ><?php echo $value->KLASIFIKASI;?></option>

            <?php endforeach; endif;?>
          </select>
      </div>
    </div>
  </div>

  <?php endif; ;?>
      <!-- <input type="submit" name=""> -->


  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Booking</label>
          <select id="status_booking" name="status_booking" class="form-control" required="">
            <option value="" disabled selected>--Pilih Status--</option>
            <option value="1" <?php echo $STATUS_BOOKING == 1?'selected':'';?>>Ya</option>
            <option value="2" <?php echo $STATUS_BOOKING == 2?'selected':'';?>>Tidak</option>
            <!-- <option value="" <?php echo $value->KLASIFIKASI == $HASIL_METODEFU2?'selected' : '';?> ><?php echo $value->KLASIFIKASI;?></option> -->
          </select>
      </div>
    </div>
    <div id="alasan-form" class="col-xs-6 col-sm-6 col-md-6" style="<?php echo $ALASAN_BOOKING == ''?'display: none;':'';?>">
      <div class="form-group">
          <label>Alasan</label>
          <input type="text" id="alasan_booking" name="alasan_booking" class="form-control" value="<?php echo $ALASAN_BOOKING;?>" placeholder="Alasan Customer">
      </div>
    </div>


    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Status FU</label>
          <select id="status_fu" name="status_fu" class="form-control" disabled="true">
            <option value="0" <?php echo ($STATUS_FU == '0' ? "selected" : ""); ?>>Confirmed</option>
            <option value="1" <?php echo ($STATUS_FU == '1' ? "selected" : ""); ?>>Not Confirmed</option>
            <option value="2" <?php echo ($STATUS_FU == '2' ? "selected" : ""); ?>>Closed</option>
          </select>
      </div>
    </div>
  </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn <?php echo $status_e?>">Simpan</button>
</div>

</form>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/fu.js");?>"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#kd_metodefu, #kd_metodefu2').change(function(){
      var metode = $(this).val();

      booking(metode);
      statusFu();
    });

    $('#kd_setup_statuscall').change(function(){
      statusFu();
    });

    $('#status_booking').change(function(){
      var metode1 = $("#kd_metodefu").val();
      var metode2 = $("#kd_metodefu2").val();


      if(metode2){
      // alert(metode1+metode2)
        booking(metode2);
      }
      else{
        booking(metode1);
      }
    });

  });

  function statusFu()
  {
      var metode1 = $("#kd_metodefu").val();
      var metode2 = $("#kd_metodefu2").val();
      var status1 = $("#kd_setup_statuscall").val();
      var status2 = $("#kd_status_metodefu2").val();

      console.log(metode1+'|'+metode2+'|'+status1);
      if(metode1 == 2 && status1 == 3){
        $('#status_fu').val(0);
      }
      else if(metode2 == 2 && status2 == 3){
        $('#status_fu').val(1);
      }
      else if(metode1 == 2 && status1 != 3){
        $('#status_fu').val(1);
      }
      else if(metode2 == 2 && status2 != 3){
        $('#status_fu').val(2);
      }
      else{
        $('#status_fu').val(0);
      }

  }

  function booking(metode)
  {
      var val = $('#status_booking').val();

      if(val == 2 && metode == 2){
        $('#alasan-form').show();
      }
      else{
        $('#alasan-form').hide();
      }

  }
</script>

