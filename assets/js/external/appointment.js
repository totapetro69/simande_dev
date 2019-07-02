//list_appointment javascript 
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function () {
    var date = new Date();
    date.setDate(date.getDate());

    $('#date,#datex').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true,
        startDate:date
    });
    $('#baru').click(function(){
        location.href=http+"/customer/add_list_appointment";
    })
    var ajaxUrls = $("#ajax-url-customer").attr("url");

    if (ajaxUrls != null) {
        $.getJSON(ajaxUrls, function (data, status) {
            if (status == 'success')
            {
                $("#nama_customer").typeahead({
                    source: data.keyword,
                    autoSelect: false
                });
            }

        });
    }
    /*pilihan propinsi*/
    $('#kd_propinsi').on('change', function () {
        loadData('kd_kabupaten', $('#kd_propinsi').val(),'')
    })
    $('#kd_kabupaten').on('change', function () {
        loadData('kd_kecamatan', $(this).val(), '')
    })
    $('#kd_kecamatan').on('change', function () {
        loadData('kd_desa', $(this).val(), '')
    })
    /* load data customer */
    /*$('#nama_customer').on('focusout', function () {
        if($('#nama_customer').val()!='') {
            __getcustomerdetail($('#kd_sales').val());
            $('#alamat').focus().select();
        }else{
          if($('#inputpicker_1').val()){
            $('#nama_customer').removeAttr("required");
          } 
        }

    })*/
      
    $('#alamat').on("focus",function(){
        console.log($("#nama_customer"));
        console.log($("#inputpicker_1"));
    })
    

});


function __getcustomerdetail_old(kd_customer,detail) {
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'POST',
        url: http+'/customer/customerdetail/0/'+detail,
        dataType: 'json',
        data:{
            'nama_customer':$('#nama_customer').val(),
            'kd_customer': kd_customer
        },
        success: function (result) {
            
            //console.log(result);
            if (result.status == true){
                $.each(result.message, function (index, d) {
                    $("#nama_customer").val(d.NAMA_CUSTOMER);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#alamat').val(d.ALAMAT_SURAT);
                    $('#kd_propinsi').val(d.KD_PROPINSI).select();
                    loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KOTA);
                    loadData('kd_kecamatan',d.KD_KOTA,d.KD_KECAMATAN);
                    loadData('kd_desa',d.KD_KECAMATAN,d.KELURAHAN);
                    $('#hp_customer').val(d.NO_HP);
                    /*if(kd_sales==''){
                        $('#kd_sales').val(d.ID_REFFERAL).select();
                    }else{
                         $('#kd_sales').val(kd_sales).select();
                    }*/
                    $('#l_alamat').html("");
                    $('#loadpage').addClass("hidden");
                })
            }else{
                //alert("Nomor KTP / Tgl Lahir harus di isi");
                $('#l_alamat').html("");
                $('#loadpage').addClass("hidden");
            }
        }/*,
         fail:function(jqXHR, textStatus, errorThrown){
         $('#l_alamat').html(""); 
         $('#kd_propinsi').val('0').select();
         loadData('kd_kabupaten','0','0');
         loadData('kd_kecamatan','0','0');
         loadData('kd_desa','0','0');
         }*/
    })
}
function __getcustomerdetail(kd_sales) {
    var kd_cus=kd_sales.split(':');

    $.ajax({
        type: 'POST',
        url:(kd_cus[1]=='KD')? http+'/customer/customerdetail/1':http+"/customer/cs_h2/true/",
        dataType: 'json',
        data:{'kd_customer':kd_cus[0]},
        success: function (result) {          
            console.log(result);
            if (result.status == true){
                $.each(result.message, function (index, d) {
                    $("#nama_customer").val(d.NAMA_CUSTOMER);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#alamat').val(d.ALAMAT_SURAT);
                    //if(kd_cus[1]=="KD"){
                        $('#kd_propinsi').val(d.KD_PROPINSI).select();
                        loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KOTA)
                        loadData('kd_kecamatan',d.KD_KOTA,d.KD_KECAMATAN)
                        loadData('kd_desa',d.KD_KECAMATAN,d.KELURAHAN);
                        $('#kd_pos').val(d.KODE_POS);
                    /*}else{
                        $('#no_pol').val(d.NO_POLISI);
                        $('#kd_typemotor').val(d.KD_TYPEMOTOR);
                        $('#thn_motor').val(d.THN_PERAKITAN)
                    }*/
                    $('#hp_customer').val(d.NO_HP);
                    
                })
            }
        }
    })
}
jQuery.fn.LoadSibling=function(id, select){
    $(this).on('change',function(){
        loadData(id, $(this).val(), select);
    })
}
function loadData(id, value, select) {
    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = http + "/customer/" + param;
    var datax = {
        "kd": value
    };
    $('#' + id + '').attr('disabled', 'disabled');
    select = (select == '' || select == "0") ? "0" : select;
    $.ajax({
        type: 'GET',
        url: urls,
        data: datax,
        typeData: 'html',
        success: function(result) {
            $('#' + id + '').empty();
            $('#' + id + '').html(result);
            $('#' + id + '').val(select).select();
            $('#l_' + param + '').html('');
            $('#alamat_lg').html("");
            $('#' + id + '').removeAttr('disabled');
        }
    });
}

