 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );

$file = ($list->totaldata > 0 ? '' : 'disabled-action'); 
$status_p = (isBolehAkses('p') ? $file : 'disabled-action' ); 
?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
<!-- 
      <a class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('claim/add_claimpromo'); ?>">
          <i class="fa fa-file-o fa-fw"></i> Input Claim
      </a> -->
      
      <!-- <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('claim/createfile_csv?kd_dealer='.$this->input->get('kd_dealer').'&jenis='.$this->input->get('jenis').'&kd_fincoy='.$this->input->get('kd_fincoy'));?>" role="button">
          <i class="fa fa-download fa-fw"></i> Create dan Simpan File .CSV
      </a> -->

      <a id="store-btn" class="btn btn-default" role="button">
          <i class="fa fa-download fa-fw"></i> Create dan Simpan File .XLS
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> List Claim Promo
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="addFormpromo" action="<?php echo base_url('claim/add_claimpromo') ?>" class="bucket-form" method="get">

          <!-- <input type="hidden" name="tahun_docno" value="<?php echo date('d/m/Y');?>"> -->
          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/sj_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled" required="true">
                    <option value="">- Pilih Dealer -</option>
                    <?php foreach ($dealer->message as $key => $group) : 
                      if($KD_DEALER!=''):
                        $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
                      elseif($this->input->get('kd_dealer') != ''):
                        $default=($this->input->get('kd_dealer')==$group->KD_DEALER)?" selected":" ";
                      else:
                        $default=($this->session->userdata("kd_dealer")==$group->KD_DEALER)?" selected":'';
                      endif;
                    ?>
                      <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-2">
                    
              <div class="form-group">
                  <label>Jenis</label>
                  
                <div class="form-inline">
                  <div class="radio">
                    <label>
                    <input type="radio" name="jenis" value="A" checked=""> Jenis A
                    </label>
                    <label>
                    <input type="radio" name="jenis" value="B"> Jenis B
                    </label>
                  </div>
                </div>
              </div>

            </div>


            <div class="col-xs-12 col-sm-4 col-sm-offset-1">
                    
              <div class="form-group">
                  <label>Company Leasing <span class="load-form"></span></label>
                  <select name="kd_fincoy" id="kd_fincoy" class="form-control">
                    <option value="">- Pilih Company -</option>
                    <?php foreach ($fincoy->message as $key => $comp) : ?>
                      <option <?php echo ($this->input->get('kd_fincoy')==$comp->KD_LEASING)?" selected":" ";?> value="<?php echo $comp->KD_LEASING;?>"><?php echo $comp->NAMA_LEASING;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Tanggal Claim</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tahun_docno" name="tahun_docno" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y'); ?>" type="text" readonly/>
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

    <div id="table_data" class="panel panel-default">
      <!-- <div class="panel-heading">
        Responsive Table
      </div> -->

       

      <?php echo $list_detail; ?>





    </div>
  </div>

</section>

<script type="text/javascript">

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function(){

  $('#kd_dealer, #kd_fincoy').change(function(){

    var kdDealer = $('#kd_dealer').val();
    var kdFincoy = $('#kd_fincoy').val();

    var url = http+'/claim/get_claimpromo';

    $('#loadpage').removeClass("hidden");
    $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
    $.getJSON(url,{
      kd_fincoy:kdFincoy,
      kd_dealer:kdDealer
    }, function(data, status){

      if(status == 'success'){
        // $('#no_terimasjm').val(data.no_terimasjm);

        $('#table_data').html(data.list_detail);

        $('#claim_all:checkbox:checked').each(function(){
          // alert('test');

            $('.claim').prop('checked', true);
        });

        $('#claim_all:checkbox:not(":checked")').each(function(){
          // alert('test');

            $('.claim').prop('checked', false);
        });

        $('#loadpage').addClass("hidden");
      }
      else{
        //alert('test');
        $('#table_data').html(data.list_detail);
        $('#loadpage').addClass("hidden");
      }

        $(".load-form").html('');

    });

    
  });

  $('.claim_all').click(function(){

    $('.claim_all:checkbox:checked').each(function(){
        $('.claim').prop('checked', true);
    });

    $('.claim_all:checkbox:not(":checked")').each(function(){
        $('.claim').prop('checked', false);
    });
  })


  $('#store-btn').click(function()
  {
    $("#addFormpromo").valid();

    $("#addFormpromo").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery("#addFormpromo").valid()) {
        storePengajuan();
    }
  });

});


