<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$end_date=date('d/m/Y',strtotime('Last day of this month'));
$start_date=date('d/m/Y');
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$asdealer=array(); $assign_event="";

if ($list) {
  if (is_array($list->message)) {
    foreach ($list->message as $key => $value) {
      $kd_propinsi = $value->KD_PROPINSI;
      $kd_kabupaten = $value->KD_KABUPATEN;
      $kd_kecamatan = $value->KD_KECAMATAN;
      $kd_desa = $value->KD_DESA;
      $kd_dealer = $value->KD_DEALER;
    }
  }

  $asdealer = explode(", ",$assign_event);
}
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/create_event_simpan');?>" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Create Event</h4>
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
          <label>Jenis Event</label>
          <select class="form-control" id="kd_jenis_event" name="kd_jenis_event" required="true">
            <option value="" >- Pilih Jenis Event -</option>
            <?php
            if ($jevent) {
              if (is_array($jevent->message)) {
                foreach ($jevent->message as $key => $value) {
                  echo "<option value='" . $value->KD_JENIS_EVENT . "'>" . $value->NAMA_JENIS_EVENT . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Inisiasi</label>
          <input type="text" id="inisiasi_event" name="inisiasi_event" class="form-control" placeholder="Inisiasi Event" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kode Event</label>
          <input type="text" class="form-control" id="kd_event" autocomplete="off" name="kd_event" placeholder="AUTO NUMBER"  readonly="true">
        </div>
      </div>

    </div>

    <!-- 2 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" id="nama_event" name="nama_event" class="form-control" placeholder="Nama Event" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>TanggaL</label>
          <div class="input-group " id="date">
            <input type="text" class="form-control" id="tgl_trans" name="tgl_trans" placeholder="dd/mm/yyyy"  value="<?php echo $start_date; ?>" readonly />
          </div>
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

      <div class="col-sm-3">
        <div class="form-group">
          <label class="control-label" for="date">Tanggal Berakhir</label>
          <div class="input-group input-append date">
            <input class="form-control" name="end_date" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y', strtotime('now')); ?>" type="text"/>
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>
    </div>

    <!-- 3 -->
    <div class="row">

      <!-- <div class="col-sm-6">
        <div class="form-group">
          <label>Assign</label>
          <select  name="assign_event[]" id="assign_event"  class="selectpicker form-control edit" multiple value="" data-live-search="true" >

            <?php
            if ($dealer) {
              if (is_array($dealer->message)) {
                foreach ($dealer->message as $key => $value) {
                $aktif = '';
                  foreach($asdealer as $asdealer_val){
                    if($value->KD_DEALER == $asdealer_val){
                      $aktif = "selected";
                    }
                  }
                  
                  echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                }
              }
            }

            ?>
          </select>
        </div>
      </div>
      <?php 
      ?> -->

      <div class="col-sm-6">
        <div class="form-group">
          <label>Keterangan Event</label>
          <textarea type="text" rows="1" name="keterangan_event" id="keterangan_event" class="form-control" placeholder="Masukkan Keterangan"></textarea>
        </div>
      </div>

    </div>

    <!-- 4 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Target Unit</label>
          <input type="number" id="target_unit" name="target_unit" class="form-control" placeholder="0" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Target Revenue</label>
          <input type="number" id="target_revenue" name="target_revenue" class="form-control" placeholder="0" >
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label>Alamat <span id="l_alamat"></span></label>
          <textarea type="text" rows="1" autocomplete="off"  name="alamat_event" id="alamat_event" class="form-control" placeholder="Masukkan Nama Alamat" required="required"></textarea>
        </div>
      </div>

    </div>

    <!-- 5 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Propinsi</label>
          <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
            <option value="0">--Pilih Propinsi--</option>
            <?php
            if ($propinsi) {
              if (is_array($propinsi->message)) {
                foreach ($propinsi->message as $key => $value) {
                  echo "<option value='" . $value->KD_PROPINSI . "'>" . $value->NAMA_PROPINSI . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kabupaten <span id="l_kabupaten"></span></label>
          <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required="true">
            <option value="">--Pilih Kabupaten--</option>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kecamatan <span id="l_kecamatan"></span></label>
          <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan" required="true">
            <option value="">--Pilih Kecamatan--</option>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kelurahan <span id="l_desa"></span></label>
          <select class="form-control" id="kd_desa" name="kd_desa" title="desa" required="true">
            <option value="">--Pilih Kelurahan--</option>
          </select>
        </div>
      </div>

    </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $(document).ready(function(){

    $('.selectpicker').selectpicker();

    var date = new Date();
    date.setDate(date.getDate());

    /*pilihan propinsi*/
    $('#kd_propinsi').on('change', function () {
      loadData('kd_kabupaten', $('#kd_propinsi').val(), '0')
    })
    $('#kd_kabupaten').on('change', function () {
      loadData('kd_kecamatan', $(this).val(), '0')
    })
    $('#kd_kecamatan').on('change', function () {
      loadData('kd_desa', $(this).val(), '0')
    })
    $('#baru').click(function(){
      document.location.reload();
    })
  })
  function loadData(id, value, select) {
    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = "<?php echo base_url(); ?>customer/" + param;
    var datax = {"kd": value};
    $('#' + id + '').attr('disabled','disabled');
    $.ajax({
      type: 'GET',
      url: urls,
      data: datax,
      typeData: 'html',
      success: function (result) {
        $('#' + id + '').empty();
        $('#' + id + '').html(result);
        $('#' + id + '').val(select).select();
        $('#l_' + param + '').html('');
        $('#' + id + '').removeAttr('disabled');
      }
    });
  }


</script>