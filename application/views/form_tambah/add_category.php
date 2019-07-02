<form id="addForm" class="bucket-form" action="<?php echo base_url('motor/add_category_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Kategori Motor</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Kode Kategori Motor</label>
        <input id="kd_category" type="text" name="kd_category" class="form-control" placeholder="Masukkan Kode Kategori Motor" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Nama Kategori Motor</label>
        <input type="text" name="nama_category" id="nama_category" class="form-control" placeholder="Masukkan Nama Kategori Motor" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
