<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $list->message[0]->KD_DEALER;
$defaultMainDealer = $list->message[0]->KD_MAINDEALER;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Harga Claim KPB</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_harga_claim/'. $list->message[0]->ID); ?>">
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
        <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
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
        <input type="text" name="no_mesin" id="no_mesin" class="form-control" value="<?php echo $list->message[0]->NO_MESIN;?>">
    </div>


    <div class="form-group">
        <label>Motor KPB</label>
        <input type="text" name="motor_kpb" id="motor_kpb" class="form-control" value="<?php echo $list->message[0]->MOTOR_KPB;?>">
    </div>


    <div class="form-group">
        <label>Inisial</label>
        <input type="text" name="inisial" id="inisial" class="form-control" value="<?php echo $list->message[0]->INISIAL;?>">
    </div>


    <div class="form-group">
        <label>Kode KPB</label>
        <input type="text" name="kd_kpb" id="kd_kpb" class="form-control" value="<?php echo $list->message[0]->KD_KPB;?>">
    </div>


    <div class="form-group">
        <label>Service</label>
        <input type="text" name="service" id="service" class="form-control" value="<?php echo $list->message[0]->SERVICE;?>">
    </div>


    <div class="form-group">
        <label>Nominal Jasa</label>
        <input type="text" name="nominal_jasa" id="nominal_jasa" class="form-control" value="<?php echo $list->message[0]->NOMINAL_JASA;?>">
    </div>


    <div class="form-group">
        <label>Isi Oli</label>
        <input type="text" name="isi_oli" id="isi_oli" class="form-control" value="<?php echo $list->message[0]->ISI_OLI;?>">
    </div>


    <div class="form-group">
        <label>Harga Oli</label>
        <input type="text" name="harga_oli" id="harga_oli" class="form-control" value="<?php echo $list->message[0]->HARGA_OLI;?>">
    </div>


    <div class="form-group">
        <label>Nomor Part Oli</label>
        <input type="text" name="no_part_oli" id="no_part_oli" class="form-control" value="<?php echo $list->message[0]->NO_PART_OLI;?>">
    </div>


    <div class="form-group">
        <label>Nomor Part Oli 2</label>
        <input type="text" name="no_part_oli2" id="no_part_oli2" class="form-control" value="<?php echo $list->message[0]->NO_PART_OLI2;?>">
    </div>


    <div class="form-group">
        <label>Isi Oli 2</label>
        <input type="text" name="isi_oli_2" id="isi_oli_2" class="form-control" value="<?php echo $list->message[0]->ISI_OLI_2;?>">
    </div>


    <div class="form-group">
        <label>Harga Oli 2</label>
        <input type="text" name="harga_oli_2" id="harga_oli_2" class="form-control" value="<?php echo $list->message[0]->HARGA_OLI_2;?>">
    </div>


    <div class="form-group">
        <label>Nomor Part Oli 1</label>
        <input type="text" name="no_part_oli_1" id="no_part_olo_1" class="form-control" value="<?php echo $list->message[0]->NO_PART_OLI_1;?>">
    </div>


    <div class="form-group">
        <label>Nomor Part Oli 2B</label>
        <input type="text" name="no_part_oli_2" id="no_part_oli_2" class="form-control" value="<?php echo $list->message[0]->NO_PART_OLI_2;?>">
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
</form>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

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
