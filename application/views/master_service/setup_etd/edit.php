<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultMainDealer = $list->message[0]->KD_MAINDEALER;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Setup ETD AHM</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_setup_etd/'. $list->message[0]->ID); ?>">
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
            <label>Sifat Part</label>
            <select name="sifat_part" class="form-control" disabled="disabled">
              <option value="<?php echo $list->message[0]->SIFAT_PART;?>"> <?php if($list->message[0]->SIFAT_PART == 'N'){echo "N - Lokal"; }else{ echo "Y - Import"; }?> </option>
               <option value="N">N - Lokal</option>
               <option value="Y">Y - Import</option>
           </select>
       </div>
       <div class="form-group">
          <label>Kategori Part</label>
          <select name="kategori_part" class="form-control" disabled="disabled">
            <option value="<?php echo $list->message[0]->KATEGORI_PART;?>"> 
              <?php if($list->message[0]->KATEGORI_PART == "C"){
                    echo 'C - Current Part';
                  }elseif($list->message[0]->KATEGORI_PART == "N"){
                    echo 'N - Non Current';
                  }else{
                    echo 'O - others';
                  } ?> 
             </option>
           <option value="C">C- Current Part</option>
           <option value="N">N - Non Current Part</option>
           <option value="O">O - Others</option>
       </select>
   </div>


   <div class="form-group">
    <label>ETD (hari)</label>
    <input id="etd" type="text" name="etd" class="form-control" value="<?php echo  $list->message[0]->ETD; ?>">
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

        $('#etd')
        .focusout(function(){
        })
        .ForceNumericOnly()


    });

</script>