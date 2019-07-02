<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('umsl/update_penerimaan/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Penerimaan Motor</h4>
</div>

<div class="modal-body">

  <div class="row">

    <div class="col-xs-12 col-sm-5">
      <div class="form-group">
          <label>No. Terima SJ</label>
          <input type="text" name="no_terimasjm" class="form-control" value="<?php echo $list->message[0]->NO_TERIMASJM;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-offset-1 col-sm-5">
      <div class="form-group">
          <label>No. SJ Masuk</label>
          <input type="text" name="no_sjmasuk" class="form-control" value="<?php echo $list->message[0]->NO_SJMASUK;?>" readonly>
      </div>
    </div>

  </div>

  <div class="row">

    <div class="col-xs-12 col-sm-3">
      <div class="form-group">
          <label>Nomor Rangka</label>
          <input type="text" name="no_rangka" class="form-control" value="<?php echo $list->message[0]->NO_RANGKA;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-3">
      <div class="form-group">
          <label>Nomor Mesin</label>
          <input type="text" name="no_mesin" class="form-control" value="<?php echo $list->message[0]->NO_MESIN;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-2">
      <div class="form-group">
          <label>KD. Maindealer</label>
          <input type="text" name="kd_maindealer" class="form-control" value="<?php echo $list->message[0]->KD_MAINDEALER;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-2">
      <div class="form-group">
          <label>KD. Dealer</label>
          <input type="text" name="kd_dealer" class="form-control" value="<?php echo $list->message[0]->KD_DEALER;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-2">
      <div class="form-group">
          <label>KD. Item</label>
          <input type="text" name="kd_item" class="form-control" value="<?php echo $list->message[0]->KD_ITEM;?>" readonly>
      </div>
    </div>

  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-3">
      <div class="form-group">
          <label>Expedisi</label>
          <input type="text" name="expedisi" class="form-control" value="<?php echo $list->message[0]->EXPEDISI;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-3">
      <div class="form-group">
          <label>NOPOL</label>
          <input type="text" name="nopol" class="form-control" value="<?php echo $list->message[0]->NOPOL;?>" readonly>
      </div>
    </div>

    <div class="col-xs-12 col-sm-6">
      <div class="form-group">
        <label>KSU</label>

        <div class="checkbox">

          <?php  if($ksu_penerimaan && (is_array($ksu_penerimaan) || is_object($ksu_penerimaan))):  foreach ($ksu_penerimaan as $penerimaan): ?>
              <label>
                <input name="ksu[]" value="<?php echo $penerimaan;?>" type="checkbox" checked><?php echo $penerimaan;?> &nbsp
              </label>
          <?php endforeach; endif;?>

          <?php if($ksu->message && (is_array($ksu->message) || is_object($ksu->message))):  foreach ($ksu->message as $ksu_row): ?>
              <label>
                <input name="ksu[]" value="<?php echo $ksu_row->KD_KSU;?>" type="checkbox"><?php echo $ksu_row->KD_KSU;?> &nbsp
              </label>
          <?php endforeach; endif;?>
        </div>
      </div>
    </div>

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

