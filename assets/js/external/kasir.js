//kasir javascript document
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
/**
 * [description]
 * @return {[type]}                                                                                                                                                                                                [description]
 */
$(document).ready(function(){
	
	$('#baru').click(function(){
		document.location.href=http+"/cashier/kasirnew";
	});
	var lkh=$('#sts_lkh').val();
	var notran=$('#no_trans').val();
	if(parseInt(lkh)>0 || notran==''){
		openclose('c');
	}
	$('#harga_titipan').on('keypress',function(e){
		if(e.which===13){
			$(this).focusout();
		}
	}).on("focusout",function(){
		var jml=$('#jumlah_titipan').val();
		var hrg = $(this).val().replace(/,/g,'');
		$('#t_harga_titipan').val($.number(parseInt(jml)*parseFloat(hrg)));
		$('#kd_akun').focus().select();
	})
})

function __grandtotal(id){
	var jml=0;
	for(i=0;i<5;i++){
		var isi=($('#tot_'+i).html().replace(/,/g,""));
		if(isi.length>0){
			jml = parseInt(jml)+parseInt(isi);
			//console.log(isi);
			//console.log(jml+'-'+id+'-'+isi+'-'+isi.length);
		}
		
	}
	//alert(jml);
	$('#grandtotal').html('<b>'+$.number(jml)+'</b>');
	$('#terbilang').html(terbilang(jml)+' Rupiah');
}
function openclose(status){
	var date= new Date();
	var param_urls="cashier/check_close_cash";
	param_urls=http+"/"+param_urls;
	var datax=$('#frmKasir').serialize();
	//alert(datax);
	$.ajax({
		type:'POST',
		url:param_urls,
		data:datax,
		dataType:'json',
		success:function(result){
			///console.log(status+':'+result.length);
			if(result.length>0){
				$.each(result,function(e,d){
					//console.log(d);
					if(d.CLOSE_DATE=='' || d.CLOSE_DATE==null){
						//var closing=;
						if (confirm('Transaksi Kemarin belum di close\nLakukan Closing transaksi sekarang')){
							$('#cls').removeClass('hidden');
							$('#opn').addClass('hidden');
							$(':not(#cls,#lsted,#lst_login)').addClass('disabled');
						}else{
							$('#cls').removeClass('hidden');
							$('#opn').addClass('hidden');
							$(':not(#baru,#lsted,#lst_login)').addClass('disabled');
							//return;
						}
					}
					$('#opendate').val(convertDate(d.OPEN_DATE));
					$('#closedate').val(convertDate(d.CLOSE_DATE));
				})
			}else{
				$('#cls').removeClass('hidden');
				$('#opn').addClass('hidden');
				$('btn :not(#cls,#lsted)').removeClass('disabled');
				$('#opendate').val('');
				$('#closedate').val('open');
				open_trans('o');
			}
			
		}
	})
	
}
function open_trans(status){

	var param_urls="cashier/check_open_cash";
	param_urls=http+"/"+param_urls;
	var datax=$('#frmKasir').serialize();
	$.ajax({
		type:'POST',
		url:param_urls,
		data:datax,
		dataType:'json',
		success:function(result){
			console.log(result);
			if(result.length==0){
				alert('Lakukan open transaksi dahulu sebelum melanjutkan\nClick tombol Open Trans');
				$('#opn').removeClass('hidden');
				$('#cls').addClass('hidden');
				$(':not(#opn,#lsted)').addClass('disabled');
				//return;
			}else{
				$.each(result,function(e,d){
					//alert(convertDate(d.OPEN_DATE)+'=='+convertDate(d.CLOSE_DATE));
					if(convertDate(d.OPEN_DATE)==convertDate(d.CLOSE_DATE)){
						alert("Transaksi Hari ini sudah di close, tidak bisa melakukan transaksi lagi")
						window.location.href=http+'/cashier/listkasir'

					}else{
						$('#opendate').val(convertDate(d.OPEN_DATE));
						$('#cls').removeClass('hidden');
						$('#opn').addClass('hidden');
						$('btn :not(#cls,#lsted)').removeClass('disabled');
						if(parseInt(d.REOPEN)==1){
							$("#tgl_trans").val(convertDate(d.OPEN_DATE))
							
						}
						$(".date").addClass('disabled-action');
						$('#reopen_status').val(d.REOPEN+":"+d.ID);
						var onclk=$('#modal-button-3').attr("url").replace(");",d.REOPEN+"x"+d.ID+"\");");
						$('#modal-button-3').attr("url",onclk);
					}
					
				});
			}
			
		}
	})
}
function __OpenTrans(status){
	$('#loadpage').removeClass("hidden");
	var urls=http+'/cashier/getSaldo/TRUE';
	var param_urls="cashier/open_cash";
	param_urls=http+"/"+param_urls;
	//dapatkan saldo akhir transaksi yng lalu
	$.ajax({
		type:'POST',
		url:urls,
		data:{'getsaldo':'1'},
		success:function(result){
			if(status=='o'){
				//console.log();
				//jika saldo awal belum ada maka input jumlah saldo awal di prompt message yang muncul
				if(result==0){
					var saldoawal =window.prompt("Masukan nilai Saldo Awal Kas Anda",'');
					if(saldoawal==null || saldoawal==''){
						alert("Transaksi tidak bisa dilakukan karena Saldo Awal Kas blm di input");
						$('#loadpage').addClass("hidden");
					}else{
						//simpan saldo awal saat open trans
						$.ajax({
							type:'POST',
							url:param_urls,
							data:{'saldo_awal':saldoawal},
							success:function(result){
								document.location.reload();
							}
						})
					}
				}else{
					$.ajax({
							type:'POST',
							url:param_urls,
							data:{'saldo_awal':$('#saldoawal').val()},
							success:function(result){
								document.location.reload();
							}
						})
				}
			}
				
		}
	})
}

