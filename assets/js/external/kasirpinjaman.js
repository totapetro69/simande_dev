//proses untuk kasir pinjaman stnkbpkb
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function() {

	$('#jumlah_p')
		.on("change", function(e) {
			e.preventDefault();
			$(this).focus().select();

		})
		.on("focus", function(e) {
			e.preventDefault();
			$('#addPinjaman').removeClass('disabled-action');

		})
		.on("keypress", function(e) {
			if (e.which == 13) {
				e.preventDefault();
				__add_itemPinjaman();
				//__lock();
			}
		})
	$('#addPinjaman').click(function(e) {
		e.preventDefault();
		__add_itemPinjaman();

	})
	var balik = "";
	balik = ($('#jenis_transaksi').val() === 'Pinjaman') ? '' : 'b';
	$('#no_reff_p').on('click', function() {
		//__getPengajuan($(this).val(),balik);
	})
	$('#SPKBT').on("click", function() {
		if ($(this).is(":checked")) {
			__getBatalSPK();
		}
	})
	$('#inputpicker-wrapped-list > table.small > tbody >tr[data-toggle="popover"]').on('hover',function(){
		console.log($(this).data('content'));
	})
})

function __lock() {
	if ($('#addPinjaman').hasClass('disabled-action')) {
		$('#smp').addClass("disabled-action");
	} else {
		$('#smp').removeClass("disabled-action");
	}
}

function __getPengurus(balik) {
	$('#ldg_0').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>")
	$.getJSON(http + "/stnk/get_pengurus/true", {
		't': balik
	}, function(result) {
		var datap = [];
		$.each(result, function(index, d) {
			datap.push({
				'value': (d.KD_BIROJASA) ? d.KD_BIROJASA : '-',
				'text': (d.NAMA_PENGURUS) ? stripslashes(d.NAMA_PENGURUS) : '-',
				'description': stripslashes(d.NAMA_BIROJASA),
				'NamaPengurus': (d.NAMA_PENGURUS) ? stripslashes(d.NAMA_PENGURUS) : '-',
				'Birojasa': d.NAMA_BIROJASA
			})
		})
		$('#ldg_0').html("");
		//console.log(datap);
		$('#ket_reff_p' + balik).inputpicker({
			data: datap,
			fields: ['NamaPengurus', 'Birojasa'],
			fieldText: 'text',
			fieldValue: 'value',
			filterOpen: false,
			headShow: true
		}).change(function(e) {
			e.preventDefault();
			//$('#no_reff_p'+balik).inputpicker({'destroy':'-'})
			$('#no_reff_p' + balik).focus();
			$("#list_pinjaman" + balik + ' > tbody').html('');
			$("#list_pinjamanl" + balik + ' > tbody').html('');
			__getPengajuan($(this).val(), balik);
		})
	})
}

function __getPengajuan(kd_pengurus, balik) {
	$('#ldg_1').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>")
	$.getJSON(http + "/stnk/get_nomorpengurusan2/true", {
		'p': kd_pengurus,
		't': balik
	}, function(result) {
		var datax = [];
		//console.log(result);
		$.each(result, function(index, d) {
			datax.push({
				'value': d.NO_TRANS,
				'text': (d.STATUS_PENGURUSAN == 2) ? d.NO_TRANS + '(M)' : d.NO_TRANS,
				'description': d.JENIS_DOC,
				'No.Pengajuan': (d.STATUS_PENGURUSAN == 2) ? d.NO_TRANS + '(M)' : d.NO_TRANS,
				'Tanggal': d.TGL_STNK,
				'Document': d.JENIS_DOC,
				'Pengajuan': d.TOTAL_BIAYAPENGAJUAN,
				'Jumlah': $.number(d.TOTAL_BIAYAAPPROVE),
				'nama_pengurus': d.NAMA_PENGURUS,
				'stnk_id': d.ID
			})
		})
		//console.log(datax);
		$('#ldg_1').html('')
		$('#no_reff_p' + balik).val('');
		$('#no_reff_p' + balik).inputpicker({
			data: datax,
			fields: ['No.Pengajuan', 'Tanggal', 'Jumlah', ],
			fieldText: 'text',
			fieldValue: 'value',
			filterOpen: false,
			headShow: true,
			autoOpen: false,
			popover:'Test over pop'
		}).change(function(e) {
			e.preventDefault();
			var dx = -2;
			dx = datax.findIndex(obj => obj['value'] === $(this).val());
			console.log('REF_'+balik+':'+dx);
			if (dx > -1) {
				$('#tbl-ctn').addClass("table-responsive h250");
				$('#jenis_reff_p' + balik).val(datax[dx]['description']);
				$('#uraian_p').val("Pinjaman Biaya Pengurusan " + datax[dx]['description'] + " No Pengajuan : " + datax[dx]['text'] + " a/n " + datax[dx]['nama_pengurus']);
				$('#uraian_pb').val("Pengembalian Biaya Pengurusan " + datax[dx]['description'] + " No Pengajuan : " + datax[dx]['text'] + " a/n " + datax[dx]['nama_pengurus'])
				if (datax[dx]['description'] == 'STNK') {
					$('#bpkb_check').addClass("hidden");
					$('#jml_pengajuan' + balik).val(datax[dx]["Pengajuan"]);
					__detailStnkBpkb(datax[dx]["value"], balik, 'STNK');
					$('#jumlah_p' + balik).val(datax[dx]["Jumlah"]);
					$('#jumlah_u').val(datax[dx]["Jumlah"]);
				} else {
					$('#bpkb_check' + balik).removeClass("hidden");
					$('#jumlah_p' + balik).val('');
					__loadBiayaBPKB(datax[dx]["stnk_id"], $(this).val(), balik)
					//$('#jml_pengajuan').val('');
				}

			}
		})
	})
	$("#inputpicker-wrapped-list").popover({
	    container: "body",
	    selector: "[data-toggle=popover][data-trigger=hover]",
	    trigger: "hover"
    });
}

