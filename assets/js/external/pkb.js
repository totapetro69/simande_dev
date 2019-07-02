var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function () {
    __getMotor();
    __getBarangSP(); 

    $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
      A: {pattern: /[A-Za-z]/},
      Z: {pattern: /[A-Za-z]/,optional:true},
      0: {pattern: /[0-9]/},
      1: {pattern: /[0-9]/,optional:true}
    }})
    var dataMekanik=[];
    // var edit
    // console.log(date);
    $.getJSON(http+"/pkb/mekanik_ready",function(result){
      // console.log(result.length);
      if(result.length>0){
        $.each(result,function(e,d){
          var pengerjaan = d.SELESAI_PENGERJAAN;
          var pengerjaanTime = '';
          if(pengerjaan != null)
          {
            pengerjaanTime = pengerjaan.slice(0, -3);
          }
          dataMekanik.push({
            'value' :d.NIK,
            'NAMA' : d.NAMA,
            'PENGERJAAN' : d.JUMLAH_PEKERJAAN,
            'SELESAI': pengerjaanTime,
            'text': d.NAMA,
            'description':d.JUMLAH_PEKERJAAN
          });
        })
      }
      // console.log(dataMekanik);
      $('#nama_mekanik').inputpicker({
        data:dataMekanik,
        fields:['NAMA','PENGERJAAN','SELESAI'],
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
        var nik=$(this).val();
        var url = http+"/pkb/mekanik_ready";
        $.getJSON(url,{"nik":nik},function(result){
          $.each(result,function(e,d){
            var time = d.SELESAI_PENGERJAAN;
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            // add a zero in front of numbers<10
            h = (h < 10? '0'+h:h);
            m = (m < 10? '0'+m:m);
            var time_start = h + ":" + m;
        // console.log(time+' | ')        
            if(time != null && time >= time_start){ 
              time_start = time.slice(0, -3);
            }
            $('#estimasi_mulai').val(time_start);     
          })
        })
      })
    })
    $('#kategori, #jenis_kpb').on('change', function () {
      $('#kd_part').val("");
      $('#qty').val("");
      $('#harga_sp').val("");
      __getBarangSP();
    });
    /*$("#kd_part").typeahead({
        source: function (query, process) {
            $('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
            return $.get('<?php echo base_url("pkb/part_typeahead"); ?>', {keyword: query}, function (data) {
                // console.log(data);
                data = $.parseJSON(data);
                $('#fd').html('');
                return process(data.keyword);
            })
        },
        minLength: 3,
        limit: 20
    });*/
    $('.qurency').mask('#.##0', {reverse: true});
    $('.tahun').mask('0000', {reverse: true});
    // $('.meter').mask('000.000.000.000.000', {reverse: true});
    $('#submit-btn').click(function(){
      $("#pkbForm").valid();
        $("#pkbForm").validate({
            focusInvalid: false,
            invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                $('html, body').animate({
                    scrollTop: $(validator.errorList[0].element).offset().top
                }, 2000);
            }
        });
        if (jQuery("#pkbForm").valid()) {
          storeData();
        }
    });
    $('.hapus2-item').click(function(){
      var detailId = this.id;
      if(detailId != '')
      {
        $.getJSON(http+'/pkb/delete_pkb_detail',{id:detailId}, function(data, status) {
            if (data.status == true) {
              $("#"+detailId).parents('tr').remove();
              var listPkb = $('#pkb_list > tbody > tr').length;

              emptyList(listPkb);

            }
        });
      }
    });


    $('#pkb_list').on('click', '.hapus-item', function(){
        $(this).parents('tr').remove();
        var listPkb = $('#pkb_list > tbody > tr').length;
        
        emptyList(listPkb);
        // alert(listPkb);

    });

    $('.proses').click(function(){
      var id = this.id;
      var btnProses = '#'+id;
      var proses = id.split('_');
      var status_pkb = 0;
      var defaultBtnproses = $(this).html();
      // $(btnProses).addClass("disabled");
      // $(btnProses).html("<i class='fa fa-spinner fa-spin'></i>");
      $(".alert-message").fadeIn();
      // console.log(proses);
      if(proses[0] == 'approve')
      {
        status_pkb = 1;
        __updatePKB(proses[0], proses[1], status_pkb, btnProses, defaultBtnproses);
      }
      else if(proses[0] == 'play'){
        status_pkb = 2;
        __updatePKB(proses[0], proses[1], status_pkb, btnProses, defaultBtnproses);
      }
      else if(proses[0] == 'pause'){
        status_pkb = 3;
        var modal_id = '#myModalLg';
        var date = new Date();
        var url = http+"/pkb/alasan_pending/"+proses[1];
        $(modal_id).find(".modal-content").html(spinner());
        $.getJSON(url, function(data, status) {
            //alert(status);
            if (status == 'success') {
                if (data.indexOf("A PHP Error") > -1) {
                    //jika terjadi error output
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                } else {
                    //data berhasil di load
                    $(modal_id).find(".modal-content").html(data);
                }
                                // load jquery form vaidation
                __updateProses(proses[0], proses[1], status_pkb, btnProses, defaultBtnproses);
              }
              else {
                  $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
              }
            })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT\n\r" + textStatus));
        });
      }
      else if(proses[0] == 'finish'){
        status_pkb = 4;
        var modal_id = '#myModalLg';
        var date = new Date();
        var url = http+"/pkb/final_confirmation/"+proses[1];
        $(modal_id).find(".modal-content").html(spinner());
        $.getJSON(url, function(data, status) {
            //alert(status);
            if (status == 'success') {
                if (data.indexOf("A PHP Error") > -1) {
                    //jika terjadi error output
                    $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
                } else {
                    //data berhasil di load
                    $(modal_id).find(".modal-content").html(data);
                }
                                // load jquery form vaidation
                __updateProses(proses[0], proses[1], status_pkb, btnProses, defaultBtnproses);
              }
              else {
                  $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
              }
            })
        .fail(function(jqXHR, textStatus, errorThrown) {
            $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT\n\r" + textStatus));
        });
      }
    });
})

