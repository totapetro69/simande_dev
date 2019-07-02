
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Ubah Password</h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('auth/update_password');?>" method="post">
      <div class="form-group">
          <label>Password Lama</label>
          <input id="passold" type="password" name="passold" class="form-control" placeholder="masukan Password Lama" >
      </div>

      <hr>

      <div class="form-group">
          <label>Password Baru</label>
          <input id="password" type="password" name="password" class="form-control" placeholder="masukan Password Baru" >
      </div>

      <div class="form-group">
          <label>Konfirmasi Password</label>
          <input id="passconf" type="password" name="passconf" class="form-control" placeholder="masukan Lagi Password" >
      </div>
</form>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>