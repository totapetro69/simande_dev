<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('master_service/add_harga_claim_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Harga Claim Kpb</h4>
</div>

<div class="modal-body">

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
    <label>Nomor Mesin</label>
    <input id="no_mesin" type="text" name="no_mesin" class="form-control" placeholder="Masukkan Nomor Mesin" maxlength="5" required=>
  </div>

  <div class="form-group">
    <label>Motor KPB</label>
    <input type="text" name="motor_kpb" id="motor_kpb" class="form-control" placeholder="Masukkan Motor. KPB" required>
  </div>

  <div class="form-group">
    <label>Inisial</label>
    <input type="text" name="inisial" id="inisial" class="form-control" placeholder="Masukkan Insial" required>
  </div>

  <div class="form-group">
    <label>Kode KPB</label>
    <input id="kd_kpb" type="text" name="kd_kpb" class="form-control" placeholder="Masukkan Kode KPB" required=>
  </div>

  <div class="form-group">
    <label>Service</label>
    <input type="text" name="service" id="service" class="form-control" placeholder="Masukkan Service" required>
  </div>

  <div class="form-group">
    <label>Nominal Jasa</label>
    <input type="text" name="nominal_jasa" id="nominal_jasa" class="form-control" placeholder="Masukkan Nominal Jasa" required>
  </div>

  <div class="form-group">
    <label> Isi Oli</label>
    <input id="isi_oli" type="text" name="isi_oli" class="form-control" placeholder="Masukkan Isi Oli" required>
  </div>

  <div class="form-group">
    <label>Harga Oli</label>
    <input type="text" name="harga_oli" id="harga_oli" class="form-control" placeholder="Masukkan Harga Oli" required>
  </div>

  <div class="form-group">
    <label>Nomor Part Oli</label>
    <input type="text" name="no_part_oli" id="no_part_oli" class="form-control" placeholder="Masukkan Nomor Part harga_oli" required>
  </div>

  <div class="form-group">
    <label>Nomor Part Oli 2</label>
    <input type="text" name="no_part_oli2" id="no_part_oli2" class="form-control" placeholder="Masukkan Nomor Part Oli 2" required>
  </div>

  <div class="form-group">
    <label>Isi Oli 2</label>
    <input id="isi_oli_2" type="text" name="isi_oli_2" class="form-control" placeholder="Masukkan Isi Oli 2" maxlength="5">
  </div>

  <div class="form-group">
    <label>Harga Oli 2</label>
    <input type="text" name="harga_oli_2" id="harga_oli_2" class="form-control" placeholder="Masukkan Harga Oli 2">
  </div>

  <div class="form-group">
    <label>Nomor Part Oli 1 </label>
    <input type="text" name="no_part_oli_1" id="no_part_oli_1" class="form-control" placeholder="Masukkan Nomor Part Oli 1" >
  </div>

  <div class="form-group">
    <label>Nomor Part Oli 2B </label>
    <input type="text" name="no_part_oli_2" id="no_part_oli_2" class="form-control" placeholder="Masukkan Nomor Part Oli 2">
  </div>
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>

<script type="text/javascript">
    
    $(document).ready(function(e){
        
        $('#service')
       .focusout(function(){
       })
       .ForceNumericOnly()

       $('#nominal_jasa')
       .focusout(function(){})
       .ForceNumericOnly()

       $('#isi_oli')
       .focusout(function(){
       })
       .ForceNumericOnly()


       $('#harga_oli')
       .focusout(function(){
       })
       .ForceNumericOnly()

       $('#isi_oli_2')
       .focusout(function(){
       })
       .ForceNumericOnly()

       $('#harga_oli_2')
       .focusout(function(){
       })
       .ForceNumericOnly()

    });

</script>


