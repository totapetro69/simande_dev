var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
	$("input[id^='qty_']").ForceNumericOnly();
	$('#jumlah_sparepart').ForceNumericOnly();
	$('#harga_sp').ForceNumericOnly();
	$('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
		A: {pattern: /[A-Za-z]/},
		Z: {pattern: /[A-Za-z]/,optional:true},
		0: {pattern: /[0-9]/},
		1: {pattern: /[0-9]/,optional:true}
	}})
	//03732/FAK-PD/M/01/2018
	$('#no_sjmasuk')
		.mask('00000/AAA-AA/00/0000',{'translation': {
		    A: {pattern: /[A-Za-z]/},
		    Z: {pattern: /[A-Za-z]/,optional:true},
		    0: {pattern: /[0-9]/}
		  }})
		.on('keypress',function(e){
			if(e.which==13){
				addSJ();
			}
			$('.sj').addClass('btn-primary');
		})
	$('#jumlah_sparepart').on('keypress',function(e){
		if(e.which==13){
			
			$('#sp').click();
		}
		
	})

	var ajaxUrlp = http+"/sparepart/sparepart_typeahead";
	//sp_typehead(ajaxUrlp);

	var ajaxUrlp = http+'/sparepart/expedisi_typeahead';
	sp_expdc(ajaxUrlp);
	$('#btn-simpan').click(function(){
		if($('#frmSJ').valid()){
			__simpan();
		}
	})
	$("#kd_sparepart").typeahead({
        source: function(query,process){
			$('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
			$.get(http+"/Sparepart/part_typeahead",{keyword:query},function(data){
				if(data.length>0){
					data=$.parseJSON(data);
					console.log(data);
					$('#fd').html('');
					return process(data.keyword);
				}
				
			})
		},
		minLength:3,
        autoSelect: true,
        afterSelect:function(){
        	var part=$('#kd_sparepart').val().split("-");
        	$('#kd_sparepart').val(part[0]);
        	$('#nama_sparepart').val(part[1]);
        	$('#sp').removeClass("disabled-action");
        	$.ajax({
	    		type:'POST',
	    		url:http+'/sparepart/sparepart_typeahead/true',
	    		data:{'part_number':($('#kd_sparepart').val().split(' - '))[0]},
	    		dataType:'json',
	    		success:function(result){
                	//$('#jumlah_sparepart').focus().select();
                	$('#harga_sp').focus().select()
		    		//console.log(result);
		    		if(result.length>0){
		    			$.each(result,function(e,d){
		    				$('#kd_sparepart').val(d.PART_NUMBER);
		    				$('#nama_sparepart').val(d.PART_DESKRIPSI);
                            $('#sp').removeClass("disabled-action");
		    			})
		    		}
		    	}
	    	})
        }
    });
    $('#harga_sp').on("keypress",function(e){
    	if(e.which==13){
    		$('#jumlah_sparepart').focus().select();
    	}
    })
   
})

/*function sp_typehead(ajaxUrlp){
	$.getJSON(ajaxUrlp, function(data, status) {
    	if (status == 'success') {*/
            
        /*}
    });*/
