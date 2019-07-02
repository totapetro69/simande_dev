<?php

if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth/true');
}
$defaultDealer = $this->session->userdata("kd_dealer");

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Defaultrakvsparts : <?php echo $list->message[0]->KD_DEALER; ?></h4>
</div>
      
  <div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part/update_defaultrakvsparts/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo $list->message[0]->ID; ?>" >
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
            <?php if($dealers && (is_array($dealers->message) || is_object($dealers->message))): foreach ($dealers->message as $key => $dealer) : ?>
                <option value="<?php echo $dealer->KD_DEALER;?>" <?php echo ($dealer->KD_DEALER == $list->message[0]->KD_DEALER ? "selected" : "");?>><?php echo $dealer->NAMA_DEALER;?></option>
                <?php endforeach; endif;?>
          </select>
        </div>

        <div class="form-group">
          <label>Part Number</label>
          <input type="text" name="part_number" id="part_number" readonly class="form-control" value="<?php echo $list->message[0]->PART_NUMBER; ?>"><?php echo $list->message[0]->PART_NUMBER; ?>
        </div>

        <div class="form-group">
          <label>Lokasi</label>
          <select class="form-control" id="kd_lokasi" name="kd_lokasi" required>
            <?php if($raks && (is_array($raks->message) || is_object($raks->message))): foreach ($raks->message as $key => $rak) : ?>
              <option value="<?php echo $rak->KD_LOKASI;?>" <?php echo ($rak->KD_LOKASI == $list->message[0]->LOKASI_RAK_BIN_ID ? "selected" : "");?>><?php echo $rak->KD_LOKASI;?></option>
              <?php endforeach; endif;?>
          </select>
        </div>

        <div class="form-group">
          <label>Keterangan</label>
          <textarea type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo $list->message[0]->KETERANGAN ; ?>" ><?php echo $list->message[0]->KETERANGAN; ?></textarea>
        </div>

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


 <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>