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
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Target SF</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_targetsf/'.$list->message[0]->ID);?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Nama Sales</label>
      <select name="kd_sales" class="form-control">
        <?php if($saless && (is_array($saless->message) || is_object($saless->message))): foreach ($saless->message as $key => $value) : ?>
          <option value="<?php echo $value->KD_SALES;?>" <?php echo ($value->KD_SALES == $list->message[0]->KD_SALES ? "selected" : "");?>><?php echo $value->KD_SALES;?> - <?php echo $value->NAMA_SALES;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    <div class="form-group">
     <label>Periode Mulai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy"/>
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
   </div>
   <div class="form-group">
     <label>Periode Selesai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
   </div>
   <div class="form-group">
    <label>Target</label>
    <input id="target" type="text" name="target" class="form-control" value="<?php echo  $list->message[0]->TARGET; ?>">
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

