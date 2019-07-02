/**
 * jQuery for spk page
 */
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
	var date = new Date();
    date.setDate(date.getDate());

    $('#date').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        setDate: date,
        todayHighlight: true
    });

    $('#datex').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true
    });
	$('#kd_item2').attr('required','required');
	var ajaxUrl = http+"/motor/aparel_typeahead";
	var ajaxUrls = http+"/motor/aksesoris_typeahead";
    $('#kd_item').change(function(){
	   var ajaxUrlp = http+"/sparepart/sparepart_vsmotor";    
        var datax=[];
        $.getJSON(ajaxUrlp,{'kd_typemotor':$('#kd_item').val()} ,function(result) {
            if(result.length>0){
                $.each(result,function(e,d){
                    datax.push({
                        'PART_NUMBER'  :d.PART_NUMBER,
                        'DESKRIPSI'    :d.PART_DESKRIPSI,
                        'value'        :d.PART_NUMBER,
                    }); 
                })
                $("#nama_sparepart").inputpicker({
                    data :datax,
                    fields:['PART_NUMBER','DESKRIPSI'],
                    fieldValue:'value',
                    fieldText:'DESKRIPSI',
                    headShow:true,
                    filterOpen:true,
                }).on("change",function(){
                    var dx=datax.findIndex(obj => obj['value'] === $(this).val());
                    if(dx>-1){
                        $('#kd_sparepart').val(datax[dx]['value']);
                        $('a#sp').removeClass("disabled-action");
                    }
                })
            
                
            }
        });
    });

    if (ajaxUrl != null) {
        $.getJSON(ajaxUrl, function(data, status) {
        	if (status == 'success') {
                $("#nama_aparel").typeahead({
                    source: data.keyword,
                    autoSelect: true,
                    afterSelect:function(){
                    	$('#jumlah_aparel').focus().select()
                    	$.ajax({
				    		type:'POST',
				    		url:http+'/motor/aparel_typeahead/true',
				    		data:{'nama_aparel':$('#nama_aparel').val()},
				    		dataType:'json',
				    		success:function(result){
					    		console.log(result);
                                if(parseInt(result.length)>0){
					    			$.each(result,function(e,d){
					    				$('#kd_aparel').val(d.KD_BARANG);
                                        $('#ap').removeClass("disabled-action");
					    			})
					    		}
					    	}
				    	})
                    }
                });
            }
        });
        //aksesoris
        $.getJSON(ajaxUrls, function(data, status) {
        	if (status == 'success') {
                $("#nama_aksesoris").typeahead({
                    source: data.keyword,
                    autoSelect: true,
                    afterSelect:function(){
                    	$('#jumlah_aksesoris').focus().select()
                    	$.ajax({
				    		type:'POST',
				    		url:http+'/motor/aksesoris_typeahead/true',
				    		data:{'nama_aksesoris':$('#nama_aksesoris').val()},
				    		dataType:'json',
				    		success:function(result){
					    		console.log(result);
					    		if(parseInt(result.length)>0){
					    			$.each(result,function(e,d){
					    				$('#kd_aksesoris').val(d.KD_BARANG);
                                        $('#hd').removeClass("disabled-action");
					    			})
					    		}
					    	}
				    	})
                    }
                });
            }
        });
        //sparepart
        
    }
    $('#jumlah_aparel').ForceNumericOnly();
    $('#jumlah_sparepart').ForceNumericOnly();
    $('#jumlah_aksesoris').ForceNumericOnly();
    $('#jumlah_aparel').nextField("ket_aparel");
    $('#ket_aparel').nextField("ap")
    $('#jumlah_sparepart').nextField("ket_bundling");
    $('#ket_bundling').nextField("sp")
    $('#jumlah_aksesoris').nextField("ket_aksesoris");
    $('#ket_aksesoris').nextField("hd");
    $('#btn-simpan').click(function(){
    	__simpan_bundling();
    })
})
function add_item(tipe){
	var html="";var bariske;
	switch(tipe){
		case "sp":
			bariske = $("#bundling_sp >tbody > tr").length;
			html  ="<tr><td class='text-center'>"+(bariske)+"</td>";
			html +="<td class='text-center'>"+$('#kd_sparepart').val()+"</td>";
			html +="<td>"+$('#nama_sparepart').val()+"</td>";
			html +="<td class='text-right'>"+$('#jumlah_sparepart').val()+"</td>";
			html +="<td>"+$('#ket_bundling').val()+"</td>";
			html += "<td class='text-center'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td></tr>";
			$('table#bundling_sp tbody').append(html);
			$('#kd_sparepart,#nama_sparepart,#ket_bundling').val('');
			$('#nama_sparepart').focus().select();
            $('#sp').addClass("disabled-action");
		break;
		case "ap":
			bariske = $("#bundling_apparel >tbody > tr").length;
			html  ="<tr><td class='text-center'>"+(bariske)+"</td>";
			html +="<td class='text-center'>"+$('#kd_aparel').val()+"</td>";
			html +="<td>"+$('#nama_aparel').val()+"</td>";
			html +="<td class='text-right'>"+$('#jumlah_aparel').val()+"</td>";
			html +="<td>"+$('#ket_aparel').val()+"</td>";
			html += "<td class='text-center'><a onclick=\"hapus('" + bariske + "','ap');\"><i class='fa fa-trash'></i></a></td></tr>";
			$('table#bundling_apparel tbody').append(html);
			$('#kd_aparel,#nama_aparel,#ket_aparel').val('');
			$('#nama_aparel').focus().select();
            $('#ap').addClass("disabled-action");
		break;
		case "hd":
			bariske = $("#bundling_aksesoris >tbody > tr").length;
			html  ="<tr><td class='text-center'>"+(bariske)+"</td>";
			html +="<td class='text-center'>"+$('#kd_aksesoris').val()+"</td>";
			html +="<td>"+$('#nama_aksesoris').val()+"</td>";
			html +="<td class='text-right'>"+$('#jumlah_aksesoris').val()+"</td>";
			html +="<td>"+$('#ket_aksesoris').val()+"</td>";
			html += "<td class='text-center'><a onclick=\"hapus('" + bariske + "','hd');\"><i class='fa fa-trash'></i></a></td></tr>";
			$('table#bundling_aksesoris tbody').append(html);
			$('#kd_aksesoris,#nama_aksesoris,#ket_aksesoris').val('');
			$('#nama_aksesoris').focus().select();
            $('#hd').addClass("disabled-action");
		break;
	}
	
}
/**
 * hapus item bundling yang barusan di pilih
 * @param  {[type]} bariske [description]
 * @param  {[type]} tipe    [description]
 * @return {[type]}         [description]
 */
