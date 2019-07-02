<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Item Bundling : <?php echo $list->message[0]->TYPE_BUNDLING;?></h4>
</div>

<div class="modal-body">
	<form id="addForm" class="bucket-form" action="<?php echo base_url('motor/edit_detail_bundling_simpan/'.$list->message[0]->ID);?>" method="post">
		<input type="hidden" id="status_bundling" type="text" name="status_bundling" value="<?php echo $list->message[0]->STATUS_BUNDLING;?>" class="form-control" >
		<input type="hidden" id="kd_bundling" type="text" name="kd_bundling" value="<?php echo $list->message[0]->KD_BUNDLING;?>" class="form-control" >
		<div class="form-group">
				<label>Kode Item</label>
					<select name="type_bundling" class="form-control" disabled>
					  <?php if($typemotors && (is_array($typemotors->message) || is_object($typemotors->message))): foreach ($typemotors->message as $key => $value) : ?>
					  <option value="<?php echo $value->KD_APPAREL;?>" <?php echo ($value->KD_APPAREL== $list->message[0]->TYPE_BUNDLING ? "selected" : "");?>><?php echo $value->NAMA_APPAREL;?></option>
					  <?php endforeach; endif;?>
				</select>
				
		</div>
	  <div class="form-group">
		  <label>Jumlah</label>
		  <input id="jumlah" type="text" name="jumlah" class="form-control" value="<?php echo $list->message[0]->JUMLAH; ?>">
	  </div>
	  
	</form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script type="text/javascript">
	
	$(document).ready(function(e){
		
		$('#jumlah')
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