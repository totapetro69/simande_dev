/**
 * jQuery for spk page
 */
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function() {
    var date = new Date();
    date.setDate(date.getDate());
    $('#popup').draggable({
        handle: ".panel-body-overlay"
    });
    $('#date').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        setDate: date,
        todayHighlight: true
    });
    $('.datetime-mulai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });
    //request on 12062018 after payment spk not edited or deleted all item
    var lock_spk = $('#stsspk').val();
    if(parseInt(lock_spk)>1){
        $(".spklock").addClass("disabled-action");
        $(".tbs").removeClass("disabled-action");
    }
    $('#datex, .datex').datepicker({
        format: 'dd/mm/yyyy',
        daysOfWeekHighlighted: "0",
        autoclose: true,
        todayHighlight: true
    });
    __getsalesmandata($('#kd_groupsales').val());
    if ($('#tabaktif').val() == "2" && $('#spk_id').val() != '') {
        __getdataKendaraan();
    }
    if ($('#tabaktif').val() == "3" && $('#spk_id').val() != '') {
        __getdataQuiz();
    }
    if ($('#tabaktif').val() == "1" && $('#spk_id').val() != '') {
        __getdataCustomer();
    }
    if ($('#tabaktif').val() == "5" && $('#spk_id').val() != '') {
        __getdataKeluarga();
    }
    $('#kd_sales').click();
    $('#kd_groupsales').change(function() {
        switch ($('#kd_groupsales').val()) {
            case "TP":
                $('#kd_salesman_tp').removeClass('hidden');
                $('#pilihsales').addClass('hidden')
                break;
            case "MK":
                $('#kd_salesman_tp').addClass('hidden');
                __getsalesmandatamk($('#kd_groupsales').val());
                $('#pilihsales').removeClass('hidden')
                break;
            default:
                $('#kd_salesman_tp').addClass('hidden');
                $('#pilihsales').removeClass('hidden')
                __getsalesmandata($('#kd_groupsales').val());
                break;
        }
        $('#nama_sales').val('');
    });
    $('#uang_muka').mask('#,##0',{reverse:true})
    $('#jumlah_angsuran').mask('#,##0',{reverse:true})
    $('#biaya_adm').mask('#,##0',{reverse:true})
    $('#bunga').mask('##0%',{reverse:true})
    $('#diskon').mask('#,##0',{reverse:true});
    $('#jml_titipan').mask('#,##0',{reverse:true});
    //tampilkan data detail customer sesuai dengan nama customer yang di pilih
    $('#kd_propinsi').LoadSibling("kd_kabupaten",'');
    $('#kd_propinsi').change(function() {
        //loadData("kd_kabupaten", $(this).val());
        $('#alamat_cust').change();
        if ($('#alamat_sama').is(":checked")) {
            loadData("kd_kabupaten_surat", $(this).val());
        }
    })
    $('#kd_kabupaten').change(function() {
        loadData("kd_kecamatan", $(this).val());
        $('#alamat_cust').change();
        if ($('#alamat_sama').is(":checked")) {
            loadData("kd_kecamatan_surat", $(this).val());
        }
    })
    $('#kd_kecamatan').change(function() {
        loadData("kd_desa", $(this).val());
        $('#alamat_cust').change();
        if ($('#alamat_sama').is(":checked")) {
            loadData("kd_desa_surat", $(this).val());
        }
    })
    $('#kd_desa').change(function() {
        $('#alamat_cust').change();
        if ($('#alamat_sama').is(":checked")) {
            $('#kd_desa_surat').val($('#kd_desa').val()).select();
        }
    })
    $('#kode_pos').change(function() {
        if ($('#alamat_sama').is(":checked")) {
            $('#kode_possurat').val($(this).val());
        }
    })
    $('#kd_propinsi_surat').change(function() {
        loadData("kd_kabupaten_surat", $(this).val());
    })
    $('#kd_kabupaten_surat').change(function() {
        loadData("kd_kecamatan_surat", $(this).val());
    })
    $('#kd_kecamatan_surat').change(function() {
        loadData("kd_desa_surat", $(this).val());
    })
    $('#mnu').removeClass("dropdown-menu-right");
    //end of document ready function
    //$('#tabs-1').click();
    $('.nav-tabs a').click(function() {
        $(this).tab('show');
    });
    $('.nav-tabs li a').on('shown.bs.tab', function(e) {
        e.preventDefault();
        var current_tab = e.target;
        var previous_tab = e.relatedTarget;
        $('#tabaktif').val(current_tab);
        var tab = $("#tabaktif").val();
        tab = (tab != '') ? tab.split('#')[1] : "tabs-1";
        txt = "<i class='fa fa-save fa-fw'></i> Simpan";
        //check apakah ada perubahan di tab sebelumnya yang belum di simpan  
        console.log(tab);
        switch (previous_tab.hash) {
            case '#tabs-2':
                if (!$('#btn-simpan_motor').hasClass('disabled')) {
                    var pindah = confirm("Ada perubahan di " + previous_tab.innerText + " belum disimpan\nSimpan perubahan?");
                    if (pindah) {
                        $('.nav-tabs a[href="#tabs-3"]').tab('show');
                        return true;
                    }
                }
                break;
            case '#tabs-3':
                if (!$('#btn-simpan_quiz').hasClass('disabled')) {
                    var pindah = confirm("Ada perubahan di " + previous_tab.innerText + " belum disimpan\nSimpan perubahan?");
                    if (pindah) {
                        $('.nav-tabs a[href="#tabs-2"]').tab('show');
                        return true;
                    }
                }
                break;
            case '#tabs-4':
                if (!$('#btn-simpan_quiz').hasClass('disabled')) {
                    var pindah = confirm("Ada perubahan di " + previous_tab.innerText + " belum disimpan\nSimpan perubahan?");
                    if (pindah) {
                        $('.nav-tabs a[href="#tabs-1"]').tab('show');
                        return true;
                    }
                }
                break;
            case '#tabs-5':
                if (!$('#btn-simpan_quiz').hasClass('disabled')) {
                    var pindah = confirm("Ada perubahan di " + previous_tab.innerText + " belum disimpan\nSimpan perubahan?");
                    if (pindah) {
                        $('.nav-tabs a[href="#tabs-4"]').tab('show');
                        return true;
                    }
                }
                break;
            default:
                if (!$('#btn-simpan_cs').hasClass('disabled')) {
                    if (confirm("Ada perubahan di " + previous_tab.innerText + " belum disimpan\nSimpan perubahan?")) {
                            $('.nav-tabs a[href="#tabs-1"]').tab('show');
                        return true;
                    }
                    
                }
                break;
        }
        switch (tab) {
            case 'tabs-2':
                $('#btn-simpan_cs').addClass("hidden");
                $('#btn-simpan_motor').removeClass("hidden");
                $('#btn-simpan_quiz').addClass("hidden");
                $('#btn-simpan_kk').addClass("hidden");
                if ($('#espekaid').val() != '') {
                    __getdataKendaraan();
                }
                break;
            case 'tabs-3':
                $('#btn-simpan_cs').addClass("hidden");
                $('#btn-simpan_motor').addClass("hidden");
                $('#btn-simpan_quiz').removeClass("hidden");
                $('#btn-simpan_kk').addClass("hidden");
                if ($('#spkid_quiz').val() != '') {
                    __getdataQuiz();
                }
                break;
            case 'tabs-4':
                $('#btn-simpan_cs').addClass("hidden");
                $('#btn-simpan_motor').addClass("hidden");
                $('#btn-simpan_quiz').addClass("hidden");
                $('#btn-simpan_kk').addClass("hidden");
                break;
            case 'tabs-5':
                $('#btn-simpan_cs').addClass("hidden");
                $('#btn-simpan_motor').addClass("hidden");
                $('#btn-simpan_quiz').addClass("hidden");
                $('#btn-simpan_kk').removeClass("hidden");
                if ($('#espekaid').val() != '') {
                    __getdataKeluarga();
                }
                break;
            default:
                $('#btn-simpan_cs').removeClass("hidden");
                $('#btn-simpan_motor').addClass("hidden");
                $('#btn-simpan_quiz').addClass("hidden");
                $('#btn-simpan_kk').addClass("hidden");
                break;
        }
        return false
    });
    $('#jenis_penjualan').change(function() {
        if ($(this).val() == "2") {
            $('#jp_antardealer').removeAttr("disabled").val("1").select().change();
            $('#kd_dealer').removeAttr("disabled");
        } else {
            $('#jp_antardealer').attr("disabled", "disabled").val("").select();
            $('#kd_dealer').attr("disabled", "disabled");
        }
    })
    $('#jp_antardealer').change(function() {
        if($(this).val()=='1'){
            //console.log($('#chanel').val());
            __loadDataOthersDealer($('#chanel').val());
            $('#type_penjualan').attr('disabled','disabled');
            $('#kd_typecustomer').append("<option value='DLR' selected>DEALER</option>").attr('disabled','disabled');
            $('#jenis_harga').val("Off The Road").select();
            $('#jenis_harga').addClass("disabled-action")
            $('#kd_groupsales').val("TP").select();
        }else{
            $('#type_penjualan').removeAttr('disabled');
            $('#kd_typecustomer').removeAttr('disabled');
            $("#kd_typecustomer option[value='DLR']").remove();
            $('#jenis_harga').val("On The Road").select();
            $('#jenis_harga').removeClass("disabled-action")
            $('#kd_groupsales').val("").select();
            __loadDataOthersDealer('');
        }
    })
    if ($('#spkid').val() == '') {
        $('#btn-simpan*').removeClass("disabled");
    }
    $('#btn-simpan_spk').click(function() {
        var tab = $("#tabaktif").val();
        switch (tab) {
            case 'tabs-2':
                __simpan_kendaraan();
                break;
            case 'tabs-3':
                __simpan_quiz();
                break;
            default:
                __simpan_customer();
                break;
        }
    })
    $('#btn-simpan_cs').click(function() {
        __simpan_customer();
    })
    $('#btn-simpan_motor').click(function() {
        __simpan_kendaraan();
    })
    $('#btn-simpan_quiz').click(function() {
        __simpan_quiz();
    })
    $('#btn-simpan_kk').click(function() {
        __simpan_kk();
    })
    $('#addForm_cs').on('keyup change paste', "input[type!='hidden'], select, textarea", function() {
        $('#btn-simpan_cs').removeClass('disabled');
        $('#btn-simpan_cs').removeClass('disabled-action');
    })
    $('#addForm_motor').on('keyup paste click', "input[type!='hidden'], select, textarea", function() {
        $('#btn-simpan_motor').removeClass('disabled');
        $('#btn-simpan_motor').removeClass('disabled-action');
    })
    $('#addForm_quiz').on('keyup change paste', "input[type!='hidden'], select, textarea", function() {
        $('#btn-simpan_quiz').removeClass('disabled');
        $('#btn-simpan_quiz').removeClass('disabled-action');
    })
    $('#addForm_kk').on('keyup change paste', "input[type!='hidden'], select, textarea", function() {
        $('#btn-simpan_kk').removeClass('disabled');
        $('#btn-simpan_kk').removeClass('disabled-action');
    })
    $('#likeNama').click(function() {
        if ($('#likeNama').is(":checked")) {
            $('#nama_dibpkb')
                .val($('#kd_guest option:selected').text())
                .addClass('disabled-action')

        } else {
           $('#nama_dibpkb').val("").removeClass('disabled-action');
        }
    })
    $("#likeAlamat").click(function() {
        var alamatrumah = $('#alamat_cust').val();
        // alamatrumah += "\n" + $("#kd_desa option:selected").text();
        // alamatrumah += " Kec.  " + $("#kd_kecamatan option:selected").text();
        // alamatrumah += "\n" + $("#kd_kabupaten option:selected").text();
        // alamatrumah += " ," + $("#kd_propinsi option:selected").text();
        if ($("#likeAlamat").is(":checked")) {
            $('#alamat_dibpkb').val(alamatrumah).addClass('disabled-action');
            $('#kd_propinsi_bpkb').val($('#kd_propinsi').val()).addClass('disabled-action')
            $('#kd_kabupaten_bpkb').val($('#kd_kabupaten').val()).addClass('disabled-action')
            $('#kd_kecamatan_bpkb').val($("#kd_kecamatan").val())
            $('#kd_kelurahan_bpkb').val($("#kd_desa").val())
            $('#kode_posbpkb').val($('#kode_pos').val());
            $('#kode_posbpkb').on('focus',function(){
                $(this).val($('#kode_pos').val());
            })
        } else {
            $('#alamat_dibpkb').val('').removeClass('disabled-action');
            $('#kd_propinsi_bpkb').removeClass('disabled-action');
            $('#kd_kabupaten_bpkb').val('0').removeClass('disabled-action');
            // $('#kecamatan_bpkb').val('')
            // $('#kelurahan_bpkb').val('')
            $('#kode_posbpkb').val('');
        }
    })
    $('#alamat_cust').change(function() {
        var alamatrumah = $('#alamat_cust').val();
        /*alamatrumah += "\n" + $("#kd_desa option:selected").text();
        alamatrumah += " Kec.  " + $("#kd_kecamatan option:selected").text();
        alamatrumah += "\n" + $("#kd_kabupaten option:selected").text();
        alamatrumah += " ," + $("#kd_propinsi option:selected").text();*/
        if ($("#likeAlamat").is(":checked")) {
            $('#alamat_dibpkb').val(alamatrumah);
        } else {
            //$('#alamat_dibpkb').val('');
        }
        if ($("#alamat_sama").is(":checked")) {
            $('#alamat_surat').val($('#alamat_cust').val());
        }
    })
    $('#alamat_sama').click(function() {
        if ($('#alamat_sama').is(":checked")) {
            $('#alamat_surat').val($('#alamat_cust').val());
            $('#kd_propinsi_surat').val($('#kd_propinsi').val()).select();
            $('#kd_kabupaten_surat').val($('#kd_kabupaten').val()).select();
            $('#kd_kecamatan_surat').val($('#kd_kecamatan').val()).select();
            $('#kd_desa_surat').val($('#kd_desa').val()).select();
            $("#kode_possurat").val($('#kode_pos').val())
            $('#almt_sama').addClass("disabled-action")
        } else {
            $('#alamat_surat').val('');
            $('#kd_propinsi_surat').val('0').select();
            $('#kd_kabupaten_surat').val('0').select();
            $('#kd_kecamatan_surat').val('0').select();
            $('#kd_desa_surat').val('0').select();
            //$("#kode_possurat").val('');
             $('#almt_sama').removeClass("disabled-action")
        }
    })
    if ($('#alamat_sama').is(":checked")) { $('#almt_sama').addClass("disabled-action");}
    $('#kd_guest').change(function() { __getdataCustomer();  })
    var spkid = $('#spkid').val();
    if (spkid != '') {
        $('#kd_guest').addClass('disabled');
        if($('#tabaktif').val() != "3"){
            __getdataCustomer();
            //__getdataKeluarga();
        }
    }
    $('#type_penjualan').on('change',function(){
        if($('#jenis_penjualan').val()=='1' && $(this).val() =='CASH' && $('#jenis_harga').val()=='On The Road'){
            __getsalesprogram();
        }
    })
    $('#jenis_harga').on("change",function(){
        if($(this).val()=='On The Road'){
            __getsalesprogram();
        }
    })
    $('#kd_fincom').change(function() {
        var kodeLsg =$('#kode_leasing').val();
        if ($(this).val()!=''){
            //alert($('#kode_leasing').val()+'=='+$(this).val());
            if($(this).val()!=kodeLsg){
                var urutan = __CheckurutanLeasing();
                //alert(urutan);
                if(urutan){
                    __getsalesprogram();
                    __getSalesKupon();
                }
            }
            if($(this).val()=='CSH' || $(this).val()=='KDS'){
                $('#jumlah_angsuran').removeClass('disabled-action');
            }else{
                $('#jumlah_angsuran').removeClass('disabled-action');
            } 
        }
        $('#kode_leasing').val($(this).val());
    });
    $('#jangka_waktu').change(function(){
        __getSalesKupon();
    })
    if ($('#fincoms').attr('disabled')) {
        // __getSalesKupon();
    }
    $('#nama_penerima').on('focus', function() {
        var alamatrumah = $('#alamat_cust').val();
        alamatrumah += "\n" + $("#kd_desa option:selected").text();
        alamatrumah += " Kec. " + $("#kd_kecamatan option:selected").text();
        alamatrumah += "\n" + $("#kd_kabupaten option:selected").text();
        alamatrumah += " ," + $("#kd_propinsi option:selected").text();
        $(this).val($('#kd_guest option:selected').text());
        $('#alamat_pengiriman').val(alamatrumah);
        $('#no_hp_surat').val($('#no_hp').val());
        $('#like_alamatrumah').attr('checked', 'checked');
    })
    $('input:radio[name="like_alamat"]').click(function() {
        var id = $(this).attr('id');
        switch (id) {
            case "like_alamatrumah":
                $('#nama_penerima').focus();
                break;
            case "like_alamatsurat":
                var alamatsurat = $('#alamat_surat').val();
                alamatsurat += "\n" + $("#kd_desa_surat option:selected").text();
                alamatsurat += " Kec.  " + $("#kd_kecamatan_surat option:selected").text();
                alamatsurat += "\n" + $("#kd_kabupaten_surat option:selected").text();
                alamatsurat += " ," + $("#kd_propinsi_surat option:selected").text();
                $(this).val($('#kd_guest option:selected').text());
                $('#alamat_pengiriman').val(alamatsurat);
                $('#like_alamatsurat').attr('checked', 'true');
                $('#no_hp_surat').val($('#no_hp').val());
                break;
            default:
                $(this).val('');
                $('#alamat_pengiriman').val('');
                $('#lainnya').attr('checked', 'true');
                $('#no_hp_surat').val('');
                break;
        }
    })
    $('#app').click(function() {
        ApproveCredit($('#spk_id').val())
    })
    $('#kd_saleskupon').change(function() {
      if($(this).val()!=''){
        __cekKuponOnItem();
      }
    })
    $('#kd_salesprogram').change(function() {
      if($(this).val()!=''){
        __cekItemSalesProgramOnItemList($(this).val());
      }
    })
    $('#kd_bundling').change(function() {
      if($(this).val()!=''){
        __CheckBundlingOnItem();
      }
    })
    $('#qty').ForceNumericOnly();
    $('#qty').on('focusout',function(){
        var jml = $(this).val();
        var hjual=$('#harga_jual').val().replace(/,/g,'');
        var bbn=$('#biaya_stnk').val().replace(/,/g,'');
        var total=0;
        total =(parseFloat(jml)*parseFloat(hjual))+(parseFloat(jml)*parseFloat(bbn));
        $('#total').val(total.toLocaleString());
    })
    // finansial proses approval 
    $('#detailAngsuran').on('click',function(){
        if($('#detail_lsg').hasClass('hidden')){
            $('#detail_lsg').removeClass("hidden");
        }else{
            $('#detail_lsg').addClass("hidden");
        }
        //console.log($('#detail_lsg').hasClass('hidden'))
    })
    $('#app_status').on('change',function(){
        if($(this).val()!='Approve'){
            $('#alasan').attr('required',true);
            $('#alasan').val("Lainnya").select();
            // $('#als_not_app').addClass("hidden");
            $('#als_not_app').removeClass("hidden");
            $('#ket_lain').addClass("hidden")
            $('#alasan').change();
            $('#alasane').val("Alasan Un Approve")
        }else{
            $('#alasan').attr('required',false).removeClass("hidden");
            $('#als_not_app').addClass("hidden");
            $('#ket_lain').addClass("hidden");
            $('#alasane').val("Alasan Lainnya")
        }
    })
    $('#alasan').on('change',function(){
        if($(this).val()=='Lainnya'){
            $('#ket_alasan').attr('required',true)
            $('#ket_lain').removeClass("hidden");
        }else{
            $('#ket_alasan').attr('required',false)
            $('#ket_alasan').empty();
            $('#ket_lain').addClass("hidden")
        }
    })
    $('#upd_app').click(function(){
        if(confirm("Yakin Pengajuan ini akan di "+$('#app_status').val()+"?")){
            ApproveCredit($('#app_status').val())
        }
    })
    $('#change_lsd').on("click",function(){
        $('#popup').addClass("hidden");
        $('#detail_lsg').removeClass("hidden");
        $('#fincoms'). removeAttr("disabled");
        /*$('#fincoms #kd_fincom').val("").select();
        $('#fincoms #jangka_waktu').val("0").select();
        $('#fincoms #type_credit').val("CREDIT").select();
        $('#fincoms input').val("");*/
        $('#popup select').attr("required",false)
        $('#popup input').attr("required",false);
        $('#btn-simpan_motor').addClass("btn-info");
    })
    var tp_jual=$('#type_penjualan').val();
    if(tp_jual=='CREDIT'){$('#kuponsales').removeClass("disabled-action");}
    $('#type_penjualan').on('change',function(){
        if($(this).val()=='CREDIT'){
            $('#fld_leasing').removeAttr('disabled');
            $('#kuponsales').removeClass("disabled-action");
        }else{
            $('#fld_leasing').attr('disabled',true);
            $('#kuponsales').addClass("disabled-action");
            $('#kd_salesprogram').removeClass('disabled-action');
        }
    })
    __loadHoby();
    $('#bunga').change(function(){
        if($('#kd_fincom').val()=='CSH' || $('#kd_fincom').val()=='KDS'){
            var hargamotor=0;
            hargamotor =$("#lst_motor >tbody > tr:eq(0) td:eq(1)").text().replace(/,/g, '');
            if(parseFloat(hargamotor)>0){
                __getCicilan(hargamotor);
            }
        }
    })
    $('#kd_typecustomer').change(function(){
        switch($(this).val()){
            case "R":
            case "RO":
                $("#npwp").html("Nomor NPWP");
                $("#identitas")
                    .html("Nomor KTP/Identitas")
                    .addClass("number")
                    .attr({'minlength':'16','maxlength':'16'});
                $("#tgl").html("Tanggal Lahir");
                $("#reguler").removeClass("hidden");
                $("#gc").addClass("hidden");
            break;
            default:
                //$("#npwp").html("Nomor TDP");
                $("#identitas")
                    .html("Nomor TDP")
                    .removeAttr('minlength')
                    .removeClass('number')
                    .attr({'maxlength':'20'});
                $("#tgl").html("Tanggal TDP");
                $("#reguler").addClass("hidden");
                $("#gc").removeClass("hidden");
            break;
            
        }
    })
})
function __loadHoby(){
   /* $.getJSON(http+"/spk/hoby",{'id':''},function(result){
        var datax=[];
        $('#kd_hobby').html("<option value=''> -- Pilih Hobby --</option>");
        $.each(result,function(index,d){
            // datax.push({
            //     'value':d.KD_HOBBY,
            //     'text':d.NAMA_HOBBY
            // })
            $('#kd_hobby').append("<option value='"+d.KD_HOBBY+"'> ["+d.KD_HOBBY+"] "+d.NAMA_HOBBY+"</option>");
        })
        ////console.log(datax);
        /*$('#kd_hobby').inputpicker({
            data:datax,
            fields:['value','text'],
            headShow:false,
            fieldText:'text',
            filterOpen:true
        })
    })*/
}
function __getdataCustomer() {
    var id_guest = $('#kd_guest').val();
    if (id_guest == 0) {
        return;
    }
    //if(parseInt($("#spkid").val())>0){ return false;}
    $('#loadpage').removeClass("hidden");
    $('#alamat_lg').html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    $.ajax({
        type: 'GET',
        url: http + "/spk/detailguest",
        dataType: 'json',
        data: {
            'id': id_guest
        },
        success: function(result) {
            $.each(result, function(index, d) {
                if ($('#spkid').val() == '') {
                    $('#btn-simpan*').removeClass("disabled");
                } else {
                    $('#btn-simpan_cs').addClass('disabled');
                }
                var tpCus=(d.KD_TYPECUSTOMER)?d.KD_TYPECUSTOMER:"R";
                $('#kd_typecustomer').val(tpCus).select();
                $('#kd_customer').val(d.KD_CUSTOMER);
                $('#nama_customer').val(stripslashes(d.NAMA_CUSTOMER));
                $('#guest_no').val(d.GUEST_NO);
                $('#alamat_cust').val(stripslashes(d.ALAMAT_SURAT));
                if (d.KD_PROPINSI) {
                    $('#kd_propinsi').val(d.KD_PROPINSI).select();
                    $('#kd_propinsi_surat').val(d.KD_PROPINSI).select();
                    //$('#kd_propinsi_bpkb').val(d.KD_PROPINSI).select();
                    $.when(
                        loadData('kd_kabupaten',d.KD_PROPINSI,d.KD_KABUPATEN),
                        loadData('kd_kecamatan',d.KD_KABUPATEN, d.KD_KECAMATAN),
                        loadData('kd_desa', d.KD_KECAMATAN, d.KELURAHAN),
                        loadData('kd_kabupaten_surat', d.KD_PROPINSI, d.KD_KABUPATEN),
                        loadData('kd_kabupaten_bpkb', d.KD_PROPINSI, d.KD_KABUPATEN),
                        loadData('kd_kecamatan_surat', d.KD_KABUPATEN, d.KD_KECAMATAN),
                        loadData('kd_kecamatan_bpkb', d.KD_KABUPATEN, d.KD_KECAMATAN),
                        loadData('kd_kelurahan_bpkb', d.KD_KECAMATAN, d.KELURAHAN),
                        loadData('kd_desa_surat', d.KD_KECAMATAN, d.KELURAHAN))
                    .done( $('#loadpage').addClass("hidden"));
                    //$('#kecamatan_bpkb').val(d.NAMA_KECAMATAN);
                    //$('#kelurahan_bpkb').val(d.NAMA_DESA)
                }
                $('#email_customer').val(d.EMAIL);
                if(!d.EMAIL){$('#email_customer').attr("required","required");}
                $('#kd_gender').val(d.JENIS_KELAMIN).select();
                if(parseInt($('#spkid').val()) >0){
                    $('#kd_sales').val('');
                    __getSalesman($('#spkid').val());
                }else{
                    $('#kd_sales').val(d.KD_SALES).select();
                }
                //$('#nomor_ktp').val(d.NO_KTP);
                //$('#tgl_lahir').val(convertDate(d.TGL_LAHIR));
                $('#kd_jeniskelamin').val(d.JENIS_KELAMIN).select();
                $('#kode_pos').val(d.KODE_POS);
                $('#kode_possurat').val(d.KODE_POS);
                $('#no_hp').val(d.NO_HP);
                $('#31_no_telpon').val(d.NO_HP);
                $('#npwp_customer').val(d.NO_NPWP);
                $("#kd_agama").val(d.KD_AGAMA).select();
                $('#jenis_cust').val(d.GB_SOURCE);
                //sosial media
                $('#kd_facebook').val(d.AKUN_FB);
                $('#kd_twiter').val(d.AKUN_TWITTER);
                $('#kd_instagram').val(d.AKUN_INSTAGRAM);
                $('#kd_youtube').val(d.AKUN_YOUTUBE);
                $('#kd_hobby').val(d.HOBI).select();
                $('#upline').val(d.UPLINE);
                //awalnya aktif - dimatikan - diaktifkan lagi on 04052018
                if(parseInt($('#spkid').val())==0){
                    $('#type_penjualan').val(d.CARA_BAYAR).select();                   
                    $("#likeNama").attr('checked',true);
                    $('#likeAlamat').attr('checked',true);
                }
                
                if ($('#likeNama').is(":checked")) {
                    $('#nama_dibpkb').val(stripslashes(d.NAMA_CUSTOMER));
                    $('#nomor_ktp').val(d.NO_KTP);
                    $('#tgl_lahir').val(convertDate(d.TGL_LAHIR))
                } else {
                    //$('#nama_dibpkb').val("");
                }
                if ($("#likeAlamat").is(":checked") && d.ALAMAT_SURAT != '') {
                    var alamatsurat = stripslashes(d.ALAMAT_SURAT);
                    /*alamatsurat += " " + d.NAMA_DESA;
                    alamatsurat += ", Kec.  " + d.NAMA_KECAMATAN;
                    alamatsurat += "\n" + d.NAMA_KABUPATEN;
                    alamatsurat += ", " + d.NAMA_PROPINSI;*/
                    $('#alamat_dibpkb').val(alamatsurat);
                    $('#kd_propinsi_bpkb').val($('#kd_propinsi').val())
                    $('#kd_kabupaten_bpkb').val($('#kd_kabupaten').val())
                    // $('#kecamatan_bpkb').val($("#kd_kecamatan option:selected").text())
                    // $('#kelurahan_bpkb').val($("#kd_desa option:selected").text())
                    $('#kode_posbpkb').val($('#kode_pos').val());
                } else {
                    $('#kd_propinsi_bpkb').val($('#kd_propinsi').val())
                    $('#kd_kabupaten_bpkb').val('0')
                    // $('#kecamatan_bpkb').val('')
                    // $('#kelurahan_bpkb').val('')
                    $('#kode_posbpkb').val('');
                }
            
                if ($('#alamat_sama').is(":checked")) {
                    $('#alamat_surat').val($('#alamat_cust').val());
                    $('#kd_propinsi_surat').val($('#kd_propinsi').val()).select();
                    $('#kd_kabupaten_surat').val($('#kd_kabupaten').val()).select();
                    $('#kd_kecamatan_surat').val($('#kd_kecamatan').val()).select();
                    $('#kd_desa_surat').val($('#kd_desa').val());
                    $("#kode_possurat").val($('#kode_pos').val())
                } else {
                    //$('#alamat_surat').val('');
                    $('#kd_propinsi_surat').val('0').select();
                    $('#kd_kabupaten_surat').val('0').select();
                    $('#kd_kecamatan_surat').val('0').select();
                    $('#kd_desa_surat').val('0').select();
                   // $("#kode_possurat").val('');
                }
                //quizioner form
                $('#1_data_pekerjaan').val(d.KD_PEKERJAAN).select();
                $('#2_pengeluaran_bulanan').val(d.PENGELUARAN).select();
                $('#3_pendidikan_terakhir').val(d.KD_PENDIDIKAN).select();
                $('#31_no_telpon').val(d.NO_TELEPON);
                $('#32_no_hp').val(d.NO_HP);
                $('#41_jenis_customer').val(d.GB_SOURCE).select();
                $('#alamat_lg').html("");
                if($('#nama_sales').val().length==0){
                    $('#kd_salesman_10_sales_quiz').val(d.KD_SALES);
                    $('#nama_sales_10_sales_quiz').val(d.NAMA_SALES);
                    if(parseInt($('#spkid').val()) >0){
                        __getSalesman($('#spkid').val());
                    }else{
                        $('#kd_salesman').val(d.KD_SALES)
                        $('#nama_sales').val(stripslashes(d.NAMA_SALES))
                        $('#kd_sales').val(d.KD_SALES).select();
                        dropdown_sales(d.KD_SALES,d.NAMA_SALES);
                    }
                    $('#kd_groupsales').val(d.GROUP_SALES);//d.KD_SALES.substr(3,2)).select();
                }
                if(d.KD_ITEM){
                    dropdown_item(d.KD_ITEM,d.NAMA_ITEM);
                }
                $('#nama_sales_10_sales_quiz').attr('disabled','disabled');
                $('#kd_sales_10_sales_quiz').attr('disabled','disabled');
                //var tp_motor=$('#kd_item').val().split('-');
                __getsalesprogram(d.KD_TYPEMOTOR);
                $('#kd_typecustomer').change();
                __getdataKeluarga();
            });
        }
    });
}
function __getSalesman(spkid){

}
/**
 * [__simpan_customer description]
 * @return {[type]} [description]
 */
