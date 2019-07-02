//javascript untuk proses po part
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(e){
	$('#part_number').typeahead({
			source:function(query,process){
				$('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
				$.get(http+"/Sparepart/part_typeahead/"+query+"/y",{'keyword':query,'price':'y'},function(data){
					if(data.length>0){
						data=$.parseJSON(data);
						console.log(data);
						$('#fd').html('');
						return process(data.keyword);
					}
					
				})
			},
			minLength:3,
			max:20,
			hint:true,
			cache:true,
			compression:true,
			afterSelect:function(){
				$('#jml_order').focus().select();
				var part=$('#part_number').val().split('-');
				$('#part_number').val($.trim(part[0]))
				$('#nama_part').val($.trim(part[1]));
				$.post(http+"/Sparepart/sparepart_typeahead/true",{'part_number':part[0]},function(result){
					result=$.parseJSON(result);
					$.each(result,function(index,e){
						$('#harga').val(e.HET);
					})
				})
				
			}
		})
	$('#baru').click(function(){document.location.href=http+"/purchasing/posp_add/"})
})

function add_item(){
	$('#part_number,#nama_part').attr('required')
	bariske = $("#listpo >tbody > tr").length;
	html  ="<tr><td class='text-center' valign='middle'>"+(bariske+1)+"</td>";
	html += "<td class='text-center' valign='middle'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td>";
	html +="<td class='text-center' valign='middle'>"+$('#part_number').val()+"</td>";
	html +="<td valign='middle'>"+$('#nama_part').val()+"</td>";
	$('table#listpo tbody').append(html);
	$('#part_number,#nama_part').val('');
	$('#part_number').focus().select();
    $('#btn-simpan').addClass("disabled-action");
}
function __simpan_po(){
	if($('#frmpoheader').valid()){
		$('#loadpage').removeClass("hidden");
		var datax=$('#frmpoheader').serialize();
		var detail=JSON.stringify(__listpoitem());
		if(detail.length<2){ alert('Tidak ada item yang disimpan');return;}
		$.ajax({
			type: 'POST',
			url : http+"/part/add_customer_part_simpan",
			dataType: 'json',
			data: datax+"&detail="+detail,
			success:function(result){
				//var result = $.parseJSON(result);
				if(result.status==true){
					$('.success').animate({ top: "0"}, 500);
                	$('.success').html('Data berhasil di simpan').fadeIn();
					setTimeout(function() {
		                document.location.href=result.nodoc
		            }, 2000);
				}else{
					$('.error').animate({ top: "0"}, 500);
	                $('.error').html('Data gagal di simpan').fadeIn();
	                $('#loadpage').addClass("hidden");
	                 setTimeout(function() {
                        hideAllMessages();
                    },2000)
				}
				
				console.log(result);
			}
		})
	}
	//console.log($('#frmpoheader').valid());
}
function __listpoitem(){
	var data=[]; var jmlbaris=0;
	jmlbaris = $("#listpo >tbody > tr").length;
	//alert(jmlbaris);
	for(i=0; i < jmlbaris; i++){
		data.push({
			'part_number':$("#listpo >tbody > tr:eq(" + i + ") td:eq(2)").html(),
			'jumlah': $('#jml_p'+(i)).val(),
			'harga'	: $("#listpo >tbody > tr:eq(" + i + ") td:eq(6)").html().replace(/,/g,"")
		})
	}
	return data;
}
function __checkPOFix(){
	$.getJSON(http+"/purchasing/posp_fix",
		{'bulan':$('#bulan_kirim').val(),'tahun':$('#tahun_kirim').val(),'kd_dealer':$('#kd_dealer').val()},function(result){
			if(result.recordexitst){
				alert('PO Fix untuk bulan ini sudah di buat');
				return;
			}
		})
}
function hapus(bariske,tipe){
	if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) 
    } else {
        bariske = bariske;
    }
    switch(tipe){
    	case "sp": $("#listpo >tbody > tr:eq(" + bariske + ")").remove();break;
    }
}
function hapusID(id,baris){
	if(confirm("Yakin akan menghapus data ini?")){
		var bariske = $("#listpo >tbody").closest('tr').index();
		$('#ld_'+baris).html("<i class='fa fa-spinner fa-spin'></i>");
		$.get(http+"/purchasing/posp_hpsdetail",{'id':id},function(result){
			var result=$.parseJSON(result);
			if(result.status==true){
					$('.success').animate({ top: "0"}, 500);
                	$('.success').html('Data berhasil di hapus').fadeIn();
					setTimeout(function() {
		                document.location.reload()
		            }, 2000);
				}else{
					$('.error').animate({ top: "0"}, 500);
	                $('.error').html('Data gagal di hapus').fadeIn();
	                setTimeout(function() {
                        hideAllMessages();
                    },2000)
	                $('#ld_'+baris).html("");
				}
		})
	}
}
function __loadSuggested(){
	$('#loadpage').removeClass("hidden");
	var html=""; var bariske=0;
	$.ajax({
		type:'get',
		url	: http+"/purchasing/posp_suggest",
		data: {'kd_dealer':$('#kd_dealer').val()},
		dataType :'json',
		success:function(result){
			var result =$.parseJSON(result);
			$.each(result,function(index,d){
				html  ="<tr><td class='text-center' valign='middle'>"+(bariske+1)+"</td>";
				html += "<td class='text-center' valign='middle'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td>";
				html +="<td class='text-center' valign='middle'></td>";
				html +="<td valign='middle'></td>";
				html +="<td class='text-right'><input type='text' class='on-grid form-control' id='jml_p"+bariske+"' name='jml_p"+bariske+"' value='0'/></td>";
				html +="<td valign='middle' class='text-right'></td>";
				html +="<td valign='middle' class='text-right'></td></tr>";
				bariske ++
			})
			$('table#listpo tbody').html('');
			$('table#listpo tbody').append(html);
			$('#loadpage').addClass("hidden");
		}
	})
}
function __approvalPO(){
	$('#loadpage').removeClass("hidden");
	var datax=$('#frmpoheader').serialize();
	$.ajax({
			type: 'POST',
			url : http+"/purchasing/posp_approve",
			dataType: 'json',
			data: datax,
			success:function(result){
				//var result = $.parseJSON(result);
				if(result.status==true){
					$('.success').animate({ top: "0"}, 500);
                	$('.success').html('Data berhasil di approve').fadeIn();
					setTimeout(function() {
		                document.location.href=result.nodoc
		            }, 2000);
				}else{
					$('.error').animate({ top: "0"}, 500);
	                $('.error').html('Data gagal di approve').fadeIn();
	                $('#loadpage').addClass("hidden");
	                 setTimeout(function() {
                        hideAllMessages();
                    },2000)
				}
				
				console.log(result);
			}
		})
}