function simpan_list_appointment(){
    var nama_customer=$('#nama_customer').val();

    $('#addFormz input').removeAttr("disabled");

    if($('#addFormz').valid()){
        $('#loadpage').removeClass("hidden");
        var urls=$('#addFormz').attr('action');
        var datax =$('#addFormz').serialize();
        $.ajax({
            type: 'POST',
            url: urls,
            dataType:'json',
            data: datax,
            success:function(result){
                console.log(result);
                if (result.status == true) {

                    $('.success').animate({top: "0"}, 500);
                    $('.success').html(result.message).fadeIn();
                    if (result.location != null) {
                        setTimeout(function() {location.replace(result.location)}, 2000);
                    } else {
                        setTimeout(function() {location.reload();}, 2000);
                    }
                } else {

                    $('.error').animate({top: "0"}, 500);
                    $('.error').html(result.message).fadeIn();

                    setTimeout(function() {hideAllMessages();}, 4000);
                }
            }
        })
    }
}

function __getCustomer(){
    $.getJSON(http+"/customer/customer_typeahead/xx/true/",function(result){
        var datax=[];
        $.each(result,function(e,d){
            datax.push({
                'value':d.KD_CUSTOMER,
                'text':d.NAMA_CUSTOMER.toUpperCase(),
                'description':d.ALAMAT_SURAT.toUpperCase()+', '+d.NAMA_KECAMATAN+' '+d.NAMA_KABUPATEN
            })
        })
        //console.log(datax);
        $('#nama_customer').inputpicker({
            data : datax,
            fields :['value','text','description'],
            fieldText :'text',
            fieldValue :'value',
            filterOpen: true,
            //selectMode :'creatable'
        }).on("change",function(input){
            var dx=datax.findIndex(obj => obj['value'] == $(this).val());
            console.log(input);
            if(dx>-1){
                $("#kd_customer").val($(this).val())
                __getcustomerdetail($(this).val(),true)
            }else{
                $("#nama_customer").removeAttr("required");
            }

        })
    })
}
function __caridata(){
    var modal_id = $("#modal-button-3").attr('data-target');
    var url = http+"/customer/cs_h2/";
    
    $.getJSON(url,{'keyword':$('#nama_customer').val(),'f':true}, function(data, status) {
            //alert(status);
        if (status == 'success') {

            if (data.indexOf("A PHP Error") > -1) {
                //jika terjadi error output
                $(modal_id).find(".modal-content").html(error_page("Ada kegagalan proses. Silahkan hubungi IT"));
            } else {
                //data berhasil di load
                $(modal_id).find(".modal-content").html(data);
            }
        }
    })
}