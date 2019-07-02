<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('user/update_user_group/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit User Group : <?php echo $list->message[0]->NAMA_GROUP;?></h4>
</div>

<div class="modal-body">

      <div class="form-group">
          <label>Kode Group</label>
          <input id="kd_group" type="text" name="kd_group" class="form-control" value="<?php echo $list->message[0]->KD_GROUP;?>" readonly>
      </div>

      <div class="form-group">
          <label>Nama Group</label>
          <input id="nama_group" type="text" name="nama_group" class="form-control" value="<?php echo $list->message[0]->NAMA_GROUP;?>" required>
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


      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn <?php echo $status_e?>">Simpan</button>
</div>

</form>
<!-- <script type="text/javascript">
  
$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});

</script> -->

