<?php if(!isBolehAkses() || isBolehAkses('c') == false){ redirect(base_url().'auth/error_auth');}

  
  $TOTAL_DATA=$total_data;
  $ID="";
  $NO_SPK="";
  $TGL_SPK="";
  $ESTIMASI="";
  $NO_SO="";
  $NAMA_CUSTOMER="";
  $KD_CUSTOMER="";
  $ALAMAT_SURAT="";
  $FAKTUR_PENJUALAN="";

  $NO_INDENT="";
  $TGL_INDENT=date('d/m/Y');
  $NO_HP="";
  $STATUS_INDENT="";

  if(base64_decode(urldecode($this->input->get("n")))){
    foreach ($soheader->message as $key => $value) {
      $ID               = $value->ID;
      $NO_SPK           = $value->NO_SPK;
      $TGL_SPK          = tglfromSql($value->TGL_SPK);
      $ESTIMASI         = tglfromSql(getNextDays($value->TGL_SPK, 30));
      $NO_SO            = $value->NO_SO;
      $NAMA_CUSTOMER    = $value->NAMA_CUSTOMER;
      $KD_CUSTOMER      = $value->KD_CUSTOMER;
      $ALAMAT_SURAT     = $value->ALAMAT_SURAT;
      $FAKTUR_PENJUALAN = $value->FAKTUR_PENJUALAN;
      $NO_HP            = $value->NO_HP;
      $STATUS_INDENT    = $value->STATUS_INDENT;
      $NO_INDENT        = $value->NO_INDENT;
    }
  }

  $disabled_action = ($NO_SO == ''?'disabled-action' : '');

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
  $status_p = (isBolehAkses('p') ? $disabled_action : 'disabled-action' ); 
?>

<style type="text/css">
  .addtional-disabled{
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.8;
  }
</style>

