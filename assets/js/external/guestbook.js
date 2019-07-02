//guestbook javascript 
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1] ;
$(document).ready(function () {
    var date = new Date();
    date.setDate(date.getDate());

    $('#date,#datex, #date_fu, #date_fu1, #date_fu2').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true
    });
    $('#baru').click(function(){
    	location.href=http+"/customer/add_guest_book";
    })
    var ajaxUrls = $("#ajax-url-customer").attr("url");
   
    $('#kd_propinsi').change();
    
    $('#kd_propinsi').on('change', function () {
        loadData('kd_kabupaten', $('#kd_propinsi').val(),'')
    })
    $('#kd_kabupaten').on('change', function () {
        loadData('kd_kecamatan', $(this).val(), '')
    })
    $('#kd_kecamatan').on('change', function () {
        loadData('kd_desa', $(this).val(), '')
    })
    /* load data customer 
     * key di code customer : nama dan nomor telpon
     */
    $('#nama_customer')
    .on('change',function(){
        $('#kd_customer').val('');
        $('.submit-btn').addClass("disabled-action");
    })
    .on('focusout', function () {
       if($('#nama_customer').val()!='') $('#no_hp').focus().select();
       //$('#kd_propinsi').change();
       // __getcustomerdetail();
    })
    $('#inputpicker-1').on("focusout",function(){
       
    })
    $('#no_hp').on('focusout', function () {
        if($('#no_hp').val()!='' && $('#guest_no').val() == '' && $('#nama_customer').val()!='') {
            __getcustomerdetail($('#kd_customer').val(),true);
        }
        $('#kd_propinsi').change(); 
    })
    if ($('#guest_no').val() != '') {
        __getcustomerdetail($('#kd_customer').val(),true);
    }else{
        //__getCustomer();
    }
    $('#test_drive').change(function(){
        if($(this).val()=="Ya"){
            $('#ridingpanel').removeClass("hidden");
            $("#kesan_test").focus().select();
            $("#kesan_test").attr('required','required')
        }else{
             $('#ridingpanel').addClass("hidden");
             $("#kesan_test").removeAttr('required','required')
        }
    })
    $('#kd_status').change(function(){
     var index=$('#kd_status option:selected').index();
     switch(index){
        case 3:
        	$('#sts_deal').removeClass("hidden");
        	 $("#sts_deal .panel-body .col-xs-12:not('#nodeal')").removeClass("hidden"); 
        	 $('#ket_notdeal').prop('required',false); 
        	 $('#fu_ke, #rencana_fu1').prop('required',true);
        	 $('#fu_ke').prop('required',true); 
        break;
        case 4:
            $('#sts_deal').removeClass("hidden"); 
            $("#sts_deal .panel-body > .col-xs-12:not('#nodeal')").addClass("hidden");
            $('#nodeal').removeClass("hidden");  
            $('#ket_notdeal').prop('required',true); 
            $('#rencana_fu1').prop('required',false);
            $('#fu_ke').prop('required',false);        
        break;
        default:
            if(index==1){
                $('#create_spk').removeClass("hidden");
            }else{
                $('#create_spk').addClass("hidden");
            }
            $('#sts_deal').addClass("hidden"); 
            $('#ket_notdeal').prop('required',false); 
            $('#rencana_fu1').prop('required',false);
            $('#fu_ke').prop('required',false); 
        break;            
     }

    })
    $('#hasil_metode').change(function(){
     var index=$('#hasil_metode option:selected').index();
     switch(index){
        case 3:
        	$("#fu_ke2").removeClass("hidden");
        	$("#nodeal").addClass("hidden");
        	$('#rencana_fu2').prop('required',true);
        	$('#ket_notdeal').prop('required',false);  
        break;
        case 4:
        	$("#fu_ke2").addClass("hidden");
        	$("#nodeal").removeClass("hidden"); 
        	$('#rencana_fu2').prop('required',false);      
        	$('#ket_notdeal').prop('required',true);  
        break;
        default:
        	$("#fu_ke2").removeClass("hidden");
        	$("#nodeal").removeClass("hidden");
        	$('#rencana_fu2').prop('required',false);
        	$('#ket_notdeal').prop('required',false);  
        break;            
     }
   })
    $('#status_fu').change(function(){
    	if($(this).val()=="Tidak Terhubung"){
    		$('#tdkterhubung').removeClass("hidden");
    		$('#sts_tdkterhubung').prop('required',true)
    	}else{
    		$('#tdkterhubung').addClass("hidden");
    		$('#klasifikasi').prop('required',true)
    	}
    })
    $('#ket_notdeal').change(function(){
        var idx=$("#ket_notdeal option:selected").index();
        if(idx==5){
            $('#nodeal_2').removeClass("hidden")
            $('#ket_notdeal_5').prop("required",true);
        }else{
            $('#nodeal_2').addClass("hidden")
            $('#ket_notdeal_5').prop("required",false);
        }
    })
   __getAppointment();
   
    $('#myModalLg').on('hidden.bs.modal', function () {
            $(this).data('bs.modal', null);

    });
    $('#modal-button').on('click',function(){
        __caridata();
    })
    $('#cari').on('keypress',function(e){
        if(e.which == 13){
            //__caridata();
            $("#modal-button").click();
        }else{
            if($(this).val().length >0){
                $('#modal-button').removeClass("disabled-action");
            }
        }
    })
});

