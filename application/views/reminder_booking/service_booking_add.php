<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 


$defaultDealer = $this->session->userdata("kd_dealer");

$tgl_trans = "";
$kd_motor = "";
$kd_customer = "";
$kd_dealer = "";
$tipe_manual = "";
$nama_customer = "";
$no_trans = "";
$no_telepon = "";
$no_polisi = "";
$no_mesin = "";
$no_rangka = "";
$tipe_motor = "";
$waktu_servis = "";
$waktu_servis2 = "";
$keluhan_cust = "";
$kd_tipepkb = "";
$keterangan = "";
$alasan = "";
$nama_pemilik = "";
$alamat = "";
$tahun_kendaraan = "";
$email = "";
$status_booking = "";

if(!empty($list) && (is_array($list) || is_object($list))){
  foreach ($list->message as $key => $value) {
    $tgl_trans = tglfromSql($value->TGL_TRANS);
    $kd_motor = $value->TIPE_MOTOR;
    $kd_customer = $value->KD_CUSTOMER;
    $kd_dealer = $value->KD_DEALER;
    $tipe_manual = $value->TIPE_MANUAL;
    $nama_customer = $value->NAMA_CUSTOMER;
    $no_trans = $value->NO_TRANS;
    $no_telepon = $value->NO_TELEPON;
    $no_polisi = $value->NO_POLISI;
    $no_mesin = $value->NO_MESIN;
    $no_rangka = $value->NO_RANGKA;
    $tipe_motor = $value->TIPE_MOTOR;
    $waktu_servis = tglfromSql($value->WAKTU_SERVIS);
    $waktu_servis2 = $value->WAKTU_SERVIS;
    $keluhan_cust = $value->KELUHAN_CUST;
    $kd_tipepkb = $value->KD_TIPEPKB;
    $keterangan = $value->KETERANGAN;
    $alasan = $value->ALASAN;
    $nama_pemilik = $value->NAMA_PEMILIK;
    $alamat = $value->ALAMAT;
    $tahun_kendaraan = $value->TAHUN_KENDARAAN;
    $email = $value->EMAIL;
    $status_booking = $value->STATUS_BOOKING;
  }
}
// var_dump($waktu_servis2);
// var_dump($waktu_servis != ''? substr($waktu_servis2,11,5) : date('hh:mm'));
// exit();
?>
<form id="addForm" class="bucket-form" action="<?php echo base_url('reminder_booking/service_booking_simpan');?>" method="post">

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Service Booking</h4>
</div>

<div class="modal-body">

      <!-- header -->
      <input id="waktu_reminderkpb" type="hidden" name="waktu_reminderkpb" class="form-control" value="<?php echo $tgl_trans != ''? $tgl_trans : date('d/m/Y');?>" required>
      <input id="kd_motor" type="hidden" name="kd_motor" class="form-control" value="<?php echo $kd_motor;?>" required>
      <input id="kd_customer" type="hidden" name="kd_customer" class="form-control" value="<?php echo $kd_customer;?>" required>

      <!-- detail -->
      <div class="row">
        <div class="form-group col-xs-4">
            <label>Dealer</label>
            <select class="form-control disabled-action" id="kd_dealer" name="kd_dealer">
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

        <div class="form-group col-xs-4">
            <label>Tanggal</label>
            <div class="input-group input-append date" id="datepicker">
                <input id="tgl_trans" type="text" name="tgl_trans" class="form-control" value="<?php echo ($tgl_trans != ''? $tgl_trans : date('d/m/Y')); ?>" placeholder="DD/MM/YYYY" required>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group col-xs-4">
            <label>No. Transaksi</label>
            <input id="no_trans" type="text" name="no_trans" class="form-control" value="<?php echo $no_trans;?>" placeholder="AUTO GENERATE" readonly>
        </div>
      </div>


      <div class="row">
        <div class="form-group col-xs-3">
            <label>No. Polisi <span class="loading-fu"></span></label>
            <input id="no_polisi" type="text" name="no_polisi" class="form-control" value="<?php echo $no_polisi;?>" style="text-transform: uppercase;" placeholder="AB-1234-XX" required>
        </div>

        <div class="form-group col-xs-3">
            <label>No. Mesin <span class="loading-fu"></span></label>
            <input id="no_mesin" type="text" name="no_mesin" class="form-control" value="<?php echo $no_mesin;?>" placeholder="No. Mesin" required>
        </div>

        <div class="form-group col-xs-3">
            <label>No. Rangka <span class="loading-fu"></span></label>
            <input id="no_rangka" type="text" name="no_rangka" class="form-control" value="<?php echo $no_rangka;?>" placeholder="No. Rangka" required>
        </div>

        <div class="form-group col-xs-3">
            <label>Tipe Motor <span class="loading-fu"></span></label>
            <input id="tipe_motor" type="text" name="tipe_motor" class="form-control" value="<?php echo $tipe_motor;?>" placeholder="Tipe Motor" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-xs-3">
            <label>Nama Pemilik Kendaraan<span class="loading-fu"></span></label>
            <input id="nama_pemilik" type="text" name="nama_pemilik" class="form-control" value="<?php echo $nama_pemilik;?>" placeholder="Nama Pemilik Kendaraan" required>
        </div>

        <div class="form-group col-xs-6">
            <label>Alamat <span class="loading-fu"></span></label>
            <input id="alamat" type="text" name="alamat" class="form-control" value="<?php echo $alamat;?>" placeholder="Alamat" required>
        </div>

        <div class="form-group col-xs-3">
            <label>Tahun Kendaraan<span class="loading-fu"></span></label>
            <input id="tahun_kendaraan" type="text" name="tahun_kendaraan" class="form-control" value="<?php echo $tahun_kendaraan;?>" placeholder="Tahun Kendaraan" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-xs-4">
            <label>Nama Customer<span class="loading-fu"></span></label>
            &nbsp;&nbsp;<input type="checkbox" id="likePemilik"><small><i> Sama data pemilik</i></small>
            <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo $nama_customer;?>" placeholder="Nama Customer" required>
        </div>

        <div class="form-group col-xs-4">
            <label>No. HP <span class="loading-fu"></span></label>
            <input id="no_telepon" type="text" name="no_telepon" class="form-control" value="<?php echo $no_telepon;?>" placeholder="No. HP" required>
        </div>

        <div class="form-group col-xs-4">
            <label>Email<span class="loading-fu"></span></label>
            <input id="email" type="Email" name="email" class="form-control" value="<?php echo $email;?>" placeholder="Email" required>
        </div>
      </div>

