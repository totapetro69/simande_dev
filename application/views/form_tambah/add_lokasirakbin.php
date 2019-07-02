<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$rak_default=($lokasirakbin=="1")?'disabled-action':'';
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('part/add_lokasirakbin_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Lokasi/Rak/Bin</h4>
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
    <label>Kode Gudang</label>
      <select class="form-control" id="kd_gudang" name="kd_gudang" required>
        <option value="" >- Pilih Kode Gudang-</option>
          <?php
            if ($gudangs):
              if($gudangs->totaldata>0):
                foreach ($gudangs->message as $key => $gudang) :
                  $select=($gudang->DEFAULTS==1)? 'selected':"";
                  echo "<option value='".$gudang->KD_GUDANG."' ".$select.">[".$gudang->KD_GUDANG."] ". strtoupper($gudang->NAMA_GUDANG)."</option>";
                endforeach;
              endif;
            endif;
          ?>
      </select>
  </div>

  <div class="form-group">
    <label>Kode Lokasi</label>
    <div class="input-group input-append">
      <input type="text" id="kd_lokasi" name="kd_lokasi" class="form-control" readonly="true" style="text-transform: uppercase;" placeholder="Masukkan Kode Lokasi" required>
      <span class="input-group-addon add-on <?php echo $rak_default;?>"><input type="checkbox" style="cursor: pointer;" id="rak_default" name="rak_default"> Sebagai Rak Default</span>
    </div>
  </div>

  <div class="form-group">
    <label>Kode Rak</label>
    <input type="text" name="kd_rak" id="kd_rak" class="form-control" placeholder="Masukkan Kode Rak" style="text-transform: uppercase;" maxlength="2" required>
  </div>

  <div class="form-group">
    <label>Kode Bin</label>
    <input type="text" name="kd_binbox" id="kd_binbox" class="form-control" placeholder="Masukkan Kode Bin" style="text-transform: uppercase;" maxlength="4" required>
  </div> 

  <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan"></textarea>
  </div>

<div class="form-group hidden">
  <label>Sebagai Lokasi/rak/bin Default</label>
  <select class="form-control" id="defaults" name="defaults">
      <option value="0">Tidak</option>
      <?php if($lokasirakbin ==0){
          echo '<option value="1">Ya</option>';
      }
      ?>
  </select>
</div>

  
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
<script type="text/javascript">
  $(document).ready(function(e){
    $('#kd_rak').on('focusout',function(){
      var bb=$('#kd_binbox').val();
      if(bb!='-'){bb='-'+bb}
      $('#kd_lokasi').val($(this).val()+bb);
    })
    $('#kd_binbox').on('focusout',function(){
      var rak=$('#kd_lokasi').val().split('-');
      $('#kd_lokasi').val(rak[0]+'-'+$(this).val());
    })
  })
</script>