function __loadBiayaBPKB(stnk_id, nomor, balik) {
	var li = "";
	$.getJSON(http + '/cashier/get_biayabpkb/true', {
		'stnk_id': stnk_id,
		'status': (balik) ? '2' : '1'
	}, function(result) {
		console.log(result);
		if (result.length > 0) {
			$.each(result, function(e, d) {
				var dsb = (parseInt(d.UNIT) > 0) ? '' : 'disabled-action';
				console.log(d.UNIT);
				if (balik == '') {
					li += "<li><input class='" + dsb + "' type='checkbox' onclick=\"__pilihbiaya('" + d.JENIS_BIAYA + "','" + balik + "');\" id='" + d.JENIS_BIAYA + "' name='list_" + d.ID + "' value='" + d.BIAYA + "' data-item='" + d.UNIT + "' style='cursor:pointer'>&nbsp;";
					li += d.JENIS_BIAYA.replace("_", " ") + "</li>";
				} else {
					//biaya balik
					li += "<li><input class='" + dsb + "' type='checkbox' onclick=\"__pilihBalikan('" + d.JENIS_BIAYA.substr(0, 1) + "');\" id='" + d.JENIS_BIAYA.substr(0, 1) + "' name='list_" + d.JENIS_BIAYA.substr(0, 1) + "' value='" + d.BIAYA + "' data-item='" + d.UNIT + "' style='cursor:pointer'>&nbsp;";
					li += d.JENIS_BIAYA.replace("_", " ") + "</li>";
				}

			})
		}
		//console.log(li);
		__detailStnkBpkb(nomor, balik);
		$('#lst_bpkb' + balik).html(li);
		$('#list_pilihan' + balik).val('');
		$("#thargane" + balik).html('');
		$('#jumlah_p' + balik).val('');
	})
}