<?php  /*
      <div class="row">
        <div class="form-group col-xs-4">          
          <label>Manual</label>
          <select id="tipe_manual" name="tipe_manual" class="form-control" <?php echo $NO_TRANS != ''?'readonly':'';?>>
              <option value="1" <?php echo ($TIPE_MANUAL == '1'?'selected':'');?>>ya</option>
              <option value="0" <?php echo ($TIPE_MANUAL == '0'?'selected':'');?>>tidak</option>
          </select>
        </div>

        <div id="tipe_manual_1" class="form-group col-xs-8">
          <label>Nama Customer</label>

          <input type="text" name="nama_customer_manual" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" placaholder="Nama Customer" required <?php echo $NO_TRANS != ''?'readonly':'';?>>
        </div>


        <div id="tipe_manual_0" class="form-group col-xs-8" style="display: none;">
          <label>Nama Customer</label>
          <input id="no_polisi_key" type="text" name="no_polisi_key" class="form-control" value="<?php echo $NO_POLISI;?>" placeholder="Masukan data" required disabled>

          <input id="nama_customer" type="hidden" name="nama_customer" class="form-control" value="<?php echo $NAMA_CUSTOMER;?>" required>

        </div>
        
      </div>
*/?>
      
      <div class="row">

        <div class="form-group col-xs-3">
            <label>Tanggal Servis</label>
            <div class="input-group input-append date" id="datepicker">
                <input id="waktu_servis" type="text" name="waktu_servis" class="form-control" value="<?php echo ($waktu_servis != ''? $waktu_servis : date('d/m/Y')); ?>" placeholder="DD/MM/YYYY" required>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>

        <div class="form-group col-xs-2">
            <label>Jam Servis</label>
            <div class="input-group input-append timepicker" id="datepicker">
                <input id="waktu_servis2" type="text" name="waktu_servis2" class="form-control" value="<?php echo ($waktu_servis != ''? substr($waktu_servis2,11,5) : date('hh:mm')); ?>" placeholder="HH:mm" required>
                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
            </div>
        </div>

        <div class="form-group col-xs-5">
            <label>Keluhan Kendaraan</label>
            <input id="keluhan_cust" type="text" name="keluhan_cust" class="form-control" value="<?php echo $keluhan_cust;?>" placeholder="Keluhan Customer" required>
        </div>

        <div class="form-group col-xs-2">
          <label>Jenis Pekerjaan</label>
          <select id="kd_tipepkb" name="kd_tipepkb" class="form-control">
              <option value="LR" <?php echo $kd_tipepkb == 'LR'? 'selected':'';?>>LR</option>
              <option value="HR" <?php echo $kd_tipepkb == 'HR'? 'selected':'';?>>HR</option>
          </select>          
        </div>

      </div>

      <?php if($no_trans != ''):?>
      <div class="row">

        <div class="form-group col-xs-6">
          <label>Keterangan Service</label>

          <select id="keterangan" name="keterangan" class="form-control">
              <option disabled selected>- keterangan -</option>
              <option value="Sudah Service" <?php echo $keterangan == 'Sudah Service'? 'selected':'';?>>Sudah Service</option>
              <option value="Belum Service" <?php echo $keterangan == 'Belum Service'? 'selected':'';?>>Belum Service</option>
          </select>
          
        </div>

        <div class="form-group col-xs-6">
            <label>Alasan</label>
            <input id="alasan" type="text" name="alasan" class="form-control" value="<?php echo $alasan;?>" placeholder="Alasan">
        </div>

      </div>
      <?php endif;?>


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

  $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
    A: {pattern: /[A-Za-z]/},
    Z: {pattern: /[A-Za-z]/,optional:true},
    0: {pattern: /[0-9]/},
    1: {pattern: /[0-9]/,optional:true}
  }})

  $('.datetimepicker').datetimepicker({
      format: 'YYYY-MM-DD',
      // locale: 'ru'
  });

   $('.timepicker').datetimepicker({
      format: 'HH:mm',
      // locale: 'ru'
  });

  $("#tipe_manual").change(function(){
    var val = $(this).val();

    if(val == 0){

      $("#tipe_manual_0").show();
      $("#tipe_manual_1").hide();

      getData();
    }
    else{
      $("#tipe_manual_1").show();
      $("#tipe_manual_0").hide();


    }
    

  })  


  $('#no_polisi').focusout(function(){
    var no_polisi = $(this).val();
    var url = http+"/reminder_booking/get_datacustmotor";
    // var url = http+"/customer_service/get_detailcustomer";
    
    $(".loading-fu").html("<i class='fa fa-spinner fa-spin'></i>");

    $.getJSON(url,{"no_polisi":no_polisi},function(result){
        if(result.list.totaldata > 0){
            $('#no_rangka').val(result.list.message[0].NO_RANGKA);
            $('#no_mesin').val(result.list.message[0].NO_MESIN);
            $('#tipe_motor').val(result.list.message[0].KD_TYPEMOTOR+'-'+result.list.message[0].KD_WARNA);
            $('#tahun_kendaraan').val(result.list.message[0].THN_PERAKITAN);
            $('#nama_pemilik').val(result.list.message[0].NAMA_PENERIMA);
            $('#alamat').val(result.list.message[0].ALAMAT);
            if (result.list.message[0].KD_TYPEMOTOR == null) {
              $('#tipe_motor').val('');
              __getMotor();          
            }
        }
        else{
            $('#no_rangka').val('');
            $('#no_mesin').val('');
            $('#tipe_motor').val('');
            $('#tahun_kendaraan').val('');
            $('#nama_pemilik').val('');
            $('#alamat').val('');

            __getMotor();          
        }
        $(".loading-fu").html("");

        $('#likePemilik').change(function(){
          if($(this).is(":checked")){
            // alert($(this).val(result.list.message[0].NAMA_PENERIMA));
            if(result.list.totaldata > 0){
              $('#nama_customer').val(result.list.message[0].NAMA_PENERIMA);
              $('#no_telepon').val(result.list.message[0].NOHP);
              $('#email').val(result.list.message[0].EMAIL);
            }else{
              $('#nama_customer').val(document.getElementById("nama_pemilik").value);
            }
          }else{
            $('#nama_customer').val('');
            $('#no_telepon').val('');
            $('#email').val('');
          }
        });
    });

  });

});

