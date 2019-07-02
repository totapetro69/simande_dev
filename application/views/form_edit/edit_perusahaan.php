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

<form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('company/update_company/' . $list->message[0]->ID); ?>">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Perusahaan : <?php echo  $list->message[0]->NAMA_COMPANY; ?></h4>
    </div>

    <div class="modal-body">
	
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
            <?php if($dealers && (is_array($dealers->message) || is_object($dealers->message))): foreach ($dealers->message as $key => $dealer) : ?>
                <option value="<?php echo $dealer->KD_DEALER;?>" <?php echo ($dealer->KD_DEALER == $list->message[0]->KD_DEALER ? "selected" : "");?>><?php echo $dealer->NAMA_DEALER;?></option>
                <?php endforeach; endif;?>
          </select>
        </div>
        <div class="form-group">
          <label>Kode Perusahaan</label>
          <input type="text" name="kd_company" id="kd_company" class="form-control" value="<?php echo  $list->message[0]->KD_COMPANY; ?>" readonly maxlength="5" required>
        </div>
        <div class="form-group">
          <label>Nama Perusahaan</label>
          <input type="text" name="nama_company" id="nama_company" class="form-control" value="<?php echo  $list->message[0]->NAMA_COMPANY; ?>" required>
        </div>
        <div class="form-group">
          <label>Pimpinan Dealer</label>
          <select class="form-control" id="nik" name="nik" required>
            <?php if($karyawans && (is_array($karyawans->message) || is_object($karyawans->message))): foreach ($karyawans->message as $key => $karyawan) : ?>
              <option value="<?php echo $karyawan->NIK;?>" <?php echo ($karyawan->NIK == $list->message[0]->PIMPINAN_DEALER ? "selected" : "");?>><?php echo $karyawan->NAMA;?></option>
              <?php endforeach; endif;?>
          </select>
        </div>
        <div class="form-group">
          <label>Kepala Gudang</label>
          <select class="form-control" id="nama" name="nama" required>
            <?php if($karyawans && (is_array($karyawans->message) || is_object($karyawans->message))): foreach ($karyawans->message as $key => $karyawan) : ?>
              <option value="<?php echo $karyawan->NIK;?>" <?php echo ($karyawan->NIK == $list->message[0]->KEPALA_GUDANG ? "selected" : "");?>><?php echo $karyawan->NAMA;?></option>
              <?php endforeach; endif;?>
          </select>
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


  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn">Simpan</button>
  </div>
</form>
