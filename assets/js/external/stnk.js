

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function(){


  $('#no_plat, .no_plat').mask('AZ-0001-AAZ',{'translation': {
    A: {pattern: /[A-Za-z]/},
    Z: {pattern: /[A-Za-z]/,optional:true},
    0: {pattern: /[0-9]/},
    1: {pattern: /[0-9]/,optional:true}
  }})
  
  var date = new Date();
  date.setDate(date.getDate());

/*
  $('.datetime').datetimepicker({
    format:'hh:mm:ss',
    pickDate: false,
    // pickTime: false,
    autoclose: true
  });*/

  $('.datetime').datetimepicker({
      format: 'DD/MM/YYYY',
      defaultDate: date
  });

  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});
  $('.form_biaya').mask('000.000.000.000.000', {reverse: true});
  // $('#total_biayapengajuan').mask('000.000.000.000.000', {reverse: true});
  // $('#total_biayaapprove').mask('000.000.000.000.000', {reverse: true});


  $('#no_trans, #kd_dealer').change(function(){

    var kdDealer = $('#kd_dealer').val();
    var transNo = $('#no_trans').val();
    var status_stnk = $('#status_stnk').val();

    var url = http+'/stnk/get_approval';

    $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

    $.getJSON(url,{
      no_trans:transNo,
      status_stnk:status_stnk,
      kd_dealer:kdDealer
    }, function(data, status){

      if(status == 'success'){


        $('#nama_pengurus').val(data.stnk_header.message['0'].NAMA_PENGURUS);
        // $('#tgl_trans').val(data.stnk_header.message['0'].TGL_STNK);

        $('tbody').html(data.list_approval);
      }

      $(".load-form").html('');

    });


  });

  $('.stnk_all').click(function(){

    $('.stnk_all:checkbox:checked').each(function(){
        $('.ajukan_all').prop('checked', true);
        $('.ajukan').prop('checked', true);
    });

    $('.stnk_all:checkbox:not(":checked")').each(function(){
        $('.ajukan_all').prop('checked', false);
        $('.ajukan').prop('checked', false);
    });
  })

  $('.ajukan_all').click(function(){

    var key = $(this).val();

    $('.ajukan_all_'+key+':checkbox:checked').each(function(){
      // alert('test');

        $('.ajukan_'+key).prop('checked', true);

        $('.total_stnk_'+key).removeAttr('style');
        $('.total_biayaapprove_'+key).focus();
        // $('.total_stnk_'+key).css('display','show');

    });

    $('.ajukan_all_'+key+':checkbox:not(":checked")').each(function(){
      // alert('test');

        $('.ajukan_'+key).prop('checked', false);
        $('.total_stnk_'+key).css('display','none');

    });

    __cekTotal(key);
  });

  $('.ajukan').click(function(){

    var key = this.id;
    var total_checked = $('.ajukan_'+key+':checkbox:checked').length;
    var total_ajukan = $('.ajukan_'+key+':checkbox').length;
    if(total_checked == 0){
      $('.ajukan_all_'+key).prop('checked', false);
    }
    else{
      $('.ajukan_all_'+key).prop('checked', true);
    }
  });

  $('#store-btn').click(function()
  {
    $("#approveForm").valid();

    $("#approveForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery("#approveForm").valid()) {
        storePengajuan();
    }
  });

  $('#add-btn').click(function(){

    $("#approveForm").valid();

    $("#approveForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery("#approveForm").valid()) {


        var totalBiaya = $('.ajukan_all').length;
        // var biayaChecked = $('.ajukan_all:checkbox:checked').length;

        for(i=0; i < totalBiaya; i++)
        {
          var biayaChecked = $('.ajukan_all_'+i+':checkbox:checked').length;
          var biayaLength = $('.biaya_stnk_'+i).length;


          if(biayaChecked != ''){

            for(j=0; j<biayaLength; j++)
            {
              var totBiy = $(".biaya_stnk_"+i+":eq(" + j + ")").val();
              var cekError = __customErrorbiaya(totBiy, i, j);

              // alert(kendaraan);

              /*var totBiy = $(".biaya_stnk_"+j).val();
              var cekError = __customError(totBiy, i)*/
            }


          }
        }

        storeBiaya();
    }
  });

  $('#approve-btn').click(function(){
    $("#approveForm").valid();

      $("#approveForm").validate({
          focusInvalid: false,
          invalidHandler: function(form, validator) {

              if (!validator.numberOfInvalids())
                  return;

              $('html, body').animate({
                  scrollTop: $(validator.errorList[0].element).offset().top
              }, 2000);

          }
      });

      if (jQuery("#approveForm").valid()) {

        var totalBiaya = $('.ajukan_all').length;
        // var biayaChecked = $('.ajukan_all:checkbox:checked').length;

        for(i=0; i < totalBiaya; i++)
        {
          var biayaChecked = $('.ajukan_all_'+i+':checkbox:checked').length;

          if(biayaChecked != ''){
            var totBiy = $(".total_biayaapprove_"+i).val();
            var cekError = __customError(totBiy, i)
          }
        }

        approvePengajuan();
      }
  });


  $('#pengajuan-btn').click(function(){

    var total_data = $(".ajukan_all:checkbox:checked").length;
    var defaultBtn = $("#pengajuan-btn").html();

    // alert(ajukanstatusChecked);

    $("#pengajuan-btn").addClass("disabled");
    $("#pengajuan-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();


    if(total_data > 0)
    {
      var url_stnk = http+'/stnk/update_status_array';

      var data_form=__ajukan();

      // console.log(data_form);
      
      $.ajax({
        url:url_stnk,
        type:"POST",
        dataType: "json",
        data:data_form,
        // data:'detail='+JSON.stringify(data_form),
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
                $("#pengajuan-btn").removeClass("disabled");
                $("#pengajuan-btn").html(defaultBtn);
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
          $("#pengajuan-btn").removeClass("disabled");
          $("#pengajuan-btn").html(defaultBtn);
      }, 4000);
    }


  });

  $('#proses-btn').click(function(){
    var data_form=__dataBukti();
    var defaultBtn = $("#proses-btn").html();
    var url_stnk = $(this).data('url');
    // alert($(".jenis_penyerahan:checked").length);

    $("#proses-btn").addClass("disabled");
    $("#proses-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    // var url_stnk = http+'/stnk/store_detailbukti';

    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      data:data_form,
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
            $("#proses-btn").removeClass("disabled");
            $("#proses-btn").html(defaultBtn);
        }, 4000);
        
      }
 
      }
    });

  })


  $('#deny-btn').click(function(){

    var total_data = $(".ajukan_all:checkbox:checked").length;
    var defaultBtn = $("#deny-btn").html();
    var cekUnfill = $('input').hasClass("has-error");

    // alert(ajukanstatusChecked);

    $("#deny-btn").addClass("disabled");
    $("#deny-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();


    if(total_data > 0 && cekUnfill == false)
    {
      var url_stnk = http+'/stnk/delete_biaya';

      var data_form=__deny();

      // console.log(data_form);
      
      $.ajax({
        url:url_stnk,
        type:"POST",
        dataType: "json",
        data:data_form,
        // data:'detail='+JSON.stringify(data_form),
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
              $("#deny-btn").removeClass("disabled");
              $("#deny-btn").html(defaultBtn);
          }, 4000);
          
        }
   
        }
      });

    }
    else{
      $(".alert-message").fadeIn();

      $('.error').animate({ top: "0" }, 500);
      $('.error').html("Maaf, tidak ada data yang yang dipilih dan form biaya yang disetujui harus diisi.");
      
      $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

      
      setTimeout(function () {
          hideAllMessages();
          $("#deny-btn").removeClass("disabled");
          $("#deny-btn").html(defaultBtn);
      }, 4000);
    }
  });


  $('#rejectaprv-btn').click(function(){

    var total_data = $(".ajukan_all:checkbox:checked").length;
    var defaultBtn = $("#rejectaprv-btn").html();
    var cekUnfill = $('input').hasClass("has-error");

    // alert(ajukanstatusChecked);

    $("#rejectaprv-btn").addClass("disabled");
    $("#rejectaprv-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();


    if(total_data > 0 && cekUnfill == false)
    {
      var url_stnk = http+'/stnk/delete_biaya';

      var data_form=__rejectaprv();

      // console.log(data_form);
      
      $.ajax({
        url:url_stnk,
        type:"POST",
        dataType: "json",
        data:data_form,
        // data:'detail='+JSON.stringify(data_form),
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
              $("#rejectaprv-btn").removeClass("disabled");
              $("#rejectaprv-btn").html(defaultBtn);
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
          $("#rejectaprv-btn").removeClass("disabled");
          $("#rejectaprv-btn").html(defaultBtn);
      }, 4000);
    }
  });

  $('#reject-btn').click(function(){
    var total_data = $("#list_data >tbody > tr").length;
    var defaultBtn = $("#reject-btn").html();
    var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;

    var data=[];
    var bariske   = $(".tr-ajukan").length;
    for (i = 0; i < (parseInt(bariske)); i++) {

      var ajukan = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .ajukan:checked").val();

      if(ajukan == 1){
        data.push({
          'id': $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .id").val(),
          'status_stnk': 0

        });
      }
        
    }
    // alert(ajukanstatusChecked);

    $("#reject-btn").addClass("disabled");
    $("#reject-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();


    if(total_data > 0 && ajukanstatusChecked)
    {
      var url_stnk = http+'/stnk/aprove_biaya_detail';

      $.ajax({
        url:url_stnk,
        type:"POST",
        dataType: "json",
        data:'detail='+JSON.stringify(data),
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
                $("#reject-btn").removeClass("disabled");
                $("#reject-btn").html(defaultBtn);
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
          $("#reject-btn").removeClass("disabled");
          $("#reject-btn").html(defaultBtn);
      }, 4000);
    }
  });

  $('.total_biayaapprove').keyup(function(){
    var key = this.id;

    var totBiy = $(".total_biayaapprove_"+key).val();
    var cekError = __customError(totBiy, key)
  });

  $('.biaya_stnk').keyup(function(){
    // var key = this.id;

    var result = this.id.split('-');

    var i = result[0];
    var j = result[1];
    
    var biayaChecked = $('.ajukan_all_'+i+':checkbox:checked').length;

    // alert(result[0]+result[1]);
    // alert(totBiy);
    if(biayaChecked != ''){
      var totBiy = $(".biaya_stnk_"+i+":eq(" + j + ")").val();
      var cekError = __customErrorbiaya(totBiy, i, j);
    }
    /*var totBiy = $(".total_biayaapprove_"+key).val();
    var cekError = __customError(totBiy, key)*/
  });


  // add plat js ===============================================================================

  $('#status_plat').click(function(){

    var stnkdetail_id = $('#stnkdetail_id').val();
    var bpkbdetail_id = $('#bpkbdetail_id').val();

    $('#status_plat:checkbox:checked').each(function(){
        stnkdetail_id != '' || bpkbdetail_id != ''?$('#no_plat').removeAttr('disabled'):$('#no_plat').attr('disabled','disabled');
        stnkdetail_id != '' || bpkbdetail_id != ''?$('#no_hcc').removeAttr('disabled'):$('#no_hcc').attr('disabled','disabled');
        stnkdetail_id != ''? $('#no_stnk').removeAttr('disabled') : $('#no_stnk').attr('disabled','disabled');
        bpkbdetail_id != ''? $('#no_nobpkb').removeAttr('disabled') : $('#no_nobpkb').attr('disabled','disabled');
    });

    $('#status_plat:checkbox:not(":checked")').each(function(){
        __disableForm();
    });
  });

  $('#no_rangka').change(function(){
    var no_rangka = $('#no_rangka').val();

    $('.no_rangka').val(no_rangka);

    var no_mesin = __noMesin(no_rangka);

  });


  $('#plat-btn').click(function()
  {
    $("#headerForm").valid();

    $("#headerForm").validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery("#headerForm").valid()) {
      __detailFormvalidation()
    }


  });

  $('.cek_alamat').click(function(){

    var id = this.id;
    var kd_customer = $('#kd_customer').val();



    var url = http+'/stnk/get_customer/'+kd_customer;
    $(".load_"+id).html("<i class='fa fa-spinner fa-spin'></i>")
    $.getJSON(url, function(data, status){

      $('#'+id+':checkbox:checked').each(function(){

        $('#nama_penerima_'+id).val(data.customer.message['0'].NAMA_CUSTOMER);
        $('#nohp_'+id).val(data.customer.message['0'].NO_HP);
        $('#alamat_'+id).val(data.customer.message['0'].ALAMAT_LENGKAP);

      });

      $('#'+id+':checkbox:not(":checked")').each(function(){

        $('#nama_penerima_'+id).val('');
        $('#nohp_'+id).val('');
        $('#alamat_'+id).val('');

      });

      $(".load_"+id).html("");

    });



    // alert(id);

  });

  /*$('.data_nomor').focusout(function(){
      var id = $(this).attr('head');
      var val = $(this).val();

      var status = $('#status_penerima_'+id).val();
      var select_status = (status == ''?0:status);

      alert(id);

      if(val != ''){
        $('#status_penerima_'+id).val(select_status);
        $('#tgl_penerima_'+id).removeAttr('disabled');
        $('#nama_penerima_'+id).removeAttr('disabled');
        $('#nohp_'+id).removeAttr('disabled');
        $('#alamat_'+id).removeAttr('disabled');
        $('#directory_buktiterima_'+id).removeAttr('disabled');
        $('#btn_'+id).removeAttr('disabled');
      }
      else{
        $('#status_penerima_'+id).val('');
        $('#tgl_penerima_'+id).attr('disabled','disabled');
        $('#nama_penerima_'+id).attr('disabled','disabled');
        $('#nohp_'+id).attr('disabled','disabled');
        $('#alamat_'+id).attr('disabled','disabled');
        $('#directory_buktiterima_'+id).attr('disabled','disabled');
        $('#btn_'+id).attr('disabled','disabled');
      }
  });*/

  $(".btn_data").click(function(){

    var btnId = this.id;
    var formId = '#' + $(this).closest('form').attr('id');
    var btnName = $(this).html();
    // alert(formId);

    $(formId).valid();

    $(formId).validate({
        focusInvalid: false,
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 2000);

        }
    });

    if (jQuery(formId).valid()) {
      storeBukti(btnId, formId, btnName);
    }
  });

  $(".file-btn").click(function(event){

    var formId = "#bukti_" + this.id;

    var options = { 
        
        success:    function(data, status) { 
            var data = JSON.parse(data);
            
            if (data.status == true) {
    //          debugger;
                $('.success').animate({top:"0"}, 500);
                $('.success').html(data.message).fadeIn();

                setTimeout(function(){
                    location.reload()
                }, 2000);

              } else {
                $('.error').animate({top:"0"}, 500);
                $('.error').html(data.message).fadeIn();

                setTimeout(function(){
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html("Simpan Data");
                }, 4000);;
              }
        } 
    }; 
    $(formId).ajaxForm(options).submit(); 

    event.preventDefault();

  });

  $(".nama_penerima").click(function(){

    var id = this.id;
    var formId = id.split('-');

    var kd_customer = $('.kd_customer-'+formId[1]).val();



    var url = http+'/stnk/get_customer/'+kd_customer;

    $.getJSON(url, function(data, status){

      console.log(formId[1]);

      $('#nama_penerima-'+formId[1]).val(data.customer.message['0'].NAMA_CUSTOMER);
      $('#nohp-'+formId[1]).val(data.customer.message['0'].NO_HP);
      $('#alamat-'+formId[1]).val(data.customer.message['0'].ALAMAT_LENGKAP);
    });
  });

  $(".jenis_penyerahan").click(function(){
    var val = $(this).val();

    // alert(val);
    if(val == 'customer'){
      $("#kd_penyerahan_customer").removeAttr("style");
      $("#kd_penyerahan_leasing").css('display','none');

      if ($("#kd_penyerahan_customer").val() != '') $("#filterForm").submit();
    }
    else{
      $("#kd_penyerahan_leasing").removeAttr("style");
      $("#kd_penyerahan_customer").css('display','none');

      if ($("#kd_penyerahan_leasing").val() != '') $("#filterForm").submit();
    }


  });

});