function __simpan_customer() {
   // __validate_customer('addForm_cs');
    if(!$('#addForm').valid()){ return false;}
    if(!$('#addForm_cs').valid()){ return false;}
    //verifikasi sosmed - harus ada salah satu yang di isi
    if($('#kd_facebook').val()=='' && 
        $('#kd_twiter').val()=='' && 
        $('#kd_youtube').val()=='' &&
        $('#kd_instagram').val()==''){
        alert("Akun sosmed harus di isi salah satu");
        return;
    }
    $('#loadpage').removeClass("hidden");
    $('.header_frm').removeAttr('disabled');
    var urls = $('#addForm').attr('action');
    var desa = $('#kd_kelurahan_bpkb option:selected').text();
    var kec = $('#kd_kecamatan_bpkb option:selected').text();
    var datax = $('#addForm').serialize() + '&' + $('#addForm_cs').serialize() +'&dsa='+desa+'&kec='+kec;
    $.ajax({
        type: 'POST',
        url: urls,
        data: datax,
        success: function(result) {
            //console.log(result);
            //PKK7D201802-00002:1031:1
            var d = result.split(":");
            $('#spk_no').val(d[0]);
            if(parseInt(d[2])==1){
                 $('.success').animate({ top: "0"}, 500);
                    $('.success').html('Data berhasil di simpan :'+ result).fadeIn();
                    setTimeout(function() {
                     document.location.href = "?tab=2&id=" + d[1];
                    }, 500);
            }
            //$('#loadpage').addClass("hidden");
        }
    })
}
/**
 * [__simpan_kendaraan description]
 * @return {[type]} [description]
 */
