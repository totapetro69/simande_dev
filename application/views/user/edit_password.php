<form id="addForm" class="bucket-form" action="<?php echo base_url('user/upddate_password/'.$list->message[0]->ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Reset Password</h4>
</div>

<div class="modal-body">

      

      <div class="form-group">
          <label>NIK</label>
          <input id="user_id" type="text" name="user_id" class="form-control" value="<?php echo $list->message[0]->USER_ID;?>" readonly>
      </div>

      <div class="row">

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Password Baru</label>
              <input id="password" type="password" name="password" class="form-control" placeholder="masukan Password Baru" required>
          </div>
        </div>

        <div class="col-xs-6 col-sm-6 col-md-6">
          <div class="form-group">
              <label>Konfirmasi Password</label>
              <input id="passconf" type="password" name="passconf" class="form-control" placeholder="masukan Lagi Password" required>
          </div>
        </div>

      </div>




      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Reset Password</button>
</div>

</form>
<!-- <script type="text/javascript">
  
$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});

</script> -->

