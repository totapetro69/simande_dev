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
    <h4 class="modal-title" id="myModalLabel">Prepare event</h4>
</div>
<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/update_prepare/' . $list->message[0]->ID); ?>">
      <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">

    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>PIC Sales</label>
          <select class="form-control" id="pic_salesevent" name="pic_salesevent" required="true">
            <option value="" >- Pilih Sales -</option>
            <?php
            if (isset($salesman)) {
              if (($salesman->totaldata)) {
                foreach ($salesman->message as $key => $value) {
                  $select=($list->message[0]->PIC_SALESEVENT == $value->NAMA_SALES)?"selected":"";
                  echo "<option value='" . $value->NAMA_SALES . "' ".$select.">" . $value->NAMA_SALES . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Sales Jaga</label>
          <select class="form-control" id="sp_salesevent" name="sp_salesevent" required="true">
            <option value="" >- Pilih Sales -</option>
            <?php
            if (isset($salesman)) {
              if (($salesman->totaldata)) {
                foreach ($salesman->message as $key => $value) {
                  $select=($list->message[0]->SP_SALESEVENT == $value->NAMA_SALES)?"selected":"";
                  echo "<option value='" . $value->NAMA_SALES . "' ".$select.">" . $value->NAMA_SALES . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Available Promotion</label>
          <select class="form-control" id="available_promotion" name="available_promotion" required="true">
            <option value="" >- Pilih Promo -</option>
            <?php
            if (isset($promoprogram)) {
              if (($promoprogram->totaldata)) {
                foreach ($promoprogram->message as $key => $value) {
                  $select=($list->message[0]->AVAILABLE_PROMOTION == $value->NAMA_PROGRAM)?"selected":"";
                  echo "<option value='" . $value->NAMA_PROGRAM . "' ".$select.">" . $value->NAMA_PROGRAM . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Unit to Display</label>
          <select class="form-control" id="unit_to_display" name="unit_to_display" required="true">
            <option value="" >- Pilih Unit -</option>
            <?php
            if (isset($motor)) {
              if (($motor->totaldata)) {
                foreach ($motor->message as $key => $value) {
                  $select=($list->message[0]->UNIT_TO_DISPLAY == $value->NAMA_ITEM)?"selected":"";
                  echo "<option value='" . $value->NAMA_ITEM . "' ".$select.">" . $value->NAMA_ITEM . "</option>";
                }
              }
            }
            ?>
          </select>
        </div>
      </div>

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
</script>