function __simpan_kendaraan() {
    if($('#spkid').val()=='0'){ return;}
    var bariske = $("#lst_motor >tbody > tr").length;
    var datamotor = [];
    var tpCus=$('#kd_typecustomer').val();
    var tpCoy=$('#kd_leasing').val();
    var tpHarga = $('#jenis_harga').val();
    var tpJual=$('#type_penjualan').val();
    /*console.log(tpHarga);
    console.log(tpCus);
    console.log(tpCoy);*/
    /**
     * untuk penjualan wajib pakai sales program kecuali
     * 1. Penjualan KDS ( leasing CSH)
     * 2. Penjualan Off The Road
     * 3. Penjualan Ke Dealer
     */
    if($('#kd_salesprogram').val()==''){
        if(tpJual=='CREDIT'){
            if(tpCus !='DLR' ){
                alert('Sales Program harus di pilih');
                return false;
            }
            if( tpCoy !='KDS'){
                alert('Sales Program harus di pilih KDS');
                return false;
            }
        }else{
            if(tpHarga !='Off The Road'){
                alert('Sales Program harus di pilih Of');
                return false;
            } 
        }  
    }

    // alert(bariske);
    for (i = 0; i < (parseInt(bariske)); i++) {
        datamotor.push({
            'kd_item': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(0)").text(),
            'harga_jual': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(1)").text().replace(/,/g, ''),
            'qty': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(2)").text().replace(/,/g, ''),
            'biaya_stnk': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(3)").text().replace(/,/g, ''),
            'diskon': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(4)").text().replace(/,/g, ''),
            'harga_otr': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(5)").text().replace(/,/g, ''),
            'harga_dealer': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(6)").text().replace(/,/g, ''),
            'harga_dealerd': $("#lst_motor >tbody > tr:eq(" + i + ") td:eq(7)").text().replace(/,/g, ''),
        });
    }
    if($("#jp_antardealer").val()=='1'){
        $('#ket_tambahan input').removeAttr("required");
        $('#ket_tambahan textarea').removeAttr("required");
        $("#type_penjualan").removeAttr("disabled");
        $('#kd_typecustomer').removeAttr("disabled");
    }
    if(!$('#addForm_motor').valid()){return false;}
    $('#addForm_motor input').unmask();
    if(bariske==0){ alert("Type Motor belum ada yang di pilih");return false};
    
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'POST',
        url: http + '/spk/simpanmotor_spk',
        data: $('#addForm_motor').serialize() + '&motor=' + JSON.stringify(datamotor)+'&'+$('#addForm').serialize(),
        success: function(result) {
            var d = result.split(":");
            if(parseInt(d[2])==1){
                $('.success').animate({ top: "0"}, 500);
                $('.success').html('Data berhasil di simpan').fadeIn();
               if (parseInt(d[1]) == 0) {
                    setTimeout(function() {
                        document.location.href = "?tab=3&id=" + d[0];
                    }, 2000);
                } else {
                    setTimeout(function() {
                        document.location.href = "?tab=2&id=" + d[0];
                    }, 2000);
                }
            }
            $('#loadpage').addClass("hidden");
        }
    })
}
/**
 * [__simpan_quiz description]
 * @return {[type]} [description]
 */