function getData()
{

  var url_fu_service = http+"/reminder_booking/get_inputpicker/true";
  // console.log(url_kategori);



  $('#no_polisi_key').inputpicker({
    url:url_fu_service,
    fields:['NAMA_CUSTOMER','NO_POLISI'],
    fieldText:'NAMA_CUSTOMER',
    fieldValue:'NO_POLISI',
    filterOpen: true,
    headShow:true,
    pagination: true,
    pageMode: '',
    pageField: 'p',
    pageLimitField: 'per_page',
    limit: 5,
    pageCurrent: 1,
    // urlDelay:2
  })
  .on("change",function(e){
    e.preventDefault();

    var no_polisi=$(this).val();

    $(".loading-fu").html("<i class='fa fa-spinner fa-spin'></i>");

    $.getJSON(url_fu_service,{"no_polisi":no_polisi},function(result){

        $('#kd_customer').val(result.message[0].KD_CUSTOMER);
        $('#nama_customer').val(result.message[0].NAMA_CUSTOMER);
        $('#tipe_motor').val(result.message[0].KD_TYPEMOTOR);
        $('#no_telepon').val(result.message[0].NO_HP);

        $('#no_polisi').val(result.message[0].NO_POLISI);

        $(".loading-fu").html("");

    });

  });

}

function __getMotor(){
  var url = http+"/pkb/tipe_motor";
  var tipe_motor = $("#tipe_motor").val();

  $('#tipe_motor').inputpicker({
    url:url,
    urlParam:{"kd_item":tipe_motor},
    fields:['KD_ITEM','NAMA_PASAR', 'KET_WARNA'],
    fieldText:'KD_ITEM',
    fieldValue:'KD_ITEM',
    filterOpen: true,
    headShow:true,
    pagination: true,
    pageMode: '',
    pageField: 'p',
    pageLimitField: 'per_page',
    limit: 15,
    pageCurrent: 1,
    urlDelay:2
  })
}
</script>
