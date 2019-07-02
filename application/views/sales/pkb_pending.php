<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('pkb/simpan_alasan/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Alasan Pending</h4>
</div>

<div class="modal-body">

      <div class="form-group">
          <label>Alasan Pending</label>
          <textarea name="alasan_pending" rows="8" placeholder="Alasan pending" class="form-control" required=""><?php echo $list->message[0]->ALASAN_PENDING;?></textarea>
      </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default batal-btn" data-dismiss="modal">Batal</button>
  <button id="alasan-btn" type="submit" class="btn btn-danger alasan-btn <?php echo $status_e?>">Simpan</button>
</div>

</form>
<!-- <script type="text/javascript">
  
$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});

</script> -->