function __simpan_quiz() {
    if($('#spkid').val()=='0'){ return;}
    $('#loadpage').removeClass("hidden");
    var urls = $('#addForm_quiz').attr('action');
    var datax = $('#addForm_quiz').serializeArray();
    $.ajax({
        type: 'POST',
        url: urls,
        data: {
            'quiz': JSON.stringify(datax),
            'kd_customer': $('#kd_customer').val()
        },
        success: function(result) {
            //alert(result)
            var d = result.split(":");
            if(parseInt(d[2])==1){
                $('.success').animate({ top: "0"}, 500);
                    $('.success').html('Data berhasil di simpan').fadeIn();
                if (parseInt(d[1]) == 0) {
                    setTimeout(function() {
                        document.location.href = "?tab=1&id=" + d[0];
                    }, 2000);
                } else {
                    setTimeout(function() {
                        document.location.href = "?tab=3&id=" + d[0];
                    }, 2000);
                }
            }else{
                $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html('Data gagal disimpan').fadeIn();
                    setTimeout(function() {
                        hideAllMessages();
                    }, 4000);
            }
            $('#loadpage').addClass("hidden");
        }
    })
}
/**
 * [__getSalesKupon description]
 * @return {[type]} [description]
 */
function __getSalesKupon() {
    var jns_harga =$('#jenis_harga').val();
    if(jns_harga=="Off The Road"){ 
        $('#kd_saleskupon').addClass('disabled-action');
        $('#kupon_lg').html("&nbsp;<span style='color:red; font-size:x-small'><em>Penjualan Off The Road tidak pakai sales kupon</em></span>");
        return;
    }
    var kd_leasing = $('#kd_fincom').val();
    var option = '';
    var html = "";
    $('#kupon_lg').html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'get',
        url: http + '/spk/saleskupon_new',
        dataType: 'json',
        data: {
            'kd_leasing': kd_leasing,
            'echo': true,
            'kd_item': $('#kdItemGuest').val(),
            'jangka_waktu':$('#jangka_waktu').val()
        },
        success: function(result) {
            var totalharga = 0;
            if ($('#kdItemGuest').val() != '' && $('#spk_id').val() == '') {
                $('#lst_motor tbody').html('');
            }
            var bariske = $("#lst_motor >tbody > tr").length;
            var sts_spk = $('#stsspk').val();
            dsb=(parseInt(sts_spk)>0)?'disabled-action':'';
            option = "<option value=''>--Pilih Sales Kupon--</option>";
            console.log(result);
            if (result.length > 0) {
                $.each(result, function(index, d) {
                    option += "<option value='" + d.KD_SALESKUPON + "'>[ " + d.KD_SALESKUPON + " ] " + d.NAMA_SALESKUPON + "</option>";
                    if ($('#kdItemGuest').val() != '' && $('#spk_id').val() == '') {
                        html += "<tr><td class='tabel-nowarp'>" + d.KD_ITEM + " [ " + d.NAMA_ITEM + " ] </td>";
                        html += "<td class='text-right'>" + (d.HARGA_OTR - d.BBN).toLocaleString() + "</td>";
                        html += "<td class='text-right'>1</td>";
                        html += "<td class='text-right'>" + parseFloat(d.BBN).toLocaleString() + "</td>";
                        html += "<td class='text-right'>" + parseFloat($('#diskount').val()).toLocaleString() + "</td>";
                        html += "<td class='text-right'>" + parseFloat(d.HARGA_OTR).toLocaleString() + "</td>";
                        html += "<td class='text-right hidden'>" + (parseFloat(d.HARGA_DEALER) - parseFloat($('#diskount').val())).toLocaleString() + "</td>";
                        html += "<td class='text-right hidden'>" + parseFloat(d.HARGA_DEALERD).toLocaleString() + "</td>";
                        html += "<td class='text-center'><a class='"+dsb+"' onclick=\"hapus('" + bariske + "');\"><i class='fa fa-trash '></i></a></td></tr>";
                        totalharga += parseFloat(d.HARGA_OTR)
                        bariske++;
                    }
                })
            }
            if ($('#kdItemGuest').val() != '' && $('#spk_id').val() == '') {
                $('#lst_motor tbody').html(html);
            }
            $('#kd_saleskupon').html(option).removeClass("disabled-action");
            $('#kupon_lg').empty();
            $('#ttharga').html(parseFloat(totalharga).toLocaleString());
            $('#loadpage').addClass("hidden");
            // jika ganti leasing atau jangka waktu kupon yng sudah terpilih di kosongkan
            $('#kd_saleskupon_grp').val('');
            $('#sls_kupon').empty();
        }
    })
}
/**
 * { function_description }
 *
 * @param      {<type>}  id      The identifier
 */
function edit(id) {
}
/**
 * on kdiitem di pilih
 * @param  {[type]} kd_item [description]
 * @return {[type]}         [description]
 */
function __getharga(kd_item) {
    $('#clsx').html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    $.ajax({
        type: 'get',
        url: http + "/spk/hargamotor",
        dataType: 'json',
        data: {
            'kd_item': kd_item
        },
        success: function(result) {
            var hargamotor=0;var bbn=0; var diskon=0;
            var spk_tipe=$('#jenis_harga').val();
            var jns_spk =$('#jp_antardealer').val();
            
            if (result.length > 0) {
                $.each(result, function(index, d) {
                    if(jns_spk=="1"){
                        hargamotor = Math.ceil(d.HARGA_DEALERD/50)*50;
                        console.log('harga_dealerd:'+d.HARGA_DEALERD);
                        bbn=0;
                        diskon=0;
                    }else{
                        hargamotor =(spk_tipe=="On The Road")?Math.ceil((d.HARGA_OTR - d.BBN)/50)*50:d.HARGA_OTR;
                        bbn = (spk_tipe=="On The Road")?d.BBN:0;
                        diskon =(spk_tipe=="On The Road")?0:d.BBN;
                        console.log('harga_oth:'+(d.HARGA_OTR - d.BBN));
                    }
                    $('#harga_jual').val(parseFloat(hargamotor).toLocaleString()).addClass("text-right"),
                    $('#qty').val('1').addClass("text-right");
                    $('#biaya_stnk').val(parseFloat(bbn).toLocaleString()).addClass("text-right");
                    $('#diskon').val(parseFloat(diskon).toLocaleString()).addClass("text-right");
                    $('#total').val((parseFloat(hargamotor)+parseFloat(bbn)).toLocaleString()).addClass("text-right");
                    $('#harga_dealer').val(parseFloat(d.HARGA_DEALER).toLocaleString()).addClass("text-right");
                    $('#harga_dealerd').val(parseFloat(d.HARGA_DEALERD).toLocaleString()).addClass("text-right");
                    $('#tmbh').removeClass('disabled-action')
                    $('#clsx').html("<i class='fa fa-save fa-fw danger'></i>");
                   //harga_motor += (d.HARGA_OTR - d.BBN);
                })
               // __getDiskon_penjualan();
            } else {
                $('#harga_jual').val(''),
                $('#qty').val('1');
                $('#biaya_stnk').val('');
                $('#diskon').val('');
                $('#total').val('');
                $('#harga_dealer').val('');
                $('#harga_dealerd').val('');
                $('#clsx').html("");
            }
        }
    })
}
/**
 * Gets the diskon penjualan.
 */
