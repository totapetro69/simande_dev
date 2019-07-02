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

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tambah Diskon</h4>
</div>

<div class="modal-body">

  <form id="addForm" class="bucket-form" action="<?php echo base_url('setup/add_diskonpart_simpan');?>" method="post">
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

    <div id="ajax-url-filter" url="<?php echo base_url('sparepart/part_typeahead');?>">
     
   </div>   
   <div class="form-group" id="part_number">
    <label>Nomor Part <span id="fd"></span></label>
    <input id="keyword_q" type="text" name="part_number" class="form-control" placeholder="0">
  </div>
    <div class="form-group">
      <label>Jenis Customer</label>
      <select name="kd_jeniscustomer" class="form-control">
        <?php if($jeniscustomers && (is_array($jeniscustomers->message) || is_object($jeniscustomers->message))): foreach ($jeniscustomers->message as $key => $jeniscustomer) : ?>
          <option value="<?php echo $jeniscustomer->KD_JENISCUSTOMER;?>"><?php echo $jeniscustomer->NAMA_JENISCUSTOMER;?></option>
        <?php endforeach; endif;?>
      </select>
    </div>
    
    <div class="form-group">
      <label>Tipe Diskon</label>
      <select name="tipe_diskon" class="form-control">
       <option value="1">RUPIAH</option>
       <option value="0">PERSEN</option>
     </select>
   </div>
   
   <div class="form-group">
    <label>Besar Diskon</label>
    <input id="amount" type="text" name="amount" class="form-control">
  </div>
  
  <div class="form-group">
   <label>Tanggal Mulai</label>
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
 
</form>


</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>

<script type="text/javascript">
  $(document).ready(function(e){
    
        $('#amount')
       .focusout(function(){

       })
       .ForceNumericOnly()

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


