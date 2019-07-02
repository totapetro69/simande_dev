

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function(){

  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});
  // $('#total_biayapengajuan').mask('000.000.000.000.000', {reverse: true});
  // $('#total_biayaapprove').mask('000.000.000.000.000', {reverse: true});


  $('#no_trans').change(function(){

    var transNo = $('#no_trans').val();
    var status_stnk = $('#status_stnk').val();

    var url = http+'/stnk/get_approval?no_trans='+transNo+'&status_stnk='+status_stnk;

    $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

    $.getJSON(url, function(data, status){

      if(status == 'success'){


        $('#nama_pengurus').val(data.stnk_header.message['0'].NAMA_PENGURUS);
        // $('#tgl_trans').val(data.stnk_header.message['0'].TGL_STNK);

        $('tbody').html(data.list_approval);
      }

      $(".load-form").html('');

    });


  });

  $('.ajukan_all').click(function(){

    var key = $(this).val();

    $('.ajukan_all_'+key+':checkbox:checked').each(function(){
      // alert('test');

        $('.ajukan_'+key).prop('checked', true);
    });

    $('.ajukan_all_'+key+':checkbox:not(":checked")').each(function(){
      // alert('test');

        $('.ajukan_'+key).prop('checked', false);
    });

    __cekTotal();

  });

  $('.ajukan').click(function(){
    __cekTotal();
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
        
        var totBiy = $("#total_biayaapprove").val();

        var cekError = __customError(totBiy)


        if(cekError == true)
        {
          // alert('yes');
          approvePengajuan();
        }
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

  $('#total_biayaapprove').focusout(function(){
    var totBiy = $("#total_biayaapprove").val();
    var cekError = __customError(totBiy)
/*
    if(cekError == true)
    {
      // alert('yes');
    }*/
  });

});

function __customError(totBiy)
{
  if(totBiy == ''){
    $("#total_biayaapprove").addClass('has-error');
    $("#custom-error").html('<label id="approve_by-error" class="has-error" for="approve_by" style="display: inline-block;">Bagian ini harus diisi.</label>')
    return false;
  }
  else{
    $("#total_biayaapprove").removeClass('has-error');
    $("#approve_by-error").remove();
    return true;
  }

}

function __cekTotal()
{
  var total_biayapengajuan=0;

  var bariske   = $(".tr-ajukan").length;

  $('.biaya_stnk').unmask();

  for (i = 0; i < (parseInt(bariske)); i++) {

    var ajukan = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .ajukan:checked").val();
    // var biaya = 3;
    var biaya = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .biaya_stnk_diajukan").text();

    if(ajukan == 1){
      total_biayapengajuan = total_biayapengajuan+Number(biaya);

    }
  }

  $("#total_biayapengajuan").val(total_biayapengajuan);
  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

}

function __data(){
  var data=[];
  var bariske   = $(".tr-ajukan").length;
  $('.biaya_stnk').unmask();
  for (i = 0; i < (parseInt(bariske)); i++) {

    var ajukan = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .ajukan:checked").val();
    var biaya = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .biaya_stnk").val();

    if(ajukan == 1){
      if(biaya == '') return false;
      data.push({
        'id': $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .id").val(),
        'biaya_stnk': $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .biaya_stnk").val(),
        'status_stnk': $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .status_stnk").val()

      });
    }
  }

  $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

  return data;

}

function storePengajuan()
{


  var data_form=__data();
  // console.log(data_form);

  var total_data = $("#list_data >tbody > tr").length;
  var defaultBtn = $("#store-btn").html();
  var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;

  // alert(ajukanstatusChecked);

  $("#store-btn").addClass("disabled");
  $("#store-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();


  if(total_data > 0 && ajukanstatusChecked > 0 && data_form != false)
  {
    var url_stnk = http+'/stnk/aprove_stnk';

    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
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
    $('.error').html("Maaf, tidak ada data yang yang dipilih dan Biaya STNK yg dipilih tidak boleh kosong.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#store-btn").removeClass("disabled");
        $("#store-btn").html(defaultBtn);
    }, 4000);
  }
}



function __approve(){
  var data=[];
  var bariske   = $(".tr-ajukan").length;
  for (i = 0; i < (parseInt(bariske)); i++) {

    var ajukan = $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .ajukan:checked").val();

    data.push({
      'id': $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .id").val(),
      'status_stnk': (ajukan == 1 ? $("#list_data >tbody > .tr-ajukan:eq(" + i + ") .status_stnk").val() : 0)

    });
  }
  return data;

}

function approvePengajuan()
{
  var total_data = $("#list_data >tbody > tr").length;
  var defaultBtn = $("#approve-btn").html();
  var ajukanstatusChecked = $('.ajukan:checkbox:checked').length;

  // alert(ajukanstatusChecked);

  $("#approve-btn").addClass("disabled");
  $("#approve-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();


  if(total_data > 0 && ajukanstatusChecked > 0)
  {
    var url_stnk = http+'/stnk/aprove_biaya';

    var data_form=__approve();
    
    $('.biaya_stnk').unmask();

    var data_biaya = {

      kd_dealer             : $("#kd_dealer").val(),
      tahun_docno           : $("#tgl_approve").val(),

      total_biayapengajuan  : $('#total_biayapengajuan').val(),
      total_biayaapprove    : $('#total_biayaapprove').val(),
      tgl_approve           : $('#tgl_approve').val(),
      approve_by            : $('#approve_by').val(),
      detail                : JSON.stringify(data_form)
    }
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    $.ajax({
      url:url_stnk,
      type:"POST",
      dataType: "json",
      data:data_biaya,
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
    $('.error').html("Maaf, tidak ada data yang yang dipilih.");
    
    $('.biaya_stnk').mask('000.000.000.000.000', {reverse: true});

    
    setTimeout(function () {
        hideAllMessages();
        $("#approve-btn").removeClass("disabled");
        $("#approve-btn").html(defaultBtn);
    }, 4000);
  }

}

