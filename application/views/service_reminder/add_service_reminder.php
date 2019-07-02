<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 




$ID  = "";
$TGL_TRANS  = "";
$KD_CUSTOMER  = "";
$NO_TRANS  = "";
$NO_MESIN  = "";
$NAMA_CUSTOMER  = "";
$WAKTU_JDWREMINDER  = "";
$WAKTU_REMINDERKPB  = "";
$JENIS_KPB  = "";
$JENIS_REMINDER  = "";
$STATUS_REMINDER  = "";
$NO_HP  = "";
$NO_POLISI  = "";
$KD_TYPEMOTOR  = "";

if(!empty($list) && (is_array($list) || is_object($list))){
  foreach ($list->message as $key => $value) {
    $ID  = $value->ID;
    $TGL_TRANS  = tglfromSql($value->TGL_TRANS);
    $KD_CUSTOMER  = $value->KD_CUSTOMER;
    $NO_TRANS  = $value->NO_TRANS;
    //$NO_MESIN  = $value->NO_MESIN;
    $NAMA_CUSTOMER  = $value->NAMA_CUSTOMER;
    $NO_HP  = $value->NO_HP;
    $NO_POLISI  = $value->NO_POLISI;
    $KD_TYPEMOTOR  = $value->KD_TYPEMOTOR;
    $WAKTU_JDWREMINDER  = tglfromSql($value->WAKTU_JDWREMINDER);
    $WAKTU_REMINDERKPB  = tglfromSql($value->WAKTU_REMINDERKPB);
    $JENIS_KPB  = $value->JENIS_KPB;
    $JENIS_REMINDER  = $value->JENIS_REMINDER;
    $STATUS_REMINDER  = $value->STATUS_REMINDER;
  }
}

