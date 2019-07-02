
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Tipe Motor Marketing</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('motor/add_typemotorm_simpan');?>" method="post">
    <div class="form-group">
        <label>Kode Tipe Motor Marketing</label>
        <input id="type_marketing" type="text" name="type_marketing" class="form-control" placeholder="Masukkan kode tipe motor marketing" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Deskrpisi/ Varian</label>
        <input type="text" name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukkan deskripsi/ varian" required>
      </div>
</form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>