function __customError(totBiy, key)
{
  if(totBiy == ''){
    $(".total_biayaapprove_"+key).addClass('has-error');
    $(".custom_error_"+key).html('<label id="approve_by_error_'+key+'" class="has-error" for="approve_by" style="display: inline-block;">Bagian ini harus diisi.</label>')
    return false;
  }
  else{
    $(".total_biayaapprove_"+key).removeClass('has-error');
    $("#approve_by_error_"+key).remove();
    return true;
  }
}


function __customErrorbiaya(totBiy, i, j)
{
  if(totBiy == ''){
    $(".biaya_stnk_"+i+":eq(" + j + ")").addClass('has-error');
    $(".custom_error_"+i+":eq(" + j + ")").html('<label id="approve_by_error_'+i+'" class="has-error" for="approve_by" style="display: inline-block;">Bagian ini harus diisi.</label>')
    return false;
  }
  else{
    $(".biaya_stnk_"+i+":eq(" + j + ")").removeClass('has-error');
    $("#approve_by_error_"+i+":eq(" + j + ")").remove();
    return true;
  }
}

function __cekTotal(key)
{
  var total_kendaraan=0;
  var total_biayapengajuan=0;
  var total_biayaapprove=0;

  // var bariske   = $(".tr-ajukan").length;
  var bariske = $("#list_data >tbody > .tr_ajukan_"+key).length;

  $('.biaya_stnk').unmask();

  for (i = 0; i < (parseInt(bariske)); i++) {

    var kendaraan = $(".kendaraan_"+key+":eq(" + i + ")").text();
    total_kendaraan = total_kendaraan+Number(kendaraan);

    var biaya = $(".biaya_stnk_diajukan_"+key+":eq(" + i + ")").text();
    total_biayapengajuan = total_biayapengajuan+Number(biaya);

    var approve = $(".biaya_stnk_diapprove_"+key+":eq(" + i + ")").text();
    total_biayaapprove = total_biayaapprove+Number(approve);
  }

  $(".total_kendaraan_"+key).val(total_kendaraan);
  $(".total_biayapengajuan_"+key).val(total_biayapengajuan);
  $(".total_biayaapprove_"+key).val(total_biayaapprove);

  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

}

