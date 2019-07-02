<form id="addForm" class="bucket-form" action="<?php echo base_url('company/add_mobil_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Mobil</h4>
</div>

<div class="modal-body">

      <div class="form-group">
        <label>Nomor Polisi</label>
        <input id="no_polisi" type="text" name="no_polisi" class="form-control" placeholder="Masukkan nomor polisi" required>
      </div>

      <div class="form-group">
        <label>Merek</label>
        <input type="text" name="merek" id="merek" class="form-control" placeholder="Masukkan merek" required>
      </div>
      
</div>


<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
