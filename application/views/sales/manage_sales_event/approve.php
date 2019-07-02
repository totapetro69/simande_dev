<?php
if (!isBolehAkses()) {
  redirect(base_url() . 'auth/error_auth/true');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$end_date=date('d/m/Y',strtotime('Last day of this month'));
$start_date=date('d/m/Y');
$defaultDealer = ($this->input->get("kd_dealer")) ? $this->input->get("kd_dealer") : $this->session->userdata("kd_dealer");
$approval_level='';


?>

<form id="addForm" class="bucket-form" action="<?php echo base_url('sales_event/approval_event_simpan/' . $list->message[0]->ID); ?>">
  <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Approval Event</h4>
  </div>

  <div class="modal-body">
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
          <label>Kode Event</label>
          <input type="text" name="kd_event" id="kd_event" class="form-control" value="<?php echo  $list->message[0]->KD_EVENT; ?>" readonly>
        </div>

        <div class="form-group">
          <label>Nama Event</label>
          <input type="text" name="nama_event" id="nama_event" class="form-control" value="<?php echo  $list->message[0]->NAMA_EVENT; ?>" readonly>
        </div>

        <div class="form-group">
          <label>Status Event <?php //echo ($survey_leasing);?></label>
           <select name="status_event" id="status_event" class="form-control">
            <option value="0">- Pilih Status -</option>
             <option value="1">Approve</option>
             <option value="2">Reject</option>
           </select>
        </div>

        <div class="form-group" style='display:none;' id="keterangan">
          <label id="keterangan">Alasan</label>
          <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder=" alasan " value=""/>
        </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
    <button id="submit-btn" type="submit" class="btn btn-danger submit-btn">Simpan</button>
  </div>
</form>

<script type="text/javascript">
   $(document).ready(function(e){
   $('#status_event').on('change', function() {
    if ( this.value == '0')
    {
      $("#keterangan").show();
    }else{
      $("#keterangan").hide();
    }
  });
   });

 </script>