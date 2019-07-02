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

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/update_gcmapping/' . $list->message[0]->ID); ?>">
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
           <label>Group Customer</label>
           <select name="kd_groupcustomer" class="form-control">
               <option value="" >- Pilih Group Customer -</option>
               <?php if($groupcustomer && (is_array($groupcustomer->message) || is_object($groupcustomer->message))): foreach ($groupcustomer->message as $key => $value) : ?>
                 <option value="<?php echo $value->KD_GROUPCUSTOMER;?>" <?php echo ($value->KD_GROUPCUSTOMER == $list->message[0]->KD_GROUPCUSTOMER ? "selected" : "");?>><?php echo $value->KD_GROUPCUSTOMER;?> - <?php echo $value->NAMA_GROUPCUSTOMER;?></option>
             <?php endforeach; endif;?>
         </select>
     </div>

   <div class="form-group">
           <label>Tipe (wajib diisi)</label>
           <select name="kd_typecustomer" class="form-control">
               <option value="" >- Pilih Tipe Customer -</option>
               <?php if($typecustomer && (is_array($typecustomer->message) || is_object($typecustomer->message))): foreach ($typecustomer->message as $key => $value) : ?>
                 <option value="<?php echo $value->KD_TYPECUSTOMER;?>" <?php echo ($value->KD_TYPECUSTOMER == $list->message[0]->KD_TYPECUSTOMER ? "selected" : "");?>><?php echo $value->NAMA_TYPECUSTOMER;?></option>
             <?php endforeach; endif;?>
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