<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>
    <div class="bar-nav pull-right ">


      <a id="modal-button" class="btn btn-default btn-indentform <?php echo $status_c?> <?php echo ($NO_SPK?'':'addtional-disabled');?>" onclick='addForm("<?php echo base_url('sales_order/add_indent?no_spk='.$NO_SPK); ?>");' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
        <i class="fa fa-refresh fa-fw"></i> Create Indent
      </a>

      <!-- <a id="indent-btn" class="btn btn-default <?php echo $status_c." ".$status_e;?> <?php echo ($NO_SPK?'':'addtional-disabled');?>" role="button"><i class="fa fa-refresh fa-fw"></i> Create Indent</a> -->

      <a class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('sales_order/add_sales_order'); ?>"><i class="fa fa-file-o fa-fw"></i> Baru</a>
      <a id="store-btn" class="btn btn-default <?php echo $status_c." ".$status_e;?>" role="button"><i class="fa fa-save fa-fw"></i> Simpan</a>
      <!-- <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('sales_order/faktur_penjualan/'.$ID); ?>" target="_blank"><i class="fa fa-print fa-fw"></i> Faktur Penjualan</a> -->
      <a class="btn btn-default" href="<?php echo base_url('sales_order/sales_order'); ?>"><i class="fa fa-list fa-fw"></i> List Sales Order</a>
    </div>
  </div>


  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Sales Order Header
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
           </span>
      </div>

      <div class="panel-body panel-body-border">

        <form id="soForm" action="#" class="bucket-form" method="POST">
          
          <input type="hidden" id="tgl_docno" name="tgl_docno" value="<?php echo date('d/m/Y');?>">
          <input type="hidden" id="total_data" name="total_data" value="<?php echo $TOTAL_DATA;?>">
          <input type="hidden" id="spk_id" name="spk_id">
          <input type="hidden" id="faktur_penjualan" name="faktur_penjualan" value="<?php echo $FAKTUR_PENJUALAN;?>">

          <input type="hidden" id="no_indent" name="no_indent" value="<?php echo $NO_INDENT;?>">
          <input type="hidden" id="tgl_indent" name="tgl_indent" value="<?php echo $TGL_INDENT;?>">
          <input type="hidden" id="no_hp" name="no_hp" value="<?php echo $NO_HP;?>">
          <input type="hidden" id="status_indent" name="status_indent" value="<?php echo $STATUS_INDENT;?>">
          <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $KD_CUSTOMER;?>">

          <div class="row">
            <div class="col-xs-12 col-sm-3">
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

            <div class="col-xs-12 col-md-2">
              <div class="form-group">
                <label>Nomor SPK</label>
                
                <input type="text" id="no_spk" name="no_spk" class="form-control" value="<?php echo @$NO_SPK;?>" <?php echo ($NO_SPK != ""?'disabled':'');?>>

                <!-- <?php if($NO_SPK != ""):?>
                  <input type="text" id="no_spk" name="no_spk" class="form-control" value="<?php echo @$NO_SPK;?>" disabled>
                <?php else: ?>
                  <select id="no_spk" name="no_spk" class="form-control">
                    <option value="null">- Pilih No SPK -</option>
                    <?php if($spk && (is_array($spk->message) || is_object($spk->message))): foreach ($spk->message as $key => $spk_row):?>
                      <option value="<?php echo $spk_row->NO_SPK;?>"><?php echo $spk_row->NO_SPK;?></option>
                    <?php endforeach; endif;?>
                  </select>
                <?php endif;?>  -->
              </div>

            </div>

            <div class="col-xs-12 col-md-2">
              <div class="form-group">
                  <label>Nomor Sales Order</label>
                  <input type="text" id="no_so" name="no_so" class="form-control" placeholder="Nomor Sales Order" value="<?php echo $NO_SO;?>" disabled>
              </div>
            </div>


         <!--  </div>

          <div class="row"> -->

            <div class="col-xs-12 col-md-3">
              <div class="form-group">
                  <label>Customer <span class="load-form"></span></label>
                  <input id="nama_customer" type="text" name="nama_customer" class="form-control" value="<?php echo @$NAMA_CUSTOMER;?>" placeholder="Nomor Induk Karyawan atau username" autocomplete="off" disabled>
              </div>
            </div>
            <div class="col-xs-12 col-md-2">
              <div class="form-group">
                  <label>Tanggal SPK <span class="load-form"></span></label>

                  <div class="input-group input-append ">
                      <input type="text" class="form-control" id="tgl_spk" name="tgl_spk" value="<?php echo $TGL_SPK;?>" placeholder="dd/mm/yyyy" disabled/>
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
      <div class="table-responsive">
        <table id="list_data" class="table table-bordered table-hover b-t b-light">
          <thead>
            <tr class="no-hover"><th colspan="5" ><i class="fa fa-list fa-fw"></i> List Sales Order Detail</th></tr>
            <tr>
              <th style="width:40px;">No.</th>
              <th>Kode Item</th>
              <th>Nama Motor</th>
              <th>Nomor Mesin</th>
              <th>Nomor Rangka</th>
              <!-- <th>Hadiah</th> -->
            </tr>
          </thead>
          <tbody>


            <?php
              if(base64_decode(urldecode($this->input->get("n")))){

                if($detail != ''):
                echo $detail;
                endif;
                
              }
            ?>

          </tbody>
        </table>
      </div>

    </div>
  </div>
  <?php echo loading_proses(); ?>
</section>
<script type="text/javascript">

var url_rangka = "<?php echo base_url().'sales_order/get_rangka/';?>";


var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
  
