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
    <h4 class="modal-title" id="myModalLabel">Edit Lead Time</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_setup_leadtime/'. $list->message[0]->ID); ?>">
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
        <label>Lead Time AHM ke Main Dealer (hari)</label>
        <input id="ahm_to_md" type="text" name="ahm_to_md" class="form-control" value="<?php echo  $list->message[0]->AHM_TO_MD; ?>">
    </div>
    <div class="form-group">
        <label>Lama Proses di Main Dealer (hari)</label>
        <input id="process_md" type="text" name="process_md" class="form-control" value="<?php echo  $list->message[0]->PROCESS_MD; ?>">
    </div>
    <div class="form-group">
        <label>Lead Time Main Dealer ke Dealer (hari)</label>
        <input id="md_to_dealer" type="text" name="md_to_dealer" class="form-control" value="<?php echo  $list->message[0]->MD_TO_DEALER; ?>">
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

        $('#md_to_dealer')
        .focusout(function(){
        })
        .ForceNumericOnly()

        $('#process_md')
        .focusout(function(){
        })
        .ForceNumericOnly()

        $('#ahm_to_md')
        .focusout(function(){
        })
        .ForceNumericOnly()


    });

</script>