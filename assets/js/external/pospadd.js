//javascript untuk proses po part
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
var api = window.location.origin+'/'+path[1]+'/backend/index.php/';
$(document).ready(function(e){
	$('#part_number').typeahead({
			source:function(query,process){
				$('#fd').html("<i class='fa fa-spinner fa-spin'></i>");
				$.get(http+"/Sparepart/part_typeahead/"+query+"/",{'keyword':query,'price':'y'},function(data){
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
				$.getJSON(http+"/sparepart/hargapart/true/part",{'part_number':part[0],jt:'part'},function(result){
					var harga=0;
					if(result.length>0){
						$.each(result,function(index,e){
							harga=e.HET
							
						})
					}
					$('#harga').val(harga);
				})
				
			}
		})
	$('#jml_order').on('keypress',function(e){
		$('#btn-simpan').removeClass('disabled-action');
		if(e.which==13){
			$('#btn-simpan').click();
		}
		
	})
	$('#jenis_order').change(function(){
		switch($(this).val()){
			case 'Hotline':
				$('fieldset.xx').removeClass('disabled-action');
				$('fieldset.xx input,textarea').attr('required',true),
				$('#nama_konsumen').focus().select();
				$('#fso').attr('disabled',true);
				$('table#listpo tbody').html('');
			break;
			case 'Reguler':
				$('#fso').attr('disabled',false);
				$('table#listpo tbody').html('');
			case 'Fix':
				__checkPOFix();
				$('table#listpo tbody').html('');
			case 'Canvasing':
				$('#fso').attr('disabled',true);
				$('table#listpo tbody').html('');
			break;
			default:
				$('fieldset.xx').addClass('disabled-action');
				$('fieldset.xx input,textarea').attr('required',false).val('');
			break;
		}
	})
	$('#fso').on("click",function(){
		if ($(this).is(":checked")){
			__loadSuggested();
		}
	})
	$('#baru').click(function(){document.location.href=http+"/purchasing/posp_add/"})
})

function add_item(){
	var jml_order=$('#jml_order').val();
	if(jml_order==''){return false;}
	var ppn =$('#ppne').is(":checked");
	var harga=parseFloat($('#harga').val());
	var jppn =(ppn)?(harga-(harga/1.1)):"0";
	console.log(jppn);
	$('#part_number,#nama_part,#jml_order').attr('required')
	bariske = $("#listpo >tbody > tr").length;
	html  ="<tr><td class='text-center' valign='middle'>"+(bariske+1)+"</td>";
	html += "<td class='text-center' valign='middle'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td>";
	html +="<td class='text-center' valign='middle'>"+$('#part_number').val()+"</td>";
	html +="<td valign='middle'>"+$('#nama_part').val()+"</td>";
	html +="<td class='text-right'><input type='text' class='on-grid form-control' id='jml_p"+bariske+"' name='jml_p"+bariske+"' value='"+$('#jml_order').val()+"'/></td>";
	html +="<td valign='middle' class='text-right'>"+Math.round(harga/*-parseFloat(jppn)*/).toLocaleString()+"</td>";
	html +="<td valign='middle' class='text-right table-nowarp'>"+Math.round(parseFloat(jppn)).toLocaleString()+"</td>";
	html +="<td valign='middle' class='text-right'>"+(parseFloat($('#jml_order').val())*parseFloat(harga)).toLocaleString()+"</td><td>&nbsp;</td></tr>";
	$('table#listpo tbody').append(html);
	$('#part_number,#nama_part,#jml_order').val('');
	$('#harga').val('0');
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
			url : http+"/purchasing/posp_simpan",
			dataType: 'json',
			data: datax+"&detail="+detail,
			success:function(result){
				//var result = $.parseJSON(result);
				if(result.status==true){
					$('.success').animate({ top: "0"}, 500);
                	$('.success').html('Data berhasil di simpan').fadeIn();
					setTimeout(function() {
						// update data sales order
						/*$.getJSON(http+'/purchasing/posp_updateSO/',{
							'nopo':$('#no_')
						})*/
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
			'harga'	: $("#listpo >tbody > tr:eq(" + i + ") td:eq(5)").html().replace(/,/g,""),
			'ppn'	: $("#listpo >tbody > tr:eq(" + i + ") td:eq(6)").html().replace(/,/g,""),
		})
	}
	return data;
}
function __checkPOFix(){
	$.getJSON(http+"/purchasing/posp_fix",
		{'bulan':$('#bulan_kirim').val(),'tahun':$('#tahun_kirim').val(),'kd_dealer':$('#kd_dealer').val()},function(result){
			if(result.totaldata >0){
				alert('PO Fix untuk bulan ini sudah di buat');
				$("#jenis_order").val('').select();
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
		url	: http+"/purchasing/posp_suggest/null/true",
		data: {'kd_dealer':$('#kd_dealer').val()},
		//dataType :'json',
		success:function(result){
			var result =$.parseJSON(result);
			$('table#listpo tbody').html('');
			console.log(result);
			$.each(result,function(e,d){
				if(parseFloat(d.SGS_ORDER)>0){
					html  +="<tr><td class='text-center' valign='middle'>"+(bariske+1)+"</td>";
					html += "<td class='text-center' valign='middle'><a onclick=\"hapus('" + bariske + "','sp');\"><i class='fa fa-trash'></i></a></td>";
					html +="<td class='text-left' valign='middle'>"+d.PART_NUMBER+"</td>";
					html +="<td valign='middle'>"+d.PART_DESKRIPSI+"</td>";
					html +="<td class='text-right'>";
					html +="<input type='text' class='on-grid form-control' id='jml_p"+bariske+"' name='jml_p"+bariske+"' value='"+parseFloat(d.SGS_ORDER).toLocaleString()+"'/></td>";
					html +="<td valign='middle' class='text-right'>"+(parseFloat(d.HET)/*-parseFloat(d.PPN)*/).toLocaleString()+"</td>";
					html +="<td valign='middle' class='text-right'>"+parseFloat(d.PPN).toLocaleString()+"</td>";
					html +="<td valign='middle' class='text-right'>"+(parseFloat(d.SGS_ORDER)*parseFloat(d.HET)).toLocaleString()+"</td>";
					html +="<td>&nbsp;</td></tr>";
					bariske++;
				}
			})
			
			$('table#listpo tbody').append(html);
			$('#loadpage').addClass("hidden");
		}
	})
}
function __approvalPO(mode){
	var alasan="";
	if(parseInt(mode)==2){
		alasan=prompt("Masukan alasan PO ini di reject");
		if(!alasan){
			return false;
		}
	}
	$('#loadpage').removeClass("hidden");
	var datax=$('#frmpoheader').serialize();
	$.ajax({
			type: 'POST',
			url : http+"/purchasing/posp_approve/"+mode,
			dataType: 'json',
			data: datax+"&alasan="+alasan,
			success:function(result){
				//var result = $.parseJSON(result);
				if(result.status==true){
					$('.success').animate({ top: "0"}, 500);
					if(mode==2){
						$('.success').html('Data berhasil di reject :'+alasan).fadeIn();
					}else{
						$('.success').html('Data berhasil di approve').fadeIn();
					}
                	
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