
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Kategori : <?php echo $list->message[0]->KD_DOCNO." ".$list->message[0]->NAMA_DOCNO;?></h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/update_data');?>">
      
        <div class="form-group">
            <label>Kode Nomor Dokumen</label>
            <input type="text" name="kd_docno" id="kd_docno"  value="<?php echo $list->message[0]->KD_DOCNO;?>" readonly="true" required class="form-control" placeholder="Masukkan Kode Nomor Dokumen" >
        </div>

          <div class="form-group">
            <label>Nama Nomor Dokumen</label>
            <input type="text" name="nama_docno" in="nama_docno" value="<?php echo $list->message[0]->NAMA_DOCNO;?>" required class="form-control" placeholder="Masukkan Nama Nomor Dokumen" >
            <!--<?php //endforeach;?>-->
        </div>
        
</form>

      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Keluar</button>
    <button type="button" id="submit-btn" class="btn btn-danger" onclick="addData();">Simpan</button>
</div>