function __detailStnkBpkb(no_pengajuan, balik, tb) {
	$('#ldg_2').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>")
	$.getJSON(http + "/stnk/paybill_kasir/true", {
		'no_trans': no_pengajuan,
		'p': balik
	}, function(result) {
		//console.log(result);
		var jmlUnit = 0;
		var jbayar = "";
		var TotaStnk = 0;
		var id_reff = "";
		var lsp = "";
		var biayasatuan = 0;
		var kd_akun = "";
		var posakun = "";
		var listitem = "";
		var hdr_stnk = "";
		hdr_stnk = "<tr><th>No</th><th>No Mesin</th><th>Kode</th><th>Customer</th>";
		hdr_stnk += (tb == 'STNK') ? "<th>BBNKB</th><th>PKB</th><th>SWDKLLJ</th>" : "<th>SAMSAT</th><th>BPKB</th><th>PLAT ASLI</th><th>STCK</th>";
		hdr_stnk += "<th>#</th></tr>";
		var yngMunculSTNK = (tb == 'STNK') ? "" : "hidden";
		var yngMunculBPKB = (tb !== 'STNK') ? "" : "hidden";
		var yngReadOnlySTNK = (tb == 'STNK') ? "" : "readonly";
		var yngReadOnlyBPKB = (tb !== 'STNK') ? "" : "readonly";
		var disabled = (balik == 'b' && tb != 'STNK') ? 'disabled-action' : '';
		var b = 0;
		a = 0;
		p = 0;
		s = 0;
		if (result.length > 0) {
			$.each(result, function(e, d) {
				jmlUnit++;
				TotaStnk += parseFloat(d.BIAYA_STNK);
				biayasatuan = d.BIAYA_STNK;
				kd_akun = d.KD_AKUN;
				posakun = d.POSISI_AKUN;
				id_reff = d.ID;
				jbayar = d.JENIS_BAYAR;
				var req = (balik == 'b') ? 2 : 1;
				var r_bpkb = (parseInt(d.REQ_BPKB) == req) ? "style='border:1px solid red'" : "";
				var r_stck = (parseInt(d.REQ_STCK) == req) ? "style='border:1px solid red'" : "";
				var r_adms = (parseInt(d.REQ_ADMIN_SAMSAT) == req) ? "style='border:1px solid red'" : "";
				var r_asli = (parseInt(d.REQ_PLAT_ASLI) == req) ? "style='border:1px solid red'" : "";
				listitem += "<tr><td class='text-center'>" + jmlUnit + "</td><td class='text-center table-nowarp'>" + d.NO_MESIN + "</td><td class='text-center table-nowarp'>" + d.KD_ITEM + "</td><td>" + d.NAMA_CUSTOMER + "</td>";
				//if(tb=='STNK'){
				listitem += "<td class='text-center " + yngMunculSTNK + "'><input type='text' onchange=\"__changeHarga('bbnkb_" + d.NO_RANGKA + "');\" class='text-right form-control on-grid disabled-action price' id='bbnkb_" + d.NO_RANGKA + "' name='bbnkb_" + d.NO_RANGKA + "' value='" + $.number(d.BBNKB) + "' data-mask='#,##0' data-mask-reverse='true' " + yngReadOnlySTNK + "></td>";
				listitem += "<td class='text-center " + yngMunculSTNK + "'><input type='text' onchange=\"__changeHarga('pkb_" + d.NO_RANGKA + "');\" class='text-right form-control on-grid disabled-action price' id='pkb_" + d.NO_RANGKA + "' name='pkb_" + d.NO_RANGKA + "' value='" + $.number(d.PKB) + "' data-mask='#,##0' data-mask-reverse='true' " + yngReadOnlySTNK + "></td>";
				listitem += "<td class='text-center " + yngMunculSTNK + "'><input type='text' onchange=\"__changeHarga('swdkllj_" + d.NO_RANGKA + "');\" class='text-right form-control on-grid disabled-action price' id='swdkllj_" + d.NO_RANGKA + "' name='swdkllj_" + d.NO_RANGKA + "' value='" + $.number(d.SWDKLLJ) + "' data-mask='#,##0' data-mask-reverse='true' " + yngReadOnlySTNK + "></td>";
				//}else{
				listitem += "<td class='text-center " + yngMunculBPKB + "'><input " + r_adms + " type='text' onchange=\"__changeHarga('Ab_" + d.NO_RANGKA + "');\" data-item='" + d.REQ_ADMIN_SAMSAT + "' class='text-right form-control on-grid disabled-action price' id='Ab_" + d.NO_RANGKA + "' name='Ab_" + d.NO_RANGKA + "' value='" + $.number(d.ADMIN_SAMSAT) + "' " + yngReadOnlyBPKB + "></td>";
				listitem += "<td class='text-center " + yngMunculBPKB + "'><input " + r_bpkb + " type='text' onchange=\"__changeHarga('Bb_" + d.NO_RANGKA + "');\" data-item='" + d.REQ_BPKB + "' class='text-right form-control on-grid disabled-action price' id='Bb_" + d.NO_RANGKA + "' name='Bb_" + d.NO_RANGKA + "' value='" + $.number(d.BPKB) + "' " + yngReadOnlyBPKB + "></td>";
				listitem += "<td class='text-center " + yngMunculBPKB + "'><input " + r_asli + " type='text' onchange=\"__changeHarga('Pb_" + d.NO_RANGKA + "');\" data-item='" + d.REQ_PLAT_ASLI + "' class='text-right form-control on-grid disabled-action price' id='Pb_" + d.NO_RANGKA + "' name='Pb_" + d.NO_RANGKA + "' value='" + $.number(d.PLAT_ASLI) + "' " + yngReadOnlyBPKB + "></td>";
				listitem += "<td class='text-center " + yngMunculBPKB + "'><input " + r_stck + " type='text' onchange=\"__changeHarga('Sb_" + d.NO_RANGKA + "');\" data-item='" + d.REQ_STCK + "' class='text-right form-control on-grid disabled-action price' id='Sb_" + d.NO_RANGKA + "' name='Sb_" + d.NO_RANGKA + "' value='" + $.number(d.STCK) + "' " + yngReadOnlyBPKB + "></td>";
				//}
				if (tb == 'STNK') {
					var jbr = (jbayar) ? jbayar.split(',') : '';
					if (jbr.length > 0) {
						disabled = "disabled-action";
					} else {
						disabled = '';
					}
				}
				listitem += "<td class='text-center '><input class='" + disabled + "' type='checkbox' onclick=\"__pilihBayar('" + d.NO_RANGKA + "');\" id='ok_" + d.NO_RANGKA + "' name='ok_" + d.NO_RANGKA + "' style='cursor:pointer'></td>";
				listitem += "<td class='text-center hidden'>" + d.NO_RANGKA + "</td><td class='hidden'><input type='hidden' id='nr_" + d.NO_MESIN + "' value='" + jbayar + "'></tr>";
				// listitem +="<td class='text-center hidden'><input type='text' class='text-right form-control on-grid disabled-action' id='pkb_"+d.NO_RANGKA+"_a' name='pkb_"+d.NO_RANGKA+"_a' value='"+parseFloat(d.PKB).toLocaleString()+"'></td>";
				// listitem +="<td class='text-center hidden'><input type='text' class='text-right form-control on-grid disabled-action' id='swdkllj_"+d.NO_RANGKA+"_a' name='swdkllj_"+d.NO_RANGKA+"_a' value='"+parseFloat(d.SWDKLLJ).toLocaleString()+"'></td>";
				//menghitung jumla item yang masih harus di bkeluarkan pinjamannya
				//{jumlah unit di banding dengan jml yang hrus di bayar}
				a += (parseInt(d.REQ_ADMIN_SAMSAT) == req) ? 1 : 0;
				b += (parseInt(d.REQ_BPKB) == req) ? 1 : 0;
				p += (parseInt(d.REQ_PLAT_ASLI) == req) ? 1 : 0;
				s += (parseInt(d.REQ_STCK) == req) ? 1 : 0;
			})
			console.log(a);
			$('#stnk_id' + balik).val(id_reff);
			var bariske = 0;
			//jmlUnit=(balik=='P')?jmlUnit:"0";
			var html = "";
			html += "<tr><td class='text-center'>" + (bariske + 1) + "</td>";
			html += "<td class='text-center'><a onclick=\"__hapus_item_p('" + bariske + "')\" role='button'><i class='fa fa-trash'></i></a><a onclick=\"__showDtl();\" role='button' title='show detail pengajuan'><i class='fa fa-chevron-down'></i></a></td>";
			html += "<td>" + $('#uraian_p' + balik).val() + " untuk <span class='hidden' id='junit'>0/</span><span id='jmlu'>" + jmlUnit + "</span> Unit Motor <span id='tjual'></span></td><td class='text-right'>1";
			html += "</td><td class='text-right' id='hargane'>" + $.number(TotaStnk) + "</td>";
			html += "<td class='text-right' id='thargane'>" + $.number(TotaStnk);
			html += "</td><td class='hidden'>" + $('#no_reff_p' + balik).val() + ":" + id_reff + "</td><td class='hidden'>" + kd_akun + "</td>";
			html += "<td class='hidden'>" + posakun + "</td><td class='hidden'>" + $('#ket_reff_p' + balik).val();
			html += "</td><td id='tbayar' class='hidden'></td></tr>";
			$('#list_pinjaman' + balik + ' > tbody').html(html);
			$('#list_pinjamanl' + balik + ' > thead').html(hdr_stnk);
			$('#list_pinjamanl' + balik + ' > tbody').html(listitem);
			$('#ldg_2').html("")
			if (balik == '') {
				$('#smp').removeClass("disabled-action");
			}
			//$('#list_pinjamanl'+balik).removeClass('hidden');
			if (balik == 'b') {
				$('#jumlah_pbp').val('0');
			}
			//$('#uraian_p'+balik).val('');
			var jb = (jbayar) ? jbayar.split('') : '';

			//$('#jtrans').html(jbayar);
			if (balik == '') {
				if (a > jmlUnit) {
					$("input[id^='A']").addClass("disabled-action").attr("checked", true);
				}
				if (b > jmlUnit) {
					$("input[id^='B']").addClass("disabled-action").attr("checked", true);
				}
				if (p > jmlUnit) {
					$("input[id^='P']").addClass("disabled-action").attr("checked", true);
				}
				if (s > jmlUnit) {
					$("input[id^='S']").addClass("disabled-action").attr("checked", true);
				}
			} else {
				if (tb != 'STNK') {
					__balikBiaya(jbayar);
					if (a > jmlUnit) {
						$("input[id^='A']").addClass("disabled-action").attr("checked", true);
					}
					if (b > jmlUnit) {
						$("input[id^='B']").addClass("disabled-action").attr("checked", true);
					}
					if (p > jmlUnit) {
						$("input[id^='P']").addClass("disabled-action").attr("checked", true);
					}
					if (s > jmlUnit) {
						$("input[id^='S']").addClass("disabled-action").attr("checked", true);
					}
					$('#bpkb_checkb').removeClass("hidden");
				} else {

					$('#bpkb_checkb').addClass("hidden")
				}

			}
		}
	})
}

