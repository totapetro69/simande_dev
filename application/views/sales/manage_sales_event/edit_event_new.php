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
?>

<?php
$kd_propinsi = "";
$kd_kabupaten = "";
$kd_kecamatan = "";
$kd_desa = "";

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

<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/event_update/' . $list->message[0]->ID); ?>">
  <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Ubah Event</h4>
  </div>

  <div class="modal-body">

    <!-- 1 -->
    <div class="row">

      <div class="col-sm-3">
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
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Jenis Event</label>
          <select class="form-control" id="kd_jenis_event" name="kd_jenis_event" required="true" >
            <option value="" >- Pilih Jenis Event -</option>
            <?php
            if (isset($jevent)) {
              if (($jevent->totaldata)) {
                foreach ($jevent->message as $key => $value) {
                  $select=($list->message[0]->KD_JENIS_EVENT == $value->KD_JENIS_EVENT)?"selected":"";
                  echo "<option value='" . $value->KD_JENIS_EVENT . "' ".$select.">" . $value->NAMA_JENIS_EVENT . "</option>";
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
          <input type="text" name="inisiasi_event" id="inisiasi_event" class="form-control" value="<?php echo  $list->message[0]->INISIASI_EVENT; ?>" readonly>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Kode Event</label>
          <input type="text" name="kd_event" id="kd_event" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" readonly>
        </div>
      </div>

    </div>

    <!-- 2 -->
    <div class="row">

      <div class="col-sm-3"> 
        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" name="nama_event" id="nama_event" class="form-control" value="<?php echo  $list->message[0]->NAMA_EVENT; ?>" >
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>TanggaL</label>
          <div class="input-group " id="date">
            <input type="text" class="form-control" id="tgl_trans" name="tgl_trans" value="<?php echo ($list->message[0]->TGL_TRANS)?tglfromSql($list->message[0]->TGL_TRANS): date('d/m/Y');?>" placeholder="dd/mm/yyyy" readonly /  >
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Tanggal Mulai</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE)?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Tanggal Berakhir</label>
          <div class="input-group input-append date" id="date">
            <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE)?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" required="required" />
            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
        </div>
      </div>

    </div>

    <!-- 3 -->
    <div class="row">

      <div class="col-sm-6">
        <div class="form-group">
          <label>Keterangan Event</label>
          <textarea type="text" rows="1" name="keterangan_event" id="keterangan_event" class="form-control " placeholder="Masukkan Keterangan" ><?php echo $list->message[0]->KETERANGAN_EVENT; ?></textarea>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label>Assign</label>
          <!-- <input type="text" name="assign_event" id="assign_event" class="form-control" value="<?php echo  $list->message[0]->ASSIGN_EVENT; ?>" > -->
          
          <!-- <select class="form-control" id="kd_dealer" name="kd_dealer" disabled>
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
          </select> -->
          <select  name="assign_event[]" id="assign_event"  class="selectpicker form-control edit" multiple value="" data-live-search="true" >
            <?php
            //$cek = array();
           
            if (isset($dealer)) {
              if (($dealer->totaldata)) {
                foreach ($dealer->message as $key => $value) {
                  
                  echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                }
              }
            }

            ?>
          </select>
        </div>
      </div>
      <?php 
      //print_r();
      ?>

    </div>

    <!-- 4 -->
    <div class="row">

      <div class="col-sm-2">
        <div class="form-group">
          <label>Target Unit</label>
          <input type="number" name="target_unit" id="target_unit" class="form-control" value="<?php echo  $list->message[0]->TARGET_UNIT; ?>" >
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Target Revenue</label>
          <input type="number" name="target_revenue" id="target_revenue" class="form-control" value="<?php echo  $list->message[0]->TARGET_REVENUE; ?>" >
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
          <label>Alamat <span id="l_alamat"></span></label>
          <textarea type="text" rows="1" name="alamat_event" id="alamat_event" class="form-control " placeholder="Masukkan Alamat" ><?php echo $list->message[0]->ALAMAT_EVENT; ?></textarea>
        </div>
      </div>

    </div>

    <!-- 5 -->
    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Propinsi</label>
          <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
            <option value="" >- Pilih Propinsi-</option>
            <?php
            if (isset($propinsi)) {
              if (($propinsi->totaldata)) {
                foreach ($propinsi->message as $key => $value) {
                  $select=($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI)?"selected":"";
                  echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
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
          <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
            <option value="0">--Pilih Kabupaten--</option>
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
        loadData('kd_kabupaten', '<?php echo $kd_propinsi; ?>', '<?php echo $kd_kabupaten; ?>');
        loadData('kd_kecamatan', '<?php echo $kd_kabupaten; ?>', '<?php echo $kd_kecamatan; ?>');
        loadData('kd_desa', '<?php echo $kd_kecamatan; ?>', '<?php echo $kd_desa; ?>');
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