function hapus(bariske,tipe){
	if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) 
    } else {
        bariske = bariske;
    }
    switch(tipe){
    	case "sp": $("#bundling_sp >tbody > tr:eq(" + bariske + ")").remove();break;
    	case "ap": $("#bundling_apparel >tbody > tr:eq(" + bariske + ")").remove();break;
    	case "hd": $("#bundling_aksesoris >tbody > tr:eq(" + bariske + ")").remove();break;
    }
    
}
/**
 * hapus item bundling yng sudah di simpan di databas
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function _hapus(id,kd_bundling){
	if(confirm('Yakin data ini akan dihapus')){
		$('#loadpage').removeClass("hidden");
		$.ajax({
		type:'POST',
		url: http+"/motor/delete_bundling_detail/"+kd_bundling,
		data:{'id':id},
		dataType:'json',
		success:function(result){
			if (result.status == true) {
				$('.success').animate({
                    top: "0"
                }, 500);
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

                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message).fadeIn();

                setTimeout(function() {
                    hideAllMessages();
                    /*$("#submit-btn").removeClass("disabled");
                    $("#submit-btn").html(defaultBtn);*/
                }, 4000);
                $('#loadpage').addClass("hidden");
            }
		}
	})
	}
	
}
/**
 * jquery AddOn focus cursor to next field
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
jQuery.fn.nextField=function(id){
	$(this).on("keypress",function(e){
		if(e.which==13){

			if($('#'+id).is('a')){
                if(!$('#'+id).hasClass("disabled-action")){
                    $('#'+id).click();
                }
				
			}else{
				$('#'+id).focus().select();
			}
		}
	})
	
}
/**
 * simpan data input
 * @return {[type]} [description]
 */
