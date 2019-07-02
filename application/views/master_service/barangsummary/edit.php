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
  <h4 class="modal-title" id="myModalLabel">Edit Barang Summary : <?php echo $list->message[0]->ID_PART; ?></h4>
</div>
      
  <div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('master_service/update_barang_summary/' . $list->message[0]->ID); ?>">
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
          <label>ID Part</label>
          <input id="id_part" type="text" name="id_part" class="form-control" placeholder="0" value="<?php echo $list->message[0]->ID_PART;?>">
        </div>

        <div class="form-group">
          <label>Harga Beli</label>
          <input id="harga_beli" type="text" name="harga_beli" class="form-control" placeholder="0" value="<?php echo $list->message[0]->HARGA_BELI;?>">
        </div>

        <div class="form-group">
          <label>Harga Jual</label>
          <input id="harga_jual" type="text" name="harga_jual" class="form-control" placeholder="0" value="<?php echo $list->message[0]->HARGA_BELI;?>">
        </div>

        <div class="form-group">
          <label>Diskon</label>
          <input id="diskon" type="text" name="diskon" class="form-control" placeholder="0" value="<?php echo $list->message[0]->DISKON;?>">
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