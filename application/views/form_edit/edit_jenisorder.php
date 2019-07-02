<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Jenis Order : <?php echo $list->message[0]->NAMA_JENISORDER; ?></h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('Setup/update_jenisorder/' . $list->message[0]->ID); ?>">

        <div class="form-group">
            <label>Kode Jenis Order</label>
            <input type="text" name="kd_jenisorder" id="kd_jenisorder" class="form-control" value="<?php echo  $list->message[0]->KD_JENISORDER; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama Jenis Order</label>
            <input type="text" name="nama_jenisorder" id="nama_jenisorder" class="form-control" value="<?php echo  $list->message[0]->NAMA_JENISORDER; ?>" >
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