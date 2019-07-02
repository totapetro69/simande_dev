<form id="addForm" class="bucket-form" action="<?php echo base_url('motor/add_series_motor_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Seri Motor</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Kode Seri Motor</label>
        <input id="kd_series" type="text" name="kd_series" class="form-control" placeholder="Masukkan Kode Series Motor" maxlength="5" required>
      </div>

      <div class="form-group">
        <label>Nama Seri Motor</label>
        <input type="text" name="nama_series" id="nama_series" class="form-control" placeholder="Masukkan Nama Seri Motor" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