function __dataBukti()
{
  var data=[];

  var dataNomor = $('.data_nomor').length;

  for(var i=0; i<dataNomor; i++){

    var status_penerima = '';

    if($(".status_penerima:eq(" + i + ")").val() == '')
    {
      status_penerima = 0;
    }
    else if($(".status_penerima:eq(" + i + ")").val() == 0)
    {
      status_penerima = 1;
    }
    else if($(".status_penerima:eq(" + i + ")").val() == 1)
    {
      status_penerima = 2;
    }
    else{
      status_penerima = -1;
    }

    var data_nomor = $(".data_nomor:eq(" + i + ")").val();
    var alamat = $(".alamat:eq(" + i + ")").val();

    if((status_penerima == 0 && data_nomor != '') || (status_penerima == 1 && alamat != '')){
      data.push({
        'no_rangka': $(".no_rangka:eq(" + i + ")").text(),
        'keterangan': $(".keterangan:eq(" + i + ")").val(),
        'nama_penerima': $(".nama_penerima:eq(" + i + ")").val(),
        'tgl_penerima': $(".tgl_penerima:eq(" + i + ")").val(),
        'tgl_penyerahan': $(".tgl_penyerahan:eq(" + i + ")").val(),
        'alamat': $(".alamat:eq(" + i + ")").val(),
        'nohp': $(".nohp:eq(" + i + ")").val(),
        'status_penerima': status_penerima,
        'data_nomor': $(".data_nomor:eq(" + i + ")").val(),
        'jenis_penyerahan': ($(".keterangan:eq(" + i + ")").val() == 'BPKB' && $(".jenis_penyerahan:checked").length > 0? $(".jenis_penyerahan:checked").val():$("#jenis_penyerahan").val())

      });
    }
  }


  var stnk = {
    detail : JSON.stringify(data)
  }

  // console.log(data);

  return stnk;

}

