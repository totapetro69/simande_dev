<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 




$KD_FU_THANKS = "";$KD_CUSTOMER = "";$HONDA_ID = "";
$KD_METODEFU = "";$KD_UNITDELIVERY = "";$NO_FRAME = "";
$NO_ENGINE = "";$TGL_PEMBELIAN = "";$NAMA_CUSTOMER = "";
$TGL_LAHIR = "";$NO_HP = "";$ALAMAT_SURAT = "";
$KELURAHAN = "";$KECAMATAN = "";$KOTA = "";
$KODE_POS = "";$PROPINSI = "";$AGAMA = "";
$EMAIL = "";$KD_SETUP_STATUSCALL = "";$NAMA_METODEFU = "";
$STATUS_METODE = "";$KET_THANKS = "";$REMINDER_KPB1 = "";$INFORMASI_DEALER = "";

if(isset($list)){
  if($list->totaldata >0){
    foreach ($list->message as $key => $value) {
      $KD_CUSTOMER = $value->KD_CUSTOMER;
      // $HONDA_ID = $value->;
      $NO_FRAME = $value->NO_RANGKA;
      $NO_ENGINE = $value->NO_MESIN;
      $TGL_PEMBELIAN = tglfromSql($value->TGL_SO);
      $NAMA_CUSTOMER = $value->NAMA_CUSTOMER;
      $ALAMAT_SURAT = $value->ALAMAT_SURAT;
      $NO_HP = $value->NO_HP;
      $TGL_LAHIR = tglfromSql($value->TGL_LAHIR);
      $AGAMA = $value->NAMA_AGAMA;
      $EMAIL = $value->EMAIL;
      
      $KELURAHAN = $value->NAMA_DESA;
      $KECAMATAN = $value->NAMA_KECAMATAN;
      $KOTA = $value->NAMA_KABUPATEN;
      $KODE_POS = $value->KODE_POS;
      $PROPINSI = $value->NAMA_PROPINSI;
      $KD_UNITDELIVERY = $value->NO_SURATJALAN;;

    }
  }
}

?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('follow_up/followup_pembelian_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Followup Call Thanks</h4>
</div>

<div class="modal-body">
    <input id="kd_customer" type="hidden" name="kd_customer" class="form-control" value="<?php echo $KD_CUSTOMER;?>" required>
      <input id="kelurahan" type="hidden" name="kelurahan" class="form-control" value="<?php echo $KELURAHAN;?>" required>
      <input id="kecamatan" type="hidden" name="kecamatan" class="form-control" value="<?php echo $KECAMATAN;?>" required>
      <input id="kota" type="hidden" name="kota" class="form-control" value="<?php echo $KOTA;?>" required>
      <input id="kode_pos" type="hidden" name="kode_pos" class="form-control" value="<?php echo $KODE_POS;?>" required>
      <input id="propinsi" type="hidden" name="propinsi" class="form-control" value="<?php echo $PROPINSI;?>" required>
      <input id="kd_unitdelivery" type="hidden" name="kd_unitdelivery" class="form-control" value="<?php echo $KD_UNITDELIVERY;?>" required>
      <input id="tgl_trans" type="hidden" name="tgl_trans" class="form-control" value="<?php echo date('d/m/Y');?>" required>
      <input id="status_metode" type="hidden" name="status_metode" class="form-control" value="" required>
  <div class="row">
    <fieldset id="hdr" class="disabled-action">
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>Nomor Rangka</label>
          <input id="no_frame" type="text" name="no_frame" class="form-control" value="<?php echo $NO_FRAME;?>" placeholder="Masukan Nomor Rangka" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>Nomor Mesin</label>
          <input id="no_engine" type="text" name="no_engine" class="form-control" value="<?php echo $NO_ENGINE;?>" placeholder="Masukan Nomor Mesin" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>Tanggal Pembelian</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_pembelian" type="text" name="tgl_pembelian" class="form-control" value="<?php echo $TGL_PEMBELIAN != ''? $TGL_PEMBELIAN : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>Nama</label>
          <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" placeholder="Masukan Nama Customer" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>No. HP</label>
          <input id="no_hp" type="text" name="no_hp" class="form-control" value="<?php echo $NO_HP;?>" placeholder="Masukan No HP" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-4 col-md-4">
      <div class="form-group">
          <label>Tanggal Lahir</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_lahir" type="text" name="tgl_lahir" class="form-control" value="<?php echo $TGL_LAHIR != ''? $TGL_LAHIR : date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6">
      <div class="form-group">
          <label>Alamat</label>
          <input id="alamat_surat" type="text" name="alamat_surat" class="form-control" value="<?php echo $ALAMAT_SURAT;?>" placeholder="Masukan Alamat" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Agama</label>
          <input id="agama" type="text" name="agama" class="form-control" value="<?php echo $AGAMA;?>" placeholder="Masukan Agama" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Alamat Email</label>
          <input id="email" type="text" name="email" class="form-control" value="<?php echo $EMAIL;?>" placeholder="Masukan Alamat Email" required>
      </div>
    </div>
  </fieldset>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>ID Metode FU</label>
          <input id="filter_metode" type="hidden" name="filter_metode" class="form-control" value="true">
          <input id="kd_metodefu" type="text" name="kd_metodefu" class="form-control" value="<?php echo '';?>" placeholder="Masukan Kode Metode FU" required>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3 hidden">
      <div class="form-group">
          <label>Metode FU</label>
          <input id="nama_metodefu" type="text" name="nama_metodefu" class="form-control" value="<?php echo '';?>" placeholder="Masukan Nama Metode FU" required readonly>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Tanggal FU</label>
          <div class="input-group input-append date" id="datepicker">
              <input id="tgl_fu" type="text" name="tgl_fu" class="form-control" value="<?php echo date('d/m/Y'); ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Status Metode</label>
          <input id="kd_setup_statuscall" type="text" name="kd_setup_statuscall" class="form-control" value="<?php echo '';?>" placeholder="Masukan Status Metode" required readonly>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Terima Kasih</label>
          <div class="form-inline">
            <div class="radio">
                  <label>
                  <input type="radio" name="ket_thanks" value="1" required> Ya 
                  </label>
                  <br>
                  <label>
                  <input type="radio" name="ket_thanks" value="0" required> Tidak
                  </label>
              </div>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Reminder KPB 1</label>

          <div class="form-inline">
            <div class="radio">
                  <label>
                  <input type="radio" name="reminder_kpb1" value="1" required> Ya 
                  </label>
                  <br>
                  <label>
                  <input type="radio" name="reminder_kpb1" value="0" required> Tidak
                  </label>
              </div>
          </div>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 col-md-3">
      <div class="form-group">
          <label>Informasi Dealer</label>
          <div class="form-inline">
            <div class="radio">
                  <label>
                  <input type="radio" name="informasi_dealer" value="1" required> Ya 
                  </label>
                  <br>
                  <label>
                  <input type="radio" name="informasi_dealer" value="0" required> Tidak
                  </label>
              </div>
          </div>
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
