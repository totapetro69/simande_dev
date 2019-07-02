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
<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/add_leasing_proposal_gc_simpan/'); ?>">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Proposal Group Customer</h4>
</div>

<div class="modal-body">

  
  
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $id; ?>" >
  <div class="form-group">
    <label>ALL LEASING?</label>
    <select name="kd_leasing" id="kd_leasing" class="form-control">
        <option value="">Pilih</option>
         <option value="ALL">YA</option>
         <option value="null">TIDAK</option>
    </select>
  </div>

    <div class="form-group" style='display:none;' id="leasing">
      <label>Leasing</label><br>
      <?php if($leasing && (is_array($leasing->message) || is_object($leasing->message))): foreach ($leasing->message as $key => $value) : ?>
        <input type="checkbox" name="leasing[]" value="<?php echo $value->KD_LEASING;?>"><?php echo $value->KD_LEASING;?> - <?php echo $value->NAMA_LEASING;?><br>
      <?php endforeach; endif;?>
    </div>



</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>
</form>
<script type="text/javascript">
  $(document).ready(function(e){
   $('#kd_leasing').on('change', function() {
    if ( this.value == 'null')
    {
      $("#leasing").show();
    }else{
      $("#leasing").hide();
    }
  });

 });

</script>