$(document).ready(function(){

  getSPk();

  $('#no_spk, #kd_dealer').change(function(){

    refreshData();


  });

  $('#store-btn').click(function(){

    var total_data = $("#list_data >tbody > tr.input-so").length;
    // alert(total_data);
    // var total_data = $("#total_data").val();
    var defaultBtn = $("#store-btn").html();
    var listData   = $("#list_data >tbody > tr.input-so").length - 1;


    $("#store-btn").addClass("disabled");
    $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    if(total_data > 0){

      for(i = 0; i < total_data; i++)
      {
        var url_sokendaraan = '<?php echo base_url("sales_order/update_sokendaraan");?>';

        var data = {
          unset             : (listData == i? true:false),

          kd_dealer         : $("#kd_dealer").val(),
          tahun_docno       : $("#tgl_docno").val(),
          spk_id            : $("#spk_id").val(),

          no_hp             : $("#no_hp").val(),
          status_indent     : $("#status_indent").val(),
          no_trans          : $("#no_indent").val(),


          no_so             : $("#no_so").val(),
          faktur_penjualan  : $("#faktur_penjualan").val(),
          sdk_id            : $("#sdk_id_"+i).val(),
          no_mesin          : $("#no_mesin_"+i+" option:selected").val(),
          no_mesin_old      : $("#no_mesin_old_"+i).val(),
          no_rangka         : $("#no_rangka_"+i).val(),
          aksesoris         : $("#aksesoris_"+i+":checked").val() == 'on' ? 1 : 0,
          hadiah            : $("#hadiah_"+i+":checked").val() == 'on' ? 1 : 0,
          program_hadiah    : $(".kd_prgram_"+i).map(function(){return $(this).val();}).get()
          // program_hadiah    : $('#kd_hadiah_'+i).val()
        } 

        // $("input[name='pname[]']").map(function(){return $(this).val();}).get();

        // console.log(data);
        
        $.ajax({
          url:url_sokendaraan,
          type:"POST",
          dataType: "json",
          data:data,
          success:function(result){

            if(result.status_unset == true){

              $.getJSON('<?php echo base_url()."sales_order/unset_notrans"?>', function(data, status){
                if(data.status == true){


                  $('.success').animate({ top: "0" }, 500);
                  $('.success').html(data.message);


                  setTimeout(function(){
                      location.replace(result.location);
                  }, 2000);


                }
              });
            }
          }

        });
      }      
    }
    else{
      $(".alert-message").fadeIn();

      $('.error').animate({ top: "0" }, 500);
      $('.error').html("Maaf, tidak ada yang ditampilkan");
      
      setTimeout(function () {
          hideAllMessages();
          $("#store-btn").removeClass("disabled");
          $("#store-btn").html(defaultBtn);
      }, 4000);
    }

  });

  $("#list_data").on('change','.val-diff',function(){

    var no_mesin = $(this).val();
    var mesin_id = this.id;
    var id = mesin_id.split("_");
    var url = url_rangka+no_mesin;

    // alert(url);

    $("#no_rangka_"+id["2"]).val("Loading..");
    $('#loadpage').removeClass("hidden");

    $.getJSON(url, function(data, status){

        if(data.status != false){

            // alert("#no_rangka_"+id["2"]+"  "+data.message["0"].NO_RANGKA);

            $("#no_rangka_"+id["2"]).val(data.message["0"].NO_RANGKA);

            $('#loadpage').addClass("hidden");
        }
        else{

            $("#no_rangka_"+id["2"]).val("");
            $('#loadpage').addClass("hidden");
        }

    });


    $(".val-diff option").removeAttr("disabled");

    DisableOptions();

  })
/*
  $('#indent-btn').click(function(){

    var defaultBtn = $("#indent-btn").html();
    var total_data = $("#list_data >tbody > tr.input-so").length;
    var listData   = total_data - 1;

    $("#indent-btn").addClass("disabled");
    $("#indent-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");


    if(total_data > 0){

      for(i = 0; i < total_data; i++)
      {
        var url = '<?php echo base_url("sales_order/create_indentlist");?>';

        var data = {
          unset             : (listData == i? true:false),

          kd_dealer         : $("#kd_dealer").val(),
          no_trans          : $("#no_indent").val(),
          no_spk            : $("#no_spk").val(),
          tgl_trans         : $("#tgl_indent").val(),
          kd_item           : $("#kd_item_"+i).val(),
          jumlah_unit       : 1,
          kd_customer       : $("#kd_customer").val()
        } 

        $.ajax({
          url:url,
          type:"POST",
          dataType: "json",
          data:data,
          success:function(result){
            if(result.status == true){
              $('.success').animate({ top: "0" }, 500).fadeIn();
              $('.success').html(result.message);
              $("#no_indent").val(result.location);
              refreshData();
              getSPk();

              setTimeout(function () {
                  hideAllMessages();
                  $("#indent-btn").removeClass("disabled");
                  $("#indent-btn").html(defaultBtn);
              }, 4000);
            }
            else{
              $('.error').animate({ top: "0" }, 500).fadeIn();
              $('.error').html(result.message);
              
              setTimeout(function () {
                  hideAllMessages();
                  $("#indent-btn").removeClass("disabled");
                  $("#indent-btn").html(defaultBtn);
              }, 4000);
            }
          }

        });
      }      
    }
    else{
      $(".alert-message").fadeIn();

      $('.error').animate({ top: "0" }, 500);
      $('.error').html("Maaf, unit tidak ada");
      
      setTimeout(function () {
          hideAllMessages();
          $("#indent-btn").removeClass("disabled");
          $("#indent-btn").html(defaultBtn);
      }, 4000);
    }


    // var formData = $('#soForm').serialize();

    // console.log(data);

  })*/
})