function __add_itemPinjaman() {
	if ($("#kd_akun").val() == '') {
		$('#kd_akun').focus();
		return;
	}
	__lock();
	var bariske = $('#list_pinjaman > tbody > tr').length;
	var html = "";
	html += "<tr><td class='text-center'>" + (bariske + 1) + "</td>";
	html += "<td class='text-center'><a onclick=\"__hapus_item_p('" + bariske + "')\" role='button'><i class='fa fa-trash'></i></a></td>";
	html += "<td>" + $('#uraian_p').val() + "</td><td class='text-right'>1";
	html += "</td><td class='text-right'>" + $('#jumlah_p').val() + "</td><td class='text-right'>" + $('#jumlah_p').val();
	html += "</td><td class='hidden'>" + $('#no_reff_p').val() + "</td><td class='hidden'>" + $('#kd_akun').val() + "</td>";
	html += "<td class='hidden'>" + $('#nama_akun').val() + "</td><td class='hidden'>" + $('#ket_reff_p').val() + "</td></tr>";
	$('#list_pinjaman > tbody').append(html);
	$('#jenis_reff_p').val('');
	$('#jumlah_p').val('');
	$('#no_reff_p').val('');
	$('#jml_pengajuan').val('');
	$('#uraian_p').val('');
	$('#kd_akun').focus().select();
}

function __hapus_item_p(bariske) {
	if (parseInt(bariske) > 0) {
		bariske = parseInt(bariske)
	} else {
		bariske = bariske;
	}
	$("#list_pinjaman >tbody > tr:eq(" + bariske + ")").remove();
}

