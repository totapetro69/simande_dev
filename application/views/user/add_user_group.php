<form id="addForm" class="bucket-form" action="<?php echo base_url('user/store_user_group');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah User Group</h4>
</div>

<div class="modal-body">

      <div class="form-group">
          <label>Kode Group</label>
          <input id="kd_group" type="text" name="kd_group" class="form-control" placeholder="masukan Kode Group" maxlength="5" required>
      </div>

      <div class="form-group">
          <label>Nama Group</label>
          <input id="nama_group" type="text" name="nama_group" class="form-control" placeholder="masukan Nama Group" required>
      </div>



      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>
<!-- <script type="text/javascript">
  
$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});

</script> -->