function __data(){
  var headerDeleted = [];
  var data=[];

  var bariske   = $(".tr-ajukan").length;

  var totalHeader = $(".ajukan_all:checkbox").length;
  var headerChecked = $(".ajukan_all:checkbox:checked").length;

  if(headerChecked > 0)
  {
    for(j = 0; j < totalHeader; j++)
    {
      var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
      var totalDetail = $(".ajukan_"+j+":checkbox").length;
      var detailChecked = $(".ajukan_"+j+":checkbox:checked").length;

      if(hedaerIndex > 0 && detailChecked > 0)
      {
        $('.biaya_stnk').unmask();
        for (i = 0; i < totalDetail; i++) {

          var ajukan = $(".ajukan_"+j+":eq(" + i + "):checkbox:checked").val();
          var biaya = $(".biaya_stnk_"+j+":eq(" + i + ")").val();

          // if(biaya == '') return false;
          data.push({
            'id': $(".id_"+j+":eq(" + i + ")").val(),
            // 'biaya_stnk': $(".biaya_stnk_"+j+":eq(" + i + ")").val(),
            // 'biaya_bbn': $(".biaya_bbn_"+j+":eq(" + i + ")").val(),
            'status_stnk': $(".status_stnk_"+j+":eq(" + i + ")").val(),
            'ajukan' : (ajukan == 1? true : false)
          });

        }
        $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});
      }/*
      else{
        headerDeleted.push({
          'stnk_id': $(".stnk_id_"+j).val()
        });
      }*/

    }


  }


  var stnk = {
    // header : JSON.stringify(headerDeleted),
    detail : JSON.stringify(data)
  }

  return stnk;

}

