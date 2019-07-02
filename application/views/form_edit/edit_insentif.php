<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Edit Master Insentif</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_insentif/'.$list->message[0]->ID);?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Kategori</label>
      <select name="kategori" id="kategori" class="form-control">
       <option value="<?php echo $list->message[0]->KATEGORI;?>"><?php echo $list->message[0]->KATEGORI;?></option>
       <option value="Reguler">Reguler</option>
       <option value="SWAT">SWAT</option>
       <option value="WING">WING</option>
       <option value="Kepala Sales">Kepala Sales</option>
       <option value="Kepala Counter">Kepala Counter</option>
       <option value="SC Reguler">SC Reguler</option>
       <option value="SC WING">SC WING</option>
     </select>
   </div>
   <?php
      if($list->message[0]->KATEGORI == "SWAT" || $list->message[0]->KATEGORI == "WING" || $list->message[0]->KATEGORI == "SC WING" ){
        ?>
      <div class="form-group" id="kd_motor">
    <label>Kode Item</label>
    <select name="kd_motor" class="form-control">
      <option value="">- Pilih Item -</option>
      <?php if($typemotors && (is_array($typemotors->message) || is_object($typemotors->message))): foreach ($typemotors->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_TYPEMOTOR;?>" <?php echo ($value->KD_TYPEMOTOR == $list->message[0]->KD_MOTOR ? "selected" : "");?>><?php echo $value->KD_TYPEMOTOR;?> - <?php echo $value->NAMA_TYPEMOTOR;?> - <?php echo $value->NAMA_PASAR;?> - <?php echo $value->KET_WARNA;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
        <?php
      }else{
        ?>
        <div class="form-group" id="kd_category">
    <label>Kategori Motor</label>
    <select name="kd_category" class="form-control">
      <option value="">- Pilih Kategori Motor -</option>
      <?php if($category && (is_array($category->message) || is_object($category->message))): foreach ($category->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_CATEGORY;?>" <?php echo ($value->KD_CATEGORY == $list->message[0]->KD_CATEGORY ? "selected" : "");?>><?php echo $value->NAMA_CATEGORY;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
        
        <?php
      }
   ?>
   
  <div class="form-group">
    <label>Cash</label>
    <input id="cash" type="text" name="cash" class="form-control" value="<?php echo $list->message[0]->CASH;?>">
  </div>
  <div class="form-group">
    <label>Kredit</label>
    <input id="kredit" type="text" name="kredit" class="form-control" value="<?php echo $list->message[0]->KREDIT;?>">
  </div>
  <div class="form-group">
    <label>Khusus</label>
    <input id="khusus" type="text" name="khusus" class="form-control" value="<?php echo $list->message[0]->KHUSUS;?>">
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(e){

    $('#cash')
    .focusout(function(){

    })
    .ForceNumericOnly()
    $('#kredit')
    .focusout(function(){

    })
    .ForceNumericOnly()
    $('#khusus')
    .focusout(function(){

    })
    .ForceNumericOnly()

    $('#kategori').on('change', function() {
      if ( this.value == 'SWAT' || this.value == 'WING' || this.value == 'SC WING')
      {
        $("#kd_motor").show();
        $("#kd_category").show();
      }
      else if(this.value == 'Reguler' || this.value == 'Kepala Sales' || this.value == 'Kepala Counter' || this.value == 'SC Reguler')
    {
      $("#kd_motor").hide();
      $("#kd_category").show();
    }else{
      $("#kd_motor").hide();
      $("#kd_category").hide();
    }
    });

  });

</script>