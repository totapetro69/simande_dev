<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_divisi_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Divisi</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Kode Divisi</label>
        <input id="kd_div" type="text" name="kd_div" class="form-control" placeholder="Masukkan Kode Divisi" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Nama Divisi</label>
        <input type="text" name="nama_div" id="nama_div" class="form-control" placeholder="Masukkan Nama Divisi" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