function storePengajuan()
{

  var data_form=__data();
  /*console.log(data_form);
  console.log(JSON.stringify(data_form));*/

  var total_data = $(".ajukan_all:checkbox:checked").length;
  var defaultBtn = $("#store-btn").html();
  var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;

  // alert(ajukanstatusChecked);

  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();


  if(total_data > 0 && data_form != false)
  {
    var url_stnk = http+'/stnk/aprove_pengajuan';
    // var url_stnk = http+'/stnk/aprove_stnk';
    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      data:data_form,
      // data:'header='+JSON.stringify(data_form.header),
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
    $('.error').html("Maaf, tidak ada data yang yang dipilih.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
    }, 4000);
  }
}


function __dataBiaya()
{
  var data=[];
  
  var bariske   = $(".tr-ajukan").length;

  var totalHeader = $(".ajukan_all:checkbox").length;
  var headerChecked = $(".ajukan_all:checkbox:checked").length;

  if(headerChecked > 0)
  {
    for(j = 0; j < totalHeader; j++)
    {
      var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
      var totalDetail = $("#list_data >tbody > .tr_ajukan_"+j).length;
      // var totalDetail = $("#list_data >tbody > .tr_ajukan_"+j+":eq(" + i + ") .ajukan:checked").val();
      var detailChecked = $(".ajukan_"+j+":checkbox:checked").length;

      // alert($("#reff_source").val());
      $('.biaya_stnk').unmask();
      for (i = 0; i < totalDetail; i++) {

        if (hedaerIndex != '') {
          var biaya = $(".biaya_stnk_"+j+":eq(" + i + ")").val();

          if(biaya == '') return false;
          data.push({
            'id': $(".id_"+j+":eq(" + i + ")").val(),
            'biaya_stnk': $(".biaya_stnk_"+j+":eq(" + i + ")").val(),
            'biaya_bbn': $(".biaya_bbn_"+j+":eq(" + i + ")").val(),
            'reff_source': $("#reff_source").val(),
            'status_stnk': $(".status_stnk_"+j+":eq(" + i + ")").val()
          });

        }

      }
      $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    }

  }
  return data;

}

