var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function() {
    console.log($('#tabaktif').val());
    /*if($('#tabaktif').val()=="5"){
        __getdataKeluarga();
    }*/
	$('#propinsi_kk').change(function() {
        loadData("kabupaten_kk", $(this).val());
    })
    $('#kabupaten_kk').change(function() {
        loadData("kecamatan_kk", $(this).val());
    })
    $('#kecamatan_kk').change(function() {
        loadData("desa_kk", $(this).val());
    })
});
function __addItem(){
    var bariske = $("#lst_kk >tbody > tr").length;
    var tr="";
        tr +="<tr><td class='text-center'>"+(bariske+1)+"</td>";
        tr +="<td class='text-center'><i class='fa fa-trash' onclick=\"__hapusItem('"+bariske+"')\"></i></td>";
        tr +="<td class=''>"+$("#nama_kk_l").val()+"</td>";
        tr +="<td class='text-center'>"+$('#sex_kk_l').val()+"</td>";
        tr +="<td class='text-center'>"+$('#bod_kk_l').val()+"</td>";
        tr +="<td class=''>"+$('#nik_kk_l').val()+"</td><td class='hidden'></td>";
        tr +="<td>&nbsp;</td></tr>";
    if(tr){
        $("#lst_kk >tbody").append(tr);
        $('#nama_kk_l').val('');
        $('#sex_kk_l').val('').select();
        $('#bod_kk_l').val('');
        $('#nik_kk_l').val('');
    }
}
function __hapusItem(id){
    $("#lst_kk >tbody > tr:eq("+id+")").remove();
}
function __hapusDaya(id){
    //$("#lst_kk >tbody > tr:eq("+id+")").remove();
    
}
function __getdataKeluarga(){
    var kd_cus=$('#kd_customer').val();
    console.log('kode customer:'+kd_cus);
    if(!kd_cus){return false;}
    var no_kaka="";
    $('#l_nokk').html("<i class='fa fa-spinner fa-spin'></i>");
    $.getJSON(http+"/spk/getDataKK",{'kd_cus':kd_cus},function(result){
        if(result.totaldata >0){
            $.each(result.message,function(e,d){
                if(d.NO_KK){
                    $('#no_kk').val(d.NO_KK).addClass('disabled-action');  
                    no_kaka = d.NO_KK;     
                }       
            })
            if(no_kaka){
                __getDataKK(no_kaka);
                __loadDataKK(no_kaka);
            }
            $('#l_nokk').html("");
        }else{
            $('#l_nokk').html("");
        }
    })
}
function __getDataKK(noKK){
    $('#l_nokk').html("<i class='fa fa-spinner fa-spin'></i>");
    $.getJSON(http+"/spk/getDataKK/"+noKK,function(result){
        if(result.status){
            $.each(result.message,function(e,d){
                $('#alamat_kk').val(d.ALAMAT_KK);
                $('#rtrw_kk').val(d.RTRW_KK);
                $('#propinsi_kk').val(d.KD_PROPINSI).select();
                $.when(
                    loadData('kabupaten_kk',d.KD_PROPINSI,d.KD_KABUPATEN),
                    loadData('kecamatan_kk',d.KD_KABUPATEN, d.KD_KECAMATAN),
                    loadData('desa_kk', d.KD_KECAMATAN, d.KD_DESA),
                );
            })
        }
    })
}
function __loadDataKK(nokk){
    var tr="";n=0;
    $.getJSON(http+"/spk/getDataKK/"+nokk+"/1",function(result){
        if(result.status){
            $.each(result.message,function(e,d){
                n++;
                tr +="<tr><td class='text-center'>"+n+"</td>";
                tr +="<td class='text-center'><i class='fa fa-trash' onclick=\"__hapusData('"+d.ID+"')\"></i></td>";
                tr +="<td class=''>"+d.NAMA_ANGGOTA+"</td>";
                tr +="<td class='text-center'>"+d.JENIS_KELAMIN+"</td>";
                tr +="<td class='text-center'>"+d.TGL_LAHIR+"</td>";
                tr +="<td class=''>"+d.NIK_ANGGOTA+"</td><td class='hidden'>"+d.ID+"</td>";
                tr +="<td>&nbsp;</td></tr>";
            })
            $("#lst_kk >tbody").append(tr);
            $('#l_nokk').html("");
        }else{
            $('#l_nokk').html("");
        }
    })
}
function __simpan_kk(){
	if(!$('#addForm_kk').valid()){return false;}
	if($('#spkid').val()=='0'){ return;}
    var bariske = $("#lst_kk >tbody > tr").length;
    var datakk = [];
    var no_kk=$('#no_kk').val();
    var kd_cus=$('#kd_customer').val();
    var spkid = $('#spkid').val();
    for(i=0 ; i < (parseInt(bariske)); i++){
        var id= $("#lst_kk >tbody > tr:eq("+i+") td:eq(6)").text();
        if($.trim(id).length==0){
        	datakk.push({
        		'no_kk':no_kk,
        		'nama_anggota': $("#lst_kk >tbody > tr:eq("+i+") td:eq(2)").text(),
        		'jenis_kelamin':$("#lst_kk >tbody > tr:eq("+i+") td:eq(3)").text(),
        		'tgl_lahir':$("#lst_kk >tbody > tr:eq("+i+") td:eq(4)").text(),
        		'nik_anggota':$("#lst_kk >tbody > tr:eq("+i+") td:eq(5)").text()
        	})
        }
    }
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'POST',
        url: http + '/spk/simpankk_spk',
        data: $('#addForm_kk').serialize() + '&kk=' + JSON.stringify(datakk)+'&kd_cus='+kd_cus+'&espekaid='+spkid,
        success: function(result) {
            console.log(result);
        	var d = result.split(":");
            if(parseInt(d[2])==1){
        		$('.success').animate({top:"0"},500);
        		$('.success').html("Data berhasil disimpan").fadeIn();
        	if (parseInt(d[1]) == 0) {
                    setTimeout(function() {
                        document.location.href = "?tab=5&id=" + d[0];
                    }, 2000);
                } else {
                    setTimeout(function() {
                        document.location.href = "?tab=5&id=" + d[0];
                    }, 2000);
                }
            }
            $('#loadpage').addClass("hidden");	
    	}
    });
}