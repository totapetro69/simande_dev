  <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
  ?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('modul/update_modul/'.$list->message[0]->ID);?>" method="post">
  
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Modul : <?php echo $list->message[0]->NAMA_MODUL;?></h4>
</div>

<div class="modal-body">


    <div class="row">
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <label>Kode Modul</label>
            <input id="kd_modul" type="text" name="kd_modul" class="form-control" placeholder="masukan Kode Modul" value="<?php echo $list->message[0]->KD_MODUL;?>" readonly>
        </div>
      </div>
      <div class="col-xs-12 col-md-5">
        <div class="form-group">
            <label>Nama Modul</label>
            <input id="nama_modul" type="text" name="nama_modul" class="form-control" placeholder="masukan Nama Modul" value="<?php echo $list->message[0]->NAMA_MODUL;?>" required>
        </div>
      </div>
      <div class="col-xs-12 col-md-4">
        <div class="form-group">
            <label>Icon Modul</label>
            <input id="icon_modul" type="text" name="icon_modul" class="form-control" placeholder="masukan Nama Icon" value="<?php echo $list->message[0]->ICON_MODUL;?>">
        </div>

      </div>


    </div>




    <div class="row">
      <div class="col-xs-12 col-md-2">
        <div class="form-group">
            <label>Urutan</label>
            <input id="urutan_modul" type="number" name="urutan_modul" class="form-control  input-number" placeholder="masukan Urutan" value="<?php echo $list->message[0]->URUTAN_MODUL;?>" min="1" required>
        </div>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <label>Link Modul</label>
            <input id="link_modul" type="text" name="link_modul" class="form-control" placeholder="masukan Alamat Link" value="<?php echo $list->message[0]->LINK_MODUL;?>">
        </div>
      </div>
      <div class="col-xs-12 col-md-3">
        <div class="form-group">
          <label>Parent</label>
          <select name="parent_modul" class="form-control">
            <option value="" <?php echo ('' == $list->message[0]->KD_MODUL ? "selected" : "");?> >- NULL -</option>
            <?php if($moduls && (is_array($moduls->message) || is_object($moduls->message))): foreach ($moduls->message as $key => $modul) : ?>
              <option value="<?php echo $modul->KD_MODUL;?>"  <?php echo ($modul->KD_MODUL == $list->message[0]->PARENT_MODUL ? "selected" : "");?> ><?php echo $modul->NAMA_MODUL;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
      </div>
      <div class="col-xs-12 col-md-2">
        <div class="form-group">
            <br>
            <input id="parent_status" name="parent_status" type="checkbox" <?php echo ($list->message[0]->PARENT_STATUS == 1 ? "checked" : "");?>> Tidak memiliki submenu
        </div>
      </div>
      <div class="col-xs-12 col-md-2">
        <div class="form-group">
            <label>Status</label>
            <select name="row_status" class="form-control">
              <option value="0" <?php echo ($list->message[0]->ROW_STATUS == 0?'selected':'');?> >Aktif</option>
              <option value="-1" <?php echo ($list->message[0]->ROW_STATUS == -1?'selected':'');?> >Tidak Aktif</option>

            </select>
        </div>
      </div>
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

