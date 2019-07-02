<form id="addForm" class="bucket-form" action="<?php echo base_url('user/store_user_otorisasi');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah User Otorisasi</h4>
</div>

<div class="modal-body">


      <div class="row">

        <div class="col-xs-6 col-sm-6 col-md-6">

          <div class="form-group">
            <label>Grup User</label>
            <select name="kd_group" class="form-control" required>
              <option value="">- Pilih grup -</option>
              <?php if($groups  && (is_array($groups->message) || is_object($groups->message))): foreach ($groups->message as $key => $group) : ?>
                <option value="<?php echo $group->KD_GROUP;?>"><?php echo $group->NAMA_GROUP;?></option>
              <?php endforeach; endif;?>
            </select>
          </div>

        </div>


        <div class="col-xs-6 col-sm-6 col-md-6">
          
          <div class="form-group">
            <label>Menu</label>
            <select id="kd_modul" name="kd_modul" class="form-control" required>
              <option value="">- Pilih Menu -</option>
              <?php if($moduls  && (is_array($moduls->message) || is_object($moduls->message))): foreach ($moduls->message as $key => $modul) : ?>
                <option value="<?php echo $modul->KD_MODUL;?>"><?php echo $modul->NAMA_MODUL;?></option>
              <?php endforeach; endif;?>
            </select>
          </div>

        </div>
      </div>


      <div class="checkbox">
        <label>
          <input id="c" name="c"  type="checkbox"> Create
        </label>
  
        <label>
          <input id="e" name="e"  type="checkbox"> Edit
        </label>
  
        <label>
          <input id="v" name="v"  type="checkbox"> View
        </label>
  
        <label>
          <input id="p" name="p"  type="checkbox"> Print
        </label>
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

