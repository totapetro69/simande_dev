//sales order part java script
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
var webapi= window.location.origin + '/' + path[1] + '/backend/index.php/api/';
$(document).ready(function(){
	var jenis_so=$('#jenis_penjualan').val();
	console.log(jenis_so);
	if($('#jenis_penjualan').val()=='Part'){
		__getBarangSPx('Part');
	}else{
		__getBarangSP(jenis_so);
	}
	$('#only_stock').click(function(e){
		$('#csp').html("<i class='fa fa-spinner fa-spin'></i>");
		__getBarangSPx('Part');
	})
	$('.xx').removeClass("disabled-action");
	
	$('#jenis_penjualan').on("change",function(e){
		if($(this).val()=='Part'){
			__getBarangSPx('Part');
			$('span.os').removeClass("hidden");
			$('#etainvo').removeClass("hidden");
			$('#kdtp').removeClass("disabled-action");
		}else{
			__getBarangSP($('#jenis_penjualan').val());
			$('span.os').addClass("hidden");
			$('#etainvo').addClass("hidden");
			$('#kdtp').addClass("disabled-action");
		}
	})
	
	$('#as_booking').click(function(){
		if($(this).is(":checked")==true){
			$('#btn_hol').removeClass("disabled-action");
			$('#nama_konsumen').focus();
			$('#inputpicker-5').focus();
			$('.xx').removeClass("disabled-action");
			$('.xx input:not(#no_telp,#kd_pos,#nama_konsumen)').attr('required',true);
			//__getCustomer();
		}else{
			$('#btn_hol').addClass("disabled-action");
			$('#nama_konsumen').attr('required',true);
			$('.xx').removeClass("disabled-action");
			$('.xx input, textarea').attr('required',false);

		}
	})
	__geTypeMotor();
	//loadData('kd_propinsi');
	//loadData('kd_kabupaten', $(this).val(),)
	$('#kd_propinsi').on('change', function () {
        loadData('kd_kabupaten', $(this).val(),'')
    })
    $('#kd_kabupaten').on('change', function () {
        loadData('kd_kecamatan', $(this).val(), '')
    })
    $('#kd_kecamatan').on('change', function () {
        loadData('kd_desa', $(this).val(), '')
       
    })
    $('#kd_desa').on('change',function(){
    	 __getKodePost($(this).val());
    })
    //if(jenis_so=='Part'){ __getBarangSPx('Part');}else{__getBarangSP("Barang");}
})
function __getBarangSP(jenis,kd_typemotor){
	$('#csp').html("<i class='fa fa-spinner fa-spin'></i>");
	var stok="";
		stok=$('#only_stock').is(":checked");
		stok=(stok==true)?true:'';
	var mode = ($('#only_stock').is(":checked"))?'':'1';
	var ajaxUrlp =(jenis=='Part')? http+"/part/parts4so/true/true/"+mode :http+"/inventori/list_sp_w_stock/true";
	var dat =(jenis=='Part')?{'kd_lokasi':$('#lokasi_jual').val(),'os':stok}:{'kd_typemotor':kd_typemotor,'jt':jenis,'os':stok};
	$.getJSON(ajaxUrlp,dat,function(result){
		var datax=[];
		console.log(result);
		if(result.status){
			$.each(result.message,function(e,d){
				switch(jenis){
					case 'Barang':
						datax.push({
							'PARTNUMBER' : d.KD_BARANG,
							'DESKRIPSI'	: (d.NAMA_BARANG)?d.NAMA_BARANG:"-" ,
							'STOCK':(d.SALDO_AKHIR)? parseFloat(d.SALDO_AKHIR).toLocaleString():'0',
							'HARGA':(parseFloat(d.SALDO_AKHIR)>0)?(d.TOTAL_HARGA/d.SALDO_AKHIR):'0',
						});
					break;
					default:
						datax.push({
							'PARTNUMBER' : d.PART_NUMBER,
							'DESKRIPSI'	: (d.PART_DESKRIPSI)?d.PART_DESKRIPSI:"-" ,
							'STOCK':(d.JUMLAH_SAK)? parseFloat(d.JUMLAH_SAK):'0'
						});
					break;
				}
				//}
			})
			
		}
		$('#csp').html('');
		//console.log(datax);
		if(!datax){ return false;}
		if(stok || jenis=='Barang'){
			$('#part_number').inputpicker({
				data:datax,
				fields:['PARTNUMBER','DESKRIPSI','STOCK'],
			    fieldText:'DESKRIPSI',
			    fieldValue:'PARTNUMBER',
			    filterOpen: true,
			    headShow:true,
			})
			var nn =$('#jenis_penjualan').val();
			console.log(nn);
			if(nn=='Barang'){
				$('#part_number').on("change",function(e){
					e.preventDefault();
					var part_number=$(this).val();
					var dx=datax.findIndex(obj => obj['PARTNUMBER'] === $(this).val());
					$('#nama_part,#jumlah_order').val('');
					$('#harga_sp,#diskon').val('0');
					$('#stock_oh').val(datax[dx]["STOCK"])
					$('#jml_stock').val(datax[dx]["STOCK"]);
					$('#nama_part').val(datax[dx]["DESKRIPSI"])
					harga_jual = datax[dx]["HARGA"];
					console.log(harga_jual);
					$('#harga_sp').val(parseFloat(harga_jual));
					$('#harga_sp').mask("#,##0",{reverse: true});
					$('#total_harga_sp').val(parseFloat(harga_jual));
					$('#total_harga_sp').mask("#,##0",{reverse: true});		
					$('#btn-simpan').removeClass('disabled-action');
						
					
				})
			}
		}
	})
}
function __getBarangSPx(jenis,kd_typemotor){
	var stok="";
		stok=$('#only_stock').is(":checked");
		stok=(stok==true)?true:'';
	var mode = ($('#only_stock').is(":checked"))?'':'1';
	var ajaxUrlp =http+"/part/parts4so/true/true/"+mode 
	var dat =(jenis=='Part')?{'kd_lokasi':$('#lokasi_jual').val(),'os':stok}:{'kd_typemotor':kd_typemotor,'jt':jenis,'os':stok};
    $('#part_number').inputpicker({
      	url:ajaxUrlp,
      	fields:['PART_NUMBER','PART_DESKRIPSI','STOCK','HET'],
      	fieldText:'PART_DESKRIPSI',
      	fieldValue:'PART_NUMBER',
      	filterOpen: true,
      	headShow:true,
      	pagination: true,
      	pageMode: '',
      	pageField: 'p',
      	pageLimitField: 'per_page',
      	limit: 15,
      	pageCurrent: 1,
      	urlDelay:1
    })
    $('#csp').html('');
    
    if(jenis=='Part'){
	    $('#part_number').on('change',function(){
	    	$('#csp').html("<i class='fa fa-spinner fa-spin'></i>");
	    	//console.log($(this).val());
	    	var jenis='Part';
	    	var part_number = $(this).val();
	    	$.getJSON(ajaxUrlp,{"part":part_number,'jt':jenis,'dt':true},function(result){
	    		console.log(result);
	    		if(result.status){
					$.each(result.message,function(e,dh){
						$('#nama_part').val(dh.PART_DESKRIPSI);
						$('#jumlah_order').val("1");
						$('#stock_oh').val(dh.STOCK)
						$('#jml_stock').val(dh.STOCK);
						var harga_jual=0;
						harga_jual =dh.HET
						$('#harga_sp').val(parseFloat(harga_jual));
						$('#harga_sp').mask("#,##0",{reverse: true});
						$('#jumlah_order').focus().select();
						$('#total_harga_sp').val(parseFloat(harga_jual));
						$('#total_harga_sp').mask("#,##0",{reverse: true});		
						$('#btn-simpan').removeClass('disabled-action');
						//$('#jml_stock').val(jml_oh);	
						if(jenis=='Part'){
							//__CheckStock(part_number,$('#lokasi_jual').val());
							__cekstock_dealerlain(dh.PART_NUMBER,$('#kd_dealer').val());
							__cekstock_md(dh.PART_NUMBER);
							__cekETA(dh.PART_NUMBER,$('#kd_dealer').val())
							__cekDiskon(dh.PART_NUMBER,dh.HET);
						}
						$('#csp').html('');
					})
				}else{
					$('#jumlah_order').val("1").focus().select();
					if($('#jenis_penjualan').val()=='Part'){
						$('#harga_sp').val('');
						$('#total_harga_sp').val('');
					}
					$('#csp').html('');
				}
			})
	    })
	}
}
function __CheckStock(part_number,jenis){
	var stock=0;
	$.getJSON(http+"/part/stockoverview/true/true",{'kd_gudang':jenis,'part_number':part_number},function(result){
		if(result.length>0){
			$.each(result,function(e,d){
				stock += parseFloat(d.JUMLAH_TOTAL);
			})
		}
		$('#jml_stock').val(stock);
		$('#stock_oh').val(stock);
		
	})
}
function add_item(){
	var jml_order=$('#jumlah_order').val();
	var jml_stock=$('#jml_stock').val();
	/*console.log(parseInt(jml_stock)-parseInt(jml_order));
	console.log(parseInt(jml_stock))*/
	if((parseInt(jml_stock)-parseInt(jml_order))<0){
		var alertInfo=($('#jenis_penjualan').val()=='Part')?"\nClik Stock Dealer  Lain atau Stock MD untuk cek ketersediaan nya":"";
		alert("Stock Tidak mencukupi \nStock Yang tersedia : "+jml_stock+alertInfo);
		if($('#jenis_penjualan').val()=='Part'){
			if(confirm("Apakah part ini akan di booking?")){
				
				$('#as_booking').click();//.attr('checked',true);
				//$('#auto_po').removeClass("disabled-action");
			}else{
				return;
			}
		}else{
			return;
		}
	}
	if(jml_order==''){return false;}
	var jml =$('#jumlah_order').val();
	var harga =$('#harga_sp').cleanVal();
	var diskon =$('#diskon').val();
		diskon = ((parseFloat(jml)*parseFloat(harga))*parseFloat(diskon)/100);
	var tharga=(parseFloat(jml_order)*parseFloat(harga))-parseFloat(diskon);
	$('#part_number,#nama_part,#jumlah_order').attr('required')
	bariske = $("#listpo >tbody > tr").length;
	html  ="<tr><td class='text-center' valign='middle'>"+(bariske+1)+"</td>";
	html +="<td class='text-center table-nowarp' valign='middle'><span class='pull-left' style='margin-right:10px'><a class='pull-right' onclick=\"__hapus('" + bariske + "');\"><i class='fa fa-trash'></i></a></span>";
	html +=$('#part_number').val()+"</td>";
	html +="<td valign='middle' style='padding-right:5px' class='td-overflow-50'>"+$('#nama_part').val()+"</td>";
	html +="<td class='text-right'>"+jml+"</td>";
	html +="<td valign='middle' class='text-right angka'>"+$.number(harga)+"</td>";
	html +="<td valign='middle' class='text-right angka'>"+$.number(diskon)+"</td>";
	html +="<td valign='middle' class='text-right total angka'>"+($.number(tharga))+"</td>";
    html +="<td class='text-right hidden'>"+$('#jml_stock').val()+"</td>";
    html +="<td class='text-left table-nowarp' valign='middle'><span class='eta_"+(bariske)+"'>"+$('#eta').html()+"</span></td>";
    html +="<td class='text-right hidden'>0</td>";
    html +="<td class='text-right hidden'>Y</td>";
    html +="</tr>";
    var total=tharga;//(parseFloat($('#jumlah_order').val())*parseFloat($('#harga_sp').cleanVal()))-parseFloat($('#diskon').val());
    var jml=jml_order;//parseFloat($('#jumlah_order').val());
	$('table#listpo tbody').append(html);
	$('#part_number,#nama_part,#jumlah_order').val('');
	$('#harga_sp,#diskon').val('0');
	$('#part_number').focus().select();
    $('#btn-simpan').addClass("disabled-action");
    __totalHarga();//$("#listpo >tfoot > tr td:eq(5)").html().replace(",","");
    /*var	total =	//total =(isNaN(totals))?total:(parseFloat(totals)+total)
    $("#listpo >tfoot > tr td:eq(5)").html($.number(total));
    var j_item=$("#listpo >tfoot > tr td:eq(2)").html().replace(",","");
    	jml =(isNaN(j_item))?jml:(parseFloat(j_item)+parseFloat(jml))
    $("#listpo >tfoot > tr td:eq(2)").html($.number(jml));*/
    $('#simpan-data').removeClass("disabled-action");
    if($('#as_booking').is(":checked")){
	    $('#nama_konsumen').focus().select();
    	
    }

    $('#inputpicker-2').text('').focus().select();
}
function __totalHarga(){
	var sum=0;
	$("table#listpo tbody tr td.total").each(function() {
	    var value = $(this).text().replace(/,/g,'');
	    // add only if the value is number
	    if(!isNaN(value) && value.length != 0) {
	        sum += parseFloat(value);
	    }
	});
	var	total =sum;
    $("#listpo >tfoot > tr td:eq(5)").html($.number(total));
    var j_item=$("#listpo >tfoot > tr td:eq(2)").html().replace(",","");
    	jml =(isNaN(j_item))?jml:(parseFloat(j_item)+parseFloat(jml))
    $("#listpo >tfoot > tr td:eq(2)").html($.number(jml));
}
function __cekstock_dealerlain(part_number,kd_dealer){
	var stock=0;
	$.getJSON(http+"/inventori/list_sp_w_stock/true/"+kd_dealer+"/",{'part_number':part_number,'os':'1'},function(result){
		var option="";
		if(result.length>0){
			$.each(result,function(e,d){
				stock += parseFloat(d.JUMLAH_SAK);
				option +="<option value='"+d.KD_DEALER+"'>"+d.NAMA_DEALER+" ("+$.number(d.JUMLAH_SAK)+")</option>";
			})
		}
		var option1="<option value='0'>Stock Di Dealer Lain ("+$.number(stock)+")</option>";
		
		$('#oth_dlr').html(option1);
		$('#oth_dlr').append(option);
		console.log(result);
	})
}
function __cekstock_md(part_number){
	var stock=0;
	$.getJSON(webapi+"login/webservice",{'link':'list32','param':part_number},function(result){
		if(result.length>0){
			var d=$.parseJSON(result);
			if( typeof d[0] !='undefined'){

				console.log(d[0].qtymd);
				stock =parseFloat(d[0].qtymd);
			}else{
				console.log(result);
			}
		}

		$("#md").html($.number(stock));
	})
}

