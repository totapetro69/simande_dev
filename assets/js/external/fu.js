

var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

  
$(document).ready(function(){
 
  var date = new Date();
  date.setDate(date.getDate());

 
  $('.date').datepicker({
      format: 'dd/mm/yyyy',
      endDate: date,
      autoclose: true
  });

  var filter_metode = $("#filter_metode").val();



  var url_metode = (filter_metode == 'true' ? http+"/follow_up/metode_fu/"+filter_metode : http+"/follow_up/metode_fu");
  // alert(filter_metode);
  // console.log(url_kategori);

  $.getJSON(url_metode, function(result){
    var datax=[];
    $.each(result,function(e,d){
      datax.push({
        'value' :d.ID,
        'ID' : d.ID,
        'DESKRIPSI' : d.NAMA_METODE,
      });
      
    })
    // console.log(datax);
    $('#kd_metodefu').inputpicker({
      data:datax,
      fields:['ID','DESKRIPSI'],
        fieldText:'DESKRIPSI',
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

      var id=$(this).val();
      $('#kd_setup_statuscall').val('');

      $.getJSON(url_metode,{"id":id},function(result){
          $('#nama_metodefu').val(result[0].NAMA_METODE);
          $('#kd_setup_statuscall').removeAttr('readonly');

          var url_status = http+"/follow_up/status_metodefu";
          // console.log(url_kategori);

          $.getJSON(url_status,{"kategori":result[0].NAMA_METODE}, function(result){
            var datax=[];
            $.each(result,function(e,d){
              datax.push({
                'value' :d.ID,
                'ID' : d.ID,
                'STATUS' : d.STATUS,
                'KETERANGAN' : (d.KETERANGAN != null? d.KETERANGAN:'-'),
                'KLASIFIKASI' : (d.KLASIFIKASI != null? d.KLASIFIKASI:'-'),
              });
              
            })
            // console.log(datax);
            $('#kd_setup_statuscall').inputpicker({
              data:datax,
              fields:['ID','STATUS','KETERANGAN','KLASIFIKASI'],
                fieldText:'STATUS',
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

              var id=$(this).val();


              $.getJSON(url_status,{"id":id},function(result){
                  $('#status_metode').val(result[0].STATUS);

              });

            })
          })



      })


    })

    
    // console.log(datax);
    $('#kd_metodefu2').inputpicker({
      data:datax,
      fields:['ID','DESKRIPSI'],
        fieldText:'DESKRIPSI',
        fieldValue:'ID',
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

      var id=$(this).val();
      $('#kd_status_metodefu2').val('');

      $.getJSON(url_metode,{"id":id},function(result){
          $('#nama_metodefu2').val(result[0].NAMA_METODE);
          $('#kd_status_metodefu2').removeAttr('readonly');

          var url_status = http+"/follow_up/status_metodefu";
          // console.log(url_kategori);

          $.getJSON(url_status,{"kategori":result[0].NAMA_METODE}, function(result){
            var datax=[];
            $.each(result,function(e,d){
              datax.push({
                'value' :d.ID,
                'ID' : d.ID,
                'STATUS' : d.STATUS,
                'KETERANGAN' : (d.KETERANGAN != null? d.KETERANGAN:'-'),
                'KLASIFIKASI' : (d.KLASIFIKASI != null? d.KLASIFIKASI:'-'),
              });
              
            })
            // console.log(datax);
            $('#kd_status_metodefu2').inputpicker({
              data:datax,
              fields:['ID','STATUS','KETERANGAN','KLASIFIKASI'],
                fieldText:'STATUS',
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

              var id=$(this).val();


              $.getJSON(url_status,{"id":id},function(result){
                  $('#status_metode2').val(result[0].STATUS);

              });

            })
          })



      })


    })
  })


  var url_fu_service = http+"/follow_up/get_rangka_bykpb";
  // console.log(url_kategori);

  $(".loading-fu").html("<i class='fa fa-spinner fa-spin'></i>");

  var url_fu_service = http+"/follow_up/get_rangka_bykpb";

  $('#no_rangka_service').inputpicker({
    url:url_fu_service,
    // urlParam:{"kd_item":kd_item},
    fields:['NO_RANGKA','JENIS_KPB', 'BULAN_SERVICE', 'STATUS_SERVICE', 'SERVICE_DI'],
    fieldText:'NO_RANGKA',
    fieldValue:'NO_RANGKA',
    filterOpen: true,
    headShow:true,
    pagination: true,
    pageMode: '',
    pageField: 'p',
    pageLimitField: 'per_page',
    limit: 15,
    pageCurrent: 1,
    // urlDelay:2
  })
  .on("change",function(e){
    e.preventDefault();

    var no_rangka=$(this).val();


    $.getJSON(http+"/follow_up/get_detail_fu",{"no_rangka":no_rangka},function(result){
        var dateso = new Date(result.sj.message[0].TGL_SO);


        yr      = dateso.getFullYear(),
        month   = (dateso.getMonth()+1) < 10 ? '0' + (dateso.getMonth()+1) : (dateso.getMonth()+1),
        day     = dateso.getDate()  < 10 ? '0' + dateso.getDate()  : dateso.getDate(),
        newDate = day + '/' + month + '/' + yr;;
        
        if(result.kpb[0].JENIS_KPB == 'KPB1'){
          var kpb = 1;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB2'){
          var kpb = 2;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB3'){
          var kpb = 3;
        }
        else if(result.kpb[0].JENIS_KPB == 'KPB4'){
          var kpb = 4;
        }
        else{
          var kpb = 0;
        }
        // alert(newDate);
        // alert(date.getDate() + '/' +  (date.getMonth() + 1) + '/' + date.getFullYear());
        $('#kd_customer').val(result.sj.message[0].KD_CUSTOMER);
        $('#kelurahan').val(result.sj.message[0].NAMA_DESA);
        $('#kecamatan').val(result.sj.message[0].NAMA_KECAMATAN);
        $('#kota').val(result.sj.message[0].NAMA_KABUPATEN);
        $('#kode_pos').val(result.sj.message[0].KODE_POS);
        $('#propinsi').val(result.sj.message[0].NAMA_PROPINSI);
        $('#jenis_kpb').val(kpb);
        $('#kd_motor').val(result.sj.message[0].KET_UNIT);
        $('#no_hp').val(result.sj.message[0].NO_HP);

        $('#no_mesin').val(result.sj.message[0].NO_MESIN);
        $('#tgl_pembelian').val(newDate);
        $('#nama_stnk').val(result.sj.message[0].NAMA_CUSTOMER);
        $('#alamat_surat').val(result.sj.message[0].ALAMAT_SURAT);

        $('#jenis_kpb_title').html(result.kpb[0].JENIS_KPB);

    });

  })

  $("#no_rangka_service").removeAttr('disabled');    
  $(".loading-fu").html("");

});