<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_supir_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Delivery Man</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Nama Delivery Man</label>
        <input id="nama_supir" type="text" name="nama_supir" class="form-control" placeholder="Masukkan nama Delivery Man" required>
      </div>

      <div class="form-group">
        <label>No HP</label>
        <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan nomor hp" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
