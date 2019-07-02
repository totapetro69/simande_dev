<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_jabatan_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Jabatan</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Kode Jabatan</label>
        <input id="kd_jabatan" type="text" name="kd_jabatan" class="form-control" placeholder="Masukkan Kode Jabatan" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Nama Jabatan</label>
        <input type="text" name="nama_jabatan" id="nama_jabatan" class="form-control" placeholder="Masukkan Nama Jabatan" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
