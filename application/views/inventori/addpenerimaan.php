<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$ROOT = ($this->session->userdata('nama_group')=='Root'?'':'disabled');
$defaultDealer=($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
$TOTAL_DATA = $total_data;
$KD_MAINDEALER="";
$KD_DEALER="";
$NO_SJMASUK="";
$NO_TERIMASJM="";
$TGL_TRANS="";
$TGL_SJMASUK="";
$EXPEDISI="";
$NOPOL="";
$NO_FAKTUR="";
$NO_PO="";
$URL_RM="";
if(base64_decode(urldecode($this->input->get("n")))){
// foreach ($rmheader->message as $key => $value) {
    // $URL_RM = $url_rm;
    $KD_MAINDEALER = $rmheader->KD_MAINDEALER;
    $KD_DEALER = $rmheader->KD_DEALER;
    $defaultDealer = $rmheader->KD_DEALER;
    $NO_SJMASUK = $rmheader->NO_SJMASUK;
    $NO_TERIMASJM = $rmheader->NO_TERIMASJM;
    $TGL_TRANS = tglfromSql($rmheader->TGL_TRANS);
    $TGL_SJMASUK = tglfromSql($rmheader->TGL_SJMASUK);
    $EXPEDISI = $rmheader->EXPEDISI;
    $NOPOL = $rmheader->NOPOL;
    $NO_FAKTUR = $rmheader->NO_FAKTUR;
    $NO_PO = $rmheader->NO_PO;
  // }
} 
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
            <a class="btn btn-default" href="<?php echo base_url('umsl/addpenerimaan'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>
            <a id="store-btn" class="btn btn-default" role="button">
            <!-- <a id="store-btn" class="btn btn-default" onclick="<?php echo ($NO_TERIMASJM == '')?'addRm()':'updateRm()';?>" role="button"> -->
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>
            <a class="btn btn-default" href="<?php echo base_url('umsl/terimamotor'); ?>" role="button">
                <i class="fa fa-table fa-fw"></i> List Penerimaan
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
        <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Input Penerimaan
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
        </div>
        <div class="panel-body panel-body-border">
          <form id="penerimaanForm" action="#" class="bucket-form" method="get">
            <input type="hidden" id="url_rm" name="url_rm" value="<?php echo $URL_RM;?>">
            <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER;?>">
            <input type="hidden" id="total_data" name="total_data" value="<?php echo $TOTAL_DATA;?>">
            <input type="hidden" id="nopol" name="nopol" value="<?php echo $NOPOL;?>">
            <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">
            <div class="row">
              <div class="col-xs-12 col-sm-2">
                  <div class="form-group">
                      <label>Dealer</label>
                      <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                        <option value="">- Pilih Dealer -</option>
                        <?php foreach ($dealer->message as $key => $group) : 
                            $defaultDealer=($defaultDealer==$group->KD_DEALER)?" selected":" ";
                        ?>
                          <option value="<?php echo $group->KD_DEALER;?>" <?php echo $defaultDealer;?> ><?php echo $group->NAMA_DEALER;?></option>
                        <?php endforeach; ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <label>No. Surat Jalan</label>
                    <?php if($NO_SJMASUK == ''):?>
                    <select id="no_sjmasuk" name="no_sjmasuk" class="form-control">
                      <option value="null">- Pilih No SJ -</option>
                      <?php 
                        if(isset($list)){
                          if($list->totaldata>0){
                            foreach ($list->message as $key => $list_row){
                              ?>
                                <option value="<?php echo $list_row->NO_SJMASUK;?>"><?php echo $list_row->NO_SJMASUK;?></option>
                      <?php 
                            }
                          }
                        }
                        if(isset($mutasi)){
                          if($mutasi->totaldata >0){
                            foreach ($mutasi->message as $key => $value) {
                              ?>
                                <option value="<?php echo $value->NO_TRANS;?>"><?php echo $value->NO_TRANS;?></option>
                              <?php
                            }
                          }
                        }
                      ?>
                    </select>
                    <?php else:?>
                    <input type="text" id="no_sjmasuk" name="no_sjmasuk" class="form-control" value="<?php echo $NO_SJMASUK;?>" placeholder="No. Terima SJ Masuk" disabled>
                    <?php endif;?>
                </div>
              </div>
              <!-- <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>No. Transaksi</label>
                    <input type="text" id="no_terimasjm" name="no_terimasjm" class="form-control" value="<?php echo $NO_TERIMASJM;?>" placeholder="No. Terima SJ Masuk" disabled>
                </div>
              </div> -->
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>Tanggal SJ <span class="load-form"></span></label>
                    <div class="input-group input-append ">
                        <input type="text" class="form-control" id="tgl_sjmasuk" name="tgl_sjmasuk" value="<?php echo $TGL_SJMASUK;?>" placeholder="dd/mm/yyyy" disabled/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>No. Faktur <span class="load-form"></span></label>
                    <input type="text" id="no_faktur" name="no_faktur" class="form-control" value="<?php echo $NO_FAKTUR;?>" placeholder="No. Faktur" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-5">
                <div class="form-group">
                    <label>Scan Nomor Mesin <span class="load-form-scan"></span></label>
                    <input type="text" id="no_mesin_scan" name="no_mesin_scan" class="form-control" value="" placeholder="Nomor Mesin">
                </div>
              </div>
              <div class="col-xs-12 col-sm-3 col-sm-offset-1">
                <div class="form-group">
                    <label>No. PO <span class="load-form"></span></label>
                    <input type="text" id="no_po" name="no_po" class="form-control" value="<?php echo $NO_PO;?>" placeholder="No. PO" disabled>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>Expedisi <span class="load-form"></span></label>
                    <input type="text" id="expedisi" name="expedisi" class="form-control" value="<?php echo $EXPEDISI;?>" placeholder="Expedisi" disabled>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
          <div class="table-responsive">
            <table id="list_data" class="table table-bordered table-hover b-t b-light">
            <thead>
                <tr class="no-hover"><th colspan="10" ><i class="fa fa-list fa-fw"></i> List Penerimaan SJ Motor</th></tr>
                <tr>
                    <th rowspan="2" style="">No.</th>
                    <!-- <th>No Terima SJ</th> -->
                    <th style="text-align: center;">Stock</th>
                    <th>Tipe  Stock</th>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>No. Mesin</th>
                    <th>No. Rangka</th>
                    <th style="">Gudang</th>
                </tr>
                <tr>
                    <th colspan="2" class="ket-stock">Keterangan</th>
                    <th colspan="5" style="text-align: center;"><input id="ksu_all" class="ksu_all" name="ksu_all" value="1" type="checkbox"> All KSU</th>
                </tr>
            </thead>
            <tbody>
            <?php
              if(base64_decode(urldecode($this->input->get("n")))){
                echo $detail;
              }
            ?>
            </tbody>
            </table>
          </div>
        <?php echo loading_proses();?>
      </div>
    </div>
</section>
<script type="text/javascript">
var path=window.location.pathname.split('/');
var http=window.location.origin + '/' + path[1];
$(document).ready(function(){
  // alert(http);
  $('#no_mesin_scan').focus();
  // var $("#ksu_all").
  $('#ksu_all').click(function(){
    $('#ksu_all:checkbox:checked').each(function(){
      // alert('test');
        $('.ksu').prop('checked', true);
    });
    $('#ksu_all:checkbox:not(":checked")').each(function(){
      // alert('test');
        $('.ksu').prop('checked', false);
    });
  });
  $("#list_data").on('change','.tipe_stock',function(){
    var tipe_stock = $(this).val();
    var key = $(this).data("key");
    // alert(key);
    if(tipe_stock==0){
      $("#keterangan_nrfs_"+key).removeAttr('disabled');
      // alert(tipe_stock);
    }
    else{
      $("#keterangan_nrfs_"+key).attr('disabled','disabled');
    }
    var $table = $('table.table');
    $table.trigger('reflow');
  });
  $('#no_sjmasuk, #kd_dealer').change(function(){
    var kdDealer = $('#kd_dealer').val();
    var sjNo = $('#no_sjmasuk').val();
    var url = '<?php echo base_url()."umsl/get_penerimaan";?>';
    $('#loadpage').removeClass("hidden");
    $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
    $.getJSON(url,{
      no_sjmasuk:sjNo,
      kd_dealer:kdDealer
    }, function(data, status){
      if(status == 'success'){
        // $('#no_terimasjm').val(data.no_terimasjm);
        $('#total_data').val(data.total_data);
        $('#tgl_sjmasuk').val(data.tgl);
        $('#kd_maindealer').val(data.sj.message['0'].KD_MAINDEALER);
        $('#no_faktur').val(data.sj.message['0'].NO_FAKTUR);
        $('#no_po').val(data.sj.message['0'].NO_PO);
        $('#expedisi').val(data.sj.message['0'].EXPEDISI);
        $('#nopol').val(data.sj.message['0'].NOPOL);
        $('tbody').html(data.rm);
        $('#ksu_all').prop('checked', false);
        /*$('#ksu_all:checkbox:checked').each(function(){
            $('.ksu').prop('checked', true);
        });
        $('#ksu_all:checkbox:not(":checked")').each(function(){
            $('.ksu').prop('checked', false);
        });*/
        $('#loadpage').addClass("hidden");
        var $table = $('table.table');
        $table.trigger('reflow');
      }
      else{
        //alert('test');
        $('tbody').html(data.rm);
        $('#loadpage').addClass("hidden");
      }
        $(".load-form").html('');
    });
  });
  $('#no_mesin_scan').focusout(function(){
    var no_mesin_scan = $(this).val();
    var kd_dealer = $("#kd_dealer").val();
    var no_sjmasuk = $("#no_sjmasuk").val();
    if(no_mesin_scan != '')
    {
      if(no_sjmasuk == 'null')
      {
        $('#loadpage').removeClass("hidden");
        $(".load-form-scan").html("<i class='fa fa-spinner fa-spin'></i>");
        $(".tr-notif").remove();
        var no = $("#list_data >tbody > tr.list-penerimaan").length;
        var url = '<?php echo base_url()."umsl/get_penerimaan/true?no_mesin=";?>'+no_mesin_scan+'&kd_dealer='+kd_dealer+'&no='+no;
        $.getJSON(url, function(data, status){
          if(status == 'success' && $('input').hasClass(data.no_mesin) == false){
            /*var cek = $('input').hasClass(data.no_mesin);
            alert(cek);*/
            $('#kd_maindealer').val(data.sj.message['0'].KD_MAINDEALER);
            $('#tgl_sjmasuk').val(data.tgl);
            $('tbody').append(data.rm);
            $('#ksu_all:checkbox:checked').each(function(){
              // alert('test');
                $('.ksu').prop('checked', true);
            });
            $('#ksu_all:checkbox:not(":checked")').each(function(){
              // alert('test');
                $('.ksu').prop('checked', false);
            });
          }
          $('#loadpage').addClass("hidden");
          $(".load-form-scan").html('');
          var $table = $('table.table');
          $table.trigger('reflow');
        });
      }
      $('.'+no_mesin_scan).prop('checked', true);
      $('#ksu_all').focus();
    }
  });
  /**
   * keypress = enter key
   */
  $('#no_mesin_scan').on('keypress',function(e){
    if(e.which===13){
      $(this).focusout();
    }
  })
  /**
   * Simpan data penerimaan
   */
  $('#store-btn').click(function(){
    var total_data = $("#list_data >tbody > tr.list-penerimaan").length;
    // alert(total_data);
    // var total_data = $("#total_data").val();
    var defaultBtn = $("#store-btn").html();
    var stockstatusNotChecked = $('.stock_status:checkbox:not(":checked")').length;
    var stockstatusChecked = $('.stock_status:checkbox:checked').length;
    var url_replace = '';
    // var bariske = $("#list_data >tbody > tr").length;
    $("#store-btn").addClass("disabled");
    $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    // alert(bariske);
    if(total_data > 0 && stockstatusChecked > 0)
    {
      var unset_number = 1;
      for(i = 0; i < total_data; i++)
      {
        var numberKSU = $('.ksu_'+i+':checkbox').length;
        var checkedKSU = $('.ksu_'+i+':checkbox:checked').length;
        var totallostKSU = numberKSU - checkedKSU;
        var stock_status = $(".stock_status_"+i+":checked").val();
        var status_gudang = $("#kd_gudang_"+i).val();
        // alert(status_gudang);
        // alert('total_data:'+total_data+ ' | stock_status:'+stock_status+ ' | stockstatusChecked:'+stockstatusChecked+ ' | ksu unset_number:'+unset_number);
        if(stock_status == 1 && status_gudang != ''){
          var url_penerimaan = '<?php echo base_url("umsl/store_penerimaan");?>';
          var val = [];
          var ksu_val = [];
          $('.ksu_'+i+':checkbox:checked').each(function(ksu){
            val[ksu] = $(this).val();
            ksu_val.push(val[ksu]);
          });
          var data = {
            unset : (stockstatusChecked == unset_number? true:false),
            jenis_penerimaan : $("#jenis_penerimaan_"+i).val(),
            id_reff : $("#id_reff_"+i).val(),
            id : $("#id_"+i).val(),
            no_sjmasuk : ($("#no_sjmasuk").val() == 'null'? $("#no_sjmasuk_"+i).val():$("#no_sjmasuk").val()),
            no_terimasjm : $("#no_terimasjm_"+i).val(),
            // no_terimasjm : ($("#no_terimasjm_"+i).val()? $("#no_terimasjm_"+i).val() : $("#no_terimasjm").val()),
            kd_maindealer : $("#kd_maindealer").val(),
            kd_dealer : $("#kd_dealer").val(),
            kd_item : $("#kd_item_"+i).val(),
            tipe_stock : $("#tipe_stock_"+i).val(),
            keterangan_nrfs : $("#keterangan_nrfs_"+i).val(),
            no_rangka : $("#no_rangka_"+i).val(),
            no_mesin : $("#no_mesin_"+i).val(),
            expedisi : ($("#no_sjmasuk").val() == 'null'? $("#expedisi_"+i).val():$("#expedisi").val()),
            nopol : ($("#no_sjmasuk").val() == 'null'? $("#nopol_"+i).val():$("#nopol").val()),
            kd_gudang : $("#kd_gudang_"+i).val(),
            ksu : ksu_val.join(": "),
            tahun_docno : $("#tgl_trans").val(),
            status_sj: (totallostKSU > 0 ? 1 : 2)
          } 
          // console.log(data);
          $.ajax({
            url:url_penerimaan,
            type:"POST",
            dataType: "json",
            data:data,
            success:function(result){
              if(result.status_unset == true){
                $.getJSON('<?php echo base_url()."umsl/unset_notrans"?>', function(data, status){
                  if(data.status == true){
                    $('.success').animate({ top: "0" }, 500);
                    $('.success').html(data.message);
                    $('#ksu_all').prop('checked', false);
                    getafterPost(defaultBtn);
                  }
                });
              }
            }
          });
          unset_number++;
        }
        else if(status_gudang == ''){
          // $(".alert-message").fadeIn();
          $('.error').animate({ top: "0" }, 500);
          $('.error').html("Maaf, gudang pada no mesin "+$("#no_mesin_"+i).val()+" tidak boleh kosong");
          getafterPost(defaultBtn);
          /*
          setTimeout(function () {
              hideAllMessages();
              $("#store-btn").removeClass("disabled");
              $("#store-btn").html(defaultBtn);
          }, 4000);*/
        }
      }
    }
    else{
      // $(".alert-message").fadeIn();
      $('.error').animate({ top: "0" }, 500);
      $('.error').html("Maaf, tidak ada data yang yang dipilih atau ditampilkan");
      setTimeout(function () {
          hideAllMessages();
          $("#store-btn").removeClass("disabled");
          $("#store-btn").html(defaultBtn);
      }, 4000);
    }
  });
})
function getafterPost(defaultBtn)
{
  var kdDealer = $('#kd_dealer').val();
  var sjNo = $('#no_sjmasuk').val();
  var url = '<?php echo base_url()."umsl/get_penerimaan?no_sjmasuk=";?>'+sjNo+'&kd_dealer='+kdDealer;
  $.getJSON(url, function(data, status){
    if(status == 'success'){
       $('#no_terimasjm').val('');
      $('tbody').html(data.rm);
      setTimeout(function(){
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
      }, 2000);
    }
    else{
      $('tbody').html(data.rm);
      setTimeout(function(){
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
      }, 2000);
    }
  });
}
</script>