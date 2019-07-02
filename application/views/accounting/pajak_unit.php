<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$NO_NPWP = "";
$ALAMAT = "";


if($faktur && $this->input->get('kd_customer') != '' && (is_array($faktur->message) || is_object($faktur->message))):
  foreach ($faktur->message as $key => $value) {
    $NO_NPWP = $value->NO_NPWP;
    $ALAMAT = $value->ALAMAT_SURAT.'. '.$value->NAMA_KABUPATEN;
  }
endif;
?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">

      <a id="save-btn" class="btn btn-default <?php echo $status_p;?>" role="button">
          <i class="fa fa-save fa-fw"></i> Save
      </a>
    </div>
    <!-- </li> -->
  </div>

  
  <form id="pajakForm" action="<?php echo base_url('pajak/faktur_pajak_unit') ?>" class="bucket-form" method="get">

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading panel-custom">
          <i class="fa fa-list fa-fw"></i> Pajak Unit

          

          <!-- <span class="tools pull-right"> -->

            <!-- <div class="form-inline"> -->
              <!-- <div class="input-group input-group-sm"> -->
                <!-- <span class="input-group-addon" id="sizing-addon2">Nomor Seri Pajak</span> -->
                <!-- <input type="text" id="no_pajak" name="no_pajak" class="form-control" placeholder="123-12.12345678" value=""> -->
                
              <!-- </div> -->
            <!-- </div> -->
              <!-- <a class="fa fa-chevron-down" href="javascript:;"></a> -->
          <!-- </span> -->
      </div>

      <div class="panel-body panel-body-border" style="display: show;">
      
          
          <input type="hidden" id="jenis_faktur" name="jenis_faktur" value="U">
          <input type="hidden" id="nama_customer" name="nama_customer">

          <div class="row">


            <div class="col-xs-12 col-sm-3">

              <div class="form-group">
                  <label>Range No Pajak</label>
                  <input type="text" id="no_pajak" name="no_pajak" class="form-control" placeholder="123-12.12345678" value="">
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
                    
              <div class="form-group">
                  <label>Nama</label>

                  <select name="kd_customer" id="kd_customer" class="form-control form-disabled" required="true" disabled="">
                    <option value="">- Pilih Customer -</option>
                    <?php foreach ($pajak->message as $key => $group) : 
                      if($this->input->get('kd_customer') != ''):
                        $default=($this->input->get('kd_customer')==$group->KD_CUSTOMER)?" selected":" ";
                      else:
                        $default="";
                      endif;
                    ?>
                      <option value="<?php echo $group->KD_CUSTOMER;?>" <?php echo $default;?> ><?php echo $group->NAMA_CUSTOMER;?></option>
                    <?php endforeach; ?>
                  </select>

              </div>

            </div>


            <div class="col-xs-12 col-sm-2 col-sm-offset-1">

              <div class="form-group">

                <label class="control-label" for="date">Tanggal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_trans" name="tgl_trans" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_trans')?$this->input->get('tgl_trans'):date('d/m/Y'); ?>" type="text" required readonly/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>

          </div>


          <div class="row">

            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>Alamat</label>
                  
                  <input class="form-control form-disabled" id="alamat_customer" name="alamat_customer" placeholder="Alamat" type="text" required value="<?php echo $ALAMAT;?>" readonly/>
                  
              </div>

            </div>


            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>NPWP</label>
                  
                  <input class="form-control form-disabled" id="npwp_customer" name="npwp_customer" placeholder="NPWP" type="text" required value="<?php echo $NO_NPWP;?>" readonly/>
                  
              </div>

            </div>

            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>No Faktur Lainnya</label>
                  
                  <select name="kd_itemfaktur" id="kd_itemfaktur" class="form-control form-disabled" readonly>
                    <!-- <option value="D">D</option> -->
                    <?php 

                    if($faktur && $this->input->get('kd_customer') != '' && (is_array($faktur->message) || is_object($faktur->message))):
                      foreach ($faktur->message as $key => $value) {
                    ?>

                      <option value="<?php echo $value->FAKTUR_PENJUALAN;?>"><?php echo $value->FAKTUR_PENJUALAN;?></option>
                        
                    <?php
                      }
                    endif;
                    ?>
                  </select>
                  
                  
              </div>
            </div>


            <div class="col-xs-12 col-md-3">
              <br>
              <a id="store-btn" class="btn btn-primary pull-right">tambah</a>
              <!-- <a onclick="__openDetail();" class="btn btn-primary pull-right">add</a> -->
            </div>

          </div>



      </div>
    </div>
  </div>
  
  </form>

  
  <div class="col-lg-12 padding-left-right-10">

    <div id="table_data" class="panel panel-default">


       
        <div class="table-responsive">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
          <tr class="no-hover"><th colspan="10" ><i class="fa fa-list fa-fw"></i> List Pajak Unit</th></tr>

          <tr>
          <th>Nomor SJ</th>
          <th>Tipe Warna</th>
          <th>Qty</th>
          <th>No Mesin</th>
          <th>No Rangka</th>
          <th>Harga</th>
          </tr>
        </thead>
        <tbody>


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


  range();


  $("#no_pajak").on("change",function(){
    $(".form-disabled").removeAttr("disabled");
    $(".form-disabled").removeAttr("readonly");
  })

  $("#kd_customer").change(function(){
    var kd_customer = $(this).val();
    // alert(kd_customer);

    $.getJSON(http+"/pajak/faktur_pajak_unit/true",{'kd_customer':kd_customer},function(result){

      if(result.faktur.message.length > 0){
        // console.log(result.option_pajak);
        $("#list_data tbody").html("");

        $("#alamat_customer").val(result.faktur.message[0].ALAMAT_SURAT+'. '+result.faktur.message[0].NAMA_KABUPATEN);
        $("#npwp_customer").val(result.faktur.message[0].NO_NPWP);
        $("#nama_customer").val(result.faktur.message[0].NAMA_CUSTOMER);
        $("#kd_itemfaktur").html(result.option_pajak);

      }


    })
  });


  $('#no_pajak').mask('000-00.00000000', {'translation': {
    0: {pattern: /[0-9]/}
  }});

  $("#store-btn").click(function(){

    var kd_itemfaktur = $("select[name=kd_itemfaktur]").val();
    var defaultBtn = $("#store-btn").html();
   
    $("#store-btn").addClass("disabled");
    $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    // alert(kd_itemfaktur);
    
    if(kd_itemfaktur != ''){
      
      var url = http+"/pajak/faktur_unit";
      
      $.getJSON(url,{'kd_itemfaktur':kd_itemfaktur},function(result){

        // console.log(result);
        if (result.status == true) {

          $("#list_data tbody").append(result.faktur_unit);
          $("#kd_itemfaktur option[value='"+kd_itemfaktur+"']").remove();

          $("#store-btn").removeClass("disabled");
          $("#store-btn").html(defaultBtn);
        }
        else{
          $('.error').animate({ top: "0" }, 500).fadeIn();
          $('.error').html(result.faktur_unit);

          setTimeout(function () {
              hideAllMessages();
              $("#store-btn").removeClass("disabled");
              $("#store-btn").html(defaultBtn);
          }, 4000);
        }


      });

    }

  });

  $("#save-btn").click(function(){
    $("#pajakForm").valid();

    $("#pajakForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery("#pajakForm").valid()) {
        storePengajuan();
    }

  });
})


