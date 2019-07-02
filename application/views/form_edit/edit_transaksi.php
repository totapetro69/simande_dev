<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

//$defaultDealer = $this->session->userdata("kd_dealer");

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit  Master Transaksi</h4>
</div>
<div class="modal-body">
    <form id="addForm2" class="bucket-form" method="post" action="<?php echo base_url('finance/update_transaksi/' . $list->message[0]->KD_TRANS); ?>">
        <div class="form-group">
            <label>Kode Transaksi</label>
            <input type="text" name="kd_trans" id="kd_trans" class="form-control" value="<?php echo  $list->message[0]->KD_TRANS; ?>" readonly>
        </div>
        <div class="form-group">
            <label>Nama Transaksi</label>
            <input type="text" name="nama_trans" id="nama_trans" class="form-control" value="<?php echo  $list->message[0]->NAMA_TRANS; ?>">
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select name="tipe" class="form-control">
               <option value="<?php echo $list->message[0]->TIPE_TRANS;?>"> <?php if($list->message[0]->TIPE_TRANS == "D"){echo "Debit"; }ELSE{ echo "Kredit"; }?> </option>
               <?php
               if($list->message[0]->TIPE_TRANS == "D"){
                   ?>
                   <option value="K">Kredit</option>
                   <?php
               }else{
                   ?>
                   <option value="D">Debit</option>
                   <?php
               }
               ?>
           </select>
       </div>
       <div class="form-group">
        <label></label>
        <?php
            if ($list->message[0]->TIPE_AR== "1") {
              ?>
              <input type="checkbox" name="ar" value="<?php echo  $list->message[0]->TIPE_AR; ?>" <?php echo (($list->message[0]->TIPE_AR == 1) ? "checked=checked" : "");?> > A/R
              <?php
            }else{
              ?>
              <input type="checkbox" name="ar" value="1"> A/R
              <?php
            }
        ?>
        
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
    <!--<input type="submit" form="addForm2"/>-->
     <button id="submit-btn" form="addForm2" class="btn btn-danger">Simpan</button>
</div>