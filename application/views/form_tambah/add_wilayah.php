<form id="addForm" class="bucket-form" action="<?php echo base_url('dealer/add_wilayah_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Wilayah Dealer</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Kode Wilayah</label>
        <input id="kd_wilayah" type="text" name="kd_wilayah" class="form-control" placeholder="Masukkan Kode Wilayah" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Nama Wilayah</label>
        <input type="text" name="nama_wilayah" id="nama_wilayah" class="form-control" placeholder="Masukkan Nama Wilayah" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