function storeBiaya()
{

  var data_form=__dataBiaya();

  var total_data = $(".ajukan_all:checkbox:checked").length;
  var defaultBtn = $("#add-btn").html();
  var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;

  $("#add-btn").addClass("disabled");
  $("#add-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();

  if(total_data > 0 && data_form != false)
  {
    var url_stnk = http+'/stnk/store_biaya';
    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      // data:data_form,
      data:'detail='+JSON.stringify(data_form),
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
            $("#add-btn").removeClass("disabled");
            $("#add-btn").html(defaultBtn);
        }, 4000);
        
      }
 
      }
    });

  }
  else{
    $(".alert-message").fadeIn();

    $('.error').animate({ top: "0" }, 500);
    $('.error').html("Maaf, tidak ada data yang dipilih dan biaya STNK harus diisi.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#add-btn").removeClass("disabled");
        $("#add-btn").html(defaultBtn);
    }, 4000);
  }
}

function __approve(){
  var data=[];
  var dataDetail=[];
  var totalHeader = $(".ajukan_all:checkbox").length;
  var headerChecked = $(".ajukan_all:checkbox:checked").length;

  if(headerChecked > 0)
  {
    for(j = 0; j < totalHeader; j++)
    {
      var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
      var totalDetail = $("#list_data >tbody > .tr_ajukan_"+j).length;

      $('.biaya_stnk').unmask();
      if (hedaerIndex != '') {
        data.push({
          'no_pengajuan': $(".no_pengajuan_normal_"+j).val(),
          'total_biayapengajuan': $(".total_biayapengajuan_"+j).val(),
          'total_biayaapprove': $(".total_biayaapprove_"+j).val(),
          'tgl_approve' : $('#tgl_approve').val(),
          'approve_by' : $('#approve_by').val()
        });

      }

      for (i = 0; i < totalDetail; i++) {

        if (hedaerIndex != '') {
          dataDetail.push({
            'id': $(".id_"+j+":eq(" + i + ")").val(),
            'biaya_stnk': $(".biaya_stnk_diajukan_"+j+":eq(" + i + ")").text(),
            'biaya_bbn': $(".biaya_bbn_"+j+":eq(" + i + ")").val(),
            'status_stnk': $(".status_stnk_"+j+":eq(" + i + ")").val()
          });

        }

      }
      $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    }

  }



  var stnk = {
    header : JSON.stringify(data),
    detail : JSON.stringify(dataDetail)
  }

  return stnk;
}

function __ajukan(){

    var data=[];

    var totalHeader = $(".ajukan_all:checkbox").length;
    var headerChecked = $(".ajukan_all:checkbox:checked").length;

    if(headerChecked > 0)
    {
      for(j = 0; j < totalHeader; j++)
      {
        var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
        var totalDetail = $(".plat_"+j).length;
        var detailChecked = $(".ajukan_"+j+":checkbox:checked").length;
        

        if(hedaerIndex > 0 && detailChecked > 0)
        {
          for (i = 0; i < totalDetail; i++) {

            var ajukan = $(".plat_"+j+":eq(" + i + ") .ajukan:checkbox:checked").val();

        // console.log(ajukan);
            // if(biaya == '') return false;
            if(ajukan == 1)
            {
              data.push({
                'id': $(".id_"+j+":eq(" + i + ")").val(),
                'status_stnk': $(".status_stnk_"+j+":eq(" + i + ")").val()
              });
            }

          }
        }

      }


    }


    var stnk = {
      detail : JSON.stringify(data)
    }

    // console.log(data);
    return stnk;
}


