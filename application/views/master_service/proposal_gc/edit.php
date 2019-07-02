<?php

if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = ($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$nama_kabupaten = "";
$defaultMainDealer = $this->session->userdata("kd_maindealer");
$nama_kabupaten = "";
$kd_kabupaten = "";
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Proposal Group Customer</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/update_proposal_gc/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Dealer</label>
      <select class="form-control" id="kd_dealer" name="kd_dealer">
        <option value="0">--Pilih Dealer--</option>
        <?php
        if ($dealer) {
          if (($dealer->totaldata > 0)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              //$aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
              echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
              $nama_kabupaten = ($aktif)?NamaWilayah("Kabupaten",$value->KD_KABUPATEN):$nama_kabupaten;
              $kd_kabupaten = $value->KD_KABUPATEN;
            }
          }
        }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label>Kabupaten</label>
      <select name="kd_kabupaten" class="form-control">
        <option value="<?php echo $kd_kabupaten; ?>"><?php echo $nama_kabupaten ?></option>
      </select>
    </div>
    <div class="form-group">
      <label>Nomor Proposal</label>
      <input id="no_trans" type="text" name="no_trans" class="form-control" value="<?php echo  $list->message[0]->NO_TRANS; ?>" disabled="disabled">
    </div>

    <div class="form-group">
      <label>Deskripsi Program</label>
      <input id="desc_program" type="text" name="desc_program" class="form-control" value="<?php echo  $list->message[0]->DESC_PROGRAM; ?>" >
    </div>


    <div class="form-group">
      <label>Tipe Program</label>
      <select name="type" class="form-control">
        <option value="<?php echo $list->message[0]->TYPE;?>"> <?php echo $list->message[0]->TYPE;?> </option>
        <?php
        if($list->message[0]->TYPE == "G-GCSwasta"){
          ?>
          <option value="D-Dinas">D-Dinas</option>
          <?php
        }else{
          ?>
          <option value="G-GCSwasta">G-GCSwasta</option>
          <?php
        }
        ?>
        
        
      </select>
    </div>

    <div class="form-group">
     <label>Tanggal Mulai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
   </div> 
   <div class="form-group">
     <label>Tanggal Selesai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
   </div>
   
  <?php
  if($detail->totaldata > 0){
    ?>
    <div class="form-group">
    <label>KD GC</label>
    <select name="kd_gc" class="form-control disabled-action" readonly>
      <?php if($gc && (is_array($gc->message) || is_object($gc->message))): foreach ($gc->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_GC;?>" <?php echo ($value->KD_GC == $list->message[0]->KD_GC ? "selected" : "");?>><?php echo $value->NAMA_PROGRAM;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
    <div class="form-group">
    <label>Jenis Transaksi</label>
    <select name="jenis_trans" class="form-control disabled-action" readonly>
      <option value="<?php echo $list->message[0]->JENIS_TRANS;?>"> <?php echo $list->message[0]->JENIS_TRANS;?> </option>
      <?php
      if($list->message[0]->JENIS_TRANS == "TUNAI"){
        ?>
        <option value="KREDIT">KREDIT</option>
        <?php
      }else{
        ?>
        <option value="TUNAI">TUNAI</option>
        <?php
      }
      ?>
    </select>
  </div>
    <?php
  }else{
    ?>
    <div class="form-group">
    <label>KD GC</label>
    <select name="kd_gc" class="form-control" required>
      <?php if($gc && (is_array($gc->message) || is_object($gc->message))): foreach ($gc->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_GC;?>" <?php echo ($value->KD_GC == $list->message[0]->KD_GC ? "selected" : "");?>><?php echo $value->NAMA_PROGRAM;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
    <div class="form-group">
    <label>Jenis Transaksi</label>
    <select name="jenis_trans" class="form-control">
      <option value="<?php echo $list->message[0]->JENIS_TRANS;?>"> <?php echo $list->message[0]->JENIS_TRANS;?> </option>
      <?php
      if($list->message[0]->JENIS_TRANS == "TUNAI"){
        ?>
        <option value="KREDIT">KREDIT</option>
        <?php
      }else{
        ?>
        <option value="TUNAI">TUNAI</option>
        <?php
      }
      ?>
    </select>
  </div>
    <?php
  }
  ?>
  

  <div class="form-group">  
    <label>Nomor PO Perusahaan</label>
    <input type="text" name="no_po_perusahaan" id="no_po_perusahaan" class="form-control" value="<?php echo $list->message[0]->NO_PO_PERUSAHAAN;?>">
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

  <!-- <div class="form-group">
    <label>ALL LEASING?</label>
    <select name="kd_leasing" id="kd_leasing" class="form-control">
      <?php
      if($list->message[0]->KD_LEASING == "ALL"){
        ?>
        <option value="<?php echo $list->message[0]->KD_LEASING;?>"> <?php echo $list->message[0]->KD_LEASING;?> </option>
        <option value="null">TIDAK</option>
        <?php
      }else{
        ?>
        <option value="null">TIDAK</option>
        <option value="ALL">YA</option>
        <?php
      }
      ?>
    </select>
  </div>
  <?php
  if($list->message[0]->KD_LEASING != "ALL" || $list->message[0]->KD_LEASING != null){
    ?>
    <div class="form-group" id="leasing">
      <label>Leasing</label><br>
      <?php if($leasing && (is_array($leasing->message) || is_object($leasing->message))): foreach ($leasing->message as $key => $value) : ?>
        <input type="checkbox" name="leasing[]" value="<?php echo $value->KD_LEASING;?>" <?php echo ((in_array($value->KD_LEASING, explode(", ", $list->message[0]->KD_LEASING))) ? "checked=checked" : "");?>><?php echo $value->KD_LEASING;?> - <?php echo $value->NAMA_LEASING;?><br>
      <?php endforeach; endif;?>
    </div>
    <?php
  }
  ?> -->

</form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script type="text/javascript">
  $(document).ready(function(e){
   $('#kd_leasing').on('change', function() {
    if ( this.value == 'null')
    {
      $("#leasing").show();
    }else{
      $("#leasing").hide();
    }
  });

 });

</script>