function __data(){
  var data=[];

  var bariske   = $(".tr-ajukan").length;

  var totalDetail = $(".claim:checkbox").length;
  var detailChecked = $(".claim:checkbox:checked").length;

  for (i = 0; i < totalDetail; i++) {

    var ajukan = $(".claim_"+i+":checkbox:checked").val();
    if(ajukan == 1) {

      data.push({

        'kd_maindealer' : $("#kd_maindealer_"+i).val(),
        'kd_dealer' : $("#kd_dealer_"+i).val(),
        'kd_dealerahm' : $("#kd_dealerahm_"+i).val(),
        'no_mesin' : $("#no_mesin_"+i).val(),
        'spk_id' : $("#spk_id_"+i).val(),
        'kd_salesprogram' : $("#kd_salesprogram_"+i).val(),
        'nama_dealer' : $("#nama_dealer_"+i).val(),
        'no_rangka' : $("#no_rangka_"+i).val(),
        'kd_tipe' : $("#kd_tipe_"+i).val(),
        'nama_tipe' : $("#nama_tipe_"+i).val(),
        'kd_warna' : $("#kd_warna_"+i).val(),
        'deskripsi_warna' : $("#deskripsi_warna_"+i).val(),
        'nama_salesprogram' : $("#nama_salesprogram_"+i).val(),
        'no_faktur_jual' : $("#no_faktur_jual_"+i).val(),
        'tgl_faktur_jual' : $("#tgl_faktur_jual_"+i).val(),
        'tipe_penjualan' : $("#tipe_penjualan_"+i).val(),
        'kd_fincoy' : $("#kd_fincoy_"+i).val(),
        'nama_fincoy' : $("#nama_fincoy_"+i).val(),
        'alamat' : $("#alamat_"+i).val(),
        'kd_kota' : $("#kd_kota_"+i).val(),
        'nama_customer' : $("#nama_customer_"+i).val(),
        'nama_kota' : $("#nama_kota_"+i).val(),
        'tgl_bast' : $("#tgl_bast_"+i).val(),
        'tgl_faktur_stnk' : $("#tgl_faktur_stnk_"+i).val()
      });
    }

  }

  return data;

}

function storePengajuan()
{

  var data_form=__data();
  var datax = $('#addFormpromo').serialize();
  var total_data = $(".claim:checkbox:checked").length;
  var defaultBtn = $("#store-btn").html();

  console.log(data_form);

  // alert(ajukanstatusChecked);

  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();

  if(total_data > 0 && data_form != false)
  {
    var url_claim = http+'/claim/store_claimpromo';
    $.ajax({
      url:url_claim,
      type:"POST",
      dataType: "json",
      data:datax+"&detail="+JSON.stringify(data_form),
      success:function(result){

        if (result.status == true) 
        {
         
          $('.success').animate({ top: "0" }, 500);
          $('.success').html(result.message);

          var kd_dealer = $("#kd_dealer").val();
          var jenis = $("input[name=jenis]:checked").val();
          var kd_fincoy = $("#kd_fincoy").val();

          // alert(http+"/claim/createfile_csv?kd_dealer="+kd_dealer+"&jenis="+jenis+"&kd_fincoy="+kd_fincoy+"&no_claim="+result.noclaim);
          location.replace(http+"/claim/createfile_csv?kd_dealer="+kd_dealer+"&jenis="+jenis+"&kd_fincoy="+kd_fincoy+"&no_claim="+result.noclaim);

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
    $('.error').html("Maaf, tidak ada data yang yang dipilih.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
    }, 4000);
  }
}
</script>