function __deny(){
  var deleteDetail=[];
  var totalHeader = $(".ajukan_all:checkbox").length;
  var headerChecked = $(".ajukan_all:checkbox:checked").length;

  if(headerChecked > 0)
  {
    for(j = 0; j < totalHeader; j++)
    {
      var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
      var totalDetail = $("#list_data >tbody > .tr_ajukan_"+j).length;

      if (hedaerIndex != '') {
        for (i = 0; i < totalDetail; i++) {

          deleteDetail.push({
            'id': $(".id_"+j+":eq(" + i + ")").val()
          });

        }

      }

    }

  }

  var stnk = {
    detail : JSON.stringify(deleteDetail)
  }

  return stnk;
}


function __rejectaprv(){
  var deleteDetail=[];
  var totalHeader = $(".ajukan_all:checkbox").length;
  var headerChecked = $(".ajukan_all:checkbox:checked").length;

  if(headerChecked > 0)
  {
    for(j = 0; j < totalHeader; j++)
    {
      var hedaerIndex = $(".ajukan_all_"+j+":checkbox:checked").length;
      var totalDetail = $("#list_data >tbody > .tr_ajukan_"+j).length;
      
      if (hedaerIndex != '') {
        for (i = 0; i < totalDetail; i++) {
  
          var totalDetailchecked = $(".ajukan_"+j+":eq(" + i + "):checkbox:checked").length;

          // alert(totalDetailchecked);

          if(totalDetailchecked == 1){
            deleteDetail.push({
              'id': $(".id_"+j+":eq(" + i + ")").val()
            });
          }
        }

      }

    }

  }

  var stnk = {
    detail : JSON.stringify(deleteDetail)
  }

  return stnk;
}

function approvePengajuan()
{
  var total_data = $(".ajukan_all:checkbox:checked").length;
  var defaultBtn = $("#approve-btn").html();
  var cekUnfill = $('input').hasClass("has-error");

  // alert(ajukanstatusChecked);

  $("#approve-btn").addClass("disabled");
  $("#approve-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();


  if(total_data > 0 && cekUnfill == false)
  {
    var url_stnk = http+'/stnk/aprove_biaya';

    var data_form=__approve();

    // console.log(data_form);
    
    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      data:data_form,
      // data:'detail='+JSON.stringify(data_form),
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
            $("#approve-btn").removeClass("disabled");
            $("#approve-btn").html(defaultBtn);
        }, 4000);
        
      }
 
      }
    });

  }
  else{
    $(".alert-message").fadeIn();

    $('.error').animate({ top: "0" }, 500);
    $('.error').html("Maaf, tidak ada data yang yang dipilih dan form biaya yang disetujui harus diisi.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#approve-btn").removeClass("disabled");
        $("#approve-btn").html(defaultBtn);
    }, 4000);
  }

}

  // add plat js ===============================================================================

function __detailData()
{
  var data=[];

  for (i = 0; i < 4; i++) {

      if($(".status_penerima:eq(" + i + ")").val() != ''){
        var file = $(".directory_buktiterima:eq(" + i + ")")[0].files[0];

        data.push({
          'no_rangka'            : $("#no_rangka").val(),
          'keterangan'           : $(".keterangan:eq(" + i + ")").val(),
          'data_nomor'           : $(".data_nomor:eq(" + i + ")").val(),
          'tgl_penerima'         : $(".tgl_penerima:eq(" + i + ")").val(),
          'nama_penerima'        : $(".nama_penerima:eq(" + i + ")").val(),
          'nohp'                 : $(".nohp:eq(" + i + ")").val(),
          'alamat'               : $(".alamat:eq(" + i + ")").val(),
          'status_penerima'      : $(".status_penerima:eq(" + i + ")").val(),
          'lastModified'         : file.lastModified,
          'lastModifiedDate'     : file.lastModifiedDate,
          'name'                 : file.name,
          'size'                 : file.size,
          'type'                 : file.type,
          'directory_buktiterima': $(".directory_buktiterima:eq(" + i + ")")[0].files[0]
        });
      }

  }

  var bukti = JSON.stringify(data);

  return data;
  // console.log(bukti);
}

function __detailFormvalidation()
{

  $("#detailForm").valid();

  $("#detailForm").validate({
      focusInvalid: false,
      invalidHandler: function(form, validator) {

          if (!validator.numberOfInvalids())
              return;

          $('html, body').animate({
              scrollTop: $(validator.errorList[0].element).offset().top
          }, 2000);

      }
  });

  if (jQuery("#detailForm").valid()) {
      storePlat();
  }
}