function __caridata(){
    if($('#cari').val().length==0){alert('masukan nama atau no hp yang akan di cari');return false;}
    var modal_id = $("#modal-button").attr('data-target');
    $(modal_id).find(".modal-content").html(spinner());
    var url = http+"/customer/customer/true/";
    $.getJSON(url,{'keyword':$('#cari').val().replace("'","\'")}, function(data, status) {
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
function __getcustomerdetail(kd_customer,pasti) {
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'POST',
        url: http+'/customer/customerdetail/0/'+pasti,
        dataType: 'json',
        data:{
            'nama_customer':$('#nama_customer').val(),
            'kd_customer':kd_customer,
            'no_hp'   :$('#no_hp').val()
        },
        success: function (result) {
            
            //console.log(result);
            if (result.status == true){
                $.each(result.message, function (index, d) {
                    $('#cari').val('');
                    $("#nama_customer").val(d.NAMA_CUSTOMER.replace('\\',''));//.addClass('disabled-action');
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#alamat').val(d.ALAMAT_SURAT);
                    $('#kd_propinsi').val(d.KD_PROPINSI).select();
                    loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KOTA);
                    loadData('kd_kecamatan',d.KD_KOTA,d.KD_KECAMATAN);
                    loadData('kd_desa',d.KD_KECAMATAN,d.KELURAHAN);
                    $('#no_hp').val(d.NO_HP);
                    $('#no_ktp').val(d.NO_KTP);
                    $('#kd_pekerjaan').val(d.KD_PEKERJAAN);
                    $('#email_customer').val(d.EMAIL);
                    $('#kd_gender').val(d.JENIS_KELAMIN).select();
                    $('#tgl_lahir').val(convertDate(d.TGL_LAHIR));
                    $('#l_alamat').html("");
                    $('#loadpage').addClass("hidden");
                    $('#addFormz').removeClass("disabled-action");
                    $('.submit-btn').removeClass("disabled-action");
                })
            }else{
                //alert("nomor hp harus di isi");
                //alert("Data tidak di temukan, Silahkan coba lagi\n"+$('#inputpicker-1').text());
                $('#loadpage').addClass("hidden");
                //$('#addFormz input, textarea').val('');
                //$('#addFormz select').val('').select();
                $('#addFormz').removeClass("disabled-action");
                $('.submit-btn').removeClass("disabled-action");

            }
            $('#cari').val('');
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
    var urls = http + "/customer/" + param+"/";
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

function simpan_guest(){
	var no_ktp=$('#no_ktp').val();
	var tgl_lahir=$('#tgl_lahir').val();
    var tipe_motor=$('#kd_item').val();
    /*if($('#nama_customer').val()==''){
        $('#nama_customer').val($('#inputpicker-1').val());      
    }*/
    //console.log($("#nama_customer").val());
    // console.log($("#inputpicker-1").val());
    
    if(tgl_lahir){
        var umur=(checkumur("tgl_lahir"));
        if(umur <=17 || umur >=60){
           // alert('Tanggal Lahir ada masalah\nMohon di check input tanggal lahir nya \n(Umur yang di ijinkan 17 s/d 60 Tahun)');
           // return;
        }
    }
    $('#addFormz input').removeAttr("disabled");
	if (tipe_motor==''){ $('#kd_item2').prop('required',true);}else{$('#kd_item2').prop('required',false);}
	/*if (no_ktp==''){$('#tgl_lahir').prop('required',true);}else{$('#tgl_lahir').prop('required',false);}
	if (tgl_lahir==''){$('#no_ktp').prop('required',true);}else{$('#no_ktp').prop('required',false);}*/
    
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
                        setTimeout(function() {
                            location.replace(result.location)
                        }, 2000);
                    } else {
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
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
function checkumur(id) {
    var today = new Date(),
        birthday = $('#' + id).datepicker("getDate"),
        age = ((today.getMonth() > birthday.getMonth()) ||
            (today.getMonth() == birthday.getMonth() && today.getDate() >= birthday.getDate())) ?
        today.getFullYear() - birthday.getFullYear() : today.getFullYear() - birthday.getFullYear() - 1;
    //e.preventDefault();
    return age
}
function __getCustomer(){
    $.getJSON(http+"/customer/customer_typeahead/xx/true/",function(result){
        var datax=[];
        $.each(result,function(e,d){
            datax.push({
                'value':d.KD_CUSTOMER,
                'text':d.NAMA_CUSTOMER,
                'description':d.ALAMAT_SURAT+', '+d.NAMA_KECAMATAN+' '+d.NAMA_KABUPATEN,
                'Kode':d.KD_CUSTOMER,
                'Nama':d.NAMA_CUSTOMER,
                'Alamat':d.ALAMAT_SURAT+', '+d.NAMA_KECAMATAN+' '+d.NAMA_KABUPATEN
            })
        })
        //console.log(datax);
        $('#nama_customer').inputpicker({
            data : datax,
            fields :['Kode','Nama','Alamat'],
            fieldText :'text',
            fieldValue :'value',
            filterOpen: true,
            selectMode: 'creatable',
            headShow: true,
            // pagination: true,
            // pageMode: '',
            // pageField: 'p',
            // pageLimitField: 'per_page',
            // limit: 5,
            // pageCurrent: 1
        }).on("change",function(input){
            var dx=datax.findIndex(obj => obj['value'] == $(this).val());
            $("#kd_customer").val($(this).val())
            //$('#nama_customer').val($(this).text());
        })
    })
}
function __getAppointment(){
    $.getJSON(http+"/customer/list_appointment/true/true",function(result){
        var dataxs=[];
        $.each(result,function(e,d){
            dataxs.push({
                'value':d.KD_CUSTOMER,
                'text':d.NAMA_CUSTOMER,
                'description':d.ALAMAT.toUpperCase()+', HP :'+d.NO_HP+' ,'+d.JENIS_APPOINTMENT +" ["+d.TANGGAL_JANJI+"]",
                'no_trans':d.NO_TRANS
            })
        })
        //console.log(datax);
        $('#appdata').inputpicker({
            data : dataxs,
            fields :['value','text','description'],
            fieldText :'text',
            fieldValue :'value',
            filterOpen: true
        }).on("change",function(input){
            var dxs=dataxs.findIndex(obj => obj['value'] == $(this).val());
            $('#noappoint').addClass("hidden");
            $('#appoint').removeClass("hidden");
            $.each(result,function (index, d) {
                if(d.NO_TRANS == dataxs[dxs]['no_trans']){
                    console.log(d.NAMA_CUSTOMER);
                    $('#no_hp').val(d.NO_HP);
                    $("#nama_customer_app").val(d.NAMA_CUSTOMER);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#alamat').val(d.ALAMAT);
                    $('#kd_propinsi').val(d.KD_PROPINSI).select();
                    loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KABUPATEN);
                    loadData('kd_kecamatan',d.KD_KABUPATEN,d.KD_KECAMATAN);
                    loadData('kd_desa',d.KD_KECAMATAN,d.KD_DESA);
                    //$('#no_ktp').val(d.NO_KTP);
                    //$('#kd_pekerjaan').val(d.KD_PEKERJAAN);
                    //$('#email_customer').val(d.EMAIL);
                    $('#kd_gender').val(d.JENIS_KELAMIN).select();
                    $('#no_appointment').val(d.NO_TRANS);
                    $('#kd_sales').val(d.KD_SALES).select();
                    
                    //$('#tgl_lahir').val(convertDate(d.TGL_LAHIR));
                    $('#l_alamat').html("");
                    $('#loadpage').addClass("hidden");

                }
                    
            });

        })
    })
}