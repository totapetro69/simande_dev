<?php
$defaultDealer = $this->session->userdata("kd_dealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_gudang_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah gudang</h4>
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
        <label>Kode Lokasi Dealer</label>
        <select class="form-control" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
            <option value="0">--Pilih Lokasi Dealer--</option>
             <?php
                if ($lokasidealer) {
                  if (is_array($lokasidealer->message)) {
                    foreach ($lokasidealer->message as $key => $value) {
                      $aktif = ($this->input->get("kd_lokasidealer") == $value->KD_LOKASI) ? "selected" : '';
                      echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                    }
                  }
                }
            ?>  
        </select>
    </div>

    <div class="form-group">
        <label>Kode Gudang</label>
        <input type="text" name="kd_gudang" id="kd_gudang" class="form-control" placeholder="Masukkan Kode Gudang" maxlength="5" required>
    </div>

    <div class="form-group">
        <label>Nama Gudang</label>
        <input type="text" name="nama_gudang" id="nama_gudang" class="form-control" placeholder="Masukkan Nama Gudang" required>
    </div>

    <div class="form-group">
        <label>Alamat</label>
        <textarea type="text" name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" ></textarea>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label>Jenis Gudang</label>
          <select name="jenis_gudang" id="jenis_gudang" class="form-control">
            <option value="Part">PART</option>
            <option value="Unit">UNIT</option>
          </select>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group">
            <label>Sebagai Gudang Default</label>
              <select class="form-control" id="defaults" name="defaults">
              <option value="0">Tidak</option>
              <option value="1">Ya</option>
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

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

    $(document).ready(function(){
      var logingroup="<?php echo $this->session->userdata("nama_group");?>";
      $('#kd_dealer').prop("disabled",true);
      if(logingroup=="Root"){
          $('#kd_dealer').removeClass("disabled-action");
          $('#kd_dealer').removeAttr("disabled");
      }else{
          //$('#kd_dealer').addClass("disabled-action");
          $('#kd_dealer').attr("disabled",true);
      }

      var jenis_gudang = $("#jenis_gudang").val();

      getGudangdefault(jenis_gudang);

      $("#jenis_gudang").change(function(){
        var jenis_gudang = $(this).val();
        getGudangdefault(jenis_gudang);

      });



    })

    function getGudangdefault(jenis_gudang)
    {
      var url = http+"/dealer/add_gudang/"+jenis_gudang;

      $.getJSON(url, function(data, status){
        console.log(data);
        if(data.totaldata > 0){
          // $("#jenis_gudang").
          // alert(jenis_gudang);
          $("#defaults option[value=1]").hide();
        }
        else{
          $("#defaults option[value=1]").show();

        }

      });
    }
    
</script>