function emptyList(listPkb){
  if(listPkb == 0){
    $('#jenis_kpb').removeClass('disabled-action');
    $("#estimasi_mulai").val('');
    $("#estimasi_selesai").val('');
  }
}


function __getMotor()
{
    var url = http+"/pkb/tipe_motor";
    var kd_item = $("#kd_item").val();

    $('#kd_item').inputpicker({
      url:url,
      urlParam:{"kd_item":kd_item},
      fields:['KD_TYPEMOTOR','NAMA_PASAR', 'KET_WARNA'],
      fieldText:'NAMA_PASAR',
      fieldValue:'KD_ITEM',
      filterOpen: true,
      headShow:true,
      pagination: true,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 15,
      pageCurrent: 1,
      urlDelay:2
    }).on("change",function(){
      var kd_item = $(this).val();
      $.getJSON(http+"/pkb/tipe_motor/true",{'kd_item':kd_item},function(data, status){
        if(status == 'success'){
          $('#kd_typemotor').val(data[0].KD_TYPEMOTOR);
          $('#nama_typemotor').val(data[0].NAMA_PASAR);
        }
      });
        __getBarangSP();
    })
}

function __updateProses(jenis, id, status_pkb, btnProses, defaultBtnproses)
{
  $(".alasan-btn").on('click', function(event) {
      var formId = '#' + $(this).closest('form').attr('id');
      var btnId = '#' + this.id;
      $(formId).valid();
      $(formId).validate({
          focusInvalid: false,
          invalidHandler: function(form, validator) {
              if (!validator.numberOfInvalids())
                  return;
              $('html, body').animate({
                  scrollTop: $(validator.errorList[0].element).offset().top
              }, 1000);
          }
      });
      if (jQuery(formId).valid()) {
          // Do something
        // event.preventDefault();
        var defaultBtn = $(btnId).html();
        $(btnId).addClass("disabled");
        $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();
        $('#loadpage').removeClass("hidden");
        $(formId +" select").removeAttr("disabled");
        $(formId +" select").removeClass("disabled-action");
        var formData = $(formId).serialize();
        var act = $(formId).attr('action');
        $.ajax({
            url: act,
            type: 'POST',
            data: formData,
            dataType: "json",
            success: function(result) {
                if (result.status == true) {
                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(result.message);
                    setTimeout(function() {
                        hideAllMessages();
                        $('#loadpage').addClass("hidden");
                        $('.batal-btn').click();
                        __updatePKB(jenis, id, status_pkb, btnProses, defaultBtnproses);
                    }, 2000);
                } else {
                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(result.message);
                    setTimeout(function() {
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
  });
}
function __updatePKB(jenis, id, status_pkb, btnProses, defaultBtnproses)
{
  $.getJSON(http+"/pkb/simpan_prosespkb",{id:id, status_pkb:status_pkb}, function(data, status) {
      if (data.status == true) {
          $('.success').animate({
              top: "0"
          }, 500);
          $('.success').html(data.message);
          setTimeout(function() {
              hideAllMessages();
              if(jenis == 'approve'){
                $(".proses_"+id).addClass('disabled-action');
                $("#play_"+id).removeClass('disabled-action');
                $("#status_"+id).text('Diapprove');
              }
              else if(jenis == 'play'){
                $(".proses_"+id).addClass('disabled-action');
                $("#pause_"+id).removeClass('disabled-action');
                // $("#finish_"+id).removeClass('disabled-action');
                $("#status_"+id).text('Pengerjaan');
                cek_detailpkb(id, "#finish_"+id);
              }
              else if(jenis == 'pause'){
                $(".proses_"+id).addClass('disabled-action');
                $("#play_"+id).removeClass('disabled-action');
                $("#status_"+id).text('Pending');
              }
              else if(jenis == 'finish'){
                $(".proses_"+id).addClass('disabled-action');
                $(".action_"+id).addClass('disabled-action');
                $("#status_"+id).text('Selesai');
                cek_detailpkb(id, ".nota_"+id);
              }
              // document.location.reload();
              // $(btnProses).removeClass("disabled");
              // $(btnProses).html(defaultBtnproses);
          }, 2000);
      } else {
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html(data.message);
          setTimeout(function() {
              hideAllMessages();
              // $(btnProses).removeClass("disabled");
              // $(btnProses).html(defaultBtnproses);
          }, 2000);
      }
  });
  return false;
}

function cek_detailpkb(id, button)
{
  var no_pkb = $(".nota_"+id).data('nopkb');
  // alert(no_pkb);
  $.getJSON(http+"/pkb/cek_detailpkb/"+no_pkb, function(data, status) {

        console.log(data);
    if(data == 'true'){
        // alert(data);
        $(button).removeClass('disabled-action');
      
    }
  });
}

function __getBarangSP(){
  var kd_kategori = $('#kategori').val();
  var lokasi_dealer = $('#lokasi_dealer').val();
  var item = $("#kd_item").val();
  // console.log(item);
  var kd_item = 'null';

  // $("#kd_part").val('');
  if(item != undefined && item != ''){
    var split = item.split("-");
    kd_item = split[0];
    var url_kategori = http+"/pkb/part_jasa/"+kd_kategori+"?kd_typemotor="+kd_item+"&lokasi_dealer="+lokasi_dealer;
    $('#kd_part').inputpicker({
      url:url_kategori,
      // urlParam:{"data_number":data_number},
      fields:['DATA_NUMBER','DATA_DESKRIPSI'],
      fieldText:'DATA_DESKRIPSI',
      fieldValue:'DATA_NUMBER',
      filterOpen: true,
      headShow:true,
      pagination: true,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 15,
      pageCurrent: 1,
      urlDelay:2
    })
  }
}

$("#kd_part").on("change",function(e){
  
    var kd_kategori = $('#kategori').val();
    var data_number= $.trim($(this).val());

    getDetailpkb(kd_kategori ,data_number);

})

$("#kpb_free").click(function(){
    var free = $("#kpb_free:checkbox:checked").val() == 'kpb'?'KPB':'REGULER';
    var jenis_tr = $("#jenis_tr").val();
    var jenis_item = $("#jenis_item").val();
    var jenis_kpb = $("#jenis_kpb").val();

    $('#jenis_pkb').val(free);

    if(free == 'KPB'){
      if(jenis_tr == 'OLI' || (jenis_item == 'ASS' && jenis_kpb != 'NONKPB')){
        $("#approval_item").val(1);
      }
      else{
        $("#approval_item").val(0);
      }
    }
    else{
      $("#approval_item").val(1);
    }
})


function getDetailpkb(kd_kategori ,data_number)
{
    $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");

    var url = http+"/pkb/part_jasa/"+kd_kategori+"/true";

    // var url = (kd_kategori == 'Part' ? http+"/sparepart/hargapart/true":http+"/pkb/hargajasa");
    $.getJSON(url,{"data_number":data_number},function(result){
      console.log(result);
      $.each(result,function(e,d){
        var harga_jual=0;
        var cek_oli = $(".data-OLI").length;
        var jenis_kpb = $("#jenis_kpb").val();

        harga_jual = d.DATA_HARGA;


        $('#kategori_item').val($("#kategori").val());
        // $('#kd_part').val(d.DATA_NUMBER);
        $('#part_desc').val(d.DATA_DESKRIPSI);
        $('#qty').val("1");
        $('#harga_sp').attr('min',parseFloat(harga_jual));     
        $('#harga_sp').val(parseFloat(harga_jual));     
        // $('#harga_sp').mask("#.##0",{reverse: true});
        $('#jenis_item').val(d.JENIS_ITEM);


        $("#kpb_free").prop('checked', false);

        $('#frt').val(d.FRT);
        $('#jenis_tr').val($("#kategori").val());
        $('#approval_item').val(1);
        $('#jenis_pkb').val("REGULER");


        if((d.JENIS_ITEM == 'OLI' && cek_oli <= 0) || (d.JENIS_ITEM == 'ASS' && jenis_kpb != 'NONKPB')){
          if(d.JENIS_ITEM == 'OLI'){
            cekOli(data_number);
          }
          else{
            $('#jenis_pkb').val("KPB");
            $("#kpb_free").prop('checked', true);
            $(".detail-loading").html("");
          }
          
        }
        else{
          $('#jenis_pkb').val("REGULER");

          $(".detail-loading").html("");
        }
      })
    })
}

function cekOli(data_number)
{
  // alert(data_number);
  var item = $("#kd_item").val();
  var kd_item = 'null';
  var jenis_kpb = $("#jenis_kpb").val();

  if(item != undefined && item != ''){
    var split = item.split("-");
    kd_item = split[0];
  }
  var mesin = $("#no_mesin").val();
  var no_mesin = mesin.substr(0,5);
  if(jenis_kpb != 'NONKPB' && kd_item != 'null'){
    var url_kpb = http+"/pkb/get_kpbpart";
    var kpb_ke = jenis_kpb.slice(-1);
    
    // console.log(kd_item);
    $.getJSON(url_kpb,{'part_number':no_mesin, 'kd_typemotor':kpb_ke},function(result){
      // console.log(result);
      // alert(result.metode4.status);
      if(result.metode4.status == true){
        // alert('test');
        $.each(result.metode4.message,function(e,d){
          if(d.PART_NUMBER == data_number){
            var kategori = d.KATEGORI == 'OLI' ? 'Part' : 'Jasa';

            $('#kategori_item').val(kategori);
            $('#frt').val(0);
            $('#kd_part').val(d.PART_NUMBER);
            $('#part_desc').val(d.PART_DESKRIPSI);
            $('#qty').val(d.JUMLAH);
            $('#qty').attr('readonly','readonly');
            $('#harga_sp').attr('min',parseFloat(d.HARGA));     
            $('#harga_sp').val(parseFloat(d.HARGA));     
            // $('#harga_sp').mask("#.##0",{reverse: true});
            $('#jenis_item').val(d.KATEGORI);

            $('#jenis_pkb').val("KPB");
            $('#jenis_tr').val(d.KATEGORI);
            $("#kpb_free").prop('checked', true);
            
            $('#approval_item').val(1);
            // __addItem();
          }
        });

      }
      
      $(".detail-loading").html("");
    });

  }
  else{
    $(".detail-loading").html("");
  }
  
}

function getJasaoli() {
  var url_kpb = http+"/pkb/get_kpbpart";

  var jenis_kpb = $("#jenis_kpb").val();
  var kpb_ke = jenis_kpb.slice(-1);

  var mesin = $("#no_mesin").val();
  var no_mesin = mesin.substr(0,5);

  $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
  
  $.getJSON(url_kpb,{'part_number':no_mesin, 'kd_typemotor':kpb_ke},function(result){
    // console.log(result);
    // alert(result.metode4.status);
    if(result.metode4.status == true){
      // alert('test');
      $.each(result.metode4.message,function(e,d){
        if(d.KATEGORI == 'JASA'){
          var kategori = d.KATEGORI == 'OLI' ? 'Part' : 'Jasa';

          $('#kategori_item').val(kategori);
          $('#frt').val(15);
          $('#kd_part').val(d.PART_NUMBER);
          $('#part_desc').val(d.PART_DESKRIPSI);
          $('#qty').val(d.JUMLAH);
          $('#harga_sp').attr('min',parseFloat(d.HARGA));     
          $('#harga_sp').val(parseFloat(d.HARGA));     
          // $('#harga_sp').mask("#.##0",{reverse: true});
          $('#jenis_item').val(d.KATEGORI);

          $('#jenis_pkb').val("KPB");
          $('#jenis_tr').val(kategori);
          $("#kpb_free").prop('checked', true);
          $('#approval_item').val(1);
          __addItem();
        }
      });
    }
    
    $(".detail-loading").html("");
  });
}

function __addItem()
{
  var bariskes=0;
  var total_bayar=0;
  var total_beli=0;
  var html="";

  var jenis_item = $('#jenis_item').val();

  
  $('#harga_sp').unmask();
  bariskes = $('#lst_sp > tbody > tr').length;
  //var diskon = $('#diskon').val();
  total_beli = $('#qty').val() * $('#harga_sp').val();
  $('#harga_sp').mask('#.##0', {reverse: true});
  if(total_beli != 0){
    $('#jenis_kpb').addClass('disabled-action');

    var total_diskon = total_beli * $('#diskon').val() / 100;
    var cek_oli = ($('#jenis_item').val() == 'OLI' ? 'cek_oli' : '');

    // html +="<tr><td class='text-center'>"+(bariskes+1)+"</td>";
    html +="<tr class='data-"+$('#jenis_tr').val()+"'>";
    html +="<td class='hidden'>"+$('#kd_part').val()+"</td>";
    html +="<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>"; 
    html +="<td>"+ $('#kd_part').val() + " - " + $('#part_desc').val() +"</td>";
    html +="<td class='text-right'>"+ $('#qty').val() +"</td>";
    html +="<td class='text-right qurency'>"+ $('#harga_sp').val() +"</td>";
    html +="<td class='text-right qurency'>"+ total_diskon +"</td>";
    html +="<td class='text-right qurency'>"+ (total_beli - total_diskon) +"</td>";
    html +="<td class='text-right'>"+ $('#kategori_item').val() +"</td>";
    html +="<td class='hidden'>"+ $('#frt').val() +"</td>";
    html +="<td class='hidden "+cek_oli+"'>"+ $('#jenis_item').val() +"</td>";
    html +="<td>"+ $('#jenis_pkb').val() +"</td>";
    html +="<td class='hidden'>"+ $('#approval_item').val() +"</td>";
    // html +="<td>"+ ($('#diskon').val()?$('#diskon').val():0) +"%</td>";
    html +="</tr>";
    if($('#kategori_item').val() == 'Jasa'){
      $('#pkb_list > tbody').prepend(html);
    }
    else{
      $('#pkb_list > tbody').append(html);
    }
    $('#diskon').val('0');
    var bariskex=0;
    // var estimasiSelesai = 0;
    var estimasiSelesai = $("#estimasi_mulai").val();
    // console.log(estimasiSelesai);
    if(estimasiSelesai == ''){
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      // add a zero in front of numbers<10
      h = (h < 10? '0'+h:h);
      m = (m < 10? '0'+m:m);
      var time_start = h + ":" + m;
      $('#estimasi_mulai').val(time_start);    
      estimasiSelesai = $("#estimasi_mulai").val();
    }
    else{
      estimasiSelesai = $("#estimasi_selesai").val();
      
    }
    var splitTime1= estimasiSelesai.split(':');
    var hour=0;
    var minute=0;
    var second=0;
    hour = parseInt(splitTime1[0]);
    minute = parseInt(splitTime1[1]);
    bariskex = $('#pkb_list > tbody > tr').length;
    var dataxx=[];

    var waktuKerja = $('#frt').val();
    minute = minute + parseInt(waktuKerja) ;

   /* for(iz=0;iz< bariskex;iz++){
      var waktuKerja = $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(9)").text();
      minute = minute + parseInt(waktuKerja) ;
    }*/
    // console.log(hour+':'+minute);
    var totSeconds = (hour * 3600) + (minute * 60);
    hour = pad(Math.floor(totSeconds/3600)%60);
    minute = pad(Math.floor(totSeconds/60)%60);
    if($('#kategori_item').val()){
        $('#estimasi_selesai').val(hour+':'+minute);
    }
    // hour = hour + minute/60;
    // minute = minute%60;
    // console.log(hour+':'+minute);
    // __deleteBtn();

    $("#kd_part").val('');
    $("#qty").val('');
    $('#qty').removeAttr('readonly');
    $("#diskon").val('');
    $("#kpb_free").prop('checked', false);
    $("#harga_sp").val('');

    if($('#jenis_tr').val() == 'OLI'){
        getJasaoli();
    }
  }
  else{
    $('.error').animate({top: "0"}, 500);
    $('.error').html("data tidak boleh kosong atau 0").fadeIn();
    setTimeout(function () {
        hideAllMessages();
    }, 2000);
  }
}
function pad(num) {
  if(num < 10) {
    return "0" + num;
  } else {
    return "" + num;
  }
}

function __data()
{
  var bariskex=0;
  bariskex = $('#pkb_list > tbody > tr').length;
  var dataxx=[];
  for(iz=0;iz< bariskex;iz++){
    var kdStatus = $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(0)").text();
    $(".qurency").unmask();
    if(kdStatus != ''){
      dataxx.push({
        'kd_pekerjaan' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(0)").text(),
        'kategori'  : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
        'qty': $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
        'harga_satuan' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
        'total_harga' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(6)").text(),
        'jenis_item' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(9)").text(),
        'jenis_pkb' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(10)").text(),
        'diskon' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(5)").text(),
        'approval_item' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(11)").text()
      })
    }
    $(".qurency").mask('#.##0', {reverse: true});
  }
  // console.log('jmlbaris: '+bariskex)
  // console.log(dataxx)
  return dataxx;
}
function storeData()
{
    var data_form=__data();
    var no_pkb = $('#no_pkb').val();
    var defaultBtn = $("#submit-btn").html();
    // $("#submit-btn").addClass("disabled");
    $("#submit-btn").html("<i class='fa fa-spinner fa-spin'></i> Loading");
    $(".alert-message").fadeIn();
    $(".form-control").removeAttr('disabled');
    $('#loadpage').removeClass("hidden");
    var formData = $("#pkbForm").serialize();
    var act = $("#pkbForm").attr('action');
    var cek_oli = $(".cek_oli").length;
    var kpb = $("#jenis_kpb").val();
    var est_mulai = $("#estimasi_mulai").val();
    var est_selesai = $("#estimasi_selesai").val();
    // console.log(no_pkb);
    if(data_form.length > 0 || no_pkb != ''){
      if(est_selesai >= est_mulai){
        if( cek_oli == 0 && kpb == 'KPB1'){
          $('.error').animate({
              top: "0"
          }, 500);
          $('.error').html('Maaf data oli harus ada !');
          setTimeout(function () {
              hideAllMessages();
              $("#submit-btn").removeClass("disabled");
              $("#submit-btn").html(defaultBtn);
              $('#loadpage').addClass("hidden");
          }, 2000);

        }
        else{
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
        }
      }
      else{
        $('.error').animate({
            top: "0"
        }, 500);
        $('.error').html('Maaf waktu selesai tidak boleh lebih kecil dari waktu mulai');
        setTimeout(function () {
            hideAllMessages();
            $("#submit-btn").removeClass("disabled");
            $("#submit-btn").html(defaultBtn);
            $('#loadpage').addClass("hidden");
        }, 2000);

      }
    }
    else{
      $('.error').animate({
          top: "0"
      }, 500);
      $('.error').html('Maaf detail pekerjaan tidak boleh kosong');
      setTimeout(function () {
          hideAllMessages();
          $("#submit-btn").removeClass("disabled");
          $("#submit-btn").html(defaultBtn);
          $('#loadpage').addClass("hidden");
      }, 2000);
    }
    return false;
}
function display_c(){
  var refresh=1000; // Refresh rate in milli seconds
  mytime=setTimeout('display_ct()',refresh);
}
function display_ct() {
  var strcount;
  var x = new Date();
  var Y = x.getFullYear();
  var M = x.getMonth()+1;
  var D = x.getDate();
  var h = x.getHours();
  var m = x.getMinutes();
  var s = x.getSeconds();
  // add a zero in front of numbers<10
  /*h = (h < 10? '0'+h:h);
  m = (m < 10? '0'+m:m);
  s = (s < 10? '0'+s:s);*/
  // $("#ct").html(D+'/'+M+'/'+Y+' '+h+':'+m+':'+s);
  $("#ct").html(pad(D)+'/'+pad(M)+'/'+Y+' '+pad(h)+':'+pad(m)+':'+pad(s));
  $.getJSON(http+"/pkb/antrian_service/true", function(data, status){
    if(status == 'success'){
      $('#antrian').html(data.antrian);
    }
  });
  // document.getElementById('ct').innerHTML = x;
  tt=display_c();
}