function __noMesin(no_rangka)
{
  var url = http+'/stnk/get_mesin/'+no_rangka;
  $(".load-form-mesin").html("<i class='fa fa-spinner fa-spin'></i>")
  $.getJSON(url, function(data, status){

    if(status == 'success'){

      // return data.no_mesin;
      // alert(data.no_mesin);
      $('#status_plat').prop('checked', false);

      __disableForm();

      $('.stck_abble').val('');

      $('.cek_alamat').removeAttr('disabled');

      $('#stnkdetail_id').val(data.stnk_header.message['0'].STNKDETAIL_ID);
      $('#bpkbdetail_id').val(data.stnk_header.message['0'].BPKBDETAIL_ID);
      $('#kd_customer').val(data.stnk_header.message['0'].KD_CUSTOMER);
      $('#no_mesin').val(data.no_mesin);

      $(".load-form-mesin").html('');

      if(data.no_mesin == ''){
        $('.cek_alamat').attr('disabled','disabled');
      }
    }

  });

}

function __disableForm()
{
  $('.cek_alamat').prop('checked', false);
  $('.data_nomor').attr('disabled','disabled').val('');
  $('.tgl_penerima').attr('disabled','disabled');
  $('.nama_penerima').attr('disabled','disabled').val('');
  $('.nohp').attr('disabled','disabled').val('');
  $('.alamat').attr('disabled','disabled').val('');
  $('.directory_buktiterima').attr('disabled','disabled').val('');
  $('.btn_data').attr('disabled','disabled');
}

function storePlat()
{
  var defaultBtn = $("#store-btn").html();
  var statusPlat = $('#status_plat:checkbox:checked').length;

  // var bukti = __detailData();

  // console.log(bukti);

  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();

  var url_plat = http+'/stnk/store_plat';

  $('.form_biaya').unmask();    
  var data = {
    id : $("#id").val(),
    stnkdetail_id : $("#stnkdetail_id").val(),
    bpkbdetail_id : $("#bpkbdetail_id").val(),
    bbnkb : $("#bbnkb").val(),
    pkb : $("#pkb").val(),
    swdkllj : $("#swdkllj").val(),
    stck : $("#stck").val(),
    plat_asli : $("#plat_asli").val(),
    admin_samsat : $("#admin_samsat").val(),
    bpkb : $("#bpkb").val(),
    ss : $("#ss").val(),
    no_rangka : $("#no_rangka").val(),
    no_mesin : $("#no_mesin").val(),
    no_stnk : (statusPlat == 1? $("#no_stnk").val() : null),
    no_plat : (statusPlat == 1? $("#no_plat").val() : null),
    no_bpkb : (statusPlat == 1? $("#no_nobpkb").val() : null),
    status_plat : $("#status_plat:checkbox:checked").val()/*,
    directory_buktiterima : bukti*/
  }
  $('.form_biaya').mask('000.000.000.000.000', {reverse: true});

  $.ajax({
    url:url_plat,
    type:"POST",
    dataType: "json",
    data:data,
    success:function(result){

      if (result.status == true) 
      {
       
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


function storeBukti(btnId, formId, btnName){

  $("#"+btnId).addClass("disabled");
  $("#"+btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();

  var options = { 
      
    success:    function(data, status) { 
      var data = JSON.parse(data);
      
      if (data.status == true) {
        $('.success').animate({top:"0"}, 500);
        $('.success').html(data.message);

        setTimeout(function(){
            hideAllMessages();
            $("#"+btnId).removeClass("disabled");
            $("#"+btnId).html(btnName);

            if(data.status_penerima == 1){
              $('#tgl_penerima_'+data.ket_id).attr('disabled','disabled');
              $('#directory_buktiterima_'+data.ket_id).attr('disabled','disabled');
              $('#btn_'+data.ket_id).attr('disabled','disabled');
            }
            else if(data.status_penerima == 0)
            {
              $('#'+data.ket_id+'_form').attr('action',http+'/stnk/update_bukti');
              $("#"+btnId).html('Penyerahan');
            }

            $('#no_'+data.ket_id).attr('disabled','disabled');
            $('#nama_penerima_'+data.ket_id).attr('disabled','disabled');
            $('#nohp_'+data.ket_id).attr('disabled','disabled');
            $('#alamat_'+data.ket_id).attr('disabled','disabled');


        }, 4000);;

      } else {
        $('.error').animate({top:"0"}, 500);
        $('.error').html(data.message);

        setTimeout(function(){
            hideAllMessages();
            $("#"+btnId).removeClass("disabled");
            $("#"+btnId).html(btnName);
        }, 4000);;
      }
    } 
  }; 
  $(formId).ajaxForm(options).submit(); 

  event.preventDefault();
  
}