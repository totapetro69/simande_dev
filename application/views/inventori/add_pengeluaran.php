<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 
$ID="";
$NO_SURATJALAN="";
$NO_SO="";
$NAMA_PENERIMA="";
$NAMA_PENGIRIM="";
$ALAMAT_KIRIM="";
$NO_POLISI="";
$NAMA_EKSPEDISI="";
$KD_GUDANG="";
$NO_MOBIL="";
$NAMA_SOPIR="";
$STATUS_SJ="process";
if(base64_decode(urldecode($this->input->get("n")))){
  foreach ($sjheader->message as $key => $value) {
    $ID             = $value->ID;
    $NO_SURATJALAN  = $value->NO_SURATJALAN;
    $NO_SO          = $value->NO_REFF;
    $NAMA_PENERIMA  = $value->NAMA_PENERIMA;
    $NAMA_PENGIRIM  = $value->NAMA_PENGIRIM;
    $ALAMAT_KIRIM   = $value->ALAMAT_KIRIM;
    $NO_POLISI      = $value->NO_POLISI;
    $NAMA_EKSPEDISI = $value->NAMA_EKSPEDISI;
    $KD_GUDANG      = $value->KD_GUDANG;
    $NO_MOBIL       = $value->NO_MOBIL;
    $NAMA_SOPIR     = $value->NAMA_SOPIR;    
    $STATUS_SJ      = $value->STATUS_SJ;    
  }
}

