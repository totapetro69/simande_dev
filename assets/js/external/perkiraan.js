var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
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
			$('#jml').focus().select();
			if(dx>-1){
				$('#nama_akun').val(datax[dx]['NAMA AKUN']);
				$('#kd_akun_1').val(datax[dx]['NO_AKUN'])
				$('#sub_akun').val(datax[dx]['SUB_AKUN'])

			}
		})
	})
}
function __simpanItem(){
	if(!$('#jml').val()){
		alert("Jumlah tidak boleh kosong!");
		return
	}
	$('#jml').unmask();
	$('#ldg').removeClass("hidden");
	$.ajax({
		type :'post',
		url :http+"/finance/jurnal_head_simpan",
		data : $('#addForm').serialize(),
		//dataType :'json',
		success : function(result){
			var lst="";
			var nojn=$('#no_jurnal').val();
			if(!nojn){
				$('#no_jurnal').val(result);
			}
			
			$.get(http+"/finance/jurnal_detail/",{'no_jurnal':result},function(data){
				$('#lst tbody').html(data);
				$('div#jurnal_item input').val('');
				$('#jml').mask("#,##0",{reverse: true});
				$('#ldg').addClass("hidden");
				keluar();
			})
		}
	})
}
function __repost(no_spk,no_jurnal,kd_trans){
	$('#ldg-repost').removeClass("hidden");
	var urls ="";
	switch(kd_trans){
		case 'K014':urls=http+"/cashier/generate_jurnal_detail/"+no_spk+"/"+no_jurnal;break;
		case 'D008':urls=http+"/cashier/postingjurnal_service/"+no_spk+"/"+no_jurnal;break;
		default :http+"/cashier/generate_jurnal/"+kd_trans+"/"+no_spk;break;
	}
	$.ajax({
		type:'POST',
		url : urls,
		data:{'no_jurnal':no_jurnal},
		success :function(result){
			$.get(http+"/finance/jurnal_detail/",{'no_jurnal':no_jurnal},function(data){
				$('#lst tbody').html(data);
				//$('div#jurnal_item input').val('');
				//$('#jml').mask("#,##0",{reverse: true});
				$('#ldg-repost').addClass("hidden");
				//keluar();
			})
		}
	})
}
function __hapus_jurnal_detail(id,no_jurnal){
	if(confirm('Yakin akan hapus item ini?')){
		$('#xls_'+id).html("<i class='fa fa-spinner fa-spin' style='color:red'></i>");
		$.getJSON(http+"/finance/hapus_jurnal_detail",{'id':id},function(result,status){
			if(result.status==true){
				$.get(http+"/finance/jurnal_detail/",{'no_jurnal':no_jurnal},function(data){
					$('#lst tbody').html(data);					
				})
			}
		})
	}
}