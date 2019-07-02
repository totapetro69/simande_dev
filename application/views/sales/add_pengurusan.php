 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$batas_toleransi = ($toleransi <= 0 ? '' : 'disabled-action' ); 
$status_c = (isBolehAkses('c') ? $batas_toleransi : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$reff_source = $reff;
$reff_link = ($reff == 1 ? 'STNK' : 'BPKB');
$status_udstk = ($udstk == true ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_udstk : 'disabled-action' ); 
$KD_DEALER = ($this->input->get('kd_dealer'))?$this->input->get('kd_dealer'):$this->session->userdata("kd_dealer");
$KD_MAINDEALER = $this->session->userdata("kd_maindealer");
if($list && (is_array($list->message) || is_object($list->message))):
  foreach ($list->message as $key => $value) {
    $KD_DEALER = $value->KD_DEALER;
    $KD_MAINDEALER = $value->KD_MAINDEALER;
    # code...
  }
endif;
?>
<section class="wrapper">
<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>
    <div class="bar-nav pull-right ">
      <a id="store-btn" class="btn btn-default <?php echo $status_c;?>" role="button">
          <i class="fa fa-save fa-fw"></i> <?php echo $toleransi <= 0 ? 'Simpan':'Melebihi batas toleransi';?>
      </a>
      <a class="btn btn-default" href="<?php echo base_url('stnk/list_data/'.$reff_link); ?>">
          <i class="fa fa-list fa-fw"></i> List Pengajuan
      </a>
    </div>
    <!-- </li> -->
  </div>
  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Input Pengajuan Pengurusan <?php echo $reff_link;?>
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>
      <div class="panel-body panel-body-border" style="display: show;">
        <form id="pengurusanForm" action="<?php echo base_url('stnk/add_pengurusan/'.$reff_link) ?>" class="bucket-form" method="get">
          <input type="hidden" id="reff_source" name="reff_source" value="<?php echo $reff_source; ?>">
          <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">
          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">
          <!-- <div id="pengurus-url" url="<?php echo base_url('stnk/pengurus_typeahead');?>"></div> -->
          <div class="row">
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                    <?php
                        if (isset($dealer)) {
                            if ($dealer->totaldata > 0) {
                                foreach ($dealer->message as $key => $value) {
                                    $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                    $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                    echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                }
                            }
                        }
                    ?>
                  </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label class="control-label" for="date">Biro Jasa</label>
                <div class="input-group input-append">
                    <select name="nama_pengurus" id="nama_pengurus" class="form-control" required="true">
                      <option value="">- Pilih Biro Jasa -</option>
                      <?php foreach ($birojasa->message as $key => $birojasa_row):?>
                      <option value="<?php echo $birojasa_row->KD_BIROJASA;?>"><?php echo $birojasa_row->NAMA_PENGURUS;?></option>
                      <?php endforeach;?>
                    </select>
                    <span id="modal-button" class="input-group-addon add-on btn" onclick='addForm("<?php echo base_url('stnk/add_birojasa/'.$reff_link); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><span class="fa fa-plus"></span></span>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label class="control-label" for="date">Wilayah <span class="loading"></span></label>
                <input type="text" id="kd_kabupaten" name="kd_kabupaten" class="form-control" value="<?php echo $this->input->get('kd_kabupaten') ? $this->input->get('kd_kabupaten'):$this->session->userdata("kd_kabupaten"); ?>" required>
                <!-- <select name="kd_kabupaten" id="kd_kabupaten" class="form-control" required="true">
                  <option value="">- Pilih Kabupaten -</option>
                  <?php foreach ($kabupaten->message as $key => $kabupaten_row):
                      if($this->input->get('kd_kabupaten') != ''):
                        $default=($this->input->get('kd_kabupaten')==$kabupaten_row->KD_KABUPATEN)?" selected":" ";
                      else:
                        $default=($this->session->userdata("kd_kabupaten")==$kabupaten_row->KD_KABUPATEN)?" selected":'';
                      endif;
                  ?>
                  <option value="<?php echo $kabupaten_row->KD_KABUPATEN;?>" <?php echo $default;?>><?php echo $kabupaten_row->NAMA_KABUPATEN;?></option>
                  <?php endforeach;?>
                </select> -->
              </div>
            </div>
            <?php if ($reff_link == 'STNK'): ?>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                  <label>Pengurusan Manual</label>
                  <select name="status_manual" id="status_manual" class="form-control">
                    <option value="1" <?php echo $this->input->get('status_manual') == 1 ?'selected':'';?> >Tidak</option>
                    <option value="2" <?php echo $this->input->get('status_manual') == 2 ?'selected':'';?> >Ya</option>
                  </select>
              </div>
            </div>
            <?php else: ?>
              <input type="hidden" id="status_manual" name="status_manual" value="1">
            <?php endif; ?>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label class="control-label" for="date">Rncna Pengrusan</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tglmulai_pengurusan" name="tglmulai_pengurusan" placeholder="DD/MM/YYYY" value="<?php echo tglfromSql(getNextDays(tglToSql(date('d/m/Y')),2)); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                <label class="control-label" for="date">Sampai Tanggal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tglselesai_pengurusan" name="tglselesai_pengurusan" placeholder="DD/MM/YYYY" value="<?php echo tglfromSql(getNextDays(tglToSql(date('d/m/Y')),5)); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-12 padding-left-right-10">
    <div class="panel panel-default">
      <!-- <div class="panel-heading">
        Responsive Table
      </div> -->
        <div class="table-responsive h350">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
        <tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List <?php echo $reff_link;?></th></tr>
        <tr>
        <th style="vertical-align: middle;">No</th>
        <th style="vertical-align: middle;"><input id="stnk_all" class="stnk_all" name="stnk_all" value="1" type="checkbox"></th>
        <th>No. Rangka</th>
        <th>No. Mesin</th>
        <th style="white-space: nowrap;">KD Item</th>
        <th>Nama Pemilik</th>
        <th>Alamat</th>
        <th>Kd POS</th>
        </tr>
        </thead>
        <tbody>
                <?php echo $list_pengajuan; ?>
        </tbody>
        </table>
        </div>
    </div>
  </div>
</section>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
  getKabupaten();
  $("#status_manual, #kd_kabupaten").change(function(){
    $("#pengurusanForm").submit();
  })
  $(".uppercaseform").keyup(function(){
    $('.uppercaseform').val (function () {
        return this.value.toUpperCase();
    })
  });
  var date = new Date();
  date.setDate(date.getDate());
  $('.date').datepicker({
      format: 'dd/mm/yyyy',
      startDate: date,
      autoclose: true
  });
  var pengurusUrl = $("#pengurus-url").attr("url");
  $.getJSON(pengurusUrl, function(data, status) {
      if (status == 'success') {
          $("#nama_pengurus").typeahead({
              source: data.nama_pengurus,
              autoSelect: false
          });
      }
  });
  // var $("#ksu_all").
  $('#stnk_all').click(function(){
    $('#stnk_all:checkbox:checked').each(function(){
      // alert('test');
        $('.ajukan').prop('checked', true);
    });
    $('#stnk_all:checkbox:not(":checked")').each(function(){
      // alert('test');
        $('.ajukan').prop('checked', false);
    });
  });
  $('#store-btn').click(function()
  {
    $("#pengurusanForm").valid();
    $("#pengurusanForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);
        }
    });
    if (jQuery("#pengurusanForm").valid()) {
        storePengajuan();
    }
  });
});
function getKabupaten()
{
  var reff = "<?php echo $reff_link;?>";
  var url = http+"/stnk/add_pengurusan/"+reff+"/true";
  var dataKabupaten=[];
  // console.log(url);
  $(".loading").html("<i class='fa fa-spinner fa-spin'></i>");
  $.getJSON(url,function(result){
    if(result.kabupaten.message.length>0){
      $.each(result.kabupaten.message,function(e,d){
        dataKabupaten.push({
          'value' :d.KABUPATEN_SAMSAT,
          'NAMA KABUPATEN' : d.NAMA_KABUPATEN,
          'KODE KABUPATEN' : d.KABUPATEN_SAMSAT
        });
      })
    }
    $('#kd_kabupaten').inputpicker({
      data:dataKabupaten,
      fields:['NAMA KABUPATEN','KODE KABUPATEN'],
      fieldText:'NAMA KABUPATEN',
      fieldValue:'value',
      filterOpen: true,
      headShow:true,
      pagination: false,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 5,
      pageCurrent: 1,
      // urlDelay:2
    });
    $(".loading").html("");
  })
}
function __data(){
  var headerDeleted = [];
  var data=[];
  var biayaNull=[];
  var totalHeader = $(".ajukan:checkbox").length;
  var headerChecked = $(".ajukan:checkbox:checked").length;
  var reffSource = $("#reff_source").val();
  $('.biaya_stnk').unmask();
  if(headerChecked > 0)
  {
    for(i = 0; i < totalHeader; i++)
    {
      var ajukan_status = $(".ajukan_"+i+":checkbox:checked").val();
      var biaya_data = reffSource == 1?$("#biaya_stnk_"+i).val():$("#biaya_bpkb_"+i).val();
      // console.log(ajukan_status+'|'+biaya_data);
      if(ajukan_status == 1 && biaya_data != 0){
        data.push({
          'no_rangka' : $("#no_rangka_"+i).val(),
          'kd_mesin' : $("#kd_mesin_"+i).val(),
          'no_mesin' : $("#no_mesin_"+i).val(),
          'reff_source' : $("#reff_source").val(),
          'nama_pemilik' : $("#nama_pemilik_"+i).val(),
          'alamat_pemilik' : $("#alamat_pemilik_"+i).val(),
          'kd_kelurahan' : $("#kd_kelurahan_"+i).val(),
          'kd_kecamatan' : $("#kd_kecamatan_"+i).val(),
          'kd_kota' : $("#kd_kota_"+i).val(),
          'kode_pos' : $("#kode_pos_"+i).val(),
          'kd_propinsi' : $("#kd_propinsi_"+i).val(),
          'jenis_pembayaran' : $("#jenis_pembayaran_"+i).val(),
          'kd_dealer' : $("#kd_dealer_"+i).val(),
          'kd_fincoy' : $("#kd_fincoy_"+i).val(),
          'dp' : $("#dp_"+i).val(),
          'tenor' : $("#tenor_"+i).val(),
          'besar_cicilan' : $("#besar_cicilan_"+i).val(),
          'kd_customer' : $("#kd_customer_"+i).val(),
          'kd_item' : $("#kd_item_"+i).val(),
          'no_suratjalan' : $("#no_suratjalan_"+i).val(),
          'biaya_stnk' : $("#biaya_stnk_"+i).val(),
          'biaya_bpkb' : $("#biaya_bpkb_"+i).val(),
          'status_pengurusan' : $("#status_pengurusan_"+i).val(),
          'stck' : $("#stck_"+i).val(),
          'plat_asli' : $("#plat_asli_"+i).val(),
          'admin_samsat' : $("#admin_samsat_"+i).val(),
          'bpkb' : $("#bpkb_"+i).val(),
          'bbnkb' : $("#bbnkb_"+i).val(),
          'pkb' : $("#pkb_"+i).val(),
          'swdkllj' : $("#swdkllj_"+i).val(),
          'ss' : $("#ss_"+i).val(),
          'banpen' : $("#banpen_"+i).val(),
          'biaya_bbn' : $("#biaya_bbn_"+i).val()
        });
      }else{
        biayaNull.push({
          'no_rangka' : $("#no_rangka_"+i).val()
        });
      }
    }
  }
  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});
  var stnk = {
    // header : JSON.stringify(headerDeleted),
    detail : data,
    detail_null : biayaNull
  }
  // console.log(stnk);
  return stnk;
}
function storePengajuan()
{
  var data_form=__data();
  var total_data = $("#list_data >tbody > tr").length;
  var defaultBtn = $("#store-btn").html();
  var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;
  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();
  if(total_data > 0 && ajukanstatusChecked > 0 && data_form['detail'].length > 0)
  {
    var url_stnk = '<?php echo base_url("stnk/store_stnk");?>';
    var data = {
      reff_source : $("#reff_source").val(),
      nama_pengurus : $("#nama_pengurus").val(),
      kd_dealer : $("#kd_dealer").val(),
      kd_maindealer : $("#kd_maindealer").val(),
      status_manual : $("#status_manual").val(),
      tglmulai_pengurusan : $("#tglmulai_pengurusan").val(),
      tglselesai_pengurusan : $("#tglselesai_pengurusan").val(),
      tahun_docno : $("#tgl_trans").val(),
      detail : JSON.stringify(data_form['detail']),
      detail_null : JSON.stringify(data_form['detail_null'])
    }
  // console.log(data);
    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      data:data,
      success:function(result){
        if (result.status == true) 
        {
          $('.success').animate({ top: "0" }, 500);
          $('.success').html(result.message);
          setTimeout(function(){
              location.reload();
          }, 2000);
        }else{
          $('.error').animate({ top: "0" }, 500);
          $('.error').html(result.message);
          setTimeout(function () {
              hideAllMessages();
              $("#store-btn").removeClass("disabled");
              $("#store-btn").html(defaultBtn);
          }, 4000);
        }
      }
    });
  }
  else{
    $(".alert-message").fadeIn();
    $('.error').animate({ top: "0" }, 500);
    $('.error').html("Maaf, tidak ada data yang yang dipilih atau master biaya tidak di temukan");
    setTimeout(function () {
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
    }, 4000);
  }
}
function storeData(url, data, defaultBtn)
{
  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();
  $.ajax({
    url:url,
    type:"POST",
    dataType: "json",
    data:data,
    success:function(result){
      //alert(result);
      if(result.status == true){
        $('.success').animate({ top: "0" }, 500);
        $('.success').html(result.message);
        setTimeout(function(){
            location.reload();
        }, 2000);
      }else{
        $('.error').animate({ top: "0" }, 500);
        $('.error').html(result.message);
        setTimeout(function () {
            hideAllMessages();
            $("#store-btn").removeClass("disabled");
            $("#store-btn").html(defaultBtn);
        }, 4000);
      }
    }
  });
}
</script>