$edit_header = $STATUS_SJ == 'process' ? ''  : 'disabled';
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
            <a class="btn btn-default" href="<?php echo base_url('pengeluaran/add_pengeluaran'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>
            <?php if($STATUS_SJ == 'process'): ;?>
            <a id="store-btn" class="btn btn-default" role="button">
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>
            <?php endif ?>
            <?php if($NO_SURATJALAN != ''): ;?>
            <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url('pengeluaran/sj_keluar/'.$NO_SURATJALAN); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Print Surat Jalan</a>
            <?php endif ?>
            <a class="btn btn-default" href="<?php echo base_url('pengeluaran/pengeluaran'); ?>" role="button">
                <i class="fa fa-table fa-fw"></i> List Delivery Unit
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
        <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Delivery Unit
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
        </div>
        <div class="panel-body panel-body-border">
          <form id="pengeluaranForm" action="#" class="bucket-form" method="get">
            <input type="hidden" id="no_suratjalan" name="no_suratjalan" value="<?php echo $NO_SURATJALAN;?>">
            <input type="hidden" id="kd_customer" name="kd_customer">
            <input type="hidden" id="spk_id" name="spk_id">
            <input type="hidden" id="tgl_suratjalan" name="tgl_suratjalan">
            <input type="hidden" id="tgl_estimasikirim" name="tgl_estimasikirim">
            <input type="hidden" id="waktu_estimasikirim" name="waktu_estimasikirim">
            <input type="hidden" id="nama_pengirim" name="nama_pengirim" value="<?php echo $NAMA_PENGIRIM;?>">
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
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <label>No. Sales Order</label>
                    <input type="text" id="no_so" name="no_so" class="form-control" value="<?php echo $NO_SO;?>" placeholder="No. Sales Order" disabled>
                    <!-- <?php if($NO_SURATJALAN == ''):?>
                    <select id="no_so" name="no_so" class="form-control">
                      <option value="null">- Pilih No SO -</option>
                      <?php if($so && (is_array($so->message) || is_object($so->message))): foreach ($so->message as $key => $so_row):?>
                        <option value="<?php echo $so_row->NO_SO;?>"><?php echo $so_row->NO_SO;?></option>
                      <?php endforeach; endif;?>
                    </select>
                    <?php else:?>
                    <input type="text" id="no_so" name="no_so" class="form-control" value="<?php echo $NO_SO;?>" placeholder="No. Sales Order" disabled>
                    <?php endif;?> -->
                </div>
              </div>
              <div class="col-xs-12 col-sm-2">
                <div class="form-group">
                    <label>Nama Penerima <span class="load-form"></span></label>
                    <input type="text" id="nama_penerima" name="nama_penerima" class="form-control click-required" value="<?php echo str_replace("\'","'",$NAMA_PENERIMA);?>" placeholder="Nama Penerima" <?php echo $NO_SURATJALAN != ''?'disabled':'';?> required>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                    <label>Alamat Tujuan Lengkap <span class="load-form"></span></label>
                    <textarea rows="1" id="alamat_kirim" name="alamat_kirim" class="form-control" placeholder="Alamat Tujuan Lengkap" <?php echo $NO_SURATJALAN != ''?'disabled':'';?> required><?php echo $ALAMAT_KIRIM;?></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>Kode Gudang</label>
                    <select name="kd_gudang" id="kd_gudang" class="form-control" required="true" <?php echo $NO_SURATJALAN != ''?'disabled':'';?>>
                    <option value="">- Pilih Gudang -</option>
                    <?php foreach ($gudang->message as $key => $gudang) :
                        if($KD_GUDANG != '' or $KD_GUDANG != null):
                            $default=($KD_GUDANG==$gudang->KD_GUDANG)?" selected":" ";
                        else:
                            $default=($gudang->DEFAULTS == 1)?" selected":" ";
                        endif;
                    ?>
                    <option value="<?php echo $gudang->KD_GUDANG;?>" <?php echo $default;?> ><?php echo $gudang->NAMA_GUDANG;?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <!-- required dihilangkan karena admin  tidak tahu delivery nya -->
                <div class="form-group">
                    <label>Nomor Mobil</label>
                    <input type="text" id="no_mobil" name="no_mobil" class="form-control" value="<?php echo $NO_MOBIL;?>" style="text-transform: uppercase;" placeholder="AB-1234-XX" <?php echo $NO_SURATJALAN != ''?'disabled':'';?> >
                </div>
              </div>
              <div class="col-xs-12 col-sm-3  ">
                <div class="form-group">
                    <label>Delivery Man</label>

                    <!-- <input type="text" id="nama_sopir_2" name="nama_sopir" class="form-control" value="<?php echo $NAMA_SOPIR;?>" placeholder="Sopir" <?php echo $edit_header;?> > -->

                    <select name="nama_sopir" id="nama_sopir_2" class="form-control" <?php echo $edit_header;?>>
                    <option value="">- Pilih Delivery Man -</option>
                    <?php foreach ($supir->message as $key => $row_supir) :
                        if($NAMA_SOPIR != '' or $NAMA_SOPIR != null):
                            $default=($NAMA_SOPIR==$row_supir->NAMA_SUPIR)?" selected":" ";
                        else:
                            $default=($row_supir->DEFAULTS == 1)?" selected":" ";
                        endif;
                    ?>
                    <option value="<?php echo $row_supir->NAMA_SUPIR;?>" <?php echo $default;?> ><?php echo $row_supir->NAMA_SUPIR;?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
              </div>
              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <label>Honda ID</label>
                    <input type="text" id="nama_ekspedisi_2" name="nama_ekspedisi" class="form-control" value="<?php echo $NAMA_EKSPEDISI;?>" placeholder="Expedisi" <?php echo $edit_header;?> >
                </div>
              </div>

              <!-- 
                page-break-after: always; untuk css dompdf
                <img src="<?php echo base_url('pengeluaran/set_barcode/ajadulu');?>"> -->
              <!-- <?php echo $barcode;?> -->
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
          <!-- <div> -->
          <div class="table-responsive">
            <table class="table table-bordered table-hover b-t b-light">
            <thead>
                <tr class="no-hover"><th colspan="7" ><i class="fa fa-list fa-fw"></i> List Detail Delivery Unit</th></tr>
            </thead>
            </table>
            <div id="tbl_kendaraan" class="col-xs-12 col-md-12 padding-unset">
            <?php
              if(base64_decode(urldecode($this->input->get("n")))){
                echo $kendaraan;
              }
            ?>
            </div>
            <div id="tbl_ksu" class="col-xs-12 col-md-4 padding-unset">
            <?php
              if(base64_decode(urldecode($this->input->get("n")))){
                echo $ksu;
              }
            ?>
            </div>
            <div id="tbl_hadiah" class="col-xs-12 col-md-4 padding-unset">
            <?php
              if(base64_decode(urldecode($this->input->get("n")))){
                echo $hadiah;
              }
            ?>
            </div>
            <div id="tbl_barang" class="col-xs-12 col-md-4 padding-unset">
            <?php
              if(base64_decode(urldecode($this->input->get("n")))){
                echo $barang;
              }
            ?>
            </div>
          </div>
      </div>
    </div>