?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('service_reminder/service_reminder_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Service Reminder 2</h4>
</div>

<div class="modal-body">

      <!-- header -->
      <input id="id" type="hidden" name="id" class="form-control" value="<?php echo $ID;?>" required>
      <input id="tgl_trans" type="hidden" name="tgl_trans" class="form-control" value="<?php echo $TGL_TRANS != ''? $TGL_TRANS : date('d/m/Y');?>" required>
      <input id="waktu_reminderkpb" type="hidden" name="waktu_reminderkpb" class="form-control" value="<?php echo $WAKTU_REMINDERKPB != ''? $WAKTU_REMINDERKPB : date('d/m/Y');?>" required>
      <input id="kd_customer" type="hidden" name="kd_customer" class="form-control" value="<?php echo $KD_CUSTOMER;?>" required>

      <!-- detail -->

      <div class="form-group">
          <label>ID Reminder</label>
          <input id="no_trans" type="text" name="no_trans" class="form-control" value="<?php echo $NO_TRANS;?>" placeholder="Kode FU SERVICE" readonly>
      </div>

      <div class="form-group">


          <?php if($NO_TRANS == ''): ;?>
          <label>Nama Customer <span class="loading-fu"></span></label>
          <input id="no_rangka_service" type="text" name="no_rangka" class="form-control" value="" placeholder="Masukan data" required disabled>
          <input id="nama_customer" type="hidden" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" required>
          <?php else: ;?>
          <label>Nama Customer</label>
          <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" required>
          <?php endif ;?>
      </div>

      <div class="form-group" >
          <label>No HP</label>
          <input id="no_hp" type="text" name="no_hp" class="form-control" value="<?php echo $NO_HP;?>" required>
      </div>

      <div class="form-group" >
          <label>No Polisi</label>
          <input id="no_polisi" type="text" name="no_polisi" class="form-control" value="<?php echo $NO_POLISI;?>" >
      </div>

      <div class="form-group" >
          <label>Tipe Unit</label>
          <input id="kd_typemotor" type="text" name="kd_typemotor" class="form-control" value="<?php echo $KD_TYPEMOTOR;?>" >
      </div>


      <div class="form-group">
          <label>Waktu Jadwal Reminder</label>


          <div class="input-group input-append date" id="datepicker">
              <input id="waktu_jdwreminder" type="text" name="waktu_jdwreminder" class="form-control" value="<?php echo ($WAKTU_JDWREMINDER != ''? $WAKTU_JDWREMINDER : date('d/m/Y')); ?>" placeholder="DD/MM/YYYY" required>
              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
          </div>
          
      </div>

      <div class="form-group">
        <label>Jenis KPB</label>

        <select id="jenis_kpb" name="jenis_kpb" class="form-control">
            <option value="0" <?php echo ($JENIS_KPB == '0' ? "selected" : ""); ?>>NON KPB</option>
            <option value="1" <?php echo ($JENIS_KPB == '1' ? "selected" : ""); ?>>KPB 1</option>
            <option value="2" <?php echo ($JENIS_KPB == '2' ? "selected" : ""); ?>>KPB 2</option>
            <option value="3" <?php echo ($JENIS_KPB == '3' ? "selected" : ""); ?>>KPB 3</option>
            <option value="4" <?php echo ($JENIS_KPB == '4' ? "selected" : ""); ?>>KPB 4</option>
        </select>
        
      </div>


      <div class="form-group">
        <label>Jenis Reminder</label>

        <select id="jenis_reminder" name="jenis_reminder" class="form-control">
            <option value="S" <?php echo ($JENIS_REMINDER == 'S' ? "selected" : ""); ?>>SMS</option>
            <option value="T" <?php echo ($JENIS_REMINDER == 'T' ? "selected" : ""); ?>>Telp</option>
        </select>
        
      </div>



      <div class="form-group">
        <label>Status Reminder Cust</label>

        <select id="status_reminder" name="status_reminder" class="form-control">
            <option value="Belum Reminder" <?php echo ($STATUS_REMINDER == 'Belum Reminder' ? "selected" : ""); ?>>Belum Reminder</option>
            <option value="Sudah Reminder" <?php echo ($STATUS_REMINDER == 'Sudah Reminder' ? "selected" : ""); ?>>Sudah Reminder</option>
        </select>
        
      </div>
      <!-- <input type="submit" name=""> -->

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  <button id="submit-btn" type="submit" class="btn btn-danger submit-btn <?php echo $status_e?>">Simpan</button>
</div>

</form>

<script type="text/javascript">


var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

  
$(document).ready(function(){

  getData();

});

function getData()
{


  var url_fu_service = http+"/follow_up/get_rangka_bykpb";
  // console.log(url_kategori);

  // $(".loading-fu").html("<i class='fa fa-spinner fa-spin'></i>");

  $('#no_rangka_service').inputpicker({
    url:url_fu_service,
    // urlParam:{"kd_item":kd_item},
    fields:['NAMA_CUSTOMER','NO_HP','NO_POLISI','KD_TYPEMOTOR','NO_RANGKA', 'JENIS_KPB',  'NO_MESIN'],
    fieldText:'NAMA_CUSTOMER',
    fieldValue:'NO_RANGKA',
    filterOpen: true,
    headShow:true,
    pagination: true,
    pageMode: '',
    pageField: 'p',
    pageLimitField: 'per_page',
    limit: 15,
    pageCurrent: 1,
    // urlDelay:2
  })
  .on("change",function(e){
    e.preventDefault();

    var no_rangka=$(this).val();


    $.getJSON(http+"/follow_up/get_detail_fu",{"no_rangka":no_rangka},function(result){
        var dateso = new Date(result.sj.message[0].TGL_SO);


        yr      = dateso.getFullYear(),
        month   = (dateso.getMonth()+1) < 10 ? '0' + (dateso.getMonth()+1) : (dateso.getMonth()+1),
        day     = dateso.getDate()  < 10 ? '0' + dateso.getDate()  : dateso.getDate(),
        newDate = day + '/' + month + '/' + yr;;
        
        if(result.kpb[0].JENIS_KPB == 'KPB1'){
          var kpb = 1;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB2'){
          var kpb = 2;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB3'){
          var kpb = 3;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB4'){
          var kpb = 4;
        }
        else{
          var kpb = 0;
        }
        // alert(date.getDate() + '/' +  (date.getMonth() + 1) + '/' + date.getFullYear());
        $('#kd_customer').val(result.sj.message[0].KD_CUSTOMER);
        $('#nama_customer').val(result.sj.message[0].NAMA_CUSTOMER);
        $('#no_hp').val(result.sj.message[0].NO_HP);
        $('#no_polisi').val(result.sj.message[0].DATA_NOMOR);
        $('#kd_typemotor').val(result.sj.message[0].KET_UNIT);
        $('#jenis_kpb').val(kpb);

        $('#kelurahan').val(result.sj.message[0].NAMA_DESA);
        $('#kecamatan').val(result.sj.message[0].NAMA_KECAMATAN);
        $('#kota').val(result.sj.message[0].NAMA_KABUPATEN);
        $('#kode_pos').val(result.sj.message[0].KODE_POS);
        $('#propinsi').val(result.sj.message[0].NAMA_PROPINSI);

        $('#no_mesin').val(result.sj.message[0].NO_MESIN);
        $('#tgl_pembelian').val(newDate);
        $('#nama_stnk').val(result.sj.message[0].NAMA_CUSTOMER);
        $('#alamat_surat').val(result.sj.message[0].ALAMAT_SURAT);

        $('#jenis_kpb_title').html(result.kpb[0].JENIS_KPB);

        // $("#no_rangka_service").removeAttr('disabled');    
        // $(".loading-fu").html("");

    });

  })

}
</script>
