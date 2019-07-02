var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function () {


  $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
    A: {pattern: /[A-Za-z]/},
    Z: {pattern: /[A-Za-z]/,optional:true},
    0: {pattern: /[0-9]/},
    1: {pattern: /[0-9]/,optional:true}
  }})

  var date = new Date();
  date.setDate(date.getDate());

  $('.datetime-mulai').datetimepicker({
      format: 'hh:mm',
      pickDate: false,
      autoclose: true
  });

  $('.datetime-selesai').datetimepicker({
      format: 'hh:mm',
      pickDate: false,
      autoclose: true
  });



  $("#pull-btn").click(function(){

    var status_cabang = $("#status_cabang").val();
    var tgl_awal = $("#tgl_awal").val();
    var tgl_akhir = $("#tgl_akhir").val();
    var month_interval = $("#month_interval").val();
    var month_end = $("#month_end").val();
    var month_start = $("#month_start").val();

    if(status_cabang == 'Y' && month_interval == 1)
    {
      // alert('cabang');
      storeKpb(tgl_awal, month_end);
      storeKpb(month_start, tgl_akhir, true);
    }
    else{
      // alert('bukan cabang');
      storeKpb(tgl_awal, tgl_akhir, true);
    }



  });


  $('.kpb_all').click(function(){

    $('.kpb_all:checkbox:checked').each(function(){
        $('.kpb_checked').prop('checked', true);
    });

    $('.kpb_all:checkbox:not(":checked")').each(function(){
        $('.kpb_checked').prop('checked', false);
    });
  });


  $('.kpb_checked').click(function(){

    if($('.kpb_checked:checkbox:checked').length == $('.kpb_checked:checkbox').length){
        $('.kpb_all').prop('checked', true);
    }
    else{
        $('.kpb_all').prop('checked', false);
    }

  });

  $("#validasi-btn").click(function(){
    var data_form=__data();

    // console.log(data_form);
    var defaultBtn = $("#validasi-btn").html();
    var total_data = $(".kpb_checked:checkbox:checked").length;

    $("#validasi-btn").addClass("disabled");
    $("#validasi-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var url = http+'/kpb/validasi_data';

    if(total_data > 0){
      $.ajax({
          url: url,
          type: 'POST',
          data: "detail="+JSON.stringify(data_form),
          dataType: "json",
          success: function (result) {

              if (result.status == true) {

                  $('.success').animate({
                      top: "0"
                  }, 500);
                  $('.success').html(result.message);


                  if (result.location != null) {
                      setTimeout(function () {
                          location.replace(result.location)
                      }, 1000);
                  } else {
                      setTimeout(function () {
                          location.reload();
                      }, 1000);
                  }
              } else {
                  $('.error').animate({
                      top: "0"
                  }, 500);
                  $('.error').html(result.message);
                  setTimeout(function () {
                      hideAllMessages();
                      $("#validasi-btn").removeClass("disabled");
                      $("#validasi-btn").html(defaultBtn);
                      $('#loadpage').addClass("hidden");
                  }, 2000);


              }
          }

      });

      return false;
    }
    else{
      $('.error').animate({
          top: "0"
      }, 500);
      $('.error').html('Tidak ada data yang dipilih');
      setTimeout(function () {
          hideAllMessages();
          $("#validasi-btn").removeClass("disabled");
          $("#validasi-btn").html(defaultBtn);
          $('#loadpage').addClass("hidden");
      }, 2000);
    }
  });



  $("#kpb-btn").click(function(){
    var data_form=__claim();

    // console.log(data_form);
    var defaultBtn = $("#kpb-btn").html();
    var total_data = $(".kpb_checked:checkbox:checked").length;

    $("#kpb-btn").addClass("disabled");
    $("#kpb-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var url = http+'/kpb/claim_data';

    if(total_data > 0){
      $.ajax({
          url: url,
          type: 'POST',
          data: "detail="+JSON.stringify(data_form),
          dataType: "json",
          success: function (result) {

              if (result.status == true) {

                  $('.success').animate({
                      top: "0"
                  }, 500);
                  $('.success').html(result.message);

                  // alert('test');
                  location.replace(result.file);

                  setTimeout(function(){
                      location.reload();
                  }, 2000);
/*
                  if (result.location != null) {
                      setTimeout(function () {
                          location.replace(result.location)
                      }, 1000);
                  } else {
                      setTimeout(function () {
                          location.reload();
                      }, 1000);
                  }*/
              } else {
                  $('.error').animate({
                      top: "0"
                  }, 500);
                  $('.error').html(result.message);
                  setTimeout(function () {
                      hideAllMessages();
                      $("#kpb-btn").removeClass("disabled");
                      $("#kpb-btn").html(defaultBtn);
                      $('#loadpage').addClass("hidden");
                  }, 2000);


              }
          }

      });

      return false;
    }
    else{
      $('.error').animate({
          top: "0"
      }, 500);
      $('.error').html('Tidak ada data yang dipilih');
      setTimeout(function () {
          hideAllMessages();
          $("#kpb-btn").removeClass("disabled");
          $("#kpb-btn").html(defaultBtn);
          $('#loadpage').addClass("hidden");
      }, 2000);
    }

  });

})


