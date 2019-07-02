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
  <h4 class="modal-title" id="myModalLabel">Edit Master Insentif H3</h4>
</div>

<div class="modal-body">
    <div class="row">
        <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_masterinsentifh3/'.$list->message[0]->ID);?>" method="post">
            <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
            <div class="col-xs-12 col-sm-12">
                <div class="form-group" id="kd_dealer">
                    <label>Kode Dealer</label>
                    <select name="kd_dealer" class="form-control">
                      <option value="">- Pilih Dealer -</option>
                    <?php if($dealer && (is_array($dealer->message) || is_object($dealer->message))): foreach ($dealer->message as $key => $value) : ?>
                        <option value="<?php echo $value->KD_DEALER;?>" <?php echo ($value->KD_DEALER == $list->message[0]->KD_DEALER ? "selected" : "");?>><?php echo $value->NAMA_DEALER;?></option>
                      <?php endforeach; endif;?>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12">
                <div class="form-group" id="kd_dealer">
                    <label>Karyawan</label>
                    <select name="nik" class="form-control">
                      <option value="">- Pilih Karyawan -</option>
                    <?php if($karyawan && (is_array($karyawan->message) || is_object($karyawan->message))): foreach ($karyawan->message as $key => $value) : ?>
                        <option value="<?php echo $value->NIK;?>" <?php echo ($value->NIK == $list->message[0]->NIK ? "selected" : "");?>><?php echo $value->NAMA;?></option>
                      <?php endforeach; endif;?>
                    </select>
                </div>
            </div>
              
            <div class="col-xs-12 col-sm-12">
               <div class="form-group">
                 <label>Persentase</label>
                 <input id="persentase" type="text" name="persentase" class="form-control" value="<?php echo $list->message[0]->PERSENTASE;?>">
               </div>
            </div>

            <div class="col-xs-12 col-sm-12">
                <div class="form-group">
                    <label>Status</label>
                    <select name="row_status" class="form-control">
                        <option value="<?php echo $list->message[0]->ROW_STATUS;?>"> <?php if($list->message[0]->ROW_STATUS == 0){echo "Aktif"; }ELSE{ echo "Tidak Aktif"; }?> </option>
                        <?php
                        if($list->message[0]->ROW_STATUS == -1){
                          ?>
                          <option value="0">Aktif</option>
                          <?php
                        }else{
                          ?>
                          <option value="-1">Tidak Aktif</option>
                          <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
  

</script>