//}
function sp_expdc(ajaxUrlp){
	$.getJSON(ajaxUrlp, function(data, status) {
    	if (status == 'success') {
            $("#nama_expedisi").typeahead({
                source: data.keyword,
                autoSelect: true,
                afterSelect:function(){
                	$.ajax({
			    		type:'POST',
			    		url:http+'/sparepart/expedisi_typeahead/true',
			    		data:{'nama_expedisi':($('#nama_expedisi').val().split(' - '))[0]},
			    		dataType:'json',
			    		success:function(result){
		                	$('#nama_driver').focus().select()
				    		//console.log(result);
				    		if(result.length>0){
				    			$.each(result,function(e,d){
				    				$('#nama_expedisi').val(d.NAMA_EXPEDISI);
				    				$('#no_polisi').val(d.NO_POLISI);
                                    $('#sp').removeClass("disabled-action");
				    			})
				    		}
				    	}
			    	})
                }
            });
        }
    });
}
function add_item(){
	$('#kd_sparepart,#nama_sparepart,#jumlah_sparepart').attr('required');
	var harga=$('#harga_sp').val().replace('/,/g','');
	var jml = $('#jumlah_sparepart').val().replace('/,/g','');
	if(harga){
		$('#total_harga').val((harga * jml));
	}
	bariske = $("#bundling_sp >tbody > tr").length;
	html  ="<tr><td class='text-center'>"+(bariske+1)+"</td>";
	html += "<td class='text-center'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td>";
	html +="<td class='text-center'>"+$('#kd_sparepart').val()+"</td>";
	html +="<td>"+$('#nama_sparepart').val()+"</td>";
	html +="<td class='text-right'><input type='text' id='hrg_"+bariske+"' name='hrg_"+bariske+"' class='on-grid text-right' value='"+$('#harga_sp').val()+"'/></td>";
	html +="<td class='text-right'><input type='text' id='jml_"+bariske+"' name='jml_"+bariske+"' class='on-grid text-right' value='"+$('#jumlah_sparepart').val()+"'/></td>";
	html +="<td class='text-right'>"+$("#total_harga").val()+"</td>";
	html +="<td><select id='rak_"+bariske+"' name='rak_"+bariske+"' class='on-grid'></select></td></tr>";
	$('table#bundling_sp tbody').append(html);
	$('#kd_sparepart,#nama_sparepart,#jumlah_sparepart').val('');
	$('#kd_sparepart').focus().select();
    $('#sp').addClass("disabled-action");
    __getGudang(bariske);
}
function __getGudang(id){
	var html="";
	var urls = http+"/inventori/gudang_part";
        var datax = {"kd_dealer": $('#kd_dealer').val()};
        $.ajax({
            type: 'GET',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').empty();
                $('#rak_' + id + '').append(result);
                //console.log(result);
                $('#rak_' + id + '').attr('disabled',true);
            }
        });
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
    $("#bundling_sp >tbody > tr:eq(" + bariske + ")").remove();
}
/**
 * [__hapusID description]
 * @param  {[type]} id [description]
 * @return {[type]}    [description]
 */
function __hapusID(id){
	if(confirm('Yakin akan menghapus item ini?')){
		$('#loadpage').removeClass("hidden");
		$.getJSON(http+"/inventori/deletedetail_tsp/"+id,{'id':id},function(result){
			console.log(result);
			if(result.status==true){
				$('.success').animate({ top: "0"}, 500);
                $('.success').html('Data berhasil di hapus').fadeIn();
			}else{
				$('.error').animate({ top: "0"}, 500);
                $('.error').html('Data gagal di hapus').fadeIn();
			}
			setTimeout(function() {
                document.location.reload();
            }, 2000);
		})
	}
}
/**
 * [__simpan description]
 * @return {[type]} [description]
 */
function __simpan(){
	var datax=[];
	var jmlrow=$("#bundling_sp >tbody > tr").length;
	for (i=0;i< parseInt(jmlrow);i++){
		/*var jml=parseInt($('#jml_'+i).val());
		if(jml>0){*/
			datax.push({
				'part_number': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(2)").html(),
				'price': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(8)").text().replace(/,/g,''),
				'diskon': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(9)").text(),
				'ppn': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(10)").text(),				
				'netprice': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(11)").text(),
				'kdtrans': $("#bundling_sp >tbody > tr:eq(" + i + ") td:eq(12)").text(),
				'qty':$('#jml_'+i).val(),
				'qty_rfs':$('#jml_rfs'+i).val(),
				'qty_nrfs':$('#jml_nrfs'+i).val(),
				/*'status_part':$('#status_part'+i).val(),*/
				'kd_gudang':$('#rak_'+i).val(),
				'kd_gudangNRFS':$('#rakNRFS_'+i).val(),
				'kd_dealer': $('#kd_dealer').val(),
				'no_sjmasuk': $('#no_sjmasuk').val()
			});
			
		//} 
	}
	console.log(datax);
	$('#loadpage').removeClass("hidden");
	if(datax.length==0){ alert("Tidak ada Item yang diterima"); return;}
	$.ajax({
		type:'POST',
		url : http+"/inventori/simpanpenerimaan",
		dataType : 'html',
		data : $('#frmSJ').serialize()+'&detail='+JSON.stringify(datax),
		success : function(result){
			console.log(result);
			var d = result.split(":");
            if(parseInt(d[2])==1){
                $('.success').animate({ top: "0"}, 500);
                $('.success').html('Data berhasil di simpan').fadeIn();
                if (parseInt(d[1]) == 0) {
                    setTimeout(function() {
                       document.location.href = http+"/inventori/penerimaanpart?v=y&t="+d[0];
                    }, 2000);
                    
                } else {
                    setTimeout(function() {
                        document.location.href = http+"/inventori/penerimaanpart?v=y&t=" + d[0];
                    }, 2000);
                    
                }
            }
            $('#loadpage').addClass("hidden");
		}
	})
}
function addSJ(){
	$('#loadpage').removeClass("hidden");
	var url=http+"/inventori/penerimaanpart?n="+$.base64.encode($('#no_sjmasuk').val());
	document.location.href=url;
}