function getSPk()
{
  var url = "<?php echo base_url().'sales_order/add_sales_order/true';?>";


  var dataSpk = [];

  $.getJSON(url,{'n':"<?php echo $this->input->get("n");?>"},function(result){

    console.log(result.message);

    if(result.totaldata>0){

      $.each(result.message,function(e,d){

        var STATUS_INDENT = (d.STATUS_INDENT == 1 ? 'Yes' : 'No');

        dataSpk.push({
          'NO SPK' :d.NO_SPK,
          'NAMA CUSTOMER' : d.NAMA_CUSTOMER,
          'MOTOR INDENT' : STATUS_INDENT
        });
        
      })
    }

    // console.log(dataMekanik);
    $('#no_spk').inputpicker({
      data:dataSpk,
      fields:['NO SPK','NAMA CUSTOMER', 'MOTOR INDENT'],
        fieldText:'NO SPK',
        fieldValue:'NO SPK',
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

function refreshData()
{
  var url = '<?php echo base_url()."sales_order/get_spk";?>';

  $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

  $.getJSON(url,{
    no_spk : $('#no_spk').val(), 
    kd_dealer : $('#kd_dealer').val()}, 
    function(data, status){

    if(status == 'success'){

      $('#spk_id').val(data.spk.message['0'].ID);
      $('#no_so').val(data.spk.message['0'].NO_SO);
      $('#faktur_penjualan').val(data.spk.message['0'].FAKTUR_PENJUALAN);
      $('#nama_customer').val(data.spk.message['0'].NAMA_CUSTOMER);
      $('#kd_customer').val(data.spk.message['0'].KD_CUSTOMER);
      $('#tgl_spk').val(data.tgl_spk);
      $('#tgl_stnk').val(data.tgl);
      $('#tgl_bpkb').val(data.tgl);
      $('#total_data').val(data.total_data);

      $('#no_indent').val(data.spk.message['0'].NO_INDENT);
      $('#no_hp').val(data.spk.message['0'].NO_HP);
      $('#status_indent').val(data.spk.message['0'].STATUS_INDENT);

      $('#list_data tbody').html(data.so).append(data.script);

      $(".btn-indentform").removeClass('addtional-disabled');
      $('.btn-indentform').attr('onclick','addForm("'+http+'/sales_order/add_indent?no_spk='+$('#no_spk').val()+'");');
    }

      $(".load-form").html('');

  });
}

 
function DisableOptions()
{
    $(".val-diff option").filter(function()
    {
    var bSuccess=false;
    var selectedEl=$(this);
    $(".val-diff option:selected").each(function()
    {

    if($(this).val()==selectedEl.val())
    {
    bSuccess=true;
    return false;
    };
    });
    return bSuccess;

    }).attr("disabled","disabled");   

}

function __checks(spk_id,id){
  if($('#hadiah_'+id).is(':checked')==true){
    $('#lst_hadiah').removeClass('hidden');
  }else{
    $('#lst_hadiah').addClass('hidden');
  }
}

</script>