
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Diskon</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_diskon_simpan');?>" method="post">
    <div class="form-group">
      <label>Kode</label>
      <input id="kd_diskon" type="text" name="kd_diskon" class="form-control" placeholder="Masukkan kode diskon" >
    </div>

    <div class="form-group">
      <label>Nama Diskon</label>
      <input id="nama_diskon" type="text" name="nama_diskon" class="form-control" placeholder="Masukkan nama diskon">
    </div>
    
    <div class="form-group">
      <label>Jenis Customer</label>
      <select name="kd_jeniscustomer" class="form-control">
        <?php if($jeniscustomers && (is_array($jeniscustomers->message) || is_object($jeniscustomers->message))): foreach ($jeniscustomers->message as $key => $jeniscustomer) : ?>
          <option value="<?php echo $jeniscustomer->KD_JENISCUSTOMER;?>"><?php echo $jeniscustomer->NAMA_JENISCUSTOMER;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    
    <div class="form-group">
      <label>Tipe Diskon</label>
      <select name="tipe_diskon" class="form-control">
       <option value="1">RUPIAH</option>
       <option value="0">PERSEN</option>
     </select>
   </div>
   
   <div class="form-group">
    <label>Besar Diskon</label>
    <input id="amount" type="text" name="amount" class="form-control">
  </div>
  
  <div class="form-group">
   <label>Tanggal Mulai</label>
   <div class="input-group input-append date" id="date">
     <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
     <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
   </div>
 </div>
 <div class="form-group">
   <label>Tanggal Selesai</label>
   <div class="input-group input-append date" id="date">
     <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
     <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
   </div>
 </div>
 
</form>


</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
	
	$(document).ready(function(e){
		
		$('#amount')
   .focusout(function(){

   })
   .ForceNumericOnly()
			/*.popover({
			placement:'top',
			html:true,
			title:'<i class=\'fa fa-info-circle fa-fw\'></i> Informasi',
			content:'Informasi demand and supply untuk po bulan ini'
		});*/
		
		//unsetSession(-1);
	});

</script>


