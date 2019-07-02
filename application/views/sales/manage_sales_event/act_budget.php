

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Aktual Budget</h4>
</div>
<div class="modal-body">
    <form id="addForm" class="bucket-form" method="post" action="<?php echo base_url('sales_event/update_act/' . $list->message[0]->ID); ?>">
      <input id="id" type="hidden" name="id" value="<?php echo $list->message[0]->ID; ?>">

    <div class="row">

      <div class="col-sm-5">
        <div class="form-group">
          <label>Kode Budget</label>
          <input type="text" name="kd_budget" id="kd_budget" class="form-control" value="<?php echo  $list->message[0]->KD_BUDGET; ?>" readonly>
        </div>
      </div>

      <div class="col-sm-5">
        <div class="form-group">
          <label>Nama Budget</label>
          <input type="text" name="nama_budget" id="nama_budget" class="form-control" value="<?php echo  $list->message[0]->NAMA_BUDGET; ?>" readonly>
        </div>
      </div>

      <div class="col-sm-5">
        <div class="form-group">
          <label>Aktual Budget</label>
          <input type="number" name="aktual_budget" id="aktual_budget" class="form-control" value="<?php echo  $list->message[0]->AKTUAL_BUDGET; ?>" >
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