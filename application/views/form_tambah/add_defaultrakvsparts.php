<?php
$defaultDealer = $this->session->userdata("kd_dealer");
$part_number="";
$kd_gudang="";
$kd_lokasi="";$keterangan="";$status="";$id_data="";
if(isset($list)){
  if($list->totaldata > 0){
    foreach ($list->message as $key => $value) {
      $defaultDealer = $value->KD_DEALER;
      $part_number = $value->PART_NUMBER;
      $kd_gudang =$value->KD_GUDANG;
      $kd_lokasi =$value->KD_LOKASI;
      $keterangan = $value->KETERANGAN;
      $status = $value->ROW_STATUS;
      $id_data = $value->ID;
    }
  }

}
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('part/add_defaultrakvsparts_simpan');?>" method="post">

  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah</h4>
</div>


<div class="modal-body">
  <div class="form-group">
    <label>Dealer</label>
    <select class="form-control <?php echo ($id_data)?'disabled-action':'';?>" id="kd_dealer" name="kd_dealer">
      <option value="0">--Pilih Dealer--</option>
      <?php
        if ($dealer) {
          if (is_array($dealer->message)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              $aktif = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $aktif;
              echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
            }
          }
        }
      ?> 
    </select>
    <input type="hidden" name="id" value='<?php echo $id_data;?>'>
  </div>


  <div class="form-group">
    <label>Part Number</label>
    <input type="text" name="part_number" id="part_number" class="form-control <?php echo ($id_data)?'disabled-action':'';?>" placeholder="Masukkan Part Number" value="<?php echo $part_number;?>">  
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 no-margin-l">
    <div class="form-group">
      <label>Gudang</label>
      <select class="form-control" id="kd_gudang" name="kd_gudang" required>
        <option value="">- Pilih Gudang -</option>
          <?php
            if(isset($gudang)){
              if($gudang->totaldata>0){
                foreach ($gudang->message as $key => $value) {
                  $select =($kd_gudang== $value->KD_GUDANG)?"selected":"";
                 ?>
                  <option value='<?php echo $value->KD_GUDANG;?>' class='<?php echo $value->KD_GUDANG;?>' <?php echo $select;?>>
                    <?php echo $value->NAMA_GUDANG." [".$value->KD_GUDANG."]";?>
                  </option>
                 <?php
                }
              }
            }
          ?>  
      </select>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 no-margin-r">
    <div class="form-group">
      <label>Rak / Bin</label>
      <select class="form-control" id="kd_lokasi" name="kd_lokasi" required>
        <option value="">-Pilih Rak/Bin-</option>
          <?php
            if(isset($raks)){
              if($raks->totaldata>0){
                foreach ($raks->message as $key => $value) {
                  $select =($kd_lokasi == $value->KD_LOKASI)?"selected":"";
                 ?>
                  <option value='<?php echo strtoupper($value->KD_LOKASI);?>' class='<?php echo $value->KD_GUDANG;?> <?php echo ($id_data && ($value->KD_GUDANG==$kd_gudang))?"":"hidden";?>' <?php echo $select;?>>
                    <?php echo strtoupper($value->KD_LOKASI);?>
                  </option>
                 <?php
                }
              }
            }
          ?>  
      </select>
    </div>
  </div>
  <div class="form-group">
      <label>Keterangan</label>
      <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan"><?php echo $keterangan;?></textarea>
  </div>
 
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
   <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
</div>

</form>

<script type="text/javascript">
  $(document).ready(function(){
    $('#kd_gudang').on('change',function(){
      $('#kd_lokasi option').addClass('hidden');
      $('#kd_lokasi option.'+$(this).val()).removeClass('hidden');
      $('#kd_lokasi').val('').selected();
    })
  })
  $("#part_number").typeahead({
            source: function (query, process) {
                $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
                return $.get('<?php echo base_url("part/part_detail_typeahead?d=y"); ?>', {keyword: query}, function (data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    $('#fd').html('');
                    return process(data.keyword);
                })
            },
            minLength: 3,
            limit: 20
        });
</script>