function __closedTrans(id){
	if(confirm("Transaksi hari ini akan di close?")){
		$('#loadpage').removeClass("hidden");

		var param_urls=http+"/cashier/close_cash";
		$.ajax({
			type:'POST',
			url:param_urls,
			data:{'saldo_awal':$('#saldoawal').val(),'open_date':$('#opendate').val()},
			success:function(result){
				//console.log(result);
				$('#closedate').val(result);
				document.location.reload();
				$('#loadpage').addClass("hidden");
			}
		})
	}
}
function __nomorator_kwt(){
	var datax=[];
	$.getJSON(http+"/cashier/get_nomorator/true",function(result){
		if(result.length>0){
			$.each(result,function(e,d){
				for(i=d.LAST_DOCNO;i<=d.TO_DOCNO;i++){
					datax.push({
						'value': pad_left(i.toString(),'0',6),
						'text' : pad_left(i.toString(),'0',6)
					})
				}
			})
			$("#tp_transaksi").removeClass("disabled-action");
		}else{
			// if(datax.length==0){
				alert("Anda belum melakukan setting nomorator kwitansi\nSilahkan setting terlebih dahulu sebelum melakukan transaksi!");
					$("#tp_transaksi").addClass("disabled-action");
					return false;
			// }
		}
		$('#no_kwitansi').inputpicker({
			data : datax,
			fields :['value'],
			fieldValue :'value',
			fieldText:'text',
			filterOpen :true
		})
	})
	//console.log(datax);
	
}

