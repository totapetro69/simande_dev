<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'remove-button' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Edit Barang : <?php echo $list->message[0]->NAMA_BARANG; ?></h4>
</div>

<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('inventori/update_barang/' . $list->message[0]->ID); ?>">
      <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
      
        
    <!-- <div class="form-group">
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
    </div> -->
        <div class="form-group">
            <label>Kode</label>
            <input type="text" name="kd_barang" id="kd_barang" class="form-control" value="<?php echo  $list->message[0]->KD_BARANG; ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama<span id="fd"></label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?php echo  $list->message[0]->NAMA_BARANG; ?>" >
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-control">
             <option value="<?php echo $list->message[0]->KATEGORI;?>"> <?php echo $list->message[0]->KATEGORI;?> </option>
             <option value="Aksesoris">Aksesoris</option>
             <option value="Apparel">Apparel</option>
             <option value="Hadiah">Hadiah</option>
             <option value="Barang">Barang</option>
             <option value="Jasa">Jasa</option>
             <option value="ATK">ATK</option>
             <option value="Umum">Umum</option>
         </select>
     </div>
<!--      <div class="form-group">
        <label>Default Qty SJ</label>
        <input id="default_qty" type="text" name="default_qty" class="form-control" value="<?php echo $list->message[0]->DEFAULT_QTY;?>">
    </div>
    <div class="form-group">
     <label>Masuk SJ</label>
     <select name="masuk_sj" class="form-control">
       <option value="<?php echo $list->message[0]->MASUK_SJ;?>"> <?php if($list->message[0]->MASUK_SJ == 0){echo "Tidak"; }ELSE{ echo "Ya"; }?> </option>
       <?php
       if($list->message[0]->MASUK_SJ == 0){
           ?>
           <option value="1">Ya</option>
           <?php
       }else{
           ?>
           <option value="0">Tidak</option>
           <?php
       }
       ?>
   </select>
</div> -->


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

        $('#default_qty')
        .focusout(function(){

        })
        .ForceNumericOnly()

        $("#nama_barang").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("inventori/barang_typeahead");?>',{keyword:query},function(data){
            console.log(data);
            data=$.parseJSON(data);
            $('#fd').html('');
            return process(data.keyword);
          })
        },
        minLength:1,
        limit:20
      });

    });

</script>