function __simpan_pinjaman(balik) {
	var bariskex = 0;
	bariskex = $('#list_pinjaman' + balik + ' > tbody > tr').length;
	var dataxx = [];
	for (iz = 0; iz < bariskex; iz++) {
		dataxx.push({
			'no_urut': (iz + 1),
			'uraian_transaksi': (balik == 'b') ? $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(2)").text() : $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(2)").text().replace("0/", ""),
			'jumlah': $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
			'harga': $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
			'saldo_awal': $('#saldo_awal').val(),
			'kd_akun': $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(7)").text(),
			'nama_akun': $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(8)").text() + ":" + $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(9)").text(),
			'tipe_bayar': $("#list_pinjaman" + balik + " > tbody > tr:eq(" + iz + ") td:eq(10)").text()
		})
	}
	//console.log('jmlbaris: '+bariskex)
	console.log(dataxx)
	return dataxx;
}

function __simpan_detailpinjaman() {
	var row = 0;
	var no_rangka = "";
	row = $('#list_pinjamanlb > tbody > tr').length;
	var detail = [];
	for (x = 0; x < row; x++) {
		no_rangka = $("#list_pinjamanlb > tbody > tr:eq(" + x + ") td:eq(12)").text();
		if ($('#ok_' + $.trim(no_rangka)).is(":checked") === true) {
			detail.push({
				'no_mesin': $("#list_pinjamanlb > tbody > tr:eq(" + x + ") td:eq(1)").text(),
				'kd_item': $("#list_pinjamanlb > tbody > tr:eq(" + x + ") td:eq(2)").text(),
				'no_rangka': $("#list_pinjamanlb > tbody > tr:eq(" + x + ") td:eq(12)").text(),
				'customer': $("#list_pinjamanlb > tbody > tr:eq(" + x + ") td:eq(3)").text(),
				'bbnkb': $('#bbnkb_' + no_rangka).val().replace(/,/g, ''),
				'pkb': $('#pkb_' + no_rangka).val().replace(/,/g, ''),
				'swdkllj': $('#swdkllj_' + no_rangka).val().replace(/,/g, ''),
				'bpkb': $('#Bb_' + no_rangka).val().replace(/,/g, ''),
				'plat_asli': $('#Pb_' + no_rangka).val().replace(/,/g, ''),
				'stck': $('#Sb_' + no_rangka).val().replace(/,/g, ''),
				'admin_samsat': $('#Ab_' + no_rangka).val().replace(/,/g, '')
			})
		}
		/*console.log(no_rangka);*/
	}
	console.log(detail);

	return detail;
}

function __getPKB() {
	$.getJSON(http + "/pkb/pkb_typeahead/true/true", {
		'c': '1'
	}, function(result) {
		var datax = [];
		if (result) {
			$.each(result, function(index, d) {
				datax.push({
					'value': d.NO_PKB,
					'text': d.NO_PKB,
					'description': stripslashes(d.NAMA_COMINGCUSTOMER),
					'NOPOL': d.NO_POLISI,
					'MEKANIK': d.NAMA,
					'KPB': d.JENIS_PKB,
					'NOPKB': d.NO_PKB,
					'CUSTOMER': stripslashes(d.NAMA_COMINGCUSTOMER)
				})
			})
		}
		$('#ldgsp').html("");
		$('#no_reff_sp').inputpicker({
			data: datax,
			fields: ["NOPKB", "NOPOL", "KPB", "MEKANIK", "CUSTOMER"],
			fieldValue: 'value',
			fieldText: 'text',
			filterOpen: true,
			headShow: true
		}).on("change", function(e) {
			e.preventDefault();
			var dx = datax.findIndex(obj => obj['value'] === $(this).val());
			if (dx > -1) {
				$('#ket_reff_sp').val(datax[dx]['NOPOL'] + ' - ' + datax[dx]['CUSTOMER']);
				$('#ket_reff_sp').focus();
				__getPKB_Detail($(this).val());
			}
		})

	});
}

function __getPKB_Detail(nopkb) {
	$('#ldgsp').html("<i class='fa fa-spinner fa-spin'></i>");
	$.getJSON(http + "/cashier/pkb_detail/true", {
		'p': nopkb
	}, function(result) {
		var html = "";
		var bariskes = 0;
		var total_bayar = 0;
		$('#lst_sp > tbody').html("");
		var uraiane = "";
		console.log(result);
		if (result) {
			$.each(result, function(index, d) {
				var harga = (d.JENIS_PKB == 'KPB') ? '0' : d.HARGA_SATUAN;
				var harga_t = (d.JENIS_PKB == 'KPB') ? '0' : d.TOTAL_HARGA;
				var coret = (d.JENIS_PKB == 'KPB') ? 'coret' : '';
				html += "<tr><td class='text-center'>" + (bariskes + 1) + "</td>";
				html += "<td class='text-center'><a class='disabled-action' onclick=\"__hapus_item_sp('" + bariskes + "')\" role='button'><i class='fa fa-trash'></i></a></td>";
				html += "<td class='td-overflow-50 " + coret + "' title='" + d.PART_DESKRIPSI + " [ " + d.JENIS_PKB + " ]'>" + d.PART_NUMBER + " - " + d.PART_DESKRIPSI + "</td>";
				html += "<td class='text-right'>" + $.number(d.JUMLAH);
				html += "</td><td class='text-right " + coret + "'>" + $.number(harga);
				html += "</td><td class='text-right " + coret + "'>" + $.number(harga_t);
				html += "</td><td class='hidden'>" + d.PART_NUMBER + "</td><td class='hidden'>" + d.KD_AKUN + "</td>";
				html += "<td class='hidden'>:" + d.PART_NUMBER + ":" + d.KATEGORI + "</td>";
				html += "<td class='hidden'>:" + d.JENIS_PKB + "</td></tr>";
				bariskes++;
				total_bayar += (d.JENIS_PKB == 'KPB') ? 0 : parseFloat(d.TOTAL_HARGA);
			})
			uraiane += "<tr class='total'><td>&nbsp;</td><td>&nbsp;</td>";
			uraiane += "<td>" + $('#tp_transaksi').val() + " " + $('#jenis_transaksi').val();
			uraiane += ' No.Reff :' + $('#no_reff_sp').val() + ' ( ' + $('#ket_reff_sp').val() + ' )</td>';
			uraiane += "<td class='text-right'>1</td><td class='text-right'>" + $.number(total_bayar) + "</td>";
			uraiane += "<td class='text-right'>" + $.number(total_bayar) + "</td><td colspan='3' class='hidden'></td></tr>";
		}
		$('#lst_sp > tbody').append(html);
		$('#lst_sp > tfoot').html(uraiane);
		$("#jml_bayar")
			.val($.number(total_bayar))
			.mask('#,##0', {
				reverse: true
			})
		//$('#sparepart input:not(#no_reff_sp,#ket_reff_sp,#jml_bayar)').val('');
		$('#kd_akun').focus();
		$('#ldgsp').html("");
	})
}

