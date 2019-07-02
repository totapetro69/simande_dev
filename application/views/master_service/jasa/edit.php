<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Jasa</h4>
</div>

<div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_jasa/' . $list->message[0]->ID); ?>">
        <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_jasa" id="kd_jasa" class="form-control" value="<?php echo  $list->message[0]->KD_JASA; ?>">
        </div>
        <div class="form-group">
            <label>Kode Item</label>
            <select name="kd_motor" class="form-control">
              <option value="">- Pilih Item -
              </option>
              <?php if($typemotors && (is_array($typemotors->message) || is_object($typemotors->message))): foreach ($typemotors->message as $key => $value) : ?>
                <option value="<?php echo $value->KD_TYPEMOTOR;?>" <?php echo ($value->KD_TYPEMOTOR == $list->message[0]->KD_MOTOR ? "selected" : "");?>><?php echo $value->KD_TYPEMOTOR;?> - <?php echo $value->NAMA_TYPEMOTOR;?> - <?php echo $value->NAMA_PASAR;?>
                </option>
            <?php endforeach; endif;?>
        </select>
    </div>

    <div class="form-group">
        <label>Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan keterangan" value="<?php echo  $list->message[0]->KETERANGAN; ?>">
    </div>
    <div class="form-group">
        <label>FRT</label>
        <input type="text" name="frt" id="frt" class="form-control" placeholder="Masukkan FRT" value="<?php echo  $list->message[0]->FRT; ?>">
    </div>
    <div class="form-group">
        <label>Harga</label>
        <input type="text" name="harga" id="harga" class="form-control" placeholder="0" value="<?php echo  $list->message[0]->HARGA; ?>">
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