function __CekSaldo(){
	var saldoawal=$('#saldo_awal').val();
	var jmlkeluar=$('#jumlah_u').cleanVal();
	if((parseFloat(saldoawal)-parseFloat(jmlkeluar))< 0){
		alert("Saldo Tidak mencukupi untuk transaksi ini");
		return;
	}else{
		return true;
	}
}
function __CekSaldoPinjaman(){	
	var saldoawal=$('#saldo_awal').val();
	var jmlkeluar=$('#jumlah_p').cleanVal();
	if((parseFloat(saldoawal)-parseFloat(jmlkeluar))< 0){
		alert("Saldo Tidak mencukupi untuk transaksi ini");
		return;
	}else{
		return true;
	}
}
function __pilihbiaya(id,balik){/*
	var bolehGabung=['list_2','list_3','list_4'];//BPKB,PLAT ASLI,STCK
	var bolehGabungJuga=['list_1','list_3','list_4'];//ADM,PLAT ASLI, STCK*/
		var pilihan=$("#tjual").html().replace("[","").replace("]","");
		var gabungan=[];
		if($('#list_pilihan').val().length>0){
			gabungan =$('#list_pilihan').val().split(',');
		}
		var tharga=$("#thargane").text().replace(/,/g,'');
			tharga=(tharga)?parseFloat(tharga):0;
		var id=id;
		var jmlunit=0;
		if($('#'+id).is(":checked")==true){
				gabungan.push(id);
				var harga=$('#'+id).val();
				 jmlunit=$('#'+id).attr('data-item');
				$('#jmlu').html(jmlunit);
				tharga +=parseFloat(harga);
				pilihan +=id.substr(0,1);
		}else{
			var index= gabungan.indexOf(id);
			if(index>-1){
				var harga=$('#'+id).val();
				gabungan.splice(index,1);
				tharga -=parseFloat(harga);
				$('#jmlu').html(jmlunit);
				pilihan =pilihan.replace(id.substr(0,1),"");
			}
		}
		//console.log('harga :'+tharga+" j:"+harga);
		var adm=gabungan.indexOf('ADMIN_SAMSAT');
		var bpk=gabungan.indexOf('BPKB');
		for(i=0;i< gabungan.length;i++){
			var idx = gabungan.indexOf(id);
			if((adm >-1 && id=='BPKB') || (bpk >-1 && id=='ADMIN_SAMSAT')){
				//alert("tidak boleh digabung");
				var index= gabungan.indexOf(id);
				if(index>-1){
					gabungan.splice(index,1);
					var harga=$('#'+id).val();
					tharga -=parseFloat(harga);
					pilihan =pilihan.replace(id.substr(0,1),"");
				}
				$('#'+id).removeAttr("checked");
			}
		}
		pilihan=pilihan.split('');
		pilihan.sort();
		$('#tjual').html("["+pilihan.toString().replace(/,/g,'')+"]");
		$('#tbayar').html(pilihan.toString().replace(/,/g,''))
		$('#list_pilihan').val(gabungan.toString());
		$("#thargane").html($.number(tharga));
		$("#hargane").html($.number(tharga));
		$('#jumlah_p'+balik).val($.number(tharga));
		$('#junit').addClass("hidden");
}
function __balikBiaya(jtrs){
	var li="";
	if(!jtrs){ return;}
	var blmLengkap=jtrs.split(',');
	if(blmLengkap.length==1){
		jtrs=jtrs+","; 
		blmLengkap=jtrs.split(',');
	}
	var yngAktifA ="disabled-action";
	var yngAktifB ="disabled-action";
	var posA=jtrs.lastIndexOf('A');
	var posB=jtrs.lastIndexOf('B');
	
	switch(blmLengkap.length){
		case 2:
			yngAktifA=(parseInt(posA)>3)?"disabled-action":"";
			yngAktifB=(parseInt(posB)>3)?"disabled-action":"";
			
		break;
		case 3:
			yngAktifA="disabled-action";yngAktifB="disabled-action";
		break;
		default:
			yngAktifA="";yngAktifB="";
		break;
	}
	console.log('A:'+yngAktifA+'pos '+posA);
	console.log('B:'+yngAktifB+'pos '+posB);
		li +="<li class='"+yngAktifA+"'><input type='checkbox' onclick=\"__pilihBalikan('APS');\" id='APS' name='list_A' value='APS' style='cursor:pointer'>&nbsp;[ADM.SAMSAT,PLAT ASLI,STCK]</li>";
		li +=/*(posB>-1)?*/"<li class='"+yngAktifB+"'><input type='checkbox' onclick=\"__pilihBalikan('B');\" id='B' name='list_A' value='B' style='cursor:pointer'>&nbsp;[BPKB]</li>"/*:""*/;
		
	
	$('#jumlah_pb').val('0');
	//$('#lst_bpkbb').html(li);
	$('#list_pilihanb').val('');
	$('li.disabled-action input[type=checkbox]').attr("checked",true);

}
function __pilihBalikan(id){
	var idd=id.split('');
	var jmlp=0;
	console.log(jmlp);
	if($('#'+id).is(":checked")==true){
		//jmlp=parseFloat($('#'+id).val())
		$("input.price").attr("readonly",true);
		$("input[type='checkbox']").not('#'+id).addClass("disabled-action");
		$("input[id^='ok_'").removeClass("disabled-action");
		var jml_baris=$('#list_pinjamanlb > tbody > tr').length;
		if($('#'+id).is(":checked")==true){
			for(iz=0;iz < parseInt(jml_baris);iz++){
				var no_mesin  = $("#list_pinjamanlb > tbody > tr:eq("+ iz +") > td:eq(1)").text();
				var no_rangka = $("#list_pinjamanlb > tbody > tr:eq("+ iz +") > td:eq(12)").text();
				var jbayar = $('#nr_'+no_mesin).val();
				var hitung =0;
					hitung = $("input#"+id+"b_"+no_rangka).attr('data-item');
					console.log(hitung);
				if(parseInt(hitung)==2){
					$('#ok_'+no_rangka).attr('disabled',false);
					$("input#"+id+"b_"+no_rangka).attr("readonly",false);
					$("input#"+id+"b_"+no_rangka).removeClass("disabled-action");
					$("input#"+id+"b_"+no_rangka).removeAttr("readonly");
					$("input#"+id+"b_"+no_rangka).each(function(el){
						if($(this).hasClass('price')){
							jmlp +=parseFloat($(this).val().replace(/,/g,''));
						}
						//console.log(jmlp);
					})
					$('#tjual').html("["+id.toString().replace(/,/g,'')+"]");
				}else{
					$('#ok_'+no_rangka).attr('disabled',true);
					$("input#"+id+"b_"+no_rangka).attr("readonly",true);
				}
			}
			//$('#jumlah_pb').val($.number(jmlp));
		}
	}else{
		$("input[id^='ok_'").addClass("disabled-action").attr("checked",false);
		$('#jumlah_pbp').val('0');
		$("input[type='checkbox']").not('#'+id).removeClass("disabled-action");
		
		for(i=0;i<idd.length;i++){

			$("input[id^='"+idd[i]+"b_']").each(function(el){
				if(!$(this).attr("readonly") && $(this).hasClass('price')){
					jmlp -=parseFloat($(this).val().replace(/,/g,''));
				}
				//console.log(jmlp);
			})
			//$("input[id^='"+idd[i]+"b_']").attr("readonly",true);
		}
		$("input.price").addClass("disabled-action");
		$("input.price").attr("readonly",true);
		$('#tjual').html("");
		//$('#jumlah_pb').val($.number(jmlp));
	}
	$('#tbayar').html(id);
	console.log(jmlp);
	$('#jumlah_pb').val($.number(jmlp));
}