function storeKpb(tglawal, tglakhir, reload = false)
{

  var defaultBtn = $("#pull-btn").html();

  $("#pull-btn").addClass("disabled");
  $("#pull-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
  $(".alert-message").fadeIn();

  var url = http+'/kpb/tarik_data';

  var data = {
    tgl_awal : tglawal,
    tgl_akhir : tglakhir,
    tahun_docno : $("#tahun_docno").val()
  }

  $.ajax({
      url: url,
      type: 'POST',
      data: data,
      dataType: "json",
      success: function (result) {

          if (result.status == true) {

              $('.success').animate({
                  top: "0"
              }, 500);
              $('.success').html(result.message);

              // if(reload == true){
                if (result.location != null) {
                    setTimeout(function () {
                        location.replace(result.location)
                    }, 1000);
                } else {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
              // }
              
          } else {
              $('.error').animate({
                  top: "0"
              }, 500);
              $('.error').html(result.message);
              setTimeout(function () {
                  hideAllMessages();
                  $("#pull-btn").removeClass("disabled");
                  $("#pull-btn").html('<i class="fa fa-download fa-fw"></i> Tarik Data');
                  $('#loadpage').addClass("hidden");
              }, 2000);


          }
      }

  });

  return false;
}

function __claim()
{
  var bariskex=0;
  bariskex = $(".kpb_checked:checkbox").length;

  // alert(bariskex);

  var dataxx=[];
  for(iz=0;iz< bariskex;iz++){

    var kpb_checked = $(".kpb_checked:eq(" + iz + "):checkbox:checked").length;
    // var kpb_value = $(".kpb_checked:eq(" + iz + ")").val();

    // alert(kpb_checked);

    if(kpb_checked == 1){
      dataxx.push({
        'no_kpb' :$(".kpb_checked:eq(" + iz + ")").val()
      })
    }

  }
  // console.log('jmlbaris: '+bariskex)
  // console.log(dataxx)
  return dataxx;

}

function __data()
{

  var bariskex=0;
  bariskex = $('#pkb_list > tbody > tr').length;
  var dataxx=[];
  for(iz=0;iz< bariskex;iz++){

    var kpb_checked = $(".kpb_checked:eq(" + iz + "):checkbox:checked").length;
    // var kpb_value = $(".kpb_checked:eq(" + iz + ")").val();

    // alert(kpb_checked);

    if(kpb_checked == 1){
      dataxx.push({
        'id' :$(".kpb_checked:eq(" + iz + ")").val(),
        'status_kpb'  : 1
      })
    }

  }
  // console.log('jmlbaris: '+bariskex)
  // console.log(dataxx)
  return dataxx;
}

function storeData()
{
    var data_form=__data();

    var defaultBtn = $("#submit-btn").html();

    $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    var formData = $("#pkbForm").serialize();
    var act = $("#pkbForm").attr('action');

    $.ajax({
        url: act,
        type: 'POST',
        data: formData+"&detail="+JSON.stringify(data_form),
        dataType: "json",
        success: function (result) {

            if (result.status == true) {

                $('.success').animate({
                    top: "0"
                }, 500);
                $('.success').html(result.message);


                if (result.location != null) {
                    setTimeout(function () {
                        location.replace(result.location)
                    }, 1000);
                } else {
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            } else {
                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message);
                setTimeout(function () {
                    hideAllMessages();
                    $("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);


            }
        }

    });

    return false;

}