function __getPOHotline() {
	$.getJSON(http + "/purchasing/podetail_list_sparepart/1", {
		'jp': 'Hotline',
		'kd_dealer': '2NG'
	}, function(result) {
		var datax = [];
		if (result.length > 0) {
			$.each(result, function(e, d) {
				datax.push({
					'value': d.NO_PO,
					'text': d.NO_PO,
					'NOPO': d.NO_PO,
					'CUSTOMER': stripslashes(d.NAMA_KONSUMEN),
					'ALAMAT': stripslashes(d.ALAMAT_KONSUMEN),
					'NOTELP': d.NO_TELP,
					'TGLPO': d.TGL_PO
				})
			})
		}
		$('#no_reff_sp').inputpicker({
			data: datax,
			fields: ['NOPO', 'TGLPO', 'CUSTOMER', 'ALAMAT', 'NOTELP'],
			fieldText: 'text',
			fieldValue: 'value',
			filterOpen: true,
			headShow: true
		}).on("change", function(e) {
			e.preventDefault();
			var dx = datax.findIndex(obj => obj['NOPO'] === $(this).val());
			//console.log(dx);
			if (dx > -1) {
				$('#ket_reff_sp').val(datax[dx]['CUSTOMER'] + ' - ' + datax[dx]['ALAMAT'] + ' ' + datax[dx]["NOTELP"]);
				$('#ket_reff_sp').focus();
				__getDetailPO($(this).val());
			}
		})
	})
}

function __getDetailPO(nopo) {
	$.getJSON(http + "/purchasing/podetail_list_sparepart/0/1", {
		'nopo': nopo
	}, function(result) {
		var html = "";
		var bariskes = 0;
		var total_bayar = 0;
		$('#lst_sp > tbody').html("");
		if (result) {
			$.each(result, function(index, d) {

				html += "<tr><td class='text-center'>" + (bariskes + 1) + "</td>";
				html += "<td class='text-center'><a class='disabled-action' onclick=\"__hapus_item_sp('" + bariskes + "')\" role='button'><i class='fa fa-trash'></i></a></td>";
				html += "<td>" + d.PART_NUMBER + " - " + d.PART_DESKRIPSI + "</td><td class='text-right'>" + $.number(d.JUMLAH);
				html += "</td><td class='text-right'>" + $.number(d.HARGA);
				html += "</td><td class='text-right'>" + $.number(parseFloat(d.JUMLAH) * parseFloat(d.HARGA));
				html += "</td><td class='hidden'>" + d.PART_NUMBER + "</td><td class='hidden'>100.52102.01</td>";
				html += "<td class='hidden'>:" + d.PART_NUMBER + ":Part</td></tr>";
				bariskes++;
				total_bayar += (parseFloat(d.JUMLAH) * parseFloat(d.HARGA));
			})
		}
		$('#lst_sp > tbody').append(html);

		$("#jml_bayar")
			.val($.number(total_bayar))
			.mask('#,##0', {
				reverse: true
			})
		//$('#sparepart input:not(#no_reff_sp,#ket_reff_sp,#jml_bayar)').val('');
		$('#kd_akun').focus();
	});
}

