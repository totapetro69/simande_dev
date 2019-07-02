<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

$defaultDealer =($this->input->get("kd_dealer"))? $this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Input Unit</h4>
</div>

<div class="modal-body">
  <form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/add_unit_simpan');?>" method="post">
    <input type="hidden" name="id" id="id" class="form-control" value="<?php echo  $list->message[0]->ID; ?>" >

    <div class="form-group">
      <label>Kode Event</label>
      <input type="text" name="kd_event" id="kd_event" class="form-control" value="<?php echo $list->message[0]->KD_EVENT; ?>" readonly>
    </div>
    <div class="form-group">
      <label>Dealer</label>
      <select class="form-control" id="kd_dealer" name="kd_dealer" disabled>
        <option value="0">--Pilih Dealer--</option>
        <?php
        if ($dealer) {
          if (($dealer->totaldata > 0)) {
            foreach ($dealer->message as $key => $value) {
              $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
              echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
            }
          }
        }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label>Kode Item</label>
      <select class="form-control" id="kd_item" name="kd_item" required="true">
        <option value="" >- Pilih Item -</option>
        <?php if($stockmotor && (is_array($stockmotor->message) || is_object($stockmotor->message))): foreach ($stockmotor->message as $key => $value) : ?>
        <option value="<?php echo $value->KD_ITEM;?>"><?php echo $value->KD_ITEM;?> - <?php echo $value->NAMA_ITEM;?></option>
      <?php endforeach; endif;?>
      </select>
    </div>

    <div class="form-group">
      <label>Nama Item</label>
      <input type="text" id="nama_item" name="nama_item" class="form-control" readonly>
    </div>
    
    <div class="form-group">
      <label>Tambahan</label>
      <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="">
    </div>

  </form>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" onclick="addData();" class="btn btn-danger">Simpan</button>
</div>



<script type="text/javascript">
   var path = window.location.pathname.split('/');
   var http = window.location.origin + '/' + path[1];

   $(document).ready(function(){
      ListUnit();

      $("#kd_item").change(function(){
         var kd_item = $(this).val();

         $.getJSON("<?php echo base_url("sales_event/get_unit");?>",
            {'kd_item':kd_item},
              function(result){
                if(result.status == true){
                  $.each(result.message,function(e,d){
                  $("#nama_item").val(d.NAMA_ITEM);
               })
               }
            }
            )
      });
   })

   function ListUnit(){
      var datax=[];
   }
 </script>