</section>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
  getSo();
  $('#no_mobil').mask('AZ-0001-AAZ',{'translation': {
    A: {pattern: /[A-Za-z]/},
    Z: {pattern: /[A-Za-z]/,optional:true},
    0: {pattern: /[0-9]/},
    1: {pattern: /[0-9]/,optional:true}
  }})
  $("#no_so, #kd_dealer").change(function(){
    var kdDealer = $('#kd_dealer').val();
    var soNo = $("#no_so").val();
    var url = '<?php echo base_url()."pengeluaran/get_so";?>';
    $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
    $.getJSON(url,{
      no_so:soNo,
      kd_dealer:kdDealer
    }, function(data, status){
      if(status == 'success'){
        $('#spk_id').val(data.so_header.message['0'].ID);
        $('#tgl_suratjalan').val(data.tgl_suratjalan);
        $('#alamat_kirim').val(data.so_header.message['0'].ALAMAT_KIRIM);
        $('#nama_penerima').val(data.so_header.message['0'].NAMA_PENERIMA);
        $('#nama_pengirim').val(data.so_header.message['0'].KEPALA_GUDANG);
        $('#kd_customer').val(data.so_header.message['0'].KD_CUSTOMER);
        $('#no_hp').val(data.so_header.message['0'].NO_HP);
        $('#tgl_estimasikirim').val(data.so_header.message['0'].TGL_KIRIM);
        $('#waktu_estimasikirim').val(data.so_header.message['0'].JAM_KIRIM);
        // $('#kd_gudang').val(data.so_header.message['0'].KD_GUDANG);
        $('#tbl_kendaraan').html(data.kendaraan);
        $('#tbl_ksu').html(data.ksu);
        $('#tbl_hadiah').html(data.hadiah);
        $('#tbl_barang').html(data.barang);
      }
      $(".load-form").html('');
    });
  });
  $('#store-btn').click(function()
  {
    $("#pengeluaranForm").valid();
    $("#pengeluaranForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);
        }
    });
    if (jQuery("#pengeluaranForm").valid()) {
        storePengeluaran();
    }
  });
  $("#tbl_kendaraan").on('click', '.status_kirim',function()
  {
      var ksu_stock = $(".status_kirim:checkbox:checked").length;
      var total_kendaraan = $("#table_kendaraan >tbody > tr").length;
      $(".data_stock").html("<i class=\'fa fa-spinner fa-spin\'></i>");
      // restock KSU
      var url = "recek_ksu/"+ksu_stock;
      $.getJSON(url, function(data, status){
          // console.log(status);
          $("#tbl_ksu").html(data);
      });
      // restock HADIAH
      var hadiah_val = [];
      for(i = 0; i < total_kendaraan; i++)
      {
          var status_kirim = $(".status_kirim_"+i+":checked").val();
          if(status_kirim == 1){
              hadiah_val.push($("#no_mesin_kendaraan_"+i).val());
          }
      }
      var url_hadiah = "recek_hadiah";
      var data_hadiah = {
          kirim : ksu_stock,
          hadiah : hadiah_val.toString(),
          no_so : $("#no_so").val()
      }
      $.ajax({
          url:url_hadiah,
          type:"POST",
          dataType: "json",
          data:data_hadiah,
          success:function(result){
              $("#tbl_hadiah").html(result);
          }
      });
      // console.log(hadiah_val);
  });
  var dataSupir=[];
  var dataMobil=[];
  var no_mobil = $("#no_mobil").val();
  // var edit
  // console.log(date);
  $.getJSON(http+"/pengeluaran/add_pengeluaran/true",{'no_mobil':no_mobil},function(result){
    //console.log(result.mobil.message);
    // if(result.supir.totaldata>0){
    //   $.each(result.supir.message,function(e,d){
    //     dataSupir.push({
    //       'value' :d.NAMA_SUPIR,
    //       'NAMA' : d.NAMA_SUPIR,
    //       'NO HP' : d.NO_HP
    //     });
    //   })
    // }
    if(result.mobil.totaldata>0){
      $.each(result.mobil.message,function(e,d){
        dataMobil.push({
          'value' :d.NO_POLISI,
          'NO MOBIL' : d.NO_POLISI,
          'MEREK' : d.MEREK
        });
      })
    }
    // console.log(dataMekanik);
    $('#nama_sopir').inputpicker({
      data:dataSupir,
      fields:['NAMA','NO HP'],
        fieldText:'NAMA',
        fieldValue:'value',
        filterOpen: true,
        headShow:true,
        pagination: true,
        pageMode: '',
        pageField: 'p',
        pageLimitField: 'per_page',
        limit: 10,
        pageCurrent: 1,
        urlDelay:1
    })
    $('#no_mobil').inputpicker({
      data:dataMobil,
      fields:['NO MOBIL','MEREK'],
        fieldText:'NO MOBIL',
        fieldValue:'value',
        filterOpen: true,
        headShow:true,
        pagination: true,
        pageMode: '',
        pageField: 'p',
        pageLimitField: 'per_page',
        limit: 10,
        pageCurrent: 1,
        urlDelay:1
    })
  })  
});
function getSo()
{
  var dataSo=[];
  // var edit
  // console.log(date);
  $.getJSON(http+"/pengeluaran/add_pengeluaran/true",{'n':"<?php echo $this->input->get("n");?>"},function(result){
    console.log(result.so.message);
    if(result.so.totaldata>0){
      $.each(result.so.message,function(e,d){
        dataSo.push({
          'NO SO' :d.NO_SO,
          'NAMA CUSTOMER' :d.NAMA_CUSTOMER
        });
      })
    }
    $('#no_so').inputpicker({
      data:dataSo,
      fields:['NO SO', 'NAMA CUSTOMER'],
        fieldText:'NO SO',
        fieldValue:'NO SO',
        filterOpen: true,
        headShow:true,
        pagination: true,
        pageMode: '',
        pageField: 'p',
        pageLimitField: 'per_page',
        limit: 10,
        pageCurrent: 1,
        urlDelay:1
    })
  })  
}