function __getSOP(type) {
	$('#ldgsp').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
	$('#lbr').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
	$.getJSON(http + "/cashier/listsop/true/true/" + type, function(result) {
		// console.log(result.soh);
		var datax = [];
		if (result.soh) {
			$.each(result.soh, function(e, d) {
				datax.push({
					'value': d.NO_TRANS,
					'text': d.NO_TRANS,
					'description': stripslashes(d.NAMA_CUSTOMER)
				})
			})
			
		}
		//get data from delivery unit untuk dapatkan barang yang auto picking
		$.getJSON(http + "/cashier/getbarangfromdounit/true",function(result){
			if(result.status){
				$.each(result.message,function(e,d){
					datax.push({
						'value': d.NO_TRANS,
						'text': d.NO_TRANS,
						'description': stripslashes(d.NO_REFF)
					})
				})
				$('#lbr').html("");
			}else{
				$('#lbr').html("");
				$('#ldgsp').html("");
			}
			$('#ldgsp').html("");

		})
		$("#no_reff_sp").inputpicker({
			data: datax,
			fields: ['text', 'description'],
			fieldValue: 'value',
			fieldText: 'text',
			filterOpen: true
		}).on("change", function(e) {
			e.preventDefault();
			var dx = datax.findIndex(obj => obj['value'] === $(this).val());
			if (dx > -1) {
				$("#ket_reff_sp").val($('#jenis_transaksi').val() + " Counter [ " + datax[dx]["description"] + " ]");
				var asal= (datax[dx]["text"]).substring(0,2);
				console.log(asal);
				if(asal==='SP'){
					__loadItemSO(datax[dx]['value']);
				}else{
					__loadItemDO(datax[dx]['value']);
				}
			}
		})
	})
}

function __loadItemDO(notrans){
	$.getJSON(http + "/cashier/getbarangfromdounit/true",{'no_trans':notrans,'mode':'1'},function(result){
		var html = "";
		var bariskes = 0;
		var total_bayar = 0;
		var jml = 0;
		var tt_harga = 0;
		var trans = "";
		$('#lst_sp > tbody').html("");
		if(result.status){
			$.each(result.message,function(e,d){
				html += "<tr><td class='text-center'>" + (bariskes + 1) + "</td>";
				html += "<td class='text-center'><a class='disabled-action' onclick=\"__hapus_item_sp('" + bariskes + "')\" role='button'><i class='fa fa-trash'></i></a></td>";
				html += "<td>" + d.KD_BARANG + " - " + d.NAMA_ITEM + "</td><td class='text-right'>" + $.number(d.JUMLAH);
				html += "</td><td class='text-right' title='Harga :" + $.number(d.HARGA_JUAL)  + "'>" + $.number(d.HARGA_JUAL);
				html += "</td><td class='text-right'>" + $.number((parseFloat(d.JUMLAH) * parseFloat(d.HARGA_JUAL)));
				html += "</td><td class='hidden'>" + d.KD_BARANG + "</td><td class='hidden'>100.52102.02</td>";
				html += "<td class='hidden'>:" + d.KD_BARANG + ":Barang</td></tr>";
				bariskes++;
				total_bayar += (parseFloat(d.JUMLAH_ORDER) * parseFloat(d.HARGA_JUAL));
				jml += d.JUMLAH;
				diskon = '0';
				tt_harga += (d.HARGA_JUAL);
			})
			$('#service_reff').html($('#jenis_transaksi').val() + " Counter no.reff : " + $('#no_reff_sp').val());
			$('#jml_total').html('1'); //$.number(jml));
			$('#harga_total').html($.number(tt_harga));
			$('#grand_total').html($.number(total_bayar));
		$('#lst_sp > tbody').append(html);
		$('#ldgsp').html("");
		$("#jml_bayar")
			.val($.number(total_bayar))
			.mask('#,##0', {
				reverse: true
			})
		$('#ket_reff_sp').focus();
		}
		
	})
}
/**
 * load detail Item Sales Order Part
 * @param  {[type]} notrans [description]
 * @return {[type]}         [description]
 */
function __loadItemSO(notrans) {
	$('#ldgsp').html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
	var jtrans = $('#jenis_transaksi').val();
	var sost = (jtrans == 'Pengeluaran Barang') ? '0' : '1';
	var jso = (jtrans == 'Penjualan Sparepart') ? 'Part' : 'Barang';
	$.getJSON(http + "/cashier/listsop/true/true/" + jso, {
		'no_trans': notrans,
		'sosts': sost
	}, function(result) {
		//console.log(result.sod);
		var html = "";
		var bariskes = 0;
		var total_bayar = 0;
		var jml = 0;
		var tt_harga = 0;
		var trans = "";
		$('#lst_sp > tbody').html("");
		if (result.sod) {
			$.each(result.sod, function(index, d) {

				html += "<tr><td class='text-center'>" + (bariskes + 1) + "</td>";
				html += "<td class='text-center'><a class='disabled-action' onclick=\"__hapus_item_sp('" + bariskes + "')\" role='button'><i class='fa fa-trash'></i></a></td>";
				html += "<td>" + d.PART_NUMBER + " - " + d.PART_DESKRIPSI + "</td><td class='text-right'>" + $.number(d.JUMLAH_ORDER);
				html += "</td><td class='text-right' title='Harga :" + $.number(d.HARGA_JUAL) + "\nDiskon :" + $.number(d.DISKON) + "'>" + $.number(d.HARGA_JUAL);
				html += "</td><td class='text-right'>" + $.number((parseFloat(d.JUMLAH_ORDER) * parseFloat(d.HARGA_JUAL)) - parseFloat(d.DISKON));
				html += "</td><td class='hidden'>" + d.PART_NUMBER + "</td><td class='hidden'>100.52102.02</td>";
				html += "<td class='hidden'>:" + d.PART_NUMBER + ":"+jso+"</td></tr>";
				bariskes++;
				total_bayar += (parseFloat(d.JUMLAH_ORDER) * parseFloat(d.HARGA_JUAL)) - parseFloat(d.DISKON);
				jml += d.JUMLAH_ORDER;
				diskon = d.DISKON;
				tt_harga += (d.HARGA_JUAL);
			});
			$('#service_reff').html($('#jenis_transaksi').val() + " Counter no.reff : " + $('#no_reff_sp').val());
			$('#jml_total').html('1'); //$.number(jml));
			$('#harga_total').html($.number(tt_harga));
			$('#grand_total').html($.number(total_bayar));
		}
		$('#lst_sp > tbody').append(html);
		$('#ldgsp').html("");
		$("#jml_bayar")
			.val($.number(total_bayar))
			.mask('#,##0', {
				reverse: true
			})
		//$('#sparepart input:not(#no_reff_sp,#ket_reff_sp,#jml_bayar)').val('');
		$('#ket_reff_sp').focus();
	});
}

