<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit Harga Jual</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('sparepart/update_hargapart/' . $list->message[0]->ID); ?>">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >
    <input type="hidden" name="kategori" id="kategori" class="form-control" value="<?php echo  $list->message[0]->KATEGORI; ?>" >
    <input type="hidden" name="part_no" id="part_no" class="form-control" value="<?php echo  $list->message[0]->PART_NUMBER; ?>" >
    <div class="form-group">
      <label>Main Dealer</label>
      <select class="form-control" id="kd_maindealer" name="kd_maindealer" disabled="disabled" required>
        <option value="0">--Pilih Main Dealer-</option>
        <?php
        if ($maindealer) {
          if (is_array($maindealer->message)) {
            foreach ($maindealer->message as $key => $value) {
              $aktif = ($defaultMainDealer == $value->KD_MAINDEALER) ? "selected" : "";
              $aktif = ($this->input->get("kd_maindealer") == $value->KD_MAINDEALER) ? "selected" : $aktif;
              echo "<option value='" . $value->KD_MAINDEALER . "' " . $aktif . ">" . $value->NAMA_MAINDEALER . "</option>";
            }
          }
        }
        ?> 
      </select>
    </div>

    <div class="form-group">
      <label>Dealer</label>
      <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled" required>
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
    </div>
    <div class="form-group">
      <label>Kategori</label>
      <select name="kategori" id="kategori" class="form-control" disabled="disabled">
        <option value="<?php echo $list->message[0]->KATEGORI;?>"><?php echo $list->message[0]->KATEGORI;?></option>
        <option value="">- Pilih Kategori -</option>
        <option value="Barang">Barang (Aksesoris, Apparel)</option>
      </select>
    </div>

    <!-- <div id="ajax-pilih" pilih="<?php echo base_url('sparepart/part_typeahead'); ?>"></div> -->

    <?php
    if ($list->message[0]->KATEGORI == 'Aksesoris'){
      ?>
     <div class="form-group" style='display:none;' id="aksesoris">
    <label>Aksesoris</label>
    <select name="aksesoris" class="form-control" >
      <option value="">- Pilih Aksesoris -</option>
      <?php if($aksesoris && (is_array($aksesoris->message) || is_object($aksesoris->message))): foreach ($aksesoris->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_BARANG;?>" <?php echo ($value->KD_BARANG == $list->message[0]->PART_NUMBER ? "selected" : "");?>><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
      <?php endforeach; endif;?>
    </select>
  </div>
      <?php
    }else if($list->message[0]->KATEGORI == 'Barang'){
      ?>
      <div class="form-group" id="kd_barang">
        <label>Barang</label>
        <select name="kd_barang" class="form-control" disabled="disabled">
          <option value="">- Pilih Barang -</option>
          <?php if($barang && (is_array($barang->message) || is_object($barang->message))): foreach ($barang->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_BARANG;?>" <?php echo ($value->KD_BARANG == $list->message[0]->PART_NUMBER ? "selected" : "");?>><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?> - <?php echo $value->KATEGORI;?></option>
          <?php endforeach; endif;?>
        </select>
      </div>
      <?php
    }else{
      ?>
      <div class="form-group" style='display:none;' id="apparel">
        <label>Apparel</label>
        <select name="apparel" class="form-control" >
          <option value="">- Pilih Apparel -</option>
          <?php if($apparel && (is_array($apparel->message) || is_object($apparel->message))): foreach ($apparel->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_BARANG;?>" <?php echo ($value->KD_BARANG == $list->message[0]->PART_NUMBER ? "selected" : "");?>><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
          <?php endforeach; endif;?>
        </select>
      </div>
      <?php
    }
    ?>
    <div class="form-group">
      <label>Tipe Customer</label>
      <select name="kd_typecustomer" class="form-control" disabled="disabled">
        <?php if($typecustomer && (is_array($typecustomer->message) || is_object($typecustomer->message))): foreach ($typecustomer->message as $key => $value) : ?>
          <option value="<?php echo $value->KD_TYPECUSTOMER;?>" <?php echo ($value->KD_TYPECUSTOMER == $list->message[0]->KD_TYPECUSTOMER ? "selected" : "");?>><?php echo $value->NAMA_TYPECUSTOMER;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    <div class="form-group">
      <label>Harga Beli</label>
      <input id="harga_beli" type="text" name="harga_beli" class="form-control" placeholder="0" value="<?php echo $list->message[0]->HARGA_BELI;?>">
    </div>
    <div class="form-group">
      <label>Harga Jual</label>
      <input id="harga_jual" type="text" name="harga_jual" class="form-control" placeholder="0" value="<?php echo $list->message[0]->HARGA_JUAL;?>">
    </div>
    <div class="form-group">
      <label>Tipe Diskon</label>
      <select name="diskon_type" id="diskon_type" class="form-control">
        <option value="<?php echo $list->message[0]->DISKON_TYPE;?>"><?php echo $list->message[0]->DISKON_TYPE;?></option>
        <option value="">- Pilih Tipe Diskon -</option>
        <option value="Persen">Persen</option>
        <option value="Rupiah">Rupiah</option>
      </select>
    </div>
    <div class="form-group">
      <label>Diskon</label>
      <input id="diskon" type="text" name="diskon" class="form-control" placeholder="0" value="<?php echo $list->message[0]->DISKON;?>">
    </div>
    <div class="form-group">
     <label>Periode Mulai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="start_date" name="start_date" value="<?php echo ($list->message[0]->START_DATE!='')?tglfromSql($list->message[0]->START_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
   </div>
   <div class="form-group">
     <label>Periode Selesai</label>
     <div class="input-group input-append date" id="date">
       <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo ($list->message[0]->END_DATE!='')?tglfromSql($list->message[0]->END_DATE): date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
       <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
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
 </form>
</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>
<script type="text/javascript">
  $(document).ready(function(e){
    
        $('#harga_jual')
       .focusout(function(){

       })
       .ForceNumericOnly()
       $('#harga_beli')
       .focusout(function(){

       })
       .ForceNumericOnly()
       $('#diskon')
       .focusout(function(){

       })
       .ForceNumericOnly()

      $('#kategori').on('change', function() {
        if ( this.value == 'Aksesoris')
      {
        $("#aksesoris").show();
        $("#kd_barang").hide();
        $("#apparel").hide();

      }
      else if(this.value == 'Barang')
      {
        $("#aksesoris").hide();
        $("#kd_barang").show();
        $("#apparel").hide();
      }
      else if(this.value == 'Apparel')
      {
        $("#aksesoris").hide();
        $("#kd_barang").hide();
        $("#apparel").show();
      }
      else{
        $("#aksesoris").hide();
        $("#kd_barang").hide();
        $("#apparel").hide();
      }
      });
      
      $("#keyword_q").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("Sparepart/part_typeahead");?>',{keyword:query},function(data){
            console.log(data);
            data=$.parseJSON(data);
            $('#fd').html('');
            return process(data.keyword);
          })
        },
        minLength:3,
        limit:20
      });
 
  });

</script>