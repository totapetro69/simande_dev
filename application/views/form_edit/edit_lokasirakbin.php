<?php

if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth/true');
}
$defaultDealer = $this->session->userdata("kd_dealer");

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$id=0;$kd_lokasi="";$kd_gudang="";$kd_rak="";$kd_binbox="";$nama_gudang="";$rak_default ="0";$keterangan ="";$row_status="0";
if ($list) {
    if (is_array($list->message)) {
      foreach ($list->message as $key => $value) {
        $id = $value->ID;
        $kd_lokasi = $value->KD_LOKASI;
        $kd_dealer = $value->KD_DEALER;
        $kd_gudang = $value->KD_GUDANG;
        $kd_rak = strtoupper($value->KD_RAK);
        $kd_binbox = strtoupper($value->KD_BINBOX);
        $nama_gudang = $value->NAMA_GUDANG;
        $rak_default = $value->RAK_DEFAULT;
        $defaults = $value->DEFAULTS;
        $keterangan = $value->KETERANGAN;
        $row_status = $value->ROW_STATUS;
      }
    }
    $defaultlokasirakbin=($lokasirakbin==1 && $defaults=='0')?'disabled-action':"";
  }
$defaultDealer=$kd_dealer;
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Lokasi/Rak/Bin : <?php echo $list->message[0]->KD_LOKASI; ?></h4>
</div>
      
  <div class="modal-body">

    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('part/update_lokasirakbin/' . $list->message[0]->ID); ?>">
      <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
            <?php
              if ($dealers) {
                if (is_array($dealers->message)) {
                  foreach ($dealers->message as $key => $value) {
                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                    //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                  }
                }
              }
            ?> 
          </select>
        </div>
        <div class="form-group">
          <label>Kode Lokasi</label>
          <div class="input-group input-append">
              <input type="text" name="kd_lokasi" id="kd_lokasi" class="form-control disabled-action" value="<?php echo strtoupper($kd_lokasi);?>" readonly maxlength="5" required>
              <span class="input-group-addon add-on"><input type="checkbox" style="cursor: pointer;" id="rak_default" name="rak_default" 
                <?php echo ($rak_default=="1")?"checked='true'":"";?>></span>
        </div>
      </div>

        <div class="form-group">
          <label>Gudang</label>
          <select class="form-control" id="kd_gudang" name="kd_gudang" required>
            <?php
            if ($gudangs):
              if($gudangs->totaldata>0):
                foreach ($gudangs->message as $key => $gudang) :
                  $select=($gudang->DEFAULTS==1)? 'selected':"";
                  $select=($kd_gudang==$gudang->KD_GUDANG)?'selected':$select;
                  echo "<option value='".$gudang->KD_GUDANG."' ".$select.">[".$gudang->KD_GUDANG."] ". strtoupper($gudang->NAMA_GUDANG)."</option>";
                endforeach;
              endif;
            endif;
          ?>
          </select>
        </div>

        <div class="form-group">
          <label>Kode Rak</label>
          <input type="text" name="kd_rak" id="kd_rak" readonly class="form-control" value="<?php echo $kd_rak;?>" maxlength="5" required>
        </div>

         <div class="form-group">
          <label>Kode Bin</label>
          <input type="text" name="kd_binbox" id="kd_binbox" readonly class="form-control" value="<?php echo $kd_binbox;?>" maxlength="5" required>
        </div>

        <div class="form-group">
          <label>Keterangan</label>
          <textarea type="text" name="keterangan" id="keterangan" class="form-control" value="<?php echo  $list->message[0]->KETERANGAN ; ?>" ><?php echo  $list->message[0]->KETERANGAN; ?></textarea>
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

        <div class="form-group hidden">
          <label>Sebagai Gudang Default</label>
          <select class="form-control <?php echo $defaultlokasirakbin;?>" id="defaults" name="defaults">
            <option value="0" <?php echo ($defaults=="0")?"selected":"";?>>Tidak</option>
            <option value="1" <?php echo ($defaults=="1")?"selected":"";?>>Ya</option>
          </select>
        </div>
      </div>
    </form>

  </div>


 <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>