function __pilihBayar(id) {
	var jml = parseFloat($('#jumlah_pbp').val().replace(/,/g, ''));
	var jmlp = parseFloat($('#jumlah_pb').val().replace(/,/g, ''));
	var jnt = parseInt($('#junit').html());

	if ($('#ok_' + id).is(":checked") === true) {
		$("input[type='text'][id*='" + id + "']").removeClass("disabled-action");
		$("input[type='text'][id*='" + id + "']").each(function(el) {
			if (!$(this).attr("readonly")) {
				jml += parseFloat($(this).val().replace(/,/g, ''));
			}
		})
		jnt += 1;

	} else {
		$("input[type='text'][id*='" + id + "']").addClass("disabled-action");
		$("input[type='text'][id*='" + id + "']").each(function(el) {
			if (!$(this).attr("readonly")) {
				jml -= parseFloat($(this).val().replace(/,/g, ''));
			}
		})
		jnt -= 1;
	}

	$('#junit').html(jnt + "/").removeClass("hidden");
	if (jml > 0) {
		$('#smp').removeClass("disabled-action");
	} else {
		$('#smp').addClass("disabled-action");
	}
	$('#jumlah_pbp').val($.number(jml));
	$("#list_pinjamanb > tbody > tr:eq(0) td:eq(4)").html($.number(jml))
	$("#list_pinjamanb > tbody > tr:eq(0) td:eq(5)").html($.number(jml))
	$('#jumlah_u').val(jml.toLocaleString());
}

function __changeHarga(id) {
	var p = 0;
	var jml = 0;

	$(".price").each(function(el) {
		var p = parseFloat($(this).val().replace(/,/g, ''));
		var idd = $(this).attr('id').split("_");
		var x = null;
		$("#ok_" + idd[1]).each(function() {
			x = ($(this).is(":checked"));
			//console.log($(this).attr("id")+ "::"+x);
			if (x) {
				jml += (isNaN(p)) ? 0 : p;
			}

		})

	})
	//jml=jml+val;
	$('#jumlah_pbp').val($.number(jml));
	$('#jumlah_u').val($.number(jml));
	$("#list_pinjamanb > tbody > tr:eq(0) td:eq(4)").html($.number(jml))
	$("#list_pinjamanb > tbody > tr:eq(0) td:eq(5)").html($.number(jml))
}

function __getBatalSPK() {
	$('#ldgspk').html("<i class='fa fa-spinner fa-spin'></i>");
	$.getJSON(http + "/spk/batal_spk_view", function(result) {
		var datax = [];
		if (result.totaldata > 0) {
			$.each(result.message, function(e, d) {
				datax.push({
					'NO_SPK': d.NO_SPK,
					'NAMA': stripslashes(d.NAMA_CUSTOMER),
					'ALAMAT': stripslashes(d.ALAMAT_SURAT) + " " + d.NAMA_DESA,
					'HARGA': d.HARGA,
					'NO_KWITANSI': d.NO_TRANS
				})
			})
		}
		$('#no_spk_batal').inputpicker({
			data: datax,
			fields: ["NO_SPK", "NAMA", "ALAMAT", 'HARGA'],
			fieldValue: "NO_SPK",
			fieldText: "NO_SPK",
			filterOpen: true
		}).on("change", function() {
			var dx = datax.findIndex(obj => obj['NO_SPK'] === $(this).val());
			if (dx > -1) {
				$("#uraian_u").val('Pengembalian Pembelian Unit No. SPK :' + datax[dx]["NO_SPK"])
				$('#no_reff_u').val(datax[dx]["NAMA"] + ' - ' + datax[dx]["ALAMAT"]);
				$('#jumlah_u').val(parseFloat(datax[dx]["HARGA"]).toLocaleString()).mask('#,##0', {
					reverse: true
				});
				$('#no_kwt_lama').val(datax[dx]["NO_KWITANSI"]);
				$('#terbilang_u').html(terbilang(parseFloat(datax[dx]["HARGA"])) + ' Rupiah');
			}
		})
		$('#ldgspk').html("");
	})
}

function __showDtl() {
	var buka = $('#list_pinjamanl').hasClass("hidden");
	if (buka) {
		$('#list_pinjamanl').removeClass('hidden');
		$('.list_pinjamanl').removeClass('hidden');
	} else {
		$('#list_pinjamanl').addClass('hidden');
		$('.list_pinjamanl').addClass('hidden');
	}
}