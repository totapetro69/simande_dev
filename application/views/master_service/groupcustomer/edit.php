<?php

if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Group Customer</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/update_groupcustomer/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Main Dealer</label>
      <select class="form-control" id="kd_maindealer" name="kd_maindealer" disabled="disabled" required>
        <option value="0">--Pilih Main Dealer-</option>
        <?php
        if ($maindealer) {
          if (is_array($maindealer->message)) {
            foreach ($maindealer->message as $key => $value) {
              $aktif = ($defaultMainDealer == $value->KD_MAINDEALER) ? "selected" : "";
              $aktif = ($this->input->get("kd_maindealer") == $value->KD_MAINDEALER) ? "selected" : $aktif;
              echo "<option value='" . $value->KD_MAINDEALER . "' " . $aktif . ">" . $value->NAMA_MAINDEALER . "</option>";
            }
          }
        }
        ?> 
      </select>
    </div>
    <div class="form-group">
      <label>Dealer</label>
      <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
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
      <label>Kode Group Customer</label>
      <input id="kd_groupcustomer" type="text" name="kd_groupcustomer" class="form-control" value="<?php echo  $list->message[0]->KD_GROUPCUSTOMER; ?>" disabled="disabled">
    </div>

    <div class="form-group">
      <label>Nama Group Customer</label>
      <input class="form-control" name="nama" id="nama" placeholder="Masukkan nama group customer" value="<?php echo  $list->message[0]->NAMA_GROUPCUSTOMER; ?>"></input>
    </div>

    <div class="form-group">
      <label>Alamat Perusahaan</label>
      <textarea class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat Perusahaan"><?php echo $list->message[0]->ALAMAT_LENGKAP; ?></textarea>
    </div>
    <div class="form-group">
      <label>Nomor Telepon Perusahaan</label>
      <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="Masukkan Nomor Telepon Perusahaan" value="<?php echo  $list->message[0]->NO_TELP; ?>">
    </div>
    <div class="form-group">
      <label>NPWP</label>
      <input class="form-control" name="npwp" id="npwp" placeholder="Masukkan NPWP" value="<?php echo  $list->message[0]->NO_NPWP; ?>"></input>
    </div>

    <div class="form-group">
      <label>Propinsi</label>
      <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
        <option value="0">--Pilih Propinsi--</option>
        <?php
        if ($propinsi) {
          if (is_array($propinsi->message)) {
            foreach ($propinsi->message as $key => $value) {
              $select=($list->message[0]->KD_PROPINSI == $value->KD_PROPINSI)?"selected":"";
              echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
            }
          }
        }
        ?>
      </select>
    </div>
    <!-- kabupaten -->
    <div class="form-group">
      <label>Kabupaten <span id="l_kabupaten"></span></label>
      <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
        <option value="0">--Pilih Kabupaten--</option>
      </select>
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
</form>

</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>");
    /*pilihan propinsi*/
    $('#kd_propinsi').on('change', function () {
      loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $list->message[0]->KD_KABUPATEN;?>")
    })
  })

  function loadData(id, value, select) {

    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = "<?php echo base_url(); ?>master_service/" + param;
    var datax = {"kd": value};
    $('#' + id + '').attr('disabled','disabled');
    $.ajax({
      type: 'POST',
      url: urls,
      data: datax,
      typeData: 'html',
      success: function (result) {
        $('#' + id + '').html('');
        $('#' + id + '').html(result);
        $('#' + id + '').val(select).select();
        $('#l_' + param + '').html('');
        $('#' + id + '').removeAttr('disabled');
      }
    });
  }
</script>