function range(){

  $.getJSON(http+"/pajak/get_seripajak",function(result){
    

    var datrans=[];
    if(result.noseri.message.length > 0){
      $.each(result.noseri.message,function(index,d){
        datrans.push({
          'NO TRANS':d.NOMOR
        })
      })
    }

    $('#no_pajak').inputpicker({
      data : datrans,
      fields :['NO TRANS'],
      fieldValue :'NO TRANS',
      fieldText:'NO TRANS',
      filterOpen :true
    }).on("change",function(){
      var range_seri = $(this).val();
      noSeri(range_seri);
    });
  })
}

function noSeri(range_seri){

  $.getJSON(http+"/pajak/get_seripajak/seri",{'range_seri':range_seri},function(result){

    var datrans=[];
    if(result.noseri.message.length > 0){
      $.each(result.noseri.message,function(index,d){
        datrans.push({
          'NO TRANS':d.NOMOR
        })
      })
    }

    $('#no_pajak').inputpicker({
      data : datrans,
      fields :['NO TRANS'],
      fieldValue :'NO TRANS',
      fieldText:'NO TRANS',
      filterOpen :true
    })

  })
}


function __data(){
  var data=[];
  var totalHarga = 0;

  var bariske   = $("#list_data >tbody > tr").length;
  // console.log(bariske);


  if(bariske > 0)
  {
    for(i = 0; i < bariske; i++)
    {

      totalHarga = totalHarga + parseFloat($(".harga_item:eq(" + i + ")").text());
      // $('.biaya_stnk').unmask();
      data.push({
        'no_faktur': $(".no_faktur:eq(" + i + ")").val(),
        'tgl_faktur': $(".tgl_faktur:eq(" + i + ")").val(),
        'kd_itemfaktur': $(".kd_itemfaktur:eq(" + i + ")").val(),
        'nama_itemfaktur': $(".nama_itemfaktur:eq(" + i + ")").text(),
        'qty_item': $(".qty_item:eq(" + i + ")").text(),
        'disc_item' : $(".disc_item:eq(" + i + ")").val(),
        'dpp_faktur' : $(".dpp_faktur:eq(" + i + ")").val(),
        'ppn_faktur' : $(".ppn_faktur:eq(" + i + ")").val(),
        'biaya_stnk' : $(".biaya_stnk:eq(" + i + ")").val(),
        'no_mesin': $(".no_mesin:eq(" + i + ")").text(),
        'no_rangka': $(".no_rangka:eq(" + i + ")").text(),
        'harga_item': $(".harga_item:eq(" + i + ")").text()
      });

      // $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    }


  }

  // console.log(totalHarga);
  var data = {
    totalHarga : totalHarga,
    detail : data
  }

  // console.log(data);

  return data;
  
}

function storePengajuan()
{
  var defaultBtn = $("#save-btn").html();

  $("#save-btn").addClass("disabled");
  $("#save-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  
  var data_form=__data();
  var url = http+'/pajak/store_pajakunit';

  var data = {
      no_pajak : $("#no_pajak").val(),
      tgl_trans : $("#tgl_trans").val(),
      tgl_pajak : $("#tgl_trans").val(),
      tgl_suratjalan : $("#tgl_trans").val(),
      kd_customer : $("#kd_customer").val(),
      nama_customer : $("#nama_customer").val(),
      alamat_customer : $("#alamat_customer").val(),
      npwp_customer : $("#npwp_customer").val(),
      jenis_faktur : $("#jenis_faktur").val(),
      total : data_form.totalHarga,
      detail : data_form.detail
  }


  // console.log(data);
  
  if(data_form.detail.length > 0){
    $.ajax({
      url:url,
      type:"POST",
      dataType: "json",
      data:data,
      success:function(result){

        if (result.status == true) 
        {
         
          $('.success').animate({ top: "0" }, 500).fadeIn();
          $('.success').html(result.message);

          setTimeout(function(){
              location.reload();
          }, 2000);
        }else{

          $('.error').animate({ top: "0" }, 500).fadeIn();
          $('.error').html(result.message);

          setTimeout(function () {
              hideAllMessages();
              $("#save-btn").removeClass("disabled");
              $("#save-btn").html(defaultBtn);
          }, 4000);
          
        }

      }
    });
  }
  else{
    $('.error').animate({ top: "0" }, 500).fadeIn();
    $('.error').html("Maaf, tidak ada data yang disimpan.");
    
    setTimeout(function () {
        hideAllMessages();
        $("#save-btn").removeClass("disabled");
        $("#save-btn").html(defaultBtn);
    }, 4000);
  }

}

</script>