function __getDiskon_penjualan(){
    var tipe_customer=$('#kd_typecustomer').val()
    $.getJSON(http+"/spk/diskon/"+tipe_customer,{'k':tipe_customer},function(result){
        if(result.length>0){
            $.each(result,function(e,d){
                $('#diskon').val(parseFloat(d.AMOUNT).toLocaleString()).addClass("text-right");
                 $('#diskon').attr('title',(d.TIPE_DISKON=='0')?'dalam persen':'dalam rupiah');
                $('#tp_diskon').val(d.TIPE_DISKON);
            })
        }
    })
}
/**
 * Adds an item spk.
 */
function addItemSPK() {
    //if(!__CheckBundling($('#kd_item').val())){return false;}
    var harga_motor = 0;
    var html = "";
    var kd_iteme = $('#kd_typemotore').val();
    var totalharga = ($('#ttharga').html() !== '') ? parseFloat($('#ttharga').html().replace(/,/g, '')) : 0;
    var bariske = $("#lst_motor >tbody > tr").length;
    var diskon =($('#tp_diskon').val()=='0')?(parseFloat($('#diskon').val().replace(/,/g,''))/100)*parseFloat($("#harga_jual").val().replace(/,/g,'')):parseFloat($('#diskon').val().replace(/,/g,''));
    //console.log(diskon);
    html += "<tr><td class='tabel-nowarp'>" + $("#kd_item2").val() + "</td>";
    html += "<td class='text-right'>" + $('#harga_jual').val() + "</td>";
    html += "<td class='text-right'>" + $('#qty').val() + "</td>";
    html += "<td class='text-right'>" + $('#biaya_stnk').val() + "</td>";
    html += "<td class='text-right'>" + $('#diskon').val() + "</td>";
    html += "<td class='text-right'>" + (parseFloat($('#total').val().replace(/,/g,''))-parseFloat(diskon)).toLocaleString() + "</td>";
    html += "<td class='text-right hidden'>" + $('#harga_dealer').val() + "</td>";
    html += "<td class='text-right hidden'>" + $('#harga_dealerd').val() + "</td>";
    html += "<td class='text-center'><a onclick=\"hapus('" + bariske + "');\"><i class='fa fa-trash'></i></a></td></tr>";
    totalharga += parseFloat($('#total').val().replace(/,/g, ''));
    harga_motor =$('#harga_jual').val().replace(/,/g,'');
    __getCicilan(harga_motor);
    $('#lst_motor tbody').append(html);
    $('#ttharga').html(totalharga.toLocaleString());
    $('#kd_typemotore').val(($("#kd_item2").val().substring(0,6)) + ',' + kd_iteme);
    //remove text field inputa
    $('#kd_item').val('');
    $('#kd_item2').val('');
    $('#harga_jual').val('');
    $('#qty').val('');
    $('#biaya_stnk').val('');
    $('#diskon').val('');
    $('#total').val('');
    $('#tmbh').addClass('disabled-action')
    $('#loadpage').addClass("hidden");
};
/**
 * [hapus description]
 * @param  {[type]} bariske [description]
 * @return {[type]}         [description]
 */
function hapus(bariske) {
    if (parseInt(bariske) > 0) {
        bariske = parseInt(bariske) - 1
    } else {
        bariske = bariske;
    }
    var tpmotor = $("#lst_motor >tbody > tr:eq(" + bariske + ") > td:eq(0)").text();
    var kd_tp = $('#kd_typemotore').val();
    //var val = new RegExp($.trim((tpmotor.split(' ['))[0]) + ',', "");
    var val =tpmotor.substr(0,6)+",";
    alert(val);
    $('#kd_typemotore').val(kd_tp.replace(val, ''));
    $("#lst_motor >tbody > tr:eq(" + bariske + ")").remove();
}
/**
 * [__getdataKendaraan description]
 * @return {[type]} [description]
 */
function __getdataKendaraan() {
    $('#loadpage').removeClass("hidden");
    var spk_id = $('#espekaid').val();
    var no_spkne =$('#spk_no').val();
    var html = "";
    var kditem = "";
    var totalharga = 0;
    $('#lst_motor tbody').html('');
    var bariske = $("#lst_motor >tbody > tr").length;
    $.ajax({
        type: 'GET',
        url: http + "/spk/spkkendaraan",
        data: {
            'spk_id': spk_id
        },
        dataType: 'json',
        success: function(result) {
            ////console.log(result);
            if (result.totaldata > 0) {
                $.each(result.message, function(index, d) {
                    //alert('uang muka :'+d.UANG_MUKA);
                    //survey_leasing
                    if(d.hasil){
                        $('#app_status').val(d.HASIL).select();
                        $('#app_status').change();
                    }
                    if(d.KETERANGAN){
                        var alasan=d.KETERANGAN.split(":");
                        $('#alasan').val(alasan[0]).select();
                        $('#alasan').change();
                        if(alasan.length>1){
                            $('#ket_alasan').val(alasan[1]);
                        }  
                    }
                    ////console.log('uang muka0 :'+d.UANG_MUKA);
                    // history leasing
                    __getHistoryLeasing(spk_id);
                    //sales program
                    //$('#kd_bundling').val(d.KD_BUNDLING).select();
                    //$('#kd_salesprogram').val(d.KD_SALESPROGRAM).select();
                    $('#kd_saleskupon').val(d.KD_SALESKUPON).select();
                    
                    $('#kd_saleskupon_grp').val(d.KD_SALESKUPON);
                    //alamatkirim
                    if(d.TGL_KIRIM!='1900-01-01'){
                        $('#tgl_kirim').val(convertDate(d.TGL_KIRIM));
                    }
                    if(d.JAM_KIRIM!=null){
                        $('#jam_kirim').val(d.JAM_KIRIM.substr(0,5))
                    }
                    $('#nama_penerima').val(stripslashes(d.NAMA_PENERIMA));
                    $('#no_hp_surat').val(d.NO_HP);
                    $('#alamat_pengiriman').val(stripslashes(d.ALAMAT_KIRIM));
                    $('#keterangan_tambahan').val(stripslashes(d.KET_TAMBAHAN));
                    if(d.ESTIMASI_STNK){
                        $('#tgl_stnk').val(convertDate(d.ESTIMASI_STNK));
                        $('#tgl_bpkb').val(convertDate(d.ESTIMASI_BPKB));
                    }
                    ////console.log('uang muka1 :'+d.UANG_MUKA);
                    //detail motor
                    var sts_spk = $('#stsspk').val();
                    var clse = (parseInt(sts_spk) > 0) ? " class='disabled-action'" : "";
                    html += "<tr><td class='tabel-nowarp'>" + d.KD_ITEM + " [ " + d.NAMA_ITEM + " ] </td>";
                    html += "<td class='text-right'>" + parseFloat(d.HARGA).toLocaleString() + "</td>";
                    html += "<td class='text-right'>"+d.JUMLAH+"</td>";
                    html += "<td class='text-right'>" + (parseFloat(d.BBN)).toLocaleString() + "</td>";
                    html += "<td class='text-right'>" + parseFloat(d.DISKON).toLocaleString() + "</td>";//$('#diskount').val()
                    html += "<td class='text-right'>" + (parseFloat(d.HARGA_OTR)).toLocaleString() + "</td>";
                    html += "<td class='text-right hidden'>" + (parseFloat(d.HARGA_DEALER) - parseFloat($('#diskount').val())).toLocaleString() + "</td>";
                    html += "<td class='text-right hidden'>" + parseFloat(d.HARGA_DEALERD).toLocaleString() + "</td>";
                    html += "<td class='text-center'><a " + clse + " onclick=\"hapus_motor('" + d.ID + "','" + bariske + "');\"><i class='fa fa-trash'></i></a></td></tr>";
                    totalharga += parseFloat(d.HARGA_OTR)
                    kditem += d.KD_ITEM;
                    kditem += (bariske == result.length) ? "" : ",";
                    bariske++;
                    switch(d.LOKASI_KIRIM){
                        case "Lainnya":
                            $('#lainnya').attr("checked",true)
                        break;
                        case "Surat":
                            $('#like_alamatsurat').attr("checked",true)
                        break;
                        default:
                            $('#like_alamatrumah').attr("checked",true)
                        break;
                    }
                    //fincon
                    $('#kd_fincom').change();
                    $('#kd_fincom').val(d.KD_FINCOY).select(),
                    $('#uang_muka').val(parseFloat(d.UANG_MUKA).toLocaleString());
                    $('#bunga').val(parseFloat(d.BUNGA).toLocaleString());
                    $('#biaya_adm').val(parseFloat(d.ADM).toLocaleString());
                    $('#jangka_waktu').val(d.JANGKA_WAKTU);//.select();
                    $('#jumlah_angsuran').val(parseFloat(d.JUMLAH_ANGSURAN).toLocaleString());
                    $('#jatuh_tempo').val(convertDate(d.JATUH_TEMPO));
                    $('#kd_typemotore').val(kditem);
                    __listSalesKupon(d.KD_SALESKUPON,no_spkne,d.KD_BUNDLING);
                })
            }else{
                // ambil data kendaraan dari guestbook berdasarkan nomor guestbook
            }
            $('#lst_motor tbody').html(html);
            $('#ttharga').html(parseFloat(totalharga).toLocaleString());
            $('#loadpage').addClass("hidden");
            console.log(kditem);
        }
    })
}
/**
 * [__getdataQuiz description]
 * @return {[type]} [description]
 */
function __getdataQuiz() {
    $('#loadpage').removeClass("hidden");
    var spk_id = $('#spkid_quiz').val();
    $.ajax({
        type: 'GET',
        url: http + "/spk/spkquiz",
        data: {
            'spk_id': spk_id
        },
        dataType: 'json',
        success: function(result) {
            if (result.length > 0) {
                $.each(result, function(index, d) {
                    var fld=d.KETERANGAN.split('_');
                    var tipe=$('#' + d.KETERANGAN).get(0).tagName;
                    //alert(tipe);
                    if (tipe === 'SELECT') {
                        $('#' + d.KETERANGAN).val(d.JAWABAN).select();
                    } else {
                        $('#' + d.KETERANGAN).val(d.JAWABAN);
                    }
                    if(fld[0]=='41'){
                        $('#41_jenis_customer').val(d.JAWABAN).select();
                    }
                })
                $('#loadpage').addClass("hidden");
            }else{
                var tjualasli=$('#type_penjualan').val();
                if($("#9_jenis_penjualan").val()!=$('#type_penjualan').val()){
                    $('#9_jenis_penjualan').val($('#type_penjualan').val()).select();
                }
                __getdataCustomer();
            }
            $('#loadpage').addClass("hidden");
        }
    })
}
/**
 * [hapus_motor description]
 * @param  {[type]} id      [description]
 * @param  {[type]} bariske [description]
 * @return {[type]}         [description]
 */