function getData()
{
  var data=[];

  var total_kendaraan = $("#table_kendaraan >tbody > tr.tr-count").length;
  var total_ksu = $("#table_ksu >tbody > tr.tr-count").length;
  var total_hadiah = $("#table_hadiah >tbody > tr.tr-count").length;
  var total_barang = $("#table_barang >tbody > tr.tr-count").length;
  
  // alert(total_kendaraan+'|'+total_ksu+'|'+total_hadiah+'|'+total_barang);


  if(total_ksu > 0){
    for(i = 0; i < total_ksu; i++)
    {
      if ($("#jumlah_ksu_"+i).val() != 0) {
        data.push({
          'kd_warna' : null,
          'no_mesin' : null,
          'no_rangka' : null,
          'nama_item' : $("#nama_item_ksu_"+i).val(),
          'jumlah' : $("#jumlah_ksu_"+i).val(),
          'ket_unit' : $("#ket_unit_ksu_"+i).val()
        });
      }
    }
  }
  if(total_hadiah > 0){
    for(i = 0; i < total_hadiah; i++)
    {
      data.push({
        'kd_warna' : null,
        'no_mesin' : null,
        'no_rangka' : null,
        'nama_item' : $("#nama_item_hadiah_"+i).val(),
        'jumlah' : $("#jumlah_hadiah_"+i).val(),
        'ket_unit' : $("#ket_unit_hadiah_"+i).val()
      });
    }
  }
  if(total_barang > 0){
    for(i = 0; i < total_barang; i++)
    {

      data.push({
        'kd_warna' : $("#kode_item_barang_"+i).val(),
        'no_mesin' : null,
        'no_rangka' : null,
        'nama_item' : $("#nama_item_barang_"+i).val(),
        'jumlah' : $("#jumlah_barang_"+i).val(),
        'ket_unit' : $("#ket_unit_barang_"+i).val()
      });
    }
  }
  if(total_kendaraan > 0){
    var urutan_kirim = 1;
    for(i = 0; i < total_kendaraan; i++)
    {
      var status_kirim = $(".status_kirim_"+i+":checked").val();
      // alert('total_kendaraan:'+total_kendaraan);
      if(status_kirim == 1)
      {
        data.push({
          'kd_warna' : $("#kd_warna_kendaraan_"+i).val(),
          'no_mesin' : $("#no_mesin_kendaraan_"+i).val(),
          'no_rangka' : $("#no_rangka_kendaraan_"+i).val(),
          'nama_item' : $("#nama_item_kendaraan_"+i).val(),
          'jumlah' : $("#jumlah_kendaraan_"+i).val(),
          'ket_unit' : $("#ket_unit_kendaraan_"+i).val()
        });
      }
    }
  }

  return data;
}