function __loadPilihan(){
	switch($('#tp_transaksi').val()){
		case "Penerimaan":
			$('#jenis_transaksi option.C').removeClass("hidden");
			$('#jenis_transaksi option.D').addClass("hidden");
			$('#jenis_transaksi option.E').removeClass("hidden");
			$('#nama_akun').val('');
			$('#jenis_transaksi').val('');
			//__getKDAkun('Penerimaan');
		break;
		case "Pengeluaran":
			$('#jenis_transaksi option.C').addClass("hidden");
			$('#jenis_transaksi option.D').removeClass("hidden");
			$('#jenis_transaksi option.E').addClass("hidden");
			$('#nama_akun').val('');
			$('#jenis_transaksi').val('');
			__getKDAkun('Pengeluaran');
		break;
		default:
			$('#jenis_transaksi option.C').addClass("hidden");
			$('#jenis_transaksi option.D').addClass("hidden");
			$('#jenis_transaksi option.E').addClass("hidden");
			$('#nama_akun').val('');
			$('#jenis_transaksi').val('');
		break;
	}
}

function __YangSudahDibayar(id){
	var jml_baris=$('#list_pinjamanlb > tbody > tr').length;
	if($('#'+id).is(":checked")==true){
		for(iz=0;iz < parseInt(jml_baris);iz++){
			var no_mesin  = $("#list_pinjamanlb > tbody > tr:eq("+ iz +") > td:eq(1)").text();
			var no_rangka = $("#list_pinjamanlb > tbody > tr:eq("+ iz +") > td:eq(12)").text();
			var jbayar = $('#nr_'+no_mesin).val();
			var hitung =0;
				hitung = $("input#"+id+"b_"+no_rangka).attr('data-item');
				console.log(hitung);
			if(parseInt(hitung)==2){
				$('#ok_'+no_rangka).attr('disabled',false);
				$("input#"+id+"b_"+no_rangka).attr("readonly",false);
				$("input#"+id+"b_"+no_rangka).removeClass("disabled-action");
				$("input#"+id+"b_"+no_rangka).removeAttr("readonly");
				$("input#"+id+"b_"+no_rangka).each(function(el){
					if($(this).hasClass('price')){
						jmlp +=parseFloat($(this).val().replace(/,/g,''));
					}
					console.log(jmlp);
				})
				$('#tjual').html("["+id.toString().replace(/,/g,'')+"]");
			}else{
				$('#ok_'+no_rangka).attr('disabled',true);
				$("input#"+id+"b_"+no_rangka).attr("readonly",true);
			}
		}
	}
}
function __getNamaBank(){
	//var bank=['BANK BCA','BANK MANDIRI','BANK BNI','BANK BNI SYARIAH','BANK BRI','BANK SYARIAH MANDIRI','BANK CIMB NIAGA','BANK CIMB NIAGA SYARIAH','BANK MUAMALAT','BANK BRI SYARIAH','BANK TABUNGAN NEGARA (BTN)','PERMATA BANK','BANK DANAMON','BANK BII MAYBANK','BANK MEGA','BANK SINARMAS','BANK COMMONWEALTH','BANK OCBC NISP','BANK BUKOPIN','BANK BCA SYARIAH','BANK LIPPO','CITIBANK','BANK TABUNGAN PENSIUNAN NASIONAL (BTPN)','BANK JABAR','BANK DKI','BPD DIY','BANK JATENG','BANK JATIM','BPD JAMBI','BPD ACEH','BANK SUMUT','BANK NAGARI','BANK RIAU','BANK SUMSEL','BANK LAMPUNG','BPD KALSEL','BPD KALIMANTAN BARAT','BPD KALTIM','BPD KALTENG','BPD SULSEL','BANK SULUT','BPD NTB','BPD BALI','BANK NTT','BANK MALUKU','BPD PAPUA','BANK BENGKULU','BPD SULAWESI TENGAH','BANK SULTRA'];
	var bank=[];
	$.getJSON(http+"/company/bank/true",function(result){
		if(result){
			//console.log(result);
			$.each(result,function(e,d){
				bank.push({
					'KODE' : d.KD_BANK,
					'NAMA' : d.NAMA_BANK,
					'NO.ACC': d.NO_REKENING,
					'ALAMAT': d.ALAMAT_BANK+" "+d.NAMA_KABUPATEN,
					'text' :d.NAMA_BANK,
					'value':d.KD_BANK,
					'description':d.NO_REKENING
				});
			})
		}
		$('#nama_bank').inputpicker({
			data:bank,
			fields:['KODE','NAMA','NO.ACC'],
			fieldText :"text",
			fieldValue : "value",
			headShow:true,
			filterOpen:true,
			autoselect:false
		}).change(function(e){
			e.preventDefault();
			var dx=bank.findIndex(obj => obj['KODE'] === $(this).val());
			//console.log(dx);
			if(dx>-1){
				//alert(bank[dx]["description"])
				$('#no_rekening').val(bank[dx]["description"]);
			}
		})
	})
	
}
function add_piutang(no_trans){

}
function __loadPengurus(){
	var datax=[];
	$.getJSON(http+"/cashier/pengurus4ss",function(result){
		if(result.status){
			console.log(result.message);
			$.each(result.message,function(e,d){
				datax.push({
					'NAMA PENGURUS' : stripslashes(d.NAMA_PENGURUS),
					'BIRO JASA'	:stripslashes(d.NAMA_BIROJASA),
					'JUMLAH'	:d.JUMLAH,
					'QTY'		:d.ROW_STATUS,
					'text'	:stripslashes(d.NAMA_PENGURUS),
					'value' :stripslashes(d.NAMA_PENGURUS)
				})
			})
			$('#nama_pengurus').inputpicker({
				data :datax,
				fields :["NAMA PENGURUS","BIRO JASA","JUMLAH"],
				fieldValue :'value',
				fieldText  :'text',
				filterOpen :true,
				headShow : true
			}).on("change",function(e){
				e.preventDefault();
				var dx=datax.findIndex(obj => obj['value'] === $(this).val());
				$('#uraian_ss').val('Pembayaran Biaya SS untuk '+datax[dx]["QTY"]+' Unit Motor oleh pengurus '+datax[dx]["value"])
				$('#jumlah_ss').val($.number(datax[dx]["JUMLAH"]));
				__loadDetailSS($(this).val());
				$('#kd_akun').attr("required",true);
				if($("#kd_akun").val()){
					$('#smp').removeClass("disabled-action");
				}else{
					$("#kd_akun").focus().select();
				}
			})
		}
	})
}
function __loadDetailSS(nama_pengurus){
	$.getJSON(http+"/cashier/detail_ss",{'nama_pengurus':nama_pengurus},function(result){
		//console.log(result);
		var tr="";
		var n=0;
		if(result.status){
			$.each(result.message,function(e,d){
				n++;
				tr +="<tr><td class='text-center'>"+n+"</td>";
				tr +="<td class='table-nowarp'>"+d.NO_SPK+"</td>";
				tr +="<td class='td-overflow' title='"+d.NAMA_ITEM+"'>["+d.KD_ITEM+"] "+d.NAMA_ITEM+"</td>";
				tr +="<td class='table-nowarp'>"+d.NO_MESIN+"</td>";
				tr +="<td class='text-right'>"+$.number(d.JUMLAH)+"</td>";
				tr +="<td class='td-overflow' title='"+d.NAMA_CUSTOMER+"'>"+stripslashes(d.NAMA_CUSTOMER)+"</td>";
				tr +="<td class='td-overflow' title='"+d.ALAMAT_KIRIM+"'>"+stripslashes(d.ALAMAT_KIRIM)+"</td></tr>";
			})
		}
		$('#lst_ss > tbody').html(tr);
	})
}
function __simpanSS(){
	var rows=$('#lst_ss > tbody > tr').length;
	var data=[];
	for(i=0;i < rows; i++){
		data.push({
			'no_mesin':$("#lst_ss > tbody > tr:eq(" + i + ") td:eq(3)").text()
		})
	}
	return data;
}
function __simpanTitipan(){
	
}