function hapus_motor(id, bariske) {
    $.ajax({
        type: 'POST',
        url: http + "/spk/spkkendaraan_delete",
        data: {
            'id': id
        },
        success: function(result) {
            if (result != "0") {
                hapus(bariske);
            }
        }
    })
}
/**
 * [ApproveCredit description]
 * @param {[type]} spkid [description]
 */
function ApproveCreditx(){
    var dialog,form  ,
      ket = $( "#pesan" ),
      allFields = $( [] ).add( ket );
      dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
      buttons: {
        "Create an account": ApproveCredit_s,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
    });
    dialog.dialog('open');
}
/**
 * Approval pengajuan krecit
 *
 * @class      ApproveCredit (name)
 * @param      {string}   spkid   The spkid
 * @return     {boolean}  { description_of_the_return_value }
 */
function ApproveCredit(spkid) {
    var notaprove = "";
    if(!$('#addForm_motor').valid()){return false;}
    if (spkid === 'Un Approve') {        
        notaprove=$('#alasan').val()+":"+$('#ket_alasan').val();
    }
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type: 'POST',
        url: http + "/spk/aprove_leasing",
        data: $('#addForm_motor').serialize() + "&hasil=" + spkid + "&ket=" + notaprove,
        success: function(result) {
            var asal = ($('#asal').val());
            $('#loadpage').addClass("hidden");
            if (asal == "1") {
                document.location.href = http + "/spk/spk";
            } else {
                document.location.reload();
            }
        }
    })
}
/**
 * [__validate_customer description]
 * @param  {[type]} frmid [description]
 * @return {[type]}       [description]
 */
function __validate_customer(frmid) {
    var fail = false;
    var fail_log = '';
    $('#' + frmid).find('select, textarea, input').each(function() {
        if (!$(this).prop('required')) {
        } else {
            if (!$(this).val()) {
                fail = true;
                name = $(this).attr('name');
                fail_log += name + " is required \n";
            }
        }
    });
    //return fail;
    //submit if fail never got set to true
    if (!fail) {
        //callback;
    } else {
        alert(fail_log);
        exit();
    }
}
/**
 * cek item yang di pilih apakah ada di program sales kupon yang di pilih
 * 
 * @return {[type]} [description]
 */
function __cekItemOnKupon() {
  $('#loadpage').removeClass("hidden");
    var kd_item = $('#kd_item2').val();
    var kdtype = kd_item.substr(0,3);
     __getsalesprogram(kdtype);
    //if ($('#kd_saleskupon').val().length>0) {
        $('#loadpage').removeClass("hidden");
        $.ajax({
            type: 'GET',
            url: http + "/spk/saleskupon_new/true/",
            dataType:'json',
            data: {
                'kd_saleskupon': $('#kd_saleskupon').val(),
                'kd_item': kdtype
            },
            success: function(result) {
                console.log(result);
                //alert(result.length);
                if (parseInt(result.length) == 0) {
                    alert(kd_item + " tidak masuk dalam program " + $('#kd_saleskupon option:selected').text());
                   // $('#kd_saleskupon').val("").select();
                    $('#loadpage').addClass("hidden");
                    return;
                } else {
                    //alert(result.length);
                   __CheckBundling(kdtype);
                }
                $('#loadpage').addClass("hidden");
            }
        })
}
/**
 * cek sales kupon yng di pilih apakah ada type motor yang sesuai dengan type motor yang di list
 * @return {[type]} [description]
 */
function __cekKuponOnItem() {
    var listitem = $('#kd_typemotore').val().split(',');
    if(listitem==''){
        __addSalesKupon();
        return;
    }
    var urls = http + "/spk/detailsales";
    var kd_kupone = $('#kd_saleskupon').val();
    //tambahan harcode untuk kode kupon K2019-006 
    //criteria - jika uang muka antara 3 - 5juta dan tempo 36 bulan -- end of 31 March 2019
    //criteria - jika uang muka antara 2.5 - 5juta dan tempo 36 bulan -- end of 30 April 2019
    //setelah masa berlaku kupon selesai tidak akan muncul lagi blok ini bisa di hapus atau di gunakan untuk
    //kode kupon yang lain jika di perlukan
    var uang_muka = $('#uang_muka').val().replace(/,/g,'');
    var jangkawaktu = $('#jangka_waktu').val();
    var d = new Date();
    var bulan = d.getMonth();
    var tahun = d.getFullYear();
    console.log(bulan);
    console.log(tahun);
    if(kd_kupone=='K2019-006' && parseInt(bulan+1)==4 && parseInt(tahun)==2019){
        if(parseFloat(uang_muka) < 2500000 ){
            alert("Minimun uang muka yang bisa mendapatkan kupon ini = 2.5 Juta rupiah");
            $('#kd_saleskupon').val('').select();
            return;
        } 
        if(parseFloat(uang_muka) > 5000000){
            alert("Maximum uang muka yang bisa mendapatkan kupon ini = 5 Juta rupiah");
            $('#kd_saleskupon').val('').select();
            return;
        }
        if(parseInt(jangkawaktu) != 36){
            alert("Jangaka Waktu Kredit yang bisa mendapatkan kupon ini adalah 36 bulan");
            $('#kd_saleskupon').val('').select();
            return;
        }
    }
    $('#loadpage').removeClass("hidden");
    //end of harcode
    var ada = 0;
    $.ajax({
        type: 'GET',
        url: urls,
        dataType: 'json',
        data: {
            'kd_saleskupon': kd_kupone
        },
        success: function(result) {
            if (parseInt(result.length) > 0) {
                $.each(result, function(index, d) {
                    for (i = 0; i < (listitem.length - 1); i++) {
                        ada += ($.trim(d.KD_TYPEMOTOR) === $.trim((listitem[i].split('-'))[0])) ? 1 : 0;
                    }
                })
                if (ada == 0) {
                    alert("Sales Kupon " + $('#kd_saleskupon option:selected').text() + " tidak mengandung item yang ada di list");
                    //$('#kd_saleskupon').val("").select();
                    $('#loadpage').addClass("hidden");
                } else {
                    
                    __addSalesKupon();
                }
            }else{
               $('#loadpage').addClass("hidden"); 
            }
        }
    })
}
/**
 * [__addSalesKupon description]
 * @return {[type]} [description]
 */
function __addSalesKupon() {
    var kd_kupon = $('#kd_saleskupon').val();
    var sts_spk = $('#stsspk').val();
    var clse = (parseInt(sts_spk) > 0) ? " class='disabled-action'" : "";
    $('#sls_kupon').append("<li class='list-group-item' id='s_" + $('#kd_saleskupon').val() + "'><small>" +
        $('#kd_saleskupon option:selected').text() +
        "</small> <span class='pull-right'><a "+clse+" style='cursor:pointer' onclick=\"hps_kupon('" + kd_kupon + "')\" title='hapus item ini'>" +
        "<i class='fa fa-close'></a></i></span></li>");
    var kupon = $('#kd_saleskupon_grp').val();
    $('#kd_saleskupon_grp').val($('#kd_saleskupon').val() + "," + kupon);
    $('#loadpage').addClass("hidden");
}
/**
 * [hps_kupon description]
 * @param  {[type]} kd_kupon [description]
 * @return {[type]}          [description]
 */
function hps_kupon(kd_kupon) {
    var conf = confirm("Yakin " + $('#kd_saleskupon option:selected').text() + " akan di hapus?");
    if (conf) {
        $('#s_' + kd_kupon).remove();
        var kupon = $('#kd_saleskupon_grp').val();
        var yngdihapus = new RegExp(kd_kupon, 'ig');
        $('#kd_saleskupon_grp').val((kupon.replace(yngdihapus, "")).replace(/,,K/g, ",K"));
    }
    return false;
}
/**$('#kd_saleskupon').val()
 * cek sales program yang di pilih apakah mengandung type motor yang sudah ada di list kendaraan
 * @return {[type]} [description]
 */
function __cekItemSalesProgramOnItemList(kd_sp) {
    var listitem = $('#kd_typemotore').val().split(',');
    var urls = http + "/spk/detailsales"
    var ada = 0;
    if(listitem.length<=1){ return false;}
    $('#loadpage').removeClass("hidden");
    var cash_tempo="";
    var jenis_sp="";
    $.ajax({
        type: 'GET',
        url: urls,
        dataType: 'json',
        data: {
            'kd_salesprogram': $('#kd_salesprogram').val()
        },
        success: function(result) {
            //console.log(result);
            $.each(result, function(index, d) {
                jenis_sp = d.TIPE_SALESPROGRAM;
                for (i = 0; i < (listitem.length - 1); i++) {
                    ada += ($.trim(d.KD_TYPEMOTOR) === $.trim((listitem[i].split('-'))[0])) ? 1 : 0;
                    if($.trim(d.KD_TYPEMOTOR) === $.trim((listitem[i].split('-'))[0])){
                        __cekSubsidiYangDiberikan(d.KD_SALESPROGRAM,d.SC_SD,d.SK_SD);
                    }
                }
                cash_tempo = $.trim(d.CASH_TEMPO);
                //console.log(cash_tempo.length);
                if(jenis_sp=='D' || jenis_sp=='G'){
                //rubah harga motor sesuai nilai kontrak di sales program itu jika ada;
                    var harga_oftr=(parseFloat(d.HARGA_KONTRAK)-parseFloat(d.PENGURUSAN_STNK)).toLocaleString();
                    var stnk=(parseFloat(d.PENGURUSAN_STNK)).toLocaleString();
                    var hkontrak =(parseFloat(d.HARGA_KONTRAK)).toLocaleString();
                    if(parseFloat(d.HARGA_KONTRAK) >0) {  
                        $('#lst_motor > tbody > tr:eq(0) > td:eq(1)').text(harga_oftr);
                        $('#lst_motor > tbody > tr:eq(0) > td:eq(3)').text(stnk);
                        $('#lst_motor > tbody > tr:eq(0) > td:eq(5)').text(hkontrak);
                    }
                }
            })
            if (ada == 0) {
                alert("Sales Program " + $('#kd_salesprogram option:selected').text() + " tidak mengandung item yang ada di list");
                $('#kd_salesprogram').val("").select();
                $('#loadpage').addClass("hidden");
            }else{
              $('#loadpage').addClass("hidden");
              if(cash_tempo.length > 0){
                $('#type_credit').val('CASH_TEMPO');
              }
              $('#kd_program_grp').val($('#kd_salesprogram').val());
              if($('#kd_salesprogram').val()){
                __getDetailSalesProgram(kd_sp,listitem);
              }
                
            }
        }
    })
}
/**
 * Gets the detail sales program.
 *
 * @param      {string}  kdsp      The kdsp
 * @param      {<type>}  listitem  The listitem
 */