function __simpan_bundling(){

	if(!$('#formBundlinge').valid()){return false;}

	$('#formBundlinge select,input').removeAttr('disabled');
	$('#loadpage').removeClass("hidden");
	var datane=__data();
	console.log(datane);
	if(datane.length==0){
	  $(".alert-message").fadeIn();
	  $('.error').animate({ top: "0" }, 500).fadeIn();
      $('.error').html("Maaf, Data Item program bundling nya harus diisi");
      setTimeout(function () {
          hideAllMessages();
          $("#kd_dealer").addClass("disabled");
          $('#loadpage').addClass("hidden");
      }, 4000);
	}
	$.ajax({
		type:'post',
		url: http+"/motor/add_bundling_simpan",
		data: $('#formBundlinge').serialize()+'&detail='+JSON.stringify(__data()),
		dataType:'json',
		success:function(result){
			if (result.status == true) {
				$('.success').animate({
                    top: "0"
                }, 500);
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

                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html(result.message).fadeIn();

                setTimeout(function() {
                    hideAllMessages();
                    $('#loadpage').addClass("hidden");
                }, 4000);
            }
		}
	})
}

/**
 * collect data from table detail bundling to array
 * for store to database
 * @return {[type]} [description]
 */
function __data(){
	var datane=[];
	var bariske 	= $("#bundling_sp >tbody > tr:not(.thead-alias-tr)").length;
    var datane 		= [];
    // alert(bariske);
    for (i = 1; i <= (parseInt(bariske)); i++) {
    	//i=(i+1);
        datane.push({
        	'kd_item':$("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(1)").text(),
        	'nama_item':$("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(2)").text(),
        	'jumlah':$("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(3)").text(),
        	'keterangan':$("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(4)").text(),
        	'group_bundling':'Sparepart'
        });
    }
	var bariske1 	= $("#bundling_apparel >tbody > tr:not(.thead-alias-tr)").length;
	for (ii = 1; ii <= (parseInt(bariske1)); ii++) {
		//ii=(ii+1);
        datane.push({
        	'kd_item':$("#bundling_apparel >tbody > tr:eq(" + ii + ") td:eq(1)").text(),
        	'nama_item':$("#bundling_apparel >tbody > tr:eq(" + ii + ") td:eq(2)").text(),
        	'jumlah':$("#bundling_apparel >tbody > tr:eq(" + ii + ") td:eq(3)").text(),
        	'keterangan':$("#bundling_apparel >tbody > tr:eq(" + ii + ") td:eq(4)").text(),
        	'group_bundling':'Apparel'
        });
    }
    var bariske2 	= $("#bundling_aksesoris >tbody > tr:not(.thead-alias-tr)").length;
	for (iii = 1; iii <= (parseInt(bariske2)); iii++) {
		//iii=(iii+1);
        datane.push({
        	'kd_item':$("#bundling_aksesoris >tbody > tr:eq(" + iii + ") td:eq(1)").text(),
        	'nama_item':$("#bundling_aksesoris >tbody > tr:eq(" + iii + ") td:eq(2)").text(),
        	'jumlah':$("#bundling_aksesoris >tbody > tr:eq(" + iii + ") td:eq(3)").text(),
        	'keterangan':$("#bundling_aksesoris >tbody > tr:eq(" + iii + ") td:eq(4)").text(),
        	'group_bundling':'Aksesoris'
        });
    }
    return datane;
}
/**
 * [__getdata_warna description]
 * @param  {[type]} kd_item [description]
 * @return {[type]}         [description]
 */
function __getdata_warna(kd_item) {
    $("#kd_item2_wm").val('');
    var kw = (kd_item) ? '' : $("#kd_item2_wm").val();
    $("#kd_items_wm #cls_wm").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
    $.ajax({
        url: http+"/purchasing/listmotor",
        type: "POST",
        dataType: "html",
        data: {"keyword": kw, "lst": '2', "kd_type": kd_item, 'lok': '_wm'},
        success: function (result) {
            $("#list_wm tbody").html("");
            $("table#list_wm tbody").append(result);
            $("#kd_items_wm #cls_wm").html("");
            //$("#kd_items_wm").click();
            $('#kd_item').change();
        }

    });
    return false;
}