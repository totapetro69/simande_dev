var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function(){
	$('#myModalLg').on('hidden.bs.modal', function () {
    	document.location.reload();
	})
})
function __get_list(id){
	var html="<tr><td colspan='8'><i class='fa fa-spinner fa-spin '></i> Loading data ... please wait</td></tr>"; var n=0;
	$('#lst > tbody').html(html);
	$.get(http+"/finance/setup_jno/true/true",{'kd':id},function(result){
		
		if(result.length >0){
			$('#lst > tbody').html(result);
		}else{
			$('#lst > tbody').html('');
		}
	})
	var tptrans=$('#kd_transaksi').val();
	switch(tptrans){
		case 'K014':
			$('#tp_transaksi option.pj').removeClass("hidden");
			$('#tp_transaksi option:not(.pj)').addClass("hidden");
			break;
		case 'D010':
		case 'D011':
			$('#tp_transaksi option.pb').removeClass("hidden");
			$('#tp_transaksi option:not(.pb)').addClass("hidden");
			break;
		case 'K013':
			$('#tp_transaksi option.sp').removeClass("hidden");
			$('#tp_transaksi option:not(.sp)').addClass("hidden");
			break;
		case 'K010':
		case 'D007':
			$('#tp_transaksi option.pjm').removeClass("hidden");
			$('#tp_transaksi option:not(.pjm)').addClass("hidden");
			break;
		case 'D008':
			$('#tp_transaksi option.svr').removeClass("hidden");
			$('#tp_transaksi option:not(.svr)').addClass("hidden");
			break;
	}
	$('#tp_transaksi option.xx').removeClass("hidden");
	$('#tp_transaksi option#all').removeClass("hidden");
	$('div.akun input').val('');
}
function __hpus_item(id){
	if(confirm('Yakin akan hapus data ini')){
		$('#l_'+id).removeClass('hidden');
		$.getJSON(http+"/finance/jno_delete",{'id':id},function(result){
			if(result.status){
				__get_list($('#kd_transaksi').val());
			}
		})
	}
	
}
function __hapus_jno(kd_trans){
	if(confirm('Yakin akan hapus data ini!')){
		$('#ls_'+kd_trans).removeClass('hidden');
		$.getJSON(http+"/finance/jno_delete_all",{'id':kd_trans},function(result){
			if(result.status){
				document.location.href=http+"/finance/setup_jno";
			}else{
				alert(result.message);
				$('#ls_'+kd_trans).addClass('hidden');
			}
		})
	}
}
function __simpan(){
	$.ajax({
		type : 'POST',
		url : $('#addForm').attr("action"),
		data : $('#addForm').serialize(),
		dataType: 'json',
		success : function(result){
			__get_list($('#kd_transaksi').val());
		}
	})
}
/**
 * [__getKDAkun description]addForm
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
				'NAMA AKUN':d.NAMA_AKUN,
				'NO_AKUN':d.COST_CENTER+"."+d.NO_AKUN,
				'SUB_AKUN':d.NO_SUBAKUN
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
				$('#kd_akun_1').val(datax[dx]['NO_AKUN'])
				$('#sub_akun').val(datax[dx]['SUB_AKUN'])
			}
		})
	})
}