function __getMinimal(jenis_trans){
	$.getJSON(http+"/cashier/minimal_value",{'jt':jenis_trans},function(result){
		console.log(result)
		$('#min_value').val(result);
		return result;
	})
}
function __getProposal(){
	var datax=[];
	$('#pldg').html("<i class='fa fa-spinner fa-spin'></i>")
	$.getJSON(http+"/stock_opname/proposal_jplist/true/true",function(result){
		if(result.totaldata>0){
			$.each(result.message,function(e,d){
				if(parseInt(d.STATUS_JOINPROMO)===4){
					datax.push({
						'NOPROPOSAL':d.NO_TRANS,
						'TGLKEGIATAN': d.TGL_JOINPROMO,
						'KEGIATAN': stripslashes(d.KEGIATAN_JOINPROMO),
						'JUMLAH':d.TOTAL_HARGA,
						'TUJUAN': stripslashes(d.TUJUAN_JOINPROMO),
					})
				}
			})
		}
		console.log(result);
		$('#pldg').html("");
		$('#no_reff_jp').inputpicker({
			data : datax,
			fields :['NOPROPOSAL','KEGIATAN','JUMLAH'],
			fieldText :'NOPROPOSAL',
			fieldValue :'NOPROPOSAL',
			filterOpen :true,
			headShow : true
		}).on("change",function(input){
			var dx=datax.findIndex(obj => obj['NOPROPOSAL'] == $(this).val());
			$('#ket_reff_jp').val(datax[dx]["KEGIATAN"]);
			$('#ket_joinpromo').text(datax[dx]["KEGIATAN"]+" - "+datax[dx]["TUJUAN"]);
			$('#jml_joinpromo').val(parseFloat(datax[dx]["JUMLAH"]).toLocaleString());
			$('#jml_joinpromo').addClass("disabled-action");
			$('#total_jp').html(parseFloat(datax[dx]["JUMLAH"]).toLocaleString());
			$('#kd_akun').val('100.11101').trigger("change");
			$('#smp').removeClass("disabled-action");
		})
	})
}
function getPICPinjaman(){
	var datax=[];
	$('#kldg').html("<i class='fa fa-spinner fa-spin'></i>")
	$.getJSON(http+"/company/karyawan/true/true",function(result){
		if(result.totaldata>0){
			$.each(result.message,function(e,d){
				datax.push({
					'NIK':d.NIK,
					'NAMA': stripslashes(d.NAMA),
					'DIVISI': d.KD_DIVISI
				})
			})
		}
		$('#kldg').html("");
		$('#pic_reff_jp').inputpicker({
			data : datax,
			fields :['NIK','NAMA','DIVISI'],
			fieldText :'NAMA',
			fieldValue :'NIK',
			filterOpen :true,
			headShow : true
		}).on("change",function(input){
			var dx=datax.findIndex(obj => obj['NIK'] == $(this).val());
			$('#ket_reff_jpn').val(datax[dx]["NAMA"]);
		})
	})
}
function __simpan_pjs(){
	var datax=[];	
	datax.push({
		'pic': $('#pic_reff_jp').val(),
		'proposal': $('#no_reff_jp').val(),
		'source': $('#source_jp').val(),
		'ket_ref': $('#ket_reff_jp').val(),
		'uraian_jp': $('#ket_joinpromo').text(),
		'jml_jp': $('#jml_joinpromo').val().replace(/,/g,'')
	})
	if(datax){
		return datax;
	}
	console.assert(datax, message);
}
function __informasiTambahan(spkid){
	var tr="<td colpsan='5'><i class='fa fa-spinner fa-spin'></i> Load data customer....";
	$('table#info_spx tfoot').html(tr);
	$.getJSON(http+"/spk/spk/1",{'spk_id':spkid},function(result){
		console.log(result);
		if(result){
			if(parseInt(result.totaldata)>0){
				$.each(result.message,function(e,d){
					tr +="<tr class='total'><td colspan='2'>No. KTP : "+d.NO_KTP+"</td>";
					tr +="<td colspan='2'>Nama Sales : "+d.NAMA_SALES +" [ "+d.KD_HSALES+" ]</td><td>Kode Sales : "+d.KD_SALES+"</td></tr>";
				})
			}
		}else{
			tr="";
		}
		console.log(tr);
		$('table#info_spx tfoot').html(tr);
		$('table#info_spux tbody').html(tr);
	})
}