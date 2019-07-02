var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function () {

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

    $('#baru').click(function () {
        document.location.reload();
    })


    $("#kd_sa").change(function(){

      // var kdDealer = $('#kd_dealer').val();
      var kd_sa = $(this).val();

      var url = http+'/pkb/get_sa';

      $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

      $.getJSON(url,{
        kd_sa:kd_sa
      }, function(data, status){

        if(status == 'success'){


          $('#no_polisi').val(data.sa_header.message['0'].NO_POLISI);
          $('#no_rangka').val(data.sa_header.message['0'].NO_RANGKA);
          $('#no_mesin').val(data.sa_header.message['0'].NO_MESIN);
          $('#km_motor').val(data.sa_header.message['0'].KM_SAATINI);
          $('#nama_typemotor').val(data.sa_header.message['0'].NAMA_PASAR);
          $('#saran_mekanik').val(data.sa_header.message['0'].SARAN_MEKANIK);
          $('#saran_mekanik_sa').val(data.sa_header.message['0'].SARAN_MEKANIK);
          // $('#tgl_trans').val(data.stnk_header.message['0'].TGL_STNK);

          // $('tbody').html(data.list_approval);
        }

        $(".load-form").html('');

      });


    });


    __getBarangSP();

    $('#kategori').on('change', function () {

      __getBarangSP();
    });

    $("#part_number").typeahead({
        source: function (query, process) {
            $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
            return $.get('<?php echo base_url("part/picking_part_detail_typeahead"); ?>', {keyword: query}, function (data) {
                console.log(data);
                data = $.parseJSON(data);
                $('#fd').html('');
                return process(data.keyword);
            })
        },
        minLength: 3,
        limit: 20
    });


    $('.qurency').mask('000.000.000.000.000', {reverse: true});
    $('.tahun').mask('0000', {reverse: true});
    $('.meter').mask('000.000.000.000.000', {reverse: true});

    $("#submit-main-button").on('click', function (event) {
        var formId = '#' + $(this).closest('form').attr('id');
        var btnId = '#' + this.id;
        $('#loadpage').removeClass("hidden");

        $('.qurency').unmask();
        $('.tahun').unmask();

        $(formId).validate({
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
        if (jQuery(formId).valid()) {
            // Do something
            event.preventDefault();

            addValid(formId, btnId);

        } else {
            $('#loadpage').addClass("hidden");
            $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
        }
    });
    $("#submit-btn").on('click', function (event) {
        var formId = '#' + $(this).closest('form').attr('id');
        var btnId = '#' + this.id;
        $('#loadpage').removeClass("hidden");

        $('.qurency').unmask();
        $('.tahun').unmask();

        $(formId).valid();

        if (jQuery(formId).valid()) {
            // Do something
            event.preventDefault();

            storeData(formId, btnId);


        } else {

            $('#loadpage').addClass("hidden");

        }
    });
})



function __getBarangSP(){

  var kd_kategori = $('#kategori').val();

  $.getJSON(http+"/inventori/list_sp_w_stock/true",{'jt':kd_kategori},function(result){
    var datax=[];
    $.each(result,function(e,d){
      datax.push({
        'value' :d.PART_NUMBER,
        'ID' : d.PART_NUMBER,
        'DESKRIPSI' : d.PART_DESKRIPSI ,
        'STOCK': d.JUMLAH_SAK,
        'text': d.PART_DESKRIPSI,
        'description':d.JUMLAH_SAK
      });
      
    })
    // console.log(datax);
    $('#kd_part').inputpicker({
      data:datax,
      fields:['ID','DESKRIPSI','STOCK'],
        fieldText:'text',
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
    .on("change",function(e){
      e.preventDefault();

      var part_number=$(this).val();

      var url = (kd_kategori == 'Part' ? http+"/sparepart/hargapart/true":http+"/pkb/hargajasa");

      
      // console.log(part_number+"|"+url);
      
      $.getJSON(url,{"part_number":part_number},function(result){
        $.each(result,function(e,d){
          $('#kd_part').val(d.PART_DESKRIPSI);
          $('#qty').val("1");
          var harga_jual=0;
          // harga_jual =d.HET;
          // harga_jual =d.HARGA_JUAL;

          // alert(harga_jual);
          // console.log(d.HARGA_JUAL);

          harga_jual =(typeof(d.HARGA_JUAL)!= "undefined" && d.HARGA_JUAL !== null)?d.HARGA_JUAL:d.HET

          $('#harga_sp').val(parseFloat(harga_jual));

          $('#jumlah_sp').focus().select();
          $('#total_harga_sp').val(parseFloat(harga_jual));
          $('#total_harga_sp').mask("#,##0",{reverse: true});       
        })
      })
    })
  })
}

function __addItem()
{
  var bariskes=0;
  var total_bayar=0;var total_beli=0;
  bariskes = $('#lst_sp > tbody > tr').length;
  var html="";
    html +="<tr><td class='text-center'>"+(bariskes+1)+"</td>";
    html +="<td class='text-center'><a onclick=\"__hapus_item('"+bariskes+"')\" role='button'><i class='fa fa-trash'></i></a></td>"; 
    html +="<td>"+$('#kd_part').val()+"</td><td class='text-right'>"+$('#qty').val();
    html +="</td><td class='text-right'>"+$('#harga_sp').val()+"</td><td class='text-right'>"+$('#qty').val() * $('#harga_sp').val();
    html +="</td><td class='hidden'>"+$('#nama_sp').val()+"</td><td class='hidden'>"+$('#kd_akun').val()+"</td>";
    html +="<td class='hidden'>"+$('#nama_akun').val()+"</td></tr>";
  $('#pkb_list > tbody').append(html);
  console.log('jmlbaris:'+bariskes);
  /*total_bayar =(isNaN(total_bayar)||total_bayar=='')?0:total_bayar;
  total_bayar = parseFloat(total_beli) +parseFloat(total_bayar);
  console.log(total_bayar);
  console.log(total_beli);
  $("#jml_bayar")
    .val(total_bayar.toLocaleString())
    .mask('#,##0',{reverse:true})
  $('#sparepart input:not(#jml_bayar)').val('');
  $('#kd_akun').focus();*/

}

function storeData(formId, btnId)
{
    // alert(formId);
    var defaultBtn = $(btnId).html();

    $(btnId).addClass("disabled");
    $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();

    $(formId + " select").removeAttr("disabled");
    $(formId + " select").removeClass("disabled-action");
    var formData = $(formId).serialize();
    var act = $(formId).attr('action');

    $.ajax({
        url: act,
        type: 'POST',
        data: formData,
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


                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message);
                setTimeout(function () {
                    hideAllMessages();
                    $(btnId).removeClass("disabled");
                    $(btnId).html(defaultBtn);
                    $('#loadpage').addClass("hidden");
                }, 2000);


            }
        }

    });

    return false;

}