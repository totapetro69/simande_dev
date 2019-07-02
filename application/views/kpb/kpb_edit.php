<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$ID="";
$KD_MAINDEALER = "";
$KD_DEALER = "";
$KD_DEALERAHM = "";
$NO_PKB = "";
$NO_KPB = "";
$KD_MESIN = "";
$NO_MESIN = "";
$NO_RANGKA = "";
$TGL_BELI = "";
$SEQUENCE = "";
$KM_SERVICE = "";
$TGL_SERVICE = "";
$MOTOR_LUAR = "";
$BUKU_BARU = "";
$STATUS_KPB = "";
$REVISI = 0;
$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');


foreach ($list->message as $key => $value) {
  $ID=$value->ID;
  $KD_MAINDEALER = $value->KD_MAINDEALER;
  $KD_DEALER = $value->KD_DEALER;
  $KD_DEALERAHM = $value->KD_DEALERAHM;
  $NO_PKB = $value->NO_PKB;
  $NO_KPB = $value->NO_KPB;
  $KD_MESIN = $value->KD_MESIN;
  $NO_MESIN = $value->NO_MESIN;
  $NO_RANGKA = $value->NO_RANGKA;
  $TGL_BELI = tglfromSql($value->TGL_BELI);
  $SEQUENCE = $value->SEQUENCE;
  $KM_SERVICE = $value->KM_SERVICE;
  $TGL_SERVICE = tglfromSql($value->TGL_SERVICE);
  $MOTOR_LUAR = $value->MOTOR_LUAR;
  $BUKU_BARU = $value->BUKU_BARU;
  $STATUS_KPB = $value->STATUS_KPB;
  $REVISI = $value->REVISI;

}

if(isset($update_status_kpb)){
  $STATUS_KPB = $update_status_kpb;
  $REVISI = $REVISI + 1;

}


$status_c = (isBolehAkses('c') ? '' : 'remove-button' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('kpb/update_kpb/'.$ID);?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Edit KPB</h4>
</div>

<div class="modal-body">

      <input type="hidden" name="kd_maindealer" id="kd_maindealer" value="<?php echo $KD_MAINDEALER;?>">
      <input type="hidden" name="kd_dealer" id="kd_dealer" value="<?php echo $KD_DEALER;?>">
      <input type="hidden" name="kd_dealerahm" id="kd_dealerahm" value="<?php echo $KD_DEALERAHM;?>">
      <input type="hidden" name="no_pkb" id="no_pkb" value="<?php echo $NO_PKB;?>">
      <input type="hidden" name="buku_baru" id="buku_baru" value="<?php echo $BUKU_BARU;?>">
      <input type="hidden" name="status_kpb" id="status_kpb" value="<?php echo $STATUS_KPB;?>">
      <input type="hidden" name="revisi" id="revisi" value="<?php echo $REVISI;?>">

      <div class="row">
        
        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>No Mesin</label>
            <input type="text" name="no_mesin" id="no_mesin" class="form-control" value="<?php echo $KD_MESIN.$NO_MESIN;?>"  required>
          </div>

        </div>

        
        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>No Rangka</label>
            <input type="text" name="no_rangka" id="no_rangka" class="form-control" value="<?php echo $NO_RANGKA;?>"  required>
          </div>

        </div>

        
        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>Sequence</label>
            <input type="text" name="sequence" id="sequence" class="form-control" value="<?php echo $SEQUENCE;?>"  required>
          </div>

        </div>

        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>Tgl Beli</label>

            <div class="input-group input-append date"><!-- date -->
                <input class="form-control" id="tgl_beli" name="tgl_beli" placeholder="DD/MM/YYYY" value="<?php echo $TGL_BELI; ?>" type="text"/>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

          </div>

        </div>

        
        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>Tgl Service</label>

            <div class="input-group input-append date"><!-- date -->
                <input class="form-control" id="tgl_service" name="tgl_service" placeholder="DD/MM/YYYY" value="<?php echo $TGL_SERVICE; ?>" type="text"/>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>

          </div>

        </div>

        <div class="col-xs-12 col-sm-4">
                
          <div class="form-group">
            <label>No Claim</label>
            <input type="text" name="no_kpb" id="no_kpb" class="form-control disabled-action" value="<?php echo $NO_KPB;?>"  required>
          </div>

        </div>

        <div class="col-xs-12 col-sm-12">
                
          <div class="form-group">
            <label>KM Service</label>
            <div class="form-inline">
              <input type="text" name="km_service" id="km_service" class="form-control" value="<?php echo $KM_SERVICE;?>"  required>
              <div class="checkbox">
                <label>
                  <input id="motor_luar" name="motor_luar" value="1" type="checkbox" <?php echo $MOTOR_LUAR == '*'?'checked':'';?>> Motor Luar
                </label>
              </div>
            </div>
          </div>


        </div>

        
      </div>


      </div>


      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger <?php echo $status_e?>  submit-btn">Simpan</button>
</div>

</form>
<script type="text/javascript">
$(document).ready(function(){

  var date = new Date();
  date.setDate(date.getDate());

/*
  $('.datetime').datetimepicker({
    format:'hh:mm:ss',
    pickDate: false,
    // pickTime: false,
    autoclose: true
  });*/

  
    $('.date').datepicker({
        format: 'dd/mm/yyyy',
        endDate: date,
        autoclose: true
    });

});

</script>

