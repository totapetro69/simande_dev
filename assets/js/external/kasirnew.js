// javascript document for kasirnew
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
	$('#tp_transaksi').on('change',function(e){
		document.location.href="?tp="+$(this).val();
	})
	__loadPilihan();
	$('#jenis_transaksi').on('change',function(e){
		//e.preventDefault();
		var id=$(this).val().replace(' ','_');
		$('#'+id).removeClass('hidden');
		switch($(this).val()){
			case "Penjualan Unit":
				$('#cbayar1').attr("checked",true);
				$('#unit').removeClass("hidden");
				$('#barang').addClass("hidden");
				$('#barang input,textarea').empty();
				$("fieldset:not(#unit,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_SPK.NO_SPK");
				$('#modal-button').removeClass("hidden");
				$('#modal-button-1').addClass("hidden");
				$('#uraian_1').attr("required",true);
				__getSPK('');
				__getKDAkun('');
				$('#smp').addClass("disabled-action");
				break;
			case "Penerimaan Barang":
				$('#cbayar1').attr("checked",true);
				$('#barang').removeClass("hidden");
				$('#unit').addClass("hidden");
				$('#unit input,textarea').empty();
				$("fieldset:not(#barang,#noncash,#kwt)").addClass("hidden");
				$('#source').val("Penerimaan Barang")
				$('#modal-button').removeClass("hidden");
				$('#modal-button-1').addClass("hidden");
				$('#uraian_1').attr("required",false);
				__getKDAkun("Penerimaan Barang");
				__getBarang();
				$('#smp').removeClass("disabled-action");
				break;
			case "Penerimaan Umum":
				$('#cbayar1').attr("checked",true);
				$('#umum').removeClass("hidden");
				$("fieldset:not(#umum,#noncash,#kwt)").addClass("hidden");
				$('#source').val("Penerimaan Umum")
				$('#modal-button').removeClass("hidden");
				$('#modal-button-1').addClass("hidden");
				$('#uraian_1').attr("required",false);
				$('#nf').html("No.Reff");
				$('#ket_reff_u').val('').removeClass("disabled-action");
				__getKDAkun($(this).val());
				//$('#smp').removeClass("disabled-action");
				$('#kd_akun').attr('required',true);
				$('#btlspk').addClass("hidden");
				break;
			case "Pengeluaran Umum":
			case "Biaya Hadiah":
				$('#cbayar1').attr("checked",true);
				$('#umum').removeClass("hidden");
				$("fieldset:not(#umum,#noncash,#kwt)").addClass("hidden");
				$('#source').val("Pengeluaran Umum")
				$('#modal-button').removeClass("hidden");
				$('#modal-button-1').addClass("hidden");
				$('#uraian_1').attr("required",false);
				$('#nf').html("Diberikan Kepada");
				$('#ket_reff_u').val($("#nama_dealer").val()).addClass("disabled-action");				
				__getKDAkun($(this).val());
				//make required
				if($(this).val()=='Pengeluaran Umum'){
					$('#btlspk').removeClass("hidden");
				}
				$('#kd_akun').attr('required',true);
				$('#no_reff_u').attr('required',true);
				$('#uraian_u').attr('required',true);
				$('#jumlah_u').attr('required',true);
				$('#smp').removeClass("disabled-action");
				break;
			case "Penjualan Sparepart":
			case "Service":
				$('#ldgsp').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
				$('#cbayar1').attr("checked",true);
				$('#sparepart').removeClass("hidden");
				$("fieldset:not(#sparepart,#noncash,#kwt)").addClass("hidden");
				$('#source').val("Penjualan Sparepart")
				//hapus footer data di list lst_sp
				$('table#lst_sp > tbody').html('');
				var tfot ='<tr class="subtotal"><td>&nbsp;</td><td>&nbsp;</td><td id="service_reff"></td>';
					tfot +='<td class="text-right" id="jml_total"></td><td class="text-right" id="harga_total"></td>';
					tfot +='<td class="text-right" id="grand_total"></td></tr>';
				$('table#lst_sp > tfoot').html(tfot);
				$('#service_reff').html("");
				$('#jml_total').html('0');//$.number(jml));
				$('#harga_total').html('0');
				$('#grand_total').html('0');

				$('#modal-button-1').removeClass("hidden");
				$('#modal-button-1').addClass("disabled-action");
				$('#modal-button').addClass("hidden");
				$('#uraian_1').attr("required",false);
				$('div.spsp input').val('');				
				//__getKDAkun("Penerimaan");
				$('#kd_akun').addClass('disabled-action');
				__getBarangSP('');
				$('#smp').removeClass("disabled-action");
				//$('#unit').removeClass("hidden");
				break;
			case "Fee Penjualan":
			case "Fee Sales":
				$('#cbayar1').attr("checked",true);
				$('fieldset#fee').removeClass("hidden");
				$("fieldset:not(#fee,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_SPK.NO_TRANS");
				__getKDAkun('');
				$('#smp').removeClass("disabled-action");
				break;
			case "Pengembalian Pinjaman":
				$('#cbayar1').attr("checked",true);
				$('fieldset#pengembalian').removeClass("hidden");
				$("fieldset:not(#pengembalian,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_STNK.NO_TRANS");
				$('#kd_akun').addClass('disabled-action');
				__getPengurus('b');
				__getKDAkun('Penerimaan');
				break;
			case "Pinjaman":
				$('#cbayar1').attr("checked",true);
				$('fieldset#pinjaman').removeClass("hidden");
				$("fieldset:not(#pinjaman,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_STNK.NO_TRANS");
				$('#kd_akun').addClass('disabled-action');
				//$('#kd_akun').focus();
				__lock();
				__getPengurus('');
				__getKDAkun('Pengeluaran');
				$('#smp').removeClass("disabled-action");
				break;
			case "Pengeluaran Barang":
			case "Penjualan Apparel":
			case "Penjualan Aksesoris":
				$('#cbayar1').attr("checked",true);
				$('#sparepart').removeClass("hidden");
				$("fieldset:not(#sparepart,#noncash,#kwt)").addClass("hidden");
				$('#source').val($(this).val())
				$('#modal-button-1').removeClass("hidden");
				$('#modal-button-1').addClass("disabled-action");
				$('#modal-button').addClass("hidden");
				$('#uraian_1').attr("required",false);
				$('div.spsp input').val('');
				__getKDAkun("Penerimaan");
				//__getBarangSP('barang');
				__getSOP('Barang');
				$('#smp').removeClass("disabled-action");
				break;
			case "Nilai SS":
				$('#cbayar1').attr("checked",true);
				$('fieldset#loadss').removeClass("hidden");
				$("fieldset:not(#loadss,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_STNK.NO_TRANS");
				//$('#kd_akun').addClass('disabled-action');
				__getKDAkun('');
				__loadPengurus();
			break;
			case "Titipan Uang":
				$('#cbayar1').attr("checked",true);
				$('fieldset#titipuang').removeClass("hidden");
				$("fieldset:not(#titipuang,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_SPK.NO_SPK");
				$('#kd_akun').addClass('disabled-action');
				__getSPK('_tp');
				__getKDAkun('');
				$('#smp').addClass("disabled-action");
			break;
			case "Pinjaman Sementara":
				$('#cbayar1').attr("checked",true);
				$('fieldset#pjmsmtr').removeClass("hidden");
				$("fieldset:not(#pjmsmtr,#noncash,#kwt)").addClass("hidden");
				$('#source').val("TRANS_JOINPROMO.NO_TRANS");
				$('#kd_akun').addClass('disabled-action');
				__getProposal();
				__getKDAkun('');
				getPICPinjaman();
				$('#smp').addClass("disabled-action");
			break;
			default:
				$('#unit').addClass("hidden");
				$('#unit input,textarea').empty();
				$('#barang').addClass("hidden");
				$('#barang input,textarea').empty();
				$("fieldset:not(#noncash,#kwt)").addClass("hidden");
				$('#modal-button').addClass("hidden");
				$('#modal-button-1').addClass("hidden");
				$('#uraian_1').attr("required",false);
				$('#smp').removeClass("disabled-action");
			break;
		}
		if($(this).val()=='Service'){
			$('#sp_item').addClass("hidden");
			$('#pkb').html('PKB');
			$('#kd_akun').attr("required",false);
			$('#no_reff_sp').attr("placeholder","");
			$(".sp").addClass("hidden");
			__getPKB();
		}else {
			$('#sp_item').addClass("hidden");
			$('#pkb').html('Sales Order');
			$('#kd_akun').attr("required",false);
			$('#no_reff_sp').inputpicker({data:[]});
			$('#ldgsp').html("");
			$(".sp").removeClass("hidden");
			if($(this).val()=='Penjualan Sparepart'){__getSOP('Part');}
		}
	})
	$('#inputpicker-wrapped-list').removeAttr('width');
	$('input:radio').on("change",function(){
		var pilih=($('input:radio:checked').val());
		switch(pilih){
			case 'Cash':
				$('#noncash').addClass("disabled-action");
				$('#noncash input').attr("required",false);
				$('#noncash input').val('');
				break;
			case 'KU':
				$('#noncash').removeClass("disabled-action");
				$('#noncash input').attr("required",true);
				$('#no_cek').attr("required",false);
				break;
			default:
				$('#noncash').removeClass("disabled-action");
				$('#noncash input').attr("required",true);
				break;
		}
	})
	//type ahead nama bank
	__getNamaBank();
	
	$("input[id^='harga_']").on('keypress',function(e){
		if(e.which==13){
			var baris=0;
			$(this).unmask();
			var sisa=0; var ttpan=0;
			var harga=$(this).val();
				ttpan = $('#jml_ttp').val().replace(/,/g,'');
			var id=$(this).attr('id').split('_');
			var jml=$('#jml_'+id[1]).val();
			if(parseInt(jml)==0 || jml==''){
				jml=1;
				$('#jml_'+id[1]).val('1');
			}
			var total=parseFloat(harga)+parseFloat(ttpan);
			$('#total_'+id[1]).val($.number(total))
			$('#bayar').focus();
			$('#totalbayar').html($.number(total));
			$('#terbilang').html(terbilang(total)+' Rupiah');
			if($('#kd_akun').val()==''){
				alert("Kode Account belum ditentukan");
				$('#kd_akun').focus();
			}
			var asli= $('#harga_awal').val();
			var bayar = $('#harga_1').val().replace(/,/g,'');
			console.log('asli:'+asli);
			console.log('bayar:'+bayar);
			console.log('titip:'+ttpan)
				sisa= parseFloat(asli) - parseFloat(bayar);
				console.log('sisa:'+sisa);
			$('#total_2').val(sisa);

			if(id[1]=="1" && $('#kd_akun').val()!=''){
				$('#smp').removeClass("disabled-action").addClass("btn-info");
				$('#harga_1').popover('hide');
				$('#harga_1').removeAttr("readonly");
				$('#harga_1').removeClass("disabled-action")
				$('#kd_akun').change();
				//proses sisa bayar

			}
		}else{
			// return false
		}
	}).focusout(function(){
		//$('#total_1').mask("#,##0", {reverse: true});
		$('#harga_1').mask("#,##0", {reverse: true});
	})
	$('#no_kwitansi').on("change",function(){
		if($(this).val().length>1){
			$('#modal-button').removeClass("disabled-action");
		}else{
			$('#modal-button').addClass("disabled-action");
		}
	})
	$('#jumlah_u').on("keypress",function(){
		$("#terbilang_u").html('');
		/*var trbl=terbilang(parseFloat($(this).val()));
		$("#terbilang_u").html(trbl);*/
	}).on("focusout",function(){
		if($('#kd_akun').val()==''){
			$('#kd_akun').focus().select();
			$('#smp').addClass("disabled-action");
		}else{
			$('#smp').removeClass("disabled-action");
		}
		var trbl=terbilang(parseFloat($(this).val().replace(/,/g,'')));
		$("#terbilang_u").html(trbl);
	})
	$('#jumlah_b')
		.on("keypress",function(e){
			if(e.which==13){
				e.preventDefault();
				console.log(e);
				$('#price_b').focus();
			}
		})
		.on("focusout",function(){
			if(!parseInt($(this).val())){
				$(this).val('1');

			}
		})
	$('#price_b')
		.on("keypress",function(e){
			if(e.which==13){
				e.preventDefault();
				console.log(e);
				$('#tprice_b').focus();
			}
		})
		.on("focusout",function(){
			var jml=$('#jumlah_b').val().replace(/,/g,"");
			//$('#price_b').unmask();
			var harga=$('#price_b').val().replace(/,/g,"");
			var jml_total =parseFloat(jml)*parseFloat(harga);
			$('#tprice_b').val(jml_total);
			console.log(jml +':'+harga+":"+jml_total);
		})
	$('#tprice_b')
		.on("keypress",function(e){
			if(e.which==13){
				e.preventDefault();
				$('#tprice_b').mask("#,##0",{reverse: true});
				__addItemBarang();
			}
		})	
	$('#inputpicker-wrapped-list').attr('style',"width:"+$('#nama_barang').attr('width')+"px");
	//autocomplete sparepart

	$('#jumlah_sp')
		.on("keypress",function(e){
			if(e.which==13){
				e.preventDefault();
				//$('#harga_sp').unmask()
				var harga=$('#harga_sp').val().replace(/,/g,"");;
				if(parseFloat(harga)>0){
					var total_harga_sp=parseFloat($('#jumlah_sp').val())*parseFloat(harga);
					$('#total_harga_sp').val(total_harga_sp);
					
					
				}
				$('#harga_sp').focus().select();
				$('#total_harga_sp').mask("#,##0",{reverse: true});
				$('#btn-add-sp').removeClass("disabled-action");
			}
		})
		.on("focusout",function(e){
			e.preventDefault();
			$(this).keypress();
		})
		.on("focus",function(){
			$('#btn-add-sp').addClass("disabled-action");
		})
	$('#harga_sp')
	.on("keypress",function(e){
		if(e.which==13){
			e.preventDefault();
			//$('#jumlah_sp').keypress();
			__addItemSP();
		}
	})
	.on("keyup",function(){
		var harga=$('#harga_sp').val().replace(/,/g,"");
		//var harga=$('#harga_sp').cleanVal();
		if(parseFloat(harga)>0){
			var total_harga_sp=parseFloat($('#jumlah_sp').val())*parseFloat(harga);
			$('#total_harga_sp').val(total_harga_sp);
		}
		$('#total_harga_sp').mask("#,##0",{reverse: true});
		$('#btn-add-sp').removeClass("disabled-action");
	})
	$('#jenis_fee').on("change",function(){
		__getPenerimaFee($(this).val());		
	})
	$('#jumlah_fe').on("keypress",function(e){
		if(e.which==13){
			e.preventDefault();
			__add_item_fee();
		}else{
			$('#addFee').removeClass("disabled-action");
		}
	})
	.on("focusout",function(e){e.preventDefault();$('#addFee').removeClass("disabled-action");})
	.on("focus",function(e){e.preventDefault();$('#addFee').removeClass("disabled-action");})
	$('#addFee').click(function(e){
		e.preventDefault();
		__add_item_fee();
	})
	$('#only_stock').click(function(e){
		__getBarangSP($('#jenis_transaksi').val());
	})
	$('#phtl').click(function(e){
		if($(this).is(':checked')==true){
			__getPOHotline();
		}else{
			$('#no_reff_sp').inputpicker({data :''})
		}
		
	})
	$('#kd_akun').on('change',function(){
		$('#smp').removeClass("disabled-action");
	})

})

