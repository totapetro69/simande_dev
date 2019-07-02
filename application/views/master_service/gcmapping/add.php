<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer = $this->session->userdata("kd_dealer");
$defaultMainDealer = $this->session->userdata("kd_maindealer");
?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_gcmapping_simpan');?>" method="post">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Tambah Grup Customer</h4>
  </div>
  

  <div class="modal-body">
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
      <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer" readonly>
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

    <div id="ajax-url-filter" url="<?php echo base_url('setup/groupcustomer_typeahead');?>">
     
   </div>   
   <div class="form-group">
    <label>Group Customer <span id="fd"></span></label>
    <input id="keyword_q" type="text" name="kd_groupcustomer" class="form-control" placeholder="Ketik kode atau nama Group Customer">
  </div>

   <div class="form-group">
           <label>Tipe (wajib diisi)</label>
           <select name="kd_typecustomer" class="form-control">
               <option value="" >- Pilih Tipe Customer -</option>
               <?php if($typecustomer && (is_array($typecustomer->message) || is_object($typecustomer->message))): foreach ($typecustomer->message as $key => $value) : ?>
                 <option value="<?php echo $value->KD_TYPECUSTOMER;?>"><?php echo $value->NAMA_TYPECUSTOMER;?></option>
             <?php endforeach; endif;?>
         </select>
     </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>

</form>

<script type="text/javascript">
  $(document).ready(function(e){
    

      $("#keyword_q").typeahead({
         source:function(query,process){
          $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
          return $.get('<?php echo base_url("setup/groupcustomer_typeahead");?>',{keyword:query},function(data){
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