
<div class="modal-header">
  <h4 class="modal-title" id="myModalLabel">Tambah Master Insentif</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_insentif_simpan');?>" method="post">
    <div class="form-group">
      <label>Kategori</label>
      <select name="kategori" id="kategori" class="form-control">
       <option value="">- Pilih Kategori -</option>
       <option value="Reguler">Reguler</option>
       <option value="SWAT">SWAT</option>
       <option value="WING">WING</option>
       <option value="Kepala Sales">Kepala Sales</option>
       <option value="Kepala Counter">Kepala Counter</option>
       <option value="SC Reguler">SC Reguler</option>
       <option value="SC WING">SC WING</option>
     </select>
   </div>
   <div class="form-group" style='display:none;' id="kd_motor">
    <label>Kode Item</label>
    <select name="kd_motor" class="form-control">
      <option value="">- Pilih Item -</option>
      <?php if($typemotors && (is_array($typemotors->message) || is_object($typemotors->message))): foreach ($typemotors->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_TYPEMOTOR;?>"><?php echo $value->KD_TYPEMOTOR;?> - <?php echo $value->NAMA_TYPEMOTOR;?> - <?php echo $value->NAMA_PASAR;?> - <?php echo $value->KET_WARNA;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
  <div class="form-group" style='display:none;' id="kd_category">
    <label>Kategori Motor</label>
    <select name="kd_category" id="kd_category" class="form-control" >
      <option value="">- Pilih Kategori Motor -</option>
      <?php if($category && (is_array($category->message) || is_object($category->message))): foreach ($category->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_CATEGORY;?>"><?php echo $value->NAMA_CATEGORY;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
   <div class="form-group">
    <label>Cash</label>
    <input id="cash" type="text" name="cash" class="form-control">
  </div>
  <div class="form-group">
    <label>Kredit</label>
    <input id="kredit" type="text" name="kredit" class="form-control">
  </div>
  <div class="form-group">
    <label>Khusus</label>
    <input id="khusus" type="text" name="khusus" class="form-control">
  </div>


</form>


</div>

<div class="modal-footer">
  <a class="btn btn-default" href="<?php echo base_url('setup/insentif');?>">Batal</a>
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
      $("#kd_category").hide();
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


