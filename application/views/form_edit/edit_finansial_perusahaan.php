<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Leasing : <?php echo $list->message[0]->NAMA_COFIN;?></h4>
</div>

<div class="modal-body">

<form id="addForm" class="bucket-form" action="<?php echo base_url('company/update_finansial_perusahaan/'.$list->message[0]->ID);?>" method="post">
      

          <div class="form-group">
              <label>Kode Finansial Perusahaan</label>
              <input id="kd_cofin" type="text" name="kd_cofin" class="form-control" value="<?php echo $list->message[0]->KD_COFIN;?>" readonly>
          </div>

           <div class="form-group">
              <label>Nama Finansial Perusahaan</label>
              <input id="nama_cofin" type="text" name="nama_cofin" class="form-control" value="<?php echo $list->message[0]->NAMA_COFIN;?>" readonly>
          </div>

          <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" value="<?php echo $list->message[0]->ALAMAT;?>" >
          </div>

         <!--<div class="form-group">
            <label>Wilayah Dealer</label>
            <select name="kd_wilayah" class="form-control">
            <option value="">- Pilih Wilayah dealer -</option>
             <?php if($wilayahs): foreach ($wilayahs->message as $key => $wilayah) : ?>
            <option value="<?php echo $wilayah->KD_WILAYAH;?>" <?php echo ($wilayah->KD_WILAYAH == $list->message[0]->KD_WILAYAH ? "selected" : "");?> ><?php echo $wilayah->NAMA_WILAYAH;?></option>
             <?php endforeach; endif;?>
            </select>
        </div> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<!-- <script type="text/javascript">
  
$(document).ready(function(){
  $("#username").change(function(){
      alert($("#username").val());
  });
});

</script> -->