function storePengeluaran()
{
  var data_form=getData();
  
  // console.log(data_form);

  var defaultBtn = $("#store-btn").html();
  var status_so = $("#no_so").val();
  var statusKirim = $('.status_kirim:checkbox:checked').length;
  var statusNotKirim = $('.status_kirim:checkbox:not(":checked")').length;
  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();
  if(status_so != 'null' && statusKirim > 0)
  {
    var url_sjkeluar = '<?php echo base_url("pengeluaran/store_sjkeluar");?>';
    var data = {
      status_spk  : (statusNotKirim == 0? 4:3),
      no_suratjalan : $("#no_suratjalan").val(),
      spk_id : $("#spk_id").val(),
      kd_dealer : $("#kd_dealer").val(),
      tahun_docno : $("#tgl_suratjalan").val(),
      kd_gudang : $("#kd_gudang").val(),
      no_mobil : $("#no_mobil").val(),
      nama_sopir : $("#nama_sopir_2").val(),
      tgl_suratjalan : $("#tgl_suratjalan").val(),
      tgl_estimasikirim : $("#tgl_estimasikirim").val(),
      waktu_estimasikirim : $("#waktu_estimasikirim").val(),
      no_so : $("#no_so").val(),
      kd_customer : $("#kd_customer").val(),
      alamat_kirim : $("#alamat_kirim").val(),
      nama_ekspedisi : $("#nama_ekspedisi_2").val(),
      nama_penerima : $("#nama_penerima").val(),
      nama_pengirim : $("#nama_pengirim").val(),
      detail : JSON.stringify(data_form)
    } 


    $.ajax({
      url:url_sjkeluar,
      type:"POST",
      dataType: "json",
      data:data,
      success:function(result){
        if(result.status == true){
          $('.success').animate({ top: "0" }, 500);
          $('.success').html(result.message);
          setTimeout(function(){
              location.replace(result.location);
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
    $('.error').animate({ top: "0" }, 500);
    $('.error').html("Maaf, tidak ada data yang yang dipilih atau ditampilkan");
    setTimeout(function () {
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
    }, 4000);
  }
}
</script>