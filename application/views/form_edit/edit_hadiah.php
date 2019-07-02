<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Program Hadiah</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/update_hadiah/'.$list->message[0]->ID);?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <div class="form-group">
      <label>Kode Program</label>
      <input id="kd_program" type="text" name="kd_program" class="form-control" value="<?php echo $list->message[0]->KD_PROGRAM;?>" disabled="disabled">
    </div>

    <div class="form-group">
      <label>Nama Program</label>
      <input type="text" name="nama_program" id="nama_program" value="<?php echo $list->message[0]->NAMA_PROGRAM;?>" class="form-control">
    </div>
    <div class="form-group">
      <label>Tangga Mulai</label>
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
    <div class="form-group">
      <label>Batas Cetak</label>
      <div class="input-group input-append date" id="date">
        <input type="text" class="form-control" id="end_print" name="end_print" value="<?php echo ($list->message[0]->END_PRINT!='')?tglfromSql($list->message[0]->END_PRINT): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>
    <div class="form-group">
      <label>Nama Hadiah</label>
      <input type="text" name="nama_hadiah" id="nama_hadiah" class="form-control" value="<?php echo $list->message[0]->NAMA_HADIAH;?>">
    </div>
    <div class="form-group">
      <label>Jumlah Hadiah</label>
      <input type="text" name="jumlah" id="jumlah" class="form-control" value="<?php echo $list->message[0]->JUMLAH_HADIAH;?>">
    </div>
    <div class="form-group">
      <label>Share Dealer</label>
      <input type="text" name="share_d" id="share_d" class="form-control" value="<?php echo (int)$list->message[0]->SHARE_D;?>">
    </div>
    <div class="form-group">
      <label>Share MD</label>
      <input type="text" name="share_md" id="share_md" class="form-control" value="<?php echo (int)$list->message[0]->SHARE_MD;?>">
    </div>
    <div class="form-group">
      <label>Share AHM</label>
      <input type="text" name="share_ahm" id="share_ahm" class="form-control" value="<?php echo (int)$list->message[0]->SHARE_AHM;?>">
    </div>
    <div class="form-group">
      <label>Tipe Motor</label>
      <select  name="kd_typemotor[]" id="kd_typemotor" class="selectpicker form-control" multiple value="" data-live-search="true">
        <?php
        if (isset($tipe)) {
          if (($tipe->totaldata)) {
            foreach ($tipe->message as $key => $value) {
              $aktif= ((in_array($value->KD_TYPEMOTOR
                , explode(",", $list->message[0]->KD_TYPEMOTOR))) ? "selected" : "");
              echo "<option value='" . $value->KD_TYPEMOTOR . "' " . $aktif . ">" . $value->KD_TYPEMOTOR . "</option>";
            }
          }
        }

        ?>
      </select>
    </div>

  </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
  $(document).ready(function(e){
    $('.selectpicker').selectpicker();

    $('#jumlah')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#share_d')
    .focusout(function(){
    })

    .ForceNumericOnly()

    $('#share_md')
    .focusout(function(){
    })

    .ForceNumericOnly()

  });

</script>