function __getDetailSalesProgram(kdsp,listitem){
    var list = $('#kd_typemotore').val().split(',');
    var kdtp = list[0].split("-");
    var tpjual = $('#type_penjualan').val();
    var li="";
    var total=0;
    var diskond=$('#lst_motor > tbody > tr:eq(0) >td:eq(4)').text().replace(/,/g,'');
    //console.log(diskond);
    var diskone=0;
    $('#sls_prg').html("<li class='list-group-item'><i class='fa fa-spinner fa-spin fa-fw'></i> load data ...</li>");
    $.getJSON(http+"/setup/detail_salesprogram/"+$.trim(kdtp[0])+"/"+kdsp,function(result){
        if(result.totaldata >0){
            li +="<li class='list-group-item active'> Detail Subsidi Sales Program</li>";
            $.each(result.message,function(e,d){
                if(tpjual=='CASH'){
                    diskone =(parseFloat(diskond) >0)?diskond:d.MIN_SC_SD;
                    li +="<li class='list-group-item'> Subsidi AHM <span class='pull-right'>"+parseFloat(d.SC_AHM).toLocaleString()+"</span></li>";
                    li +="<li class='list-group-item'> Subsidi MD <span class='pull-right'>"+parseFloat(d.SC_MD).toLocaleString()+"</span></li>";
                    li +="<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>"+parseFloat(diskone).toLocaleString()+"</span></li>";
                    total +=parseFloat(d.SC_AHM);
                    total +=parseFloat(d.SC_MD);
                    total +=parseFloat(diskone);
                }else{
                    diskone =(parseFloat(diskon) >0)?diskond:d.MIN_SK_SD;
                    li +="<li class='list-group-item'> Subsidi AHM <span class='pull-right'>"+parseFloat(d.SK_AHM).toLocaleString()+"</span></li>";
                    li +="<li class='list-group-item'> Subsidi MD <span class='pull-right'>"+parseFloat(d.SK_MD).toLocaleString()+"</span></li>";
                    li +="<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>"+parseFloat(diskone).toLocaleString()+"</span></li>";
                    li +="<li class='list-group-item'> Subsidi FINANCE <span class='pull-right'>"+parseFloat(d.SK_FINANCE).toLocaleString()+"</span></li>";
                    total +=parseFloat(d.SK_AHM);
                    total +=parseFloat(d.SK_MD);
                    total +=parseFloat(diskone);
                    total +=parseFloat(d.SK_FINANCE);
                }
            })
            li +="<li class='list-group-item info'> Total Subsidi <span class='pull-right'><b><em>"+total.toLocaleString()+"</em></b></span></li>";
        }
        //console.log(li);
        $('#sls_prg').html(li);
    })
    return;
}
/**
 * { function_description }
 *
 * @param      {<type>}  kd_sp         The kd sp
 * @param      {string}  nilai_cash    The nilai cash
 * @param      {<type>}  nilai_kredit  The nilai kredit
 */
function __cekSubsidiYangDiberikan(kd_sp,nilai_cash, nilai_kredit){
    var nilaidiskon=$('#lst_motor > tbody > tr:eq(0) > td:eq(4)').text();
        nilaidiskon = nilaidiskon.replace(/,/g,'');
    var tipe_spk = $('#type_penjualan').val();
    var selisih =0;
        selisih = (tipe_spk=='CASH')? (parseFloat(nilai_cash)-parseFloat(nilaidiskon)):(parseFloat(nilai_kredit)-parseFloat(nilaidiskon));
        console.log('selisih:'+selisih+',cash_tempo:'+nilai_cash+', diskon:'+nilaidiskon);
    if(selisih < 0){
        if(!confirm("Jumlah Subsidi yang di berikan melebihi nilai subsidi sales program ("+parseFloat(nilai_cash).toLocaleString()+")")){
            $('#kd_salesprogram').val('').select();
            return ;
        }
        //$('#kd_salesprogram').val('').select();
    }
}
/**
 * [__getsalesprogram description]
 * @return {[type]} [description]
 */
function __getsalesprogram(kdtype) {
    var jns_harga=$('#jenis_harga').val();
    // if(jns_harga=="Off The Road"){ $('#kd_salesprogram').addClass('disabled-action');return;}
    if(jns_harga=="Off The Road"){ 
        $('#kd_salesprogram').addClass('disabled-action');
        $('#program_lg').html("&nbsp;<span style='color:red; font-size:x-small'><em>Penjualan Off The Road tidak pakai sales program</em></span>");
        return;
    }
    var option = '';
    console.log(kdtype);
    $('#program_lg').html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    var kd_leasing = $('#kd_fincom').val();
    kd_leasing=(kd_leasing)?kd_leasing:"CSH";
    var urls = http + "/spk/salesprogram_new/true";
    var listitem = $('#kd_typemotore').val().split(',');
    console.log(listitem);
    option = "<option value=''>--Pilih Sales Program--</option>";
    var sp="";
    $.ajax({
        type: 'GET',
        url: urls,
        data: {
            'kd_leasing': kd_leasing,
            'kd_typemotor':kdtype
        },
        dataType: 'json',
        success: function(result) {
            console.log(result);
            if (result.length > 0) {
                $.each(result, function(index, d) {
                    option += "<option value='" + d.KD_SALESPROGRAM + "'>[" + d.KD_SALESPROGRAM + "] " + d.NAMA_SALESPROGRAM + "</option>";
                    sp=d.KD_SALESPROGRAM;
                });
            }
            $('#kd_salesprogram').html('');
            $('#kd_salesprogram').append(option).val($('#kd_program_grp').val()).select();
            $('#program_lg').html('');
            __getDetailSalesProgram($('#kd_program_grp').val(),listitem);
        }
    })
}
/**
 * [__listDetail_salesProgram description]
 * @param  {[type]} kd_sp [description]
 * @return {[type]}       [description]
 */
function __listDetail_salesProgram(kd_sp){
    var isCash=$('#type_penjualan').val();
    var li=""; var total=0; var diskon = $('#diskount').val();
    $('#sls_prg').html("<li class='list-group-item'><i class='fa fa-spinner fa-spin fa-fw'></i> load data ...</li>");
    $.getJSON(http+"/spk/spk_salesprogram/"+kd_sp,function(result){
        if(result.status){
            li +="<li class='list-group-item active'> Detail Subsidi Sales Program</li>";
            if(result.totaldata >0){
                $.each(result.message,function(e,d){
                    if(isCash=='CASH'){
                        diskon = (parseFloat(diskon)>0)? diskon : d.MIN_SC_SD;
                        li +="<li class='list-group-item'> Subsidi AHM <span class='pull-right'>"+parseFloat(d.SC_AHM).toLocaleString()+"</li>";
                        li +="<li class='list-group-item'> Subsidi MD <span class='pull-right'>"+parseFloat(d.SC_MD).toLocaleString()+"</li>";
                        li +="<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>"+parseFloat(diskon).toLocaleString()+"</li>";
                        total += d.SC_AHM;
                        total += d.SC_MD;
                        total += diskon; 
                    }else{
                        diskon = (parseFloat(diskon)>0)? diskon : d.MIN_SC_SD;
                        li +="<li class='list-group-item'> Subsidi AHM <span class='pull-right'>"+parseFloat(d.SK_AHM).toLocaleString()+"</li>";
                        li +="<li class='list-group-item'> Subsidi MD <span class='pull-right'>"+parseFloat(d.SK_MD).toLocaleString()+"</li>";
                        li +="<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>"+parseFloat(diskon).toLocaleString()+"</li>";
                        li +="<li class='list-group-item'> Subsidi FINANCE <span class='pull-right'>"+parseFloat(d.SK_FINANCE).toLocaleString()+"</li>";
                        total += d.SK_AHM;
                        total += d.SK_MD;
                        total += d.SK_FINANCE;
                        total += diskon; 
                    }
                })
            }
            li +="<li class='list-group-item info'> Total Subsidi <span class='pull-right'><b><em>"+(total.toLocaleString())+"</em></b></span></li>"
        }
        $('#sls_prg').html(li);
    })
}
/**
 * { function_description }
 *
 * @param      {string}  id      The identifier
 */
function checkumur(id) {
    var today = new Date(),
        birthday = $('#' + id).datepicker("getDate"),
        age = ((today.getMonth() > birthday.getMonth()) ||
            (today.getMonth() == birthday.getMonth() && today.getDate() >= birthday.getDate())) ?
        today.getFullYear() - birthday.getFullYear() : today.getFullYear() - birthday.getFullYear() - 1;
    //e.preventDefault();
    return age
}
/**
 * [__listSalesKupon description]
 * @param  {[type]} kdkupon [description]
 * @return {[type]}         [description]
 */
function __listSalesKupon(kdkupon,kdprogram,kdbnd) {
    $('#ldsp').html("<i class='fa fa-spinner fa-spin red'></i> loading data program....")
    $.ajax({
        type: 'GET',
        url: http + "/spk/list_saleskupon",
        dataType: 'html',
        data: {
            'kd_saleskupon': kdkupon
        },
        success: function(result) {
            $('#sls_kupon').html('');
            if (result.length > 0) {
                $('#sls_kupon').append(result);
            }
            console.log('kupon:'+kdprogram);
            __listSalesProgram(kdprogram)
            __listProgramBnd(kdbnd);
        }
    })
}
/**
 * { function_description }
 *
 * @param      {<type>}  kdkupon  The kdkupon
 */
function __listSalesProgram(kdkupon) {
    $.ajax({
        type: 'GET',
        url: http + "/spk/list_salesprogram_new",
        dataType: 'html',
        data: {'no_spk': kdkupon},
        success: function(result) {
            console.log(result);
            if (result.length > 0) {
                var rs=result.split('::');
                $('#sls_kupon').append(rs[0]);
                $('#kd_program_grp').val(rs[1])
                $('#kd_salesprogram').val(rs[1]).select();
            }
            var kdtype = $('#kd_typemotore').val().split(',');
            var kdtypex =kdtype[0].split('-');
                console.log(kdtypex);
            __getsalesprogram(kdtypex[0])
        }
    })
}
/**
 * { function_description }
 *
 * @param      {<type>}  kdkupon  The kdkupon
 */
function __listProgramBnd(kdkupon) {
    $.ajax({
        type: 'GET',
        url: http + "/spk/listbundling",
        dataType: 'html',
        data: {
            'kd_salesprogram': kdkupon
        },
        success: function(result) {
            //$('#sls_kupon').append("li class='list-group-item'>Sales Program</li>");
            if (result.length > 0) {
                var rs=result.split('::');
                $('#sls_kupon').append(rs[0]);
                $('#kd_bundling_grp').val(rs[1])
                $('#kd_bundling').val(rs[1]).select();
            }
            $('#ldsp').html("");
        }
    })
}
/**
 * [__CheckBundling description]
 * @param  {[type]} kd_item [description]
 * @return {[type]}         [description]
 */
