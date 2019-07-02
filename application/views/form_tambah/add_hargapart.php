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
  <h4 class="modal-title" id="myModalLabel">Tambah Harga Jual</h4>
</div>

<form id="addForm" class="bucket-form" action="<?php echo base_url('sparepart/add_hargapart_simpan');?>" method="post">
  <div class="modal-body">
    <div class="row">
      <!-- <div id="ajax-url-filter" url="<?php echo base_url('sparepart/part_typeahead');?>"></div> -->
      <?php
      if($this->session->userdata("nama_group") == "Root"){
        ?>
          <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Main Dealer</label>
          <select name="kd_maindealer" class="form-control">
          <?php if($maindealer && (is_array($maindealer->message) || is_object($maindealer->message))): foreach ($maindealer->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_MAINDEALER;?>"><?php echo $value->NAMA_MAINDEALER;?></option>
          <?php endforeach; endif;?>
        </select>
      </div>
    </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Dealer</label>
          <select name="kd_dealer" class="form-control">
          <?php if($dealer && (is_array($dealer->message) || is_object($dealer->message))): foreach ($dealer->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_DEALER;?>"><?php echo $value->KD_DEALER;?> - <?php echo $value->NAMA_DEALER;?></option>
          <?php endforeach; endif;?>
        </select>
        </div>
      </div>
        <?php
      }else{
        ?>
        <div class="col-xs-12 col-sm-6 col-md-6">
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
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Dealer</label>
          <select class="form-control" id="kd_dealer" name="kd_dealer" required>
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
      </div>
        <?php
      }
      ?>
      
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Kategori</label>
          <select name="kategori" id="kategori" class="form-control">
           <option value="">- Pilih Kategori -</option>
           <option value="Barang">Barang</option>
           <option value="Aksesoris">Aksesoris</option>
           <option value="Apparel">Apparel</option>
         </select>
       </div>
     </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
         <!-- <div class="form-group" style='display:none;' id="part_number">
          <label>Nomor Part <span id="fd"></span></label>
          <input id="keyword_q" type="text" name="part_no" class="form-control" placeholder="0">
      </div> -->
      <!-- </div>
      <div class="col-xs-12 col-sm-6 col-md-6"> -->
        <div class="form-group" style='display:block;' id="kd_barang">
          <label>Barang</label>
          <select name="kd_barang" class="form-control" >
            <option value="">- Pilih Barang -</option>
            <?php if($barang && (is_array($barang->message) || is_object($barang->message))): foreach ($barang->message as $key => $value) : ?>
              <option value="<?php echo $value->KD_BARANG;?>"><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
      <!-- </div>
      <div class="col-xs-12 col-sm-6 col-md-6"> -->
        <div class="form-group" style='display:none;' id="aksesoris">
          <label>Aksesoris</label>
          <select name="aksesoris" class="form-control" >
            <option value="">- Pilih Aksesoris -</option>
            <?php if($aksesoris && (is_array($aksesoris->message) || is_object($aksesoris->message))): foreach ($aksesoris->message as $key => $value) : ?>
              <option value="<?php echo $value->KD_BARANG;?>"><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
      <!-- </div>
      <div class="col-xs-12 col-sm-6 col-md-6"> -->
        <div class="form-group" style='display:none;' id="apparel">
          <label>Apparel</label>
          <select name="apparel" class="form-control" >
            <option value="">- Pilih Apparel -</option>
            <?php if($apparel && (is_array($apparel->message) || is_object($apparel->message))): foreach ($apparel->message as $key => $value) : ?>
              <option value="<?php echo $value->KD_BARANG;?>"><?php echo $value->KD_BARANG;?> - <?php echo $value->NAMA_BARANG;?></option>
            <?php endforeach; endif;?>
          </select>
        </div>
      
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="form-group">
        <label>Tipe Customer</label>
        <select name="kd_typecustomer" class="form-control">
          <?php if($typecustomer && (is_array($typecustomer->message) || is_object($typecustomer->message))): foreach ($typecustomer->message as $key => $value) : ?>
            <option value="<?php echo $value->KD_TYPECUSTOMER;?>"><?php echo $value->NAMA_TYPECUSTOMER;?></option>
          <?php endforeach; endif;?>
        </select>
      </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Harga Beli</label>
          <input id="harga_beli" type="text" name="harga_beli" class="form-control" placeholder="0">
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Harga Jual</label>
          <input id="harga_jual" type="text" name="harga_jual" class="form-control" placeholder="0">
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
            <label>Tipe Diskon</label>
            <select name="diskon_type" id="diskon_type" class="form-control">
             <option value="">- Pilih Tipe Diskon -</option>
             <option value="Persen">Persen</option>
             <option value="Rupiah">Rupiah</option>
           </select>
         </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
          <label>Diskon</label>
          <input id="diskon" type="text" name="diskon" class="form-control" placeholder="0">
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="form-group">
         <label>Periode Mulai</label>
         <div class="input-group input-append date" id="date">
           <input type="text" class="form-control" id="start_date" name="start_date" value="" placeholder="dd/mm/yyyy" />
           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
         </div>
       </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
       <div class="form-group">
         <label>Periode Selesai</label>
         <div class="input-group input-append date" id="date">
           <input type="text" class="form-control" id="end_date" name="end_date" value="" placeholder="dd/mm/yyyy" />
           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
         </div>
       </div>
      </div>
    </div>
  </div>
</form>

<div class="modal-footer">
  <a class="btn btn-default" href="<?php echo base_url('sparepart/hargapart/false/barang?jt=barang');?>">Batal</a>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger <?php echo $status_c;?>">Simpan</button>
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
        $("#kd_barang").hide();
        $("#aksesoris").hide();
        $("#apparel").hide();
      }
      });
 
  });

</script>