<?php

$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');


?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('stnk/store_birojasa/'.$reff);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Biro Jasa</h4>
</div>

<div class="modal-body">

      <input id="kd_maindealer" type="hidden" name="kd_maindealer" value="<?php echo $this->session->userdata('kd_maindealer');?>">


      <div class="row">

        <div class="col-xs-12 col-md-3">

          <div class="form-group">
              <label>Dealer</label>
              <select name="kd_dealer" id="kd_dealer" class="form-control" <?php echo $ROOT;?> required="true">
                
                  <?php
                  if (isset($dealer)) {
                      if ($dealer->totaldata > 0) {
                          foreach ($dealer->message as $key => $value) {
                              $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                              $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                              echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                          }
                      }
                  }
                  ?>
              </select>
          </div>

        </div>

        <div class="col-xs-12 col-md-3">
          <div class="form-group">
              <label>Kode Biro Jasa</label>
              <input id="kd_birojasa" type="text" name="kd_birojasa" class="form-control uppercaseform" placeholder="kode biro jasa" required style="text-transform:uppercase">
          </div>
        </div>

        <div class="col-xs-12 col-md-3">
          <div class="form-group">
              <label>Nama Biro Jasa</label>
              <input id="nama_birojasa" type="text" name="nama_birojasa" class="form-control uppercaseform" placeholder="nama biro jasa" required style="text-transform:uppercase" >
          </div>
        </div>

        <div class="col-xs-12 col-md-3">
          <div class="form-group">
            <label>Nama Pengurus</label>
            <input type="text" name="nama_pengurus" id="nama_pengurus" class="form-control uppercaseform" placeholder="nama pengurus" required style="text-transform:uppercase" >
          </div>
        </div>

      </div>

      <div class="row">

        <div class="col-xs-12 col-md-12">
          <div class="form-group">
              <label>Alamat</label>
              <textarea rows="5" name="alamat" class="form-control" placeholder="masukan alamat" required></textarea>

          </div>
        </div>

      </div>


      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>


<script type="text/javascript">
$(document).ready(function(){

  $(".uppercaseform").keyup(function(){
    $('.uppercaseform').val (function () {
        return this.value.toUpperCase();
    })
  });

});
</script>