function __CheckBundling(kd_item){
     if($('#kd_bundling').val()!=''){
        $.ajax({
        type: 'GET',
        url : http+"/spk/bundlingmotor",
        data: {'kd_item':kd_item,'kd_bundling':$('#kd_bundling').val()},
        dataType :'json',
        success:function(result){
            if(result.length==0){
                alert("Type Motor "+$('#kd_item2').val()+" tidak masuk ke dalam program bundling "+$('#kd_bundling option:selected').text());
                $('#loadpage').addClass("hidden");
                return false;
            }else{
                addItemSPK();
            }
        }
       })
    }else{
       addItemSPK(); 
    }
}
/**
 * [__CheckBundlingOnItem description]
 * @return {[type]} [description]
 */
function __CheckBundlingOnItem(){
    $('#loadpage').removeClass("hidden");
    var listitem = $('#kd_typemotore').val().split(',');
    var ada=0; var adawarna=0;
    $.ajax({
    type: 'GET',
    url : http+"/spk/bundlingmotor",
    data: {'kd_bundling':$('#kd_bundling').val()},
    dataType :'json',
    success:function(result){
            $.each(result, function(index, d) {
                for (i = 0; i < (listitem.length - 1); i++) {
                    if(listitem[i]!=''){
                        ada += ($.trim(d.KD_TYPEMOTOR) === $.trim((listitem[i].split('-'))[0])) ? 1 : 0;
                        if($.trim(d.KD_WARNA)!=''){
                            adawarna +=(ada >0 && $.trim(d.KD_WARNA) === $.trim((listitem[i].split('-'))[1]))?1:0;
                        }else{
                            adawarna=(ada>0)?1:0;
                        }
                    }
                }
                 // //console.log(d.KD_TYPEMOTOR+'-'+d.KD_WARNA);
                 // //console.log(ada +';;'+adawarna)
            })
            // //console.log(listitem);
            // //console.log()
            if (ada == 0 && adawarna==0) {
                alert("Program Bundling " + $('#kd_bundling option:selected').text() + " tidak mengandung item yang ada di list");
                $('#kd_bundling').val("").select();
                $('#loadpage').addClass("hidden");
            }else{
              $('#loadpage').addClass("hidden");
               $('#kd_bundling_grp').val($('#kd_bundling').val());
            }
        }
    })
}
/**
 * Load data on depenency field after parent success load
 */
jQuery.fn.LoadSibling=function(id, select){
    $(this).on('change',function(){
        loadData(id, $(this).val(), select);
    })
}
/**
 * [loadData on dropdown based on parent id]
 * @param  {[type]} id     [description]
 * @param  {[type]} value  [description]
 * @param  {[type]} select [description]
 * @return {[type]}        [description]
 */
function loadData(id, value, select) {
    $('#' + id + '').attr('disabled', 'disabled');
    var param = $('#' + id + '').attr('title');
    $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
    $('#l_' + id + '').html("<i class='fa fa-spinner fa-spin'></i>");
    var urls = http + "/customer/" + param;
    var datax = {
        "kd": value
    };
    console.log(param);
    select = (select == '' || select == "0") ? "0" : select;
    return $.ajax({
            type: 'GET',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function(result) {
             ////console.log(result)
            $('#' + id + '').empty();
            $('#' + id + '').html(result);
            $('#' + id + '').val(select).select();
            $('#l_' + param + '').html('');
            $('#l_' + id + '').html('');
            $('#alamat_lg').html("");
            $('#' + id + '').removeAttr('disabled');
            if(id=='kd_desa'){
                __getKodePos(select);
            }
        }
    }).promise();
}
/**
 * Gets the kode position.
 *
 * @param      {string}  kd_desa  The kd desa
 */
function __getKodePos(kd_desa){
    $.getJSON(http+"/customer/desa/true/"+kd_desa,function(result){
        $('#kode_pos').val(result);
        $('#kode_possurat').val(result);
        $('#kode_posbpkb').val(result);
    })
    var fromGb=$('#autogb').val();
    if(fromGb!=''){
        //console.log(fromGb);
        $('#btn-simpan_cs').removeClass('disabled');
        $('#btn-simpan_cs').removeClass('disabled-action');
    }
}
/**
 * [_loadDataOthersDealer description]
 * @return {[type]} [description]
 */
function __loadDataOthersDealer(notin){
    $('#lgd').html("<i class='fa fa-spinner fa-spin fa-fw'></i>");
    $.ajax({
        type:'POST',
        url:http+"/spk/dealer/true",
        data:{},
        dataType:'json',
        success:function(result){
            if(result.length>0){
                $('#kd_dealer').empty();
                $('#kd_dealer').append('<option value="">--Pilih Dealer--</option>');
                $.each(result,function(e,d){
                    $('#kd_dealer').append('<option value="'+d.KD_DEALER+'">'+d.NAMA_DEALER+' - <em>'+ d.NAMA_KABUPATEN+'</em></option>');
                })
                //console.log(notin);
                if(notin){
                    $('#kd_dealer').val(notin).select();
                }else{
                    $('#kd_dealer').prop('selectedIndex','1');
                }
                $('#lgd').empty();
            }else{
                $('#kd_dealer').empty();
                $('#kd_dealer').append('<option value="">--Pilih Dealer--</option>');
                $('#lgd').empty();
            }
        }
    })
}
/**
 * { function_description }
 *
 * @return     {boolean}  { description_of_the_return_value }
 */
function __CheckurutanLeasing(){
    var urutan=$('#'+$('#kd_fincom').val()).index();
    var alasanmaksa="";
    if(urutan <0){ urutan=3}
    //console.log(urutan);
    if(parseInt(urutan)>0){
        // check urutan pertama
        for(i=0;i< parseInt(urutan);i++){
            var target=$("#komposisi > tbody > tr:eq("+i+") > td:eq(3)").html();
            var achive=$("#komposisi > tbody > tr:eq("+i+") > td:eq(5)").html();
            var kdFin=$("#komposisi > tbody > tr:eq("+i+") > td:eq(1)").html();
            if(parseFloat(target) > parseFloat(achive)){
                var alasan=confirm("Leasing "+kdFin +" Masih belum terpenuhi targetnya\nTetap dilanjutkan memakai leasing "+$('#kd_fincom').val())
                if(alasan){
                    //console.log(alasan)
                    $('#myModal_alasan').attr("style","display:block")
                    return false;
                    /*if(alasanmaksa==""){
                        $('#kd_fincom').prop('selectedIndex','0');
                        alert("Alasan tetap menggunakan leasing "+$('#kd_fincom').val()+" harus di isi dengan jelas!");
                        return false;
                    }*/
                }else{
                    $('#kd_fincom').prop('selectedIndex','0');
                    return false;
                }
            }else{
                return true;
            }
           // //console.log(target +"--"+achive+"--"+kdFin+"--"+urutan+"=="+alasanmaksa);
        }
    }
    //$('#alasan_maksa').val(alasanmaksa);
}
/**
 * Gets the history leasing.
 *
 * @param      {<type>}  spk_id  The spk identifier
 */
function __getHistoryLeasing(spk_id){
}
/**
 * { function_description }
 */
function __simpanAlasan(){
    var alsan=$("input:radio[name=radiox]:checked").val();
    //console.log(alsan);
    $("#alasan_maksa").val(alsan);
    if(alsan!=''){
        keluar()
        $('#panelsales fieldset').removeAttr("disabled");
        $('#tab-header fieldset').attr("disabled",false)
        $('#btn-simpan_motor').removeClass("disabled-action").attr("disabled",false);
        __getsalesprogram();
        __getSalesKupon();
    }
}
/**
 * Gets the cicilan.
 *
 * @param      {<type>}  harga_motor  The harga motor
 */
function __getCicilan(harga_motor){
    if($('#kd_fincom').val()=='CSH' || $('#kd_fincom').val()=='KDS'){
        var uang_muka = parseFloat($('#uang_muka').val().replace(/,/g, ''));
        var tempo = parseFloat($('#jangka_waktu').val());
        var bunga = parseFloat($('#bunga').val().replace(/%/g,''));
        var sisa_harga=0
        var bungane=0;
        var cicilan=0;
        var angsuran=0;
            sisa_harga = parseFloat(harga_motor) - uang_muka;
            cicilan = sisa_harga/tempo;
            bungane = cicilan * (bunga/100);
            angsuran=(cicilan + bungane);
            angsuran= Math.ceil(angsuran/100)*100;
        /*//console.log(harga_motor+'t:'+tempo+"u: "+uang_muka);
        //console.log(sisa_harga);
        //console.log(cicilan);
        //console.log(bungane);
        //console.log(angsuran);*/
        $('#jumlah_angsuran').addClass("disabled-action");
        $('#jumlah_angsuran').val(angsuran.toLocaleString());
    }else{
        $('#jumlah_angsuran').removeClass("disabled-action");
    }
}
/**
 * Gets the data bpkb.
 *
 * @param      {<type>}  nospk   The nospk
 */
function __getDataBPKB(nospk){
    return;
}
/**
 * { function_description }
 *
 * @param      {string}  no_spk  No spk
 */
function __tipum_simpan(no_spk){
    var datax=[];
    var jml = $('#jml_titipan').val().replace(/,/g,'');
    //datax.push()
    if(parseFloat(jml)>0){
        $('#loadpage').removeClass("hidden");
        $.ajax({
            type :'POST',
            url  : http+"/cashier/simpan_titipan",
            data : {
                    'no_reff' :no_spk,
                    't_harga_titipan' : jml,
                    'no_kwitansi'   :'',
                    'uraian_titipan':'Titipan Uang untuk pembayaran Unit Motor No.SPK: '+no_spk,
                    'spk_id':$('#spkid').val()
                },
            dataType :'json',
            success :function(result){
                if(result.status){
                    $('.success').animate({ top: "0"}, 500);
                    $('.success').html('Data berhasil di simpan').fadeIn();
                    setTimeout(function() {
                        document.location.href = result.location;
                    }, 2000);
                }else{
                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html('Data gagal disimpan').fadeIn();
                    setTimeout(function() {
                        hideAllMessages();
                    }, 4000);
                }
                $('#loadpage').addClass("hidden");
            }
        })
    }
}
function _tipum_delete(id){
    $('#loadpage').removeClass("hidden");
    $.ajax({
        type :'post',
        url : http+"/cashier/hapus_titipan",
        data :{'no_trans':id,'spk_id':$('#spkid').val()},
        dataType:'json',
        success : function(result){
            if(result.status){
                $('.success').animate({ top: "0"}, 500);
                $('.success').html('Data berhasil di simpan').fadeIn();
                setTimeout(function() {
                    document.location.href = result.location;
                }, 2000);
            }else{
                $('.error').animate({
                    top: "0"
                }, 500);
                $('.error').html('Data gagal disimpan').fadeIn();
                setTimeout(function() {
                    hideAllMessages();
                }, 4000);
            }
            $('#loadpage').addClass("hidden");
        }
    })
}
