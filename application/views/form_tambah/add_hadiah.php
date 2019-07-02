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
  <h4 class="modal-title" id="myModalLabel">Tambah Program Hadiah</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('setup/add_hadiah_simpan'); ?>">

    <div class="form-group">
      <label>Kode Program</label>
      <input id="kd_program" type="text" name="kd_program" class="form-control" placeholder="AUTO GENERATE" disabled="disabled">
    </div>

    <div class="form-group">
      <label>Nama Program</label>
      <input type="text" name="nama_program" id="nama_program" class="form-control">
    </div>
    <div class="form-group">
      <label>Tangga Mulai</label>
      <div class="input-group input-append date" id="date">
        <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>

    <div class="form-group">
      <label>Tanggal Selesai</label>
      <div class="input-group input-append date" id="date">
        <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>
    <div class="form-group">
      <label>Batas Cetak</label>
      <div class="input-group input-append date" id="date">
        <input type="text" class="form-control" id="end_print" name="end_print" value="" placeholder="dd/mm/yyyy" />
        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
      </div>
    </div>
    <div class="form-group">
      <label>Nama Hadiah</label>
      <input type="text" name="nama_hadiah" id="nama_hadiah" class="form-control">
    </div>
    <div class="form-group">
      <label>Jumlah Hadiah</label>
      <input type="text" name="jumlah" id="jumlah" class="form-control">
    </div>
    <div class="form-group">
      <label>Share Dealer</label>
      <input type="text" name="share_d" id="share_d" class="form-control">
    </div>
    <div class="form-group">
      <label>Share MD</label>
      <input type="text" name="share_md" id="share_md" class="form-control">
    </div>
    <div class="form-group">
      <label>Share AHM</label>
      <input type="text" name="share_ahm" id="share_ahm" class="form-control">
    </div>
    <div class="form-group">
      <label>Tipe Motor</label>
      <select  name="kd_typemotor[]" id="kd_typemotor" class="selectpicker form-control" multiple value="" data-live-search="true">
        <option value="0">- Pilih Tipe Motor -</option>
        <?php
        if (isset($tipe)) {
          if (($tipe->totaldata)) {
            foreach ($tipe->message as $key => $value) {
             echo "<option value='" . $value->KD_TYPEMOTOR . "'>" . $value->KD_TYPEMOTOR . "</option>";
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