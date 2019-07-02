
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Kategori : 
    <?php echo $list->message[0]->NO_SURAT_JALAN." ".$list->message[0]->TGL_SHIPPING;?></h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('surat_jalan/update_sj');?>">
      
        <div class="form-group">
            <label>No Surat</label>
            <input type="text" name="no_surat_jalan" id="no_surat_jalan"  value="<?php echo $list->message[0]->NO_SURAT_JALAN;?>" readonly="true" required class="form-control" placeholder="Masukkan No Surat" >
        </div>

          <div class="form-group">
            <label>Tgl Shipping</label>
            <input type="text" name="tgl_shipping" in="tgl_shipping" value="<?php echo $list->message[0]->TGL_SHIPPING;?>" required class="form-control" placeholder="Masukkan Tgl Shipping" >
            <!--<?php //endforeach;?>-->
        </div>
        
</form>

      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
    <button type="button" id="submit-btn" class="btn btn-danger" onclick="addData();">Simpan</button>
</div>