function __cekETA(part_number,kd_dealer){
	var fast=0; var slow=0;
	$.getJSON(webapi+"inventori/part_eta",{'kd_dealer':kd_dealer,'part_number':part_number},function(result){
		if(result.totaldata>0){
			$.each(result.message,function(e,d){
				fast=d.ETA_DEALER
				slow=d.ETA_DEALER2
			})
		}
		var nDate= new Date();
		nDate.setDate(nDate.getDate()+fast)
		var nDatex = new Date();nDatex.setDate(nDatex.getDate()+slow)
		$('#eta').html("<b>"+$.datepicker.formatDate('dd/mm/yy',nDate)+"</b> s/d <b>"+$.datepicker.formatDate('dd/mm/yy',nDatex)+"</b>");
	})
}

function __caridata(){
	var modal_id = $("#modal-button-3").attr('data-target');
	var url = http+"/customer/cs_h2";
	
	$.getJSON(url,{'keyword':$('#nama_konsumen').val()}, function(data, status) {
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
function __getCustomer(){
	var datax=[];
	$.getJSON(http+"/customer/customer_typeahead/"+$('#kd_dealer').val()+"/1/",{'id':''},function(result){
		if(result.length>0){
			$.each(result,function(e,d){
				datax.push({
					'text' : d.NAMA_CUSTOMER,
					'value': d.KD_CUSTOMER,
					'KODE' : d.KD_CUSTOMER,
					'NAMA': d.NAMA_CUSTOMER,
					'ALAMAT': d.ALAMAT_SURAT +","+d.NAMA_DESA+","+d.NAMA_KECAMATAN,
					'Kabupaten':d.NAMA_KABUPATEN,
					'HP':d.NO_HP
				})
			})
		}
		$('#nama_konsumen').inputpicker({
			data: datax,
			fields :['KODE','NAMA','ALAMAT'],
			fieldText:'text',
			fieldValue:'value',
			filterOpen:true,
			headShow:true,
			selectMode:'creatable'
			
		}).on("change",function(e){
			e.preventDefault();
			/*var dx=datax.findIndex(obj => obj['value'] === $(this).val());
			if(dx>-1){
				$('#alamat_konsumen').val(datax[dx]["ALAMAT"]);
				$('#kota_konsumen').val(datax[dx]["Kabupaten"]);
				$('#no_telp').val(datax[dx]['HP']);
				$('#kd_customer').val(datax[dx]["KD_CUSTOMER"]);
			}*/
			__getcustomerdetail($(this).val())
		})
		$('#alamat_konsumen').focus(function(){
			var kd_cus=$("#kd_customer").val();
			if(kd_cus==''){
				//__getKabupaten();
			}
		})
	})
	
}
function __getKabupaten(){
	var kab=[]
	$.getJSON(http+"/company/kabupaten/1",{'p':"1"},function(result){
		if(result.length > 0){
			$.each(result,function(e,d){
				kab.push({
					'value' : d.KD_KABUPATEN,
					'text'	: d.NAMA_KABUPATEN,
					'description': d.NAMA_PROPINSI
				})
			})
		}
		$('#kota_konsumen').inputpicker({
			data : kab,
			fields :['value','text','description'],
			fieldText : 'text',
			fieldValue : 'value',
			filterOpen : true,
		})
	})
}
function __geTypeMotor(){
	var motor=[]
	$.getJSON(http+"/sparepart/sparepart_vsmotor/true/true",{'otm':true,'aktif':true},function(result){
		if(result.length > 0){
			$.each(result,function(e,d){
				motor.push({
					'value' : d.KD_TYPEMOTOR,
					'text'	: d.NAMA_TYPEMOTOR,
					'NAMATYPE':d.NAMA_TYPEMOTOR,
					'KODE':d.KD_TYPEMOTOR,
					'GROUP':d.NAMA_GROUPMOTOR
				})
			})
		}
		$('#kd_typemotor').inputpicker({
			data : motor,
			fields :['KODE','NAMATYPE','GROUP'],
			fieldText : 'value',
			fieldValue : 'value',
			filterOpen : true,
			headShow:true
		}).on("change",function(){
			$("#thn_motor").focus();
			var dx=motor.findIndex(obj => obj['value'] === $(this).val());
			if(dx>-1){
				var kdm=motor[dx]["value"];
				//__getBarangSP('Part',kdm);
			}
		})
	})
}
function __hapus(bariske){
	if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) 
    } else {
        bariske = bariske;
    }
    $("#listpo >tbody > tr:eq(" + bariske + ")").remove();
    var jml_row = $("#listpo >tbody > tr").length;
    if(jml_row==0){ $('#simpan-data').addClass("disabled-action")};
    __totalHarga();
}
function __cekDiskon(part_number,harga){
	var tipe_diskon=0;
	var jml_diskon=0; var dapat_diskon=0;
	$.getJSON(http+"/setup/diskonpart/1",{'part_number':part_number,'tp_cus':$('#jenis_customer').val()},function(result){
		if(result.length>0){
			$.each(result,function(e,d){
				tipe_diskon=d.TIPE_DISKON;
				jml_diskon = d.AMOUNT
			})
		}else{
			dapat_diskon=0;
		}
		if(tipe_diskon==0){
			dapat_diskon = jml_diskon;//(parseFloat(harga)*(parseFloat(jml_diskon)/100));
			//dapat_diskon =(parseFloat(harga)-dapat_diskon);
		}else{
			dapat_diskon =(parseFloat(jml_diskon)/(parseFloat(harga)*100))//jml_diskon;
		}
		$('#diskon').val(dapat_diskon);
	})
}
function __simpanData(){
	if(!$('#frm_so').valid()){return};
	var langsung_po=0;
	var stok_dln =$('#oth_dlr option:selected').text().split("(");
		stok_dln =(stok_dln.length>1)?stok_dln[1].replace(")",""):"0";
	var stok_md =($('#md').html().replace(/,/g,""));
	var mode_edit=$('#no_transaksi').val();
	if(!mode_edit){
		if((stok_md > 0 && $('#on_md').is(":checked")==false)||
			(stok_dln > 0 && $('#oth_dlr option:selected').index()==0)){
			if($('#as_booking').is(":checked")){
				if(confirm("Part ini tersedia di dealer lain atau di Main Dealer\nApakah order dari mereka atau langsung hotline ke AHM?\nTekan OK jika order ke Delaer Lain atau MD\nCancel Jika langsung Hotline ke AHM" )){
					return;
				}else{
					if(confirm("Apakah akan langsung membuat PO Hotline untuk Order booking ini")){
						$('#auto_po').attr("checked",true);
						langsung_po=1;
					}else{
						$('#auto_po').attr("checked",false);
						langsung_po=0;
					}
				}
			}
		}
	}
	var urls=http+"/cashier/simpan_so/"+langsung_po;
	var datax =__simpanDetail();
	
	if(datax.length >0 || mode_edit.length >0){
		$('#loadpage').removeClass("hidden");
		$.ajax({
			type :'post',
			url : urls,
			dataType :'html',
			data: $('#frm_so').serialize()+"&dt="+JSON.stringify(datax),
			success:function(result){
				
				if(result){
					$('.success').animate({ top: "0"}, 500);
		            $('.success').html('Data berhasil di simpan').fadeIn();
		            setTimeout(function() {
		            	document.location.href=http+"/"+result; 
		            }, 2000);
				}else{
					$('.error').animate({ top: "0"}, 500);
		            $('.error').html('Data gagal di simpan').fadeIn();
		            setTimeout(function() {
						hideAllMessages();
			        }, 2000);
				}
				
			}
		})
		
	}else{
		$('.error').animate({ top: "0"}, 500);
        $('.error').html('Tidak ada data yang disimpan').fadeIn();
		
		setTimeout(function() {
			hideAllMessages();
        }, 2000);
	}
}

function __simpanDetail(){
	var bariskex=0;
	bariskex = $('#listpo > tbody > tr').length;
	var dataxx=[];
	for(iz=0;iz< bariskex;iz++){
		var sts_baru= $("#listpo > tbody > tr:eq(" + iz + ") td:eq(10)").text();
		if(sts_baru=='Y'){
			dataxx.push({
				'part_number': $.trim($("#listpo > tbody > tr:eq(" + iz + ") td:eq(1)").text()),
				'jumlah_order' : $("#listpo > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
				'harga_jual'  : $("#listpo > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
				'stock_awal': $("#listpo > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
				'diskon'	: $("#listpo > tbody > tr:eq(" + iz + ") td:eq(5)").text(),
				'eta'	: $('.eta_'+iz).html(),
				'picking': $("#listpo > tbody > tr:eq(" + iz + ") td:eq(9)").text()
			})
		}
	}
	
	return dataxx;
}

function loadData(id, value, select) {
	var r=$.Deferred();
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

function __getcustomerdetail(kd_sales) {
    var kd_cus=kd_sales.split(':');

    $.ajax({
        type: 'POST',
        url:(kd_cus[1]=='KD')? http+'/customer/customerdetail/1':http+"/customer/cs_h2/true",
        dataType: 'json',
        data:{'kd_customer':kd_cus[0]},
        success: function (result) {          
            console.log(result);
            if (result.status == true){
                $.each(result.message, function (index, d) {
                    $("#nama_konsumen").val(d.NAMA_CUSTOMER);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#alamat_konsumen').val(d.ALAMAT_SURAT);
                    if(kd_cus[1]=="KD"){
	                    $('#kd_propinsi').val(d.KD_PROPINSI).select();
	                    loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KOTA)
	                    loadData('kd_kecamatan',d.KD_KOTA,d.KD_KECAMATAN)
	                    loadData('kd_desa',d.KD_KECAMATAN,d.KELURAHAN);
	                    $('#kd_pos').val(d.KODE_POS);
	                }else{
	                	$('#no_pol').val(d.NO_POLISI);
	                	$('#kd_typemotor').val(d.KD_TYPEMOTOR);
	                	$('#thn_motor').val(d.THN_PERAKITAN)
	                }
                    $('#no_telp').val(d.NO_HP);
                    
                })
            }
        }
    })
}
function __hapusItem(id){
	if(confirm("Yakin item ini akan dihapus?")){
		$.ajax({
            type :'POST',
            url  : http+"/cashier/hapus_sod",
            data : {'id':id},
            dataType :'json',
            success:function(result){
                result_message(result);
            }
        })
	}
}
function __getKodePost(id){
	$.getJSON(http+"/company/desa/true",{'kd_desa':id},function(result){
		if(result.length>0){
			$.each(result,function(e,d){
				$('#kd_pos').val(d.KODE_POS);
			})
		}
	})
    
}