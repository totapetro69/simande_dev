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
$kd_kabupaten = "";
?>


<form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_proposal_gc_simpan');?>" method="post">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Proposal GC</h4>
</div>

<div class="modal-body">

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
      <input id="no_trans" type="text" name="no_trans" class="form-control" placeholder="AUTO GENERATE" disabled="disabled">
    </div>

    <div class="form-group">
      <label>Deskripsi Program</label>
      <input id="desc_program" type="text" name="desc_program" class="form-control" placeholder="Masukkan Deskripsi Program">
    </div>
    <div class="form-group">
      <label>Tipe Program</label>
      <select name="type" class="form-control">
        <option value="G-GCSwasta">G-GCSwasta</option>
        <option value="D-Dinas">D-Dinas</option>
      </select>
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
      <label>KD GC</label>
      <select name="kd_gc" class="form-control" required>
        <option value="" >- Pilih Master GC -</option>
        <?php if($gc && (is_array($gc->message) || is_object($gc->message))): foreach ($gc->message as $key => $value) : ?>
          <option value="<?php echo $value->KD_GC;?>"><?php echo $value->KD_GC;?> - <?php echo $value->NAMA_PROGRAM;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    <div class="form-group">
      <label>Jenis Transaksi</label>
      <select name="jenis_trans" class="form-control">
        <option value="TUNAI">TUNAI</option>
        <option value="KREDIT">KREDIT</option>
      </select>
    </div>

    <div class="form-group">
      <label>NO PO PERUSAHAAN</label>
      <input id="no_po_perusahaan" type="text" name="no_po_perusahaan" class="form-control" placeholder="">
    </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>
</form>

<!-- <script type="text/javascript">
  $(document).ready(function(e){

   $("#kd_gc").change(function(){
      var kd_gc = $(this).val();
            $.getJSON("<?php echo base_url("setup/get_gc_new");?>",
                {'kd_gc':kd_gc},
                  function(result){
                    if(result.status == true){
                      $.each(result.message,function(e,d){
                      $("#start_date").setAttribute("min", d.START_DATE);
                      $("#start_date").setAttribute("max", d.END_DATE);
                      $("#end_date").setAttribute("max", d.END_DATE);

                   })
                   }
                }
                )
          });

 });

</script> -->