/**
 * [__getKDAkun description]
 * @param  {[type]} jenisakun [description]
 * @return {[type]}           [description]
 */
function  __getKDAkun(jenisakun){
	$.getJSON(http+"/cashier/kodeakun/"+jenisakun,{'id':jenisakun},function(result){
		//console.log(result);
		$('#kd_akun').html('');
		var datax=[];
		$.each(result,function(index,d){
			datax.push({
				'value':d.KD_AKUN,
				'text':d.NAMA_AKUN,
				'KD AKUN':d.KD_AKUN,
				'NAMA AKUN':d.NAMA_AKUN
			})
		})
		$('#kd_akun').val('');
		$('#kd_akun').inputpicker({
			data:datax,
			fields:['KD AKUN','NAMA AKUN'],
			headShow:true,
			fieldText:'value',
			filterOpen:true
		}).change(function(e){
			e.preventDefault();
			var dx=datax.findIndex(obj => obj['value'] === $(this).val());
			//console.log(dx);
			if(dx>-1){
				$('#nama_akun').val(datax[dx]['NAMA AKUN']);
				
				switch($('#jenis_transaksi').val()){
					case'Penjualan Unit':
						$('#no_reff').focus().select();
						$('#smp').removeClass("disabled-action");
						$('#kd_akun').attr("required",true);
						break;
					case 'Penerimaan Barang':
						$('#no_reff_b').focus();
						break;
					default:
					$('#kd_akun').removeAttr("required");
					break;
				}
			}
		})
	})
}
function __getBarangSP(jenis){
	var stok="";
		stok=$('#only_stock').is(":checked");
		stok=(stok==true)?true:'';
	$.getJSON(http+"/inventori/list_sp_w_stock/true",{'jt':jenis,'os':stok},function(result){
		var datax=[];
		//var d=$.parseJSON(result);
		if(result.length>2){
			$.each(result,function(e,d){
				datax.push({
					'value'	:d.PART_NUMBER,
					'PART NUMBER' : d.PART_NUMBER,
					'DESKRIPSI'	: d.PART_DESKRIPSI ,
					'STOCK': d.JUMLAH_SAK,
					'text': d.PART_DESKRIPSI,
					'description':d.JUMLAH_SAK
				});
				
			})
			
		}else{
			$('#ldgsp').html("");
		}
		console.log(result);
		$('#nama_sp').inputpicker({data:[]});
		$('#nama_sp').inputpicker({
			data:datax,
			fields:['PART NUMBER','DESKRIPSI','STOCK'],
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
		    urlDelay:1,
		    //selectMode:'active'
		})
		.on("change",function(e){
			e.preventDefault();
			var part_number=$(this).val();
			//console.log(part_number);
			jenis=(jenis=="Penjualan Sparepart")?'':'barang';
			$.getJSON(http+"/sparepart/hargapart/true/"+jenis,{"part_number":part_number,'jt':jenis},function(result){
				if(result.length>0){
					$.each(result,function(e,d){
						$('#part_number').val(d.PART_DESKRIPSI);
						$('#jumlah_sp').val("1");
						var harga_jual=0;
						harga_jual =(typeof(d.HARGA_JUAL)!= "undefined" && d.HARGA_JUAL !== null)?d.HARGA_JUAL:d.HET
						$('#harga_sp').val(parseFloat(harga_jual));
						$('#harga_sp').mask("#,##0",{reverse: true});
						$('#jumlah_sp').focus().select();
						$('#total_harga_sp').val(parseFloat(harga_jual));
						$('#total_harga_sp').mask("#,##0",{reverse: true});				
					})
				}else{
					$('#jumlah_sp').val("1").focus().select();
					$('#harga_sp').val('');
					$('#total_harga_sp').val('');
				}
			})
		})
	})
}
function __getSPK(tp){
	$('#lgd').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
	$.getJSON(http+"/spk/spk_detail/true",{'id':''},function(result){
		var datax=[];
		if(result.length <=2){ $('#lgd').html("");}
		$.each(result,function(e,d){
			datax.push({
				'value'	:d.NO_SPK,
				'NO SPK' : d.NO_SPK,
				'CUSTOMER'	: stripslashes(d.NAMA_CUSTOMER +' [ '+d.ALAMAT_SURAT+' ]'),
				//'ITEM NAME': d.NAMA_ITEM +' [ '+d.TYPE_PENJUALAN+' ]',
				'text': d.TYPE_PENJUALAN,
				'description':stripslashes(d.NAMA_CUSTOMER)
			})
			$('#lgd').html("");
		})
		if(tp=='_tp'){
			//get sopart hotline order
			$.getJSON(http+"/cashier/uangmuka_sopart",function(result){
				if(result.totaldata >0){
					$.each(result.message,function(e,d){
						datax.push({
							'value' : d.NO_TRANS,
							'NO SPK': d.NO_TRANS,
							'CUSTOMER': stripslashes(d.NAMA_CUSTOMER +' [ '+d.ALAMAT_SURAT+' ] -> '+ 'Booking Part to '+d.ORDER_TO ),
							'text': 'CASH',
							'description':stripslashes(d.NAMA_CUSTOMER)
						})
					})
				}
			})
		}
		if(!datax){$('#no_reff'+tp).addClass("disabled-action");}else{$('#no_reff').removeClass("disabled-action");}
		$('#no_reff'+tp).inputpicker({
				data:datax,
				fields:['NO SPK','CUSTOMER'],
				headShow:true,
				fieldText:'value',
				filterOpen:true
			}).change(function(){
				var dx=datax.findIndex(obj => obj['value'] === $(this).val());
				//console.log(dx);
				if(dx>-1){
					$('#lgd').html("<i class='fa fa-spinner fa-spin'></i>");
					$('#ket_reff'+tp).val(stripslashes(datax[dx]['CUSTOMER']));
					$('#carabayar'+tp).val(datax[dx]['text']);
					$('#harga_1').popover('destroy');
					__totalTitipan($(this).val());
					// __getSPKDetail($(this).val());
					if(tp=='_tp'){
						__getTitipan($(this).val())
					}
					$('#harga_titipan').focus().select();
				}
			})
			$('#kd_akun').focus();
	})
}
 function __getSPKDetail(nospk){
 	$('#lgd').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
	$.getJSON(http+"/spk/spk_detail/true",{'nospk':nospk},function(result){
		if(result.totaldata ==0){ alert("SPK belum lengkap, Tidak bisa di proses di kasir sebelum di lengkapi");$('#lgd').html("");;return;}
		var datax=[];var n=0; var jml_titip=0;
		var text="";var harga=0;var jumlah=1;var total=0; var kd_item="";var no_spk=""; var$jenis="";
		var subsidi=0; var kurang_bayar=0;
		__loadSubsidi(result.message);
		$.each(result.message,function(e,d){
			subsidi = __getSubsidi(result);
			// jml_titip=__totalTitipan(nospk);
			var nama_customer = d.NAMA_CUSTOMER;
			n = n+1; 
			var spa="";
			spa=(n>1)?', ':'';
			kurang_bayar =parseFloat(d.KURANG_BAYAR);
			//console.log('kurang_bayar:'+d.TYPE_PENJUALAN);
			switch(d.TYPE_PENJUALAN){
				case 'CREDIT':
					text ="Pembayaran Uang Muka Penjualan "
					kd_item +=spa+d.KD_ITEM+" - "+d.NAMA_PASAR;
					harga +=(d.UANG_MUKA)?parseFloat(d.UANG_MUKA):0;
					jumlah +=d.JUMLAH
					console.log('hargane kredit ->'+harga);
				break;
				case 'CASH':
					text ="Penjualan ";
					//todo: tambahkan subsidi 
					kd_item +=spa+d.KD_ITEM+" - "+d.NAMA_PASAR;
					jumlah +=d.JUMLAH
					harga +=(kurang_bayar > 0)?(kurang_bayar):(parseFloat(d.HARGA_OTR)/*-parseFloat(subsidi)*/);
					console.log('hargane cash ->'+harga);
				break;
			}
			/**
			*	
			*/
			//console.log('diskonasli: '+parseFloat(d.DISKON));
			total =(parseFloat(d.DISKON)>0 || d.TYPE_PENJUALAN=='CREDIT')? harga : (harga - subsidi);
			/*console.log('harga asli :'+(parseFloat(d.HARGA_OTR)+parseFloat(subsidi)));
			console.log('subsidi :'+  subsidi);
			console.log('total harga :'+total);
			console.log('kurang :'+kurang_bayar);*/
			var group_motor =(d.GROUP_MOTOR)?d.GROUP_MOTOR:" ";
			var titipan =0;
				titipan = $('#jml_ttp').val().replace(/,/g,'');
			harga = (parseFloat(titipan)>0)?(total - parseFloat(titipan)):total;
			total = harga + parseFloat(titipan);
			console.log('seharusnya :'+total);
			$('#uraian_1').val(text+" "+n+" Unit Motor "+group_motor+" ["+kd_item+"] \nNo.SPK:"+nospk+" a/n "+nama_customer);
			$('#jml_1').val('1');
			$('#harga_1').val(harga).focus().select();
			$('#harga_1').popover('show');
			$('#harga_1').removeAttr('readonly');
			$('#harga_1').removeClass('disabled-action');
			$('#total_1').val(total).attr('readonly',true).mask("#,##0", {reverse: true});
			$('#smp').addClass("disabled-action");
			$('#harga_awal').val(harga);
			__informasiTambahan(d.SPK_ID);
			if(d.KD_AKUN){
				$('#kd_akun').val(d.KD_AKUN);
			}else{
				//if(d.GROUP_MOTOR){
					$.getJSON(http+"/cashier/get_perkiraan_setup/kd_akun/true",{
						'kd_transaksi':'K014',
						'type_transaksi':'DPP',
						'cara_bayar': (d.GROUP_MOTOR)?d.GROUP_MOTOR:'OTH'
					},function(resulted){
						console.log(resulted);
						$('#kd_akun').val(resulted);
					})
				//}
			}

			$('#lgd').html("");
		})
	})
}
function __totalTitipan(nospk){
	var jml_titip=0;
	$.getJSON(http+"/cashier/titipan_uang/true",{'no_spk':nospk},function(result){
		if(result.status){
			$('#ttp').removeClass("hidden");
			$.each(result.message,function(e,d){
				$('#uraian_ttp').val(d.URAIAN_TITIPAN);
				$('#jml_ttp').val($.number(d.JUMLAH_TITIPAN));
				$('#t_jml_ttp').val($.number(d.JUMLAH_TITIPAN));
				jml_titip += parseFloat(d.JUMLAH_TITIPAN);
			})
		}else{
			$('#ttp').addClass("hidden");
			$('#uraian_ttp').val('');
			$('#jml_ttp').val('0');
			$('#t_jml_ttp').val('0');
		}
		//return jml_titip;
		__getSPKDetail(nospk);
	})
}
function __getTitipan(nospk){
	$.getJSON(http+"/cashier/titipan_uang",{'no_spk':nospk},function(result){
		var tr=""; n=0; var totaltipu=0;
		if(result.status){
			$.each(result.message,function(e,d){
				if(parseInt(d.STATUS_TITIPAN) >0){
					n++;
					tr +="<tr><td class='text-center'>"+n+"</td><td colspan='3'>"+d.URAIAN_TITIPAN+" ["+d.TGL_TRANS+"]</td>";
					tr +="<td class='text-right' style='padding-right:10px !important'>"+$.number(d.JUMLAH_TITIPAN)+"</td></tr>";
					totaltipu += parseFloat(d.JUMLAH_TITIPAN);
				}else{
					$('#harga_titipan').val($.number(d.JUMLAH_TITIPAN));
					$('#t_harga_titipan').val($.number(d.JUMLAH_TITIPAN));
					$('#jumlah_titipan').attr("readonly",true);
					$('#no_trans_tp').val(d.NO_TRANS);
				}
			})
			tr +="<tr class='total'><td colspan='4' class='text-right'>Total titipan sebelumnya</td> ";
			tr +="<td class='text-right' style='padding-right:10px !important'>"+$.number(totaltipu)+"</td></tr>";
		}
		var uraian=(nospk.substring(0,2)=='PK')?"Titipan Uang untuk pembayaran Unit Motor No.SPK: "+$('#no_reff_tp').val():
		"Titipan Uang Muka pembayaran booking part No. SO : "+$('#no_reff_tp').val();
		$('#uraian_titipan').val(uraian);
		if(nospk.substring(0,2)=='PK'){
			$('#harga_titipan').attr('readonly',true).trigger('change');
		}else{
			$('#harga_titipan').removeAttr('readonly');
		}
		$('#harga_titipan').mask("#,##0",{reverse: true});
		$('#lst_ttp > tfoot').html(tr);
	})
}
function __getSubsidi(result){
	var hasil=0;
	console.log(result);
	if(result.status){
		$.each(result.message,function(e,d){
			var diskon=parseFloat(d.DISKON);
			hasil=0;
			var sc_sd=0;
			switch(d.TYPE_PENJUALAN){
				case 'CASH':
					sc_sd = (parseFloat(diskon) > 0)?diskon:parseFloat(d.MIN_SC_SD);
					hasil = (parseFloat(d.SC_AHM)+parseFloat(d.SC_MD)+parseFloat(sc_sd));
				break;
				case 'CREDIT':
					sc_sd = (parseFloat(diskon) >0)?diskon :parseFloat(d.MIN_SK_SD);
					hasil = (parseFloat(d.SK_AHM)+parseFloat(d.SK_MD)+parseFloat(d.SK_FINANCE)+parseFloat(sc_sd));
				break;
				default: hasil=0;break
			}
			console.log('diskone : '+diskon);
			console.log('hargane : '+d.HARGA_OTR);
			$('#harga_1').popover({
				title:'Tekan enter atau klik untuk edit',
				content:'Harga OTR :'+$.number((parseFloat(d.HARGA_OTR)+parseFloat(diskon)))+'<br>Total Subsidi :'+$.number(hasil),
				html:true
			});
		})
		
	}
	console.log('subsidi->'+hasil);
	return hasil;
}
function __loadSubsidi(result){
	var html="";
	// $.getJSON(http+"/cashier/getSubsidi/"+no_spk+"/true/true",{'subsidi':subsidi},function(result){
		$.each(result,function(e,d){
			var subManual=parseFloat(d.DISKON);
			if(d.TYPE_PENJUALAN == 'CASH'){
				subManual = (subManual >0)?subManual:parseFloat(d.MIN_SC_SD);
				html +="<tr><td>&nbsp;</td><td> Subsidi AHM : "+ $.number(d.SC_AHM);
				html +="</td><td> Subsidi MD : "+ $.number(d.SC_MD);
				html +="</td><td> Subsidi DLR: "+ $.number(subManual);
				html +="</td></tr>";
			}else{
				subManual = (subManual >0)?subManual:parseFloat(d.MIN_SK_SD);
				html +="<tr><td>&nbsp;</td><td> Subsidi AHM : "+ $.number(d.SK_AHM);
				html +="</td><td> Subsidi MD : "+ $.number(d.SK_MD);
				html +="</td><td> Subsidi DLR: "+ $.number(subManual);
				html +="</td><td> Subsidi FIN: "+ $.number(d.SK_FINANCE);
				html +="</td></tr>";
			}
		})
		console.log(html);
		$('table#info_spx tbody').html(html);
	// })
}
function __simpan_transaksi(){
	if(!$('#frmKasir').valid()){return false;}
	
	var url=http+"/cashier/kwitansi";
	var datax =[];var detail=[]; var dataxp=[];
	switch($('#jenis_transaksi').val()){
		case "Penerimaan Barang":
			datax=[];//__simpan_barang();
			dataxp=__simpan_barang();
		break;
		case "Penjualan Sparepart":
		case "Penjualan Aksesoris":
		case "Penjualan Apparel":
		case "Service":
		case "Pengeluaran Barang":
			datax = __simpan_sp();
			dataxp = __simpan_sp_all();
		break;
		case "Pinjaman":
			dataxp = __simpan_pinjaman('');
			detail = __simpan_detailpinjaman();
			if(!__CekSaldoPinjaman()){ return;}
		//return;
		break;
		case "Pengembalian Pinjaman":
			datax = __simpan_pinjaman('b');
			dataxp= __simpan_pinjaman('b');
			detail = __simpan_detailpinjaman();
		//console.log(detail);return;
		break;
		case "Fee Sales":
		case "Fee Penjualan":
			datax = __simpanFee();
			dataxp = __simpanFee();
			if(!__CekSaldo()){ return;};
		break;
		case "Pengeluaran Umum":
		case "Biaya Hadiah":
			if($('#kd_akun').val()==''){
				alert("Kode Akun belum di tentukan");
				$('#kd_akun').focus();
				return;
			}
			if(!__CekSaldo()){ return;};
			//return;
		break;
		case "Nilai SS":
			dataxp = __simpanSS();
			if(!__CekSaldo()){ return;};
		break;
		case "Titipan Uang":
			dataxp = __simpanTitipan();
		break;
		case "Pinjaman Sementara":
			if(!__CekSaldo()){ return;};
			if($('#pic_reff_jp').val().length==0){ alert('PIC harus di isi'); return false;}
			dataxp = __simpan_pjs();
			break;
		default:
			datax=[];
			detail=[];
		break;
	}
	$('#loadpage').removeClass("hidden");
	/*console.log(dataxp);
	console.log(datax);
	return;*/
	$.ajax({
		type :'POST',
		url : url,
		data: $('#frmKasir').serialize()+"&dt="+JSON.stringify(datax)+"&pj="+JSON.stringify(detail)+"&xp="+JSON.stringify(dataxp),
		dataType:'json',
		success:function(result){
			//console.log(result);;
			if(result.status){
				$('.success').animate({ top: "0"}, 500);
	            $('.success').html('Data berhasil di simpan').fadeIn();
	            
		        setTimeout(function() {
		            document.location.href=result.location;
		        	$('#loadpage').addClass("hidden");
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
}
function __print_kwitansi(){	
	//ga jadi
}
function __getBarang(){
	$.getJSON(http+"/inventori/list_barang/true/",function(result){
		var datax=[];
		var text="";var harga=0;var jumlah=1;var total=0;
		$.each(result,function(e,d){
			datax.push({
				'value' :d.KD_BARANG,
				'KODE'	:d.KD_BARANG,
				'text'	:d.NAMA_BARANG,
				'NAMA BARANG':d.NAMA_BARANG,
				'description':d.KATEGORI,
				'KATEGORI':d.KATEGORI
			})

		})
		$('#nama_barang,#nama_barang_p').inputpicker({
			data:datax,
			fields:['KODE','NAMA BARANG','KATEGORI'],
			headShow:true,
			fieldText:'text',
			fieldValue:'text',
			filterOpen:true,
			width:"100%"
		}).on("change",function(e){
			var dx=datax.findIndex(obj => obj['text'] === $(this).val());
			$('#price_b').val('0');
			$('#tprice_b').val('0');
			if(parseInt(dx)>-1){
				$('#kd_barang').val(datax[dx]['value']);
				$('#jumlah_b').focus().select();
				//getharga barang
				var harga_barang=0;
				$.getJSON(http+"/sparepart/harga_belibarang/true",{'part_number':datax[dx]['value']},function(result){
					if(result.length >0){
						$.each(result,function(e,d){
							$('#price_b').val($.number(d.HARGA_BELI));
							harga_barang=d.HARGA_BELI
						})
						var jml=$('#jumlah_b').val();
						$('#tprice_b').val(parseFloat(jml)*parseFloat(harga_barang));
					}
				})

			}
		})
		$('#tprice_b').addClass("disabled-action");
	})
}
function __addItemBarang(){
	if($('#kd_akun').val()==''){
		$('#kd_akun').focus();
		$('#kda').addClass("required");
		return;
	}
	var bariske=$('#lst_barang > tbody > tr').length;
	var html="";
		html +="<tr><td class='text-center'>"+(bariske+1)+"</td>";
		html +="<td class='text-center'><a onclick=\"__hapus_item('"+bariske+"')\" role='button'><i class='fa fa-trash'></i></a></td>"; 
		html +="<td>"+$('#nama_barang').val()+"</td><td class='text-right'>"+$('#jumlah_b').val();
		html +="</td><td class='text-right'>"+$('#price_b').val()+"</td><td class='text-right'>"+$('#tprice_b').val();
		html +="</td><td class='hidden'>"+$('#kd_barang').val()+"</td><td class='hidden'>"+$('#kd_akun').val()+"</td>";
		html +="<td class='hidden'>"+$('#nama_akun').val()+"</td></tr>";
	$('#lst_barang > tbody').append(html);
	$('#barang input:not(.refbarang input)').val('');
	$('#kd_akun').val('');$('#nama_akun').val('')
	$('#inputpicker-6').val('');
	$('#kd_akun').focus().select();
}
function __addItemSP(){
	if($('#kd_akun').val()==''){$('#kd_akun').focus();return;}
	$('#jumlah_sp').keypress();
	var bariskes=0;
	var total_bayar=0;var total_beli=0;
	total_bayar =$("#jml_bayar").cleanVal();
	total_beli =($('#total_harga_sp').cleanVal());
	if(total_beli==0){ return false;}
	bariskes = $('#lst_sp > tbody > tr').length;
	var html="";
		html +="<tr><td class='text-center'>"+(bariskes+1)+"</td>";
		html +="<td class='text-center'><a onclick=\"__hapus_item_sp('"+bariskes+"')\" role='button'><i class='fa fa-trash'></i></a></td>"; 
		html +="<td>"+$('#nama_sp').val()+" - "+ $('#part_number').val()+"</td><td class='text-right'>"+$('#jumlah_sp').val();
		html +="</td><td class='text-right'>"+$('#harga_sp').val()+"</td><td class='text-right'>"+$('#total_harga_sp').val();
		html +="</td><td class='hidden'>"+$('#nama_sp').val()+"</td><td class='hidden'>"+$('#kd_akun').val()+"</td>";
		html +="<td class='hidden'>"+$('#nama_akun').val()+"</td></tr>";
	$('#lst_sp > tbody').append(html);
	total_bayar =(isNaN(total_bayar)||total_bayar=='')?0:total_bayar;
	total_bayar = parseFloat(total_beli) +parseFloat(total_bayar);
	$("#jml_bayar")
		.val($.number(total_bayar))
		.mask('#,##0',{reverse:true})
	$('#sparepart input:not(#jml_bayar)').val('');
	$('#kd_akun').focus();
	//$('#lst_sp > tfoot').removeClass("hidden");
}
function __simpan_barang(){
	var bariske=$('#lst_barang > tbody > tr').length;
	var datax=[];
	for(i=0;i< bariske;i++){
		datax.push({
			'no_urut': (i+1),
			'uraian_transaksi': $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(2)").text(),
			'jumlah' : $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(3)").text(),
			'harga'  : $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(4)").text().replace('/,/g',''),
			'total'	 : $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(5)").text().replace('/,/g',''),
			'saldo_awal': $('#saldo_awal').val(),
			'kd_akun'	: $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(7)").text(),
			'nama_akun' : $("#lst_barang > tbody > tr:eq(" + i + ") td:eq(8)").text()+":"+$("#lst_barang > tbody > tr:eq(" + i + ") td:eq(6)").text()
		})
	}
	console.log(datax);
	return datax;
}
function __simpan_sp(){
	var bariskex=0;
	bariskex = $('#lst_sp > tbody > tr').length;
	var dataxx=[]; var dataxp=[];
	for(iz=0;iz< bariskex;iz++){
		var jml=$("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(3)").text();
		dataxx.push({
			'no_urut': (iz+1),
			'uraian_transaksi': $("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(2)").text(),
			'jumlah' : (parseFloat(jml)>0)?jml:"1",
			'harga'  : $("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
			'saldo_awal': $('#saldo_awal').val(),
			'kd_akun'	: $("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
			'nama_akun' : $("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(8)").text()+":"+$("#lst_sp > tbody > tr:eq(" + iz + ") td:eq(6)").text()
		})
	}
	
	//console.log('jmlbaris: '+bariskex)
	//console.log(dataxx)
	return dataxx;
}
function __simpan_sp_all(){
	var dataxp=[];
	dataxp.push({
			'no_urut': '1',
			'uraian_transaksi': $("#lst_sp > tfoot > tr:eq(0) td:eq(2)").text(),
			'jumlah' : $("#lst_sp > tfoot > tr:eq(0) td:eq(3)").text(),
			'harga'  : $("#lst_sp > tfoot > tr:eq(0) td:eq(5)").text(),
			'saldo_awal': $('#saldo_awal').val(),
			'kd_akun'	: $("#lst_sp > tfoot > tr:eq(0) td:eq(7)").text(),
			'nama_akun' : $("#lst_sp > tfoot > tr:eq(0) td:eq(8)").text()+":"+$("#lst_sp > tfoot > tr:eq(0) td:eq(6)").text()
	});
	return dataxp;
}
function __hapus_item(bariske){
	if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) 
    } else {
        bariske = bariske;
    }
    $("#lst_barang >tbody > tr:eq(" + bariske + ")").remove();
    $("#lst_sp >tbody > tr:eq(" + bariske + ")").remove();
}
function __hapus_item_sp(bariske){
	if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) 
    } else {
        bariske = bariske;
    }
    var jumlah= $("#lst_sp > tbody > tr:eq(" + bariske + ") td:eq(4)").text().replace(/,/g,'');
    var total = $('#jml_bayar').val().replace(/,/g,'');
    $("#lst_sp >tbody > tr:eq(" + bariske + ")").remove();
    $('#jml_bayar').val($.number(parseFloat(total)-parseFloat(jumlah)));
}
function __hapus_item_b(id,baris){
	if(confirm("Yakin data ini akan di hapus?")){

		$.post(http+"/cashier/hapus_item",{'id':id},function(result){
			document.location.reload();
		})
	}
}
function __cancel_transaksi(notran,dari){
	if(confirm("Yakin semua transaksi ini akan di batalkan")){
		$.post(http+"/cashier/batal_trans/"+notran,{'notrans':notran},function(result){
			document.location.href=(dari=='1')?http+"/cashier/kasirnew":http+"/cashier/listkasir";
		})
	}
}
function __getPenerimaFee(fee){
	$.getJSON(http+"/spk/fee_penjualan/true",{'grp':fee},function(result){
		var datax=[];
		var nama="";
		switch(fee){ case 'MK':nama='FEE MAKELAR';break;case "GCS": nama='FEE SWASTA';break;case "GCD":nama='FEE DINAS';break;}
		$.each(result,function(e,d){
			datax.push({
				'value':d.KD_SALES,
				'text':(d.NAMA_SALES)//?d.NAMA_SALES:nama
			})
		})
		$('#nama_penerima').inputpicker({
			data 	: datax,
			fields 	:['value','text'],
			fieldValue :'value',
			fieldText  :'text',
			filterOpen :true
		}).on("change",function(e){
			switch(fee){
				case "MK":
					//get data spk untuk makelar terpilih
					var dx=datax.findIndex(obj => obj['value'] == $(this).val());
					//console.log(dx);
					if(dx >-1){
						var kd_sales=datax[dx]['value']
						$.getJSON(http+"/spk/fee_penjualan/true",{'grp':fee+" AND NAMA_SALES='"+kd_sales+"'"},function(dataz){
							var dataxx=[];
							console.log(dataz);
							$.each(data,function(e,c){
								dataxx.push({
									'value'	:c.NO_SPK,
									'text': c.NO_SPK,
									'description':c.NAMA_TYPEMOTOR+'-'+stripslashes(c.NAMA_CUSTOMER) +' [ '+c.NO_HP+' ]',
								})
								
							})
							$('#no_reff_fe').val('').focus();
							$('#no_reff_fe').inputpicker({
								data : dataxx,
								fields :['value','text','description'],
								fieldText :'value',
								fieldValue :'value',
								filterOpen :true
							}).on("change",function(input){
								var dxx=dataxx.findIndex(obj => obj['value'] == $(this).val());
								//console.log('dxx: '+dxx);
								if(dxx>-1){
									$('#ket_reff_fe').val(dataxx[dxx]['description']);
								}
								$("#jumlah_fe").focus().select();

							})
						})
					}
				break;
				default:
				break;
			}
		})
	})
}
function __add_item_fee(){
	if($('#kd_akun').val()==''){$('#kd_akun').focus();return;}
	var bariske=$('#list_fee > tbody > tr').length;
	var uraian_transaksi="";
	switch($('#jenis_fee').val()){
		case "MK":
			uraian_transaksi="Fee Penjualan "+($('#ket_reff_fe').val().split('-'))[0]+" SPK No.: "+$('#no_reff_fe').val() +' a/n '+($('#ket_reff_fe').val().split('-'))[1];
			break;
		default:
		uraian_transaksi="";
		break;
	}
	var html="";
		html +="<tr><td class='text-center'>"+(bariske+1)+"</td>";
		html +="<td class='text-center'><a onclick=\"__hapus_item('"+bariske+"')\" role='button'><i class='fa fa-trash'></i></a></td>"; 
		html +="<td>"+uraian_transaksi+"</td><td class='text-right'>1";
		html +="</td><td class='text-right'>"+$('#jumlah_fe').val()+"</td><td class='text-right'>"+$('#jumlah_fe').val();
		html +="</td><td class='hidden'>"+$('#jenis_fee').val()+"</td><td class='hidden'>"+$('#kd_akun').val()+"</td>";
		html +="<td class='hidden'>"+$('#nama_akun').val()+":"+$('#nama_penerima').val()+"</td><td class='hidden'>"+$('#no_reff_fe').val()+"</td></tr>";
	$('#list_fee > tbody').append(html);
	$('#fee input:not(.ajeg input)').val('');
	//$('#fee .ajeg input,select').addClass('disabled-action');
	$('#no_reff_fe').focus().select();
}
function __simpanFee(){
	var bariske=$('#list_fee > tbody > tr').length;
	var datax=[];
	for(i=0;i< bariske;i++){
		datax.push({
			'no_urut': (i+1),
			'uraian_transaksi': $("#list_fee > tbody > tr:eq(" + i + ") td:eq(2)").text(),
			'jumlah' : $("#list_fee > tbody > tr:eq(" + i + ") td:eq(3)").text(),
			'harga'  : $("#list_fee > tbody > tr:eq(" + i + ") td:eq(4)").text(),
			'saldo_awal': $('#saldo_awal').val(),
			'kd_akun'	: $("#list_fee > tbody > tr:eq(" + i + ") td:eq(7)").text(),
			'nama_akun' : $("#list_fee > tbody > tr:eq(" + i + ") td:eq(8)").text()+":"+$("#list_fee > tbody > tr:eq(" + i + ") td:eq(6)").text()+":"+$("#list_fee > tbody > tr:eq(" + i + ") td:eq(9)").text()
		})
	}
	return datax;
}
function __Simpan_Approval(no_transx){
	$.getJSON(http+"/cashier/simpan_approval",{'no_trans':no_transx},function(result){
		    if(result.status){
		    	$('.success').animate({ top: "0"}, 500);
	            $('.success').html('Transaksi menunggu approval, bisa lanjut transaksi berikut ya').fadeIn();
	            
		        setTimeout(function() {
		            document.location.href=http+"/cashier/kasirnew";
		        	$('#loadpage').addClass("hidden");
		            //jika data success . print kwitansi trus register
		           // __print_kwitansi();
		        }, 2000);    
			}else{
				$('.error').animate({ top: "0"}, 500);
	            $('.error').html('Data gagal di simpan').fadeIn();
	            setTimeout(function() {
		            hideAllMessages();
		        }, 2000);
			}
	});
}

function blinkElement(elm, interval, duration) {

    elm.style.visibility = (elm.style.visibility === "hidden" ? "visible" : "hidden");

    if (duration > 0) {
        setTimeout(blinkElement, interval, elm, interval, duration - interval);
    } else {
        elm.style.visibility = "visible";
    }
}
