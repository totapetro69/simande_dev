<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Promo Program</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_promoprogram/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_promo" id="kd_promo" class="form-control" value="<?php echo  $list->message[0]->KD_PROMO; ?>" readonly >
        </div>

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama_program" id="nama_program" class="form-control" value="<?php echo  $list->message[0]->NAMA_PROGRAM; ?>" >
        </div>
		<div class="form-group">
       <label>Tanggal Mulai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
		</div>
		<div class="form-group">
		   <label>Tanggal Selesai</label>
		   <div class="input-group input-append date" id="date">
			   <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
			   <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
		   </div>
		</div>
		<div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo  $list->message[0]->KETERANGAN; ?>">
        </div>
		<div class="form-group">
			<label>Status</label>
			<select name="row_status" class="form-control">
				  <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }else{ echo "Tidak Aktif"; }?> </option>
				  <?php
				  if($list->message[0]->ROW_STATUS == 0){
				  ?>
				  <option value="-1">Tidak Aktif</option>
				  <?php
				  }else{
				  ?>
				  <option value="0">Aktif</option>
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