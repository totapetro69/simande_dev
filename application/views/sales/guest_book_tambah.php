<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_ce= (isBolehAkses('c') || isBolehAkses('e'))? '' : ' disabled-action';
$defaultDealer = $this->session->userdata("kd_dealer");
?>
<form class="bucket-form" id="addForm" method="post" action="<?php echo base_url("customer/simpan_guest"); ?>">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user-md fa-fw" aria-hidden="true"></i> Input Guest Book</h4>
    </div>

    <div class="modal-body">

        <div class="row table-responsive">

            <div class="col-xs-6 col-sm-6 col-md-6">
                <!-- nama dealer -->
                <div class="form-group">
                    <label>Nama Dealer</label>
                    <select class="form-control" id="kd_dealer" name="kd_dealer" <?php echo ($this->session->userdata("kd_group")=="Root")?"":"disabled";?>>
                        <option value="0">--Pilih Dealer--</option>
                        <?php
                        if ($dealer) {
                            if (is_array($dealer->message)) {
                                foreach ($dealer->message as $key => $value) {
                                    $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                    $aktif = ($this->input->get("kd_delaer") == $value->KD_DEALER) ? "selected" : $aktif;
                                    echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                }
                            }
                        }
                        ?> 
                    </select>
                </div>
                <!-- nama customer -->
                <div class="form-group">
                    <div id="ajax-url-customer" url="<?php echo base_url('customer/customer_typeahead/'.$defaultDealer.'/'); ?>"></div>
                    <label>Nama Customer </label>
                    <input type="text" name="nama_customer"  id="nama_customer" class="form-control" placeholder="Masukkan Nama Customer" autocomplete="off" required >
                    <input type="hidden" id="kd_customer" name="kd_customer" value="">
                </div>
                <!-- alamat -->
                <div class="form-group">
                    <label>Alamat <span id="l_alamat"></span></label>
                    <textarea type="text"  name="alamat" id="alamat" class="form-control" placeholder="Masukkan Nama Alamat" required="required"></textarea>
                </div>
                <!-- propinsi -->
                <div class="form-group">
                    <label>Propinsi</label>
                    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
                        <option value="0">--Pilih Propinsi--</option>
                        <?php
                        if ($propinsi) {
                            if (is_array($propinsi->message)) {
                                foreach ($propinsi->message as $key => $value) {
                                    echo "<option value='" . $value->KD_PROPINSI . "'>" . $value->NAMA_PROPINSI . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- kabupaten -->
                <div class="form-group">
                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required="true">
                        <option value="0">--Pilih Kabupaten--</option>
                    </select>
                </div>
                <!-- kecamatan -->
                <div class="form-group">
                    <label>Kecamatan <span id="l_kecamatan"></span></label>
                    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan" required="true">
                        <option value="0">--Pilih Kecamatan--</option>
                    </select>
                </div>
                <!-- keluarahan -->
                <div class="form-group">
                    <label>Kelurahan <span id="l_desa"></span></label>
                    <select class="form-control" id="kd_desa" name="kd_desa" title="desa" required="true">
                        <option value="0">--Pilih Kelurahan--</option>
                    </select>
                </div>
                <!-- email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email_customer" id="email_customer" class="form-control" placeholder="Masukkan Email">
                </div>
                <!-- nama sales -->
                <div class="form-group">
                    <label>Nama Sales</label>
                    <select class="form-control" name="kd_sales" id="kd_sales">
                        <option value='0'>--Pilih Nama Sales--</option>
                        <?php
                        if ($sales) {
                            if (is_array($sales->message)) {
                                foreach ($sales->message as $key => $value) {
                                    echo "<option value='" . $value->KD_SALES . "'>" . $value->NAMA_SALES . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- type customer -->
                <div class="form-group">
                    <label>Type Customer</label>
                    <select class="form-control" name="kd_typecustomer" id="kd_typecustomer">
                        <option value='0'>--Pilih Type Customer--</option>
                        <?php
                        if ($typecustomer) {
                            if (is_array($typecustomer->message)) {
                                foreach ($typecustomer->message as $key => $value) {
                                    echo "<option value='" . $value->KD_TYPECUSTOMER . "'>" . $value->NAMA_TYPECUSTOMER . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Rencana Pembayaran</label>
                    <select id="carabayar" name="carabayar" class="form-control" required="true">
                        <option value="">&nbsp;</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Kredit">Kredit</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                <!-- tanggal kedatangan  -->
                <div class="form-group">
                    <label class="control-label" for="date">Tanggal Kedatangan</label>
                    <div class="input-group input-append date" id="date">
                        <input class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <!-- nomor ktp -->
                <div class="form-group">
                    <label>No. KTP</label>
                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="" placeholder="Masukan nomor ktp" required>
                </div>
                <!-- tgl lahir -->
                <div class="form-group">
                    <label>Tgl. Lahir</label>
                    <div class="input-group input-append date" id="datex">
                        <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="" placeholder="dd/mm/yyyy" required/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <!-- nomor telephon -->
                <div class="form-group">
                    <label>No. Telphone/HP</label>
                    <input type="text" name="hp_customer" id="hp_customer" class="form-control" placeholder="Masukkan nomor telpon atau HP">
                </div>
                <!-- jenis kelamin -->
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" id="kd_gender" name="kd_gender" required>
                        <option value="0">--Pilih Jenis Kelamin--</option>
                        <?php
                        if ($gender) {
                            if (is_array($gender->message)) {
                                foreach ($gender->message as $key => $value) {
                                    echo "<option value='" . $value->KD_GENDER . "'>" . $value->NAMA_GENDER . "</option>";
                                }
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- type motoe -->
                <div class="form-group">
                    <label>Type Motor</label>
                    <?php echo DropDownMotor(true); ?>
                </div>
                <!-- warna motor -->
                <div class="form-group">
                    <label>Warna Motor</label>
                    <?php echo DropDownWarnaMotor(); ?>
                </div>
                <!-- status deal -->
                <div class="form-group">
                    <label>Status Deal</label>
                    <select class="form-control" id="kd_status" name="kd_status">
                        <option value="">--Pilih Status--</option>
                        <option value="Deal">Deal</option>
                        <option value="Deal Indent">Deal Indent</option>
                        <option value="Pending">Pending</option>
                        <option value="Not Deal">Not Deal</option>
                    </select>
                </div>
                <!-- keterangan -->
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea type="text" name="alasan_nodeal" id="alasan_nodeal" class="form-control" placeholder="Masukkan Keterangan" ></textarea>
                </div>
                 <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="statuse" name="statuse">
                        <option value="">--Pilih Status--</option>
                        <option value="Hot">Hot</option>
                        <option value="Hot">Low</option>
                    </select>
                </div>
                <!-- test drive -->
                <div class="form-group">
                    <label>Test Drive</label>
                    <select class="form-control" name="test_drive" id="test_drive">
                        <option value="Tidak">Tidak</option>
                        <option value="Ya">Ya</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-sm-12 hidden" id="ridingpanel">
                <div class="col-sm-8 col-md-offset-4">
                    <div class="panel margin-botom-10">
                        <div class="panel-heading"><i class="fa fa-list"></i> Data Riding Test</div>
                        <div class="panel-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Test</label>
                                     <div class="input-group input-append date" id="date">
                                        <input type="text" class="form-control" id="tgl_test" name="tgl_test" value="<?php echo date('d/m/Y');?>" placeholder="dd/mm/yyyy" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kesan Experience</label>
                                    <textarea class="form-control" id="kesan_test" name="kesan_test"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Keluar</button>
        <button id="submit-btn" type="submit" class="btn btn-danger submit-btn <?php echo $status_ce;?>"><i class="fa fa-save"></i> Simpan</button>
    </div>

</form>
<?php echo loading_proses();?>
<script type="text/javascript">
    var path = window.location.pathname.split('/');
    var http = window.location.origin + '/' + path[1];
    $(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());

        $('#date,#datex').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true/*,
            setDate: date*/
        });

        var ajaxUrls = $("#ajax-url-customer").attr("url");

        if (ajaxUrls != null) {
            $.getJSON(ajaxUrls, function (data, status) {
                if (status == 'success')
                {
                    $("#nama_customer").typeahead({
                        source: data.keyword,
                        autoSelect: false
                    });
                }

            });
        }
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change', function () {
            loadData('kd_kabupaten', $('#kd_propinsi').val(),'')
        })
        $('#kd_kabupaten').on('change', function () {
            loadData('kd_kecamatan', $(this).val(), '')
        })
        $('#kd_kecamatan').on('change', function () {
            loadData('kd_desa', $(this).val(), '')
        })
        /* load data customer */
        $('#nama_customer').on('focusout', function () {
           if($('#nama_customer').val()!='') $('#no_ktp').focus().select();// __getcustomerdetail();
        })
        $('#no_ktp').on('focusout', function () {
           if($('#no_ktp').val()!='') __getcustomerdetail();
        })
        $('#tgl_lahir').on('focusout', function () {
           if($('#tgl_lahir').val()!='') __getcustomerdetail();
        })
       $('#test_drive').change(function(){
            if($(this).val()==="Ya"){
                $('#ridingpanel').removeClass("hidden");
                $("#kesan_test").focus().select();
                $("#kesan_test").attr('required','required')
            }else{
                 $('#ridingpanel').addClass("hidden");
                 $("#kesan_test").removeAttr('required','required')
            }
       })
       $('#kd_status').change(function(){
         var index=$('#kd_status option:selected').index();
         swicth(index){
            case 2:
            case 3:
            case 4:
                $('#alasan_nodeal').prop('required',true).attr('placeholder','Masukan keterangan '+$('#kd_status option:selected').text());
            break;
            default:
                $('#alasan_nodeal').prop('required',false);
            break;            
         }
       })
    });

    function __getdata_warna(kd_item) {
        $("#kd_item2_wm").val('');
        var kw = (kd_item) ? '' : $("#kd_item2_wm").val();
        $("#kd_items_wm #cls_wm").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
        $.ajax({
            url: '<?php echo base_url("purchasing/listmotor"); ?>',
            type: "POST",
            dataType: "html",
            data: {"keyword": kw, "lst": '2', "kd_type": kd_item, 'lok': '_wm'},
            success: function (result) {
                $("#list_wm tbody").html("");
                $("table#list_wm tbody").append(result);
                $("#kd_items_wm #cls_wm").html("");
                $("#kd_items_wm").click();
            }

        });
        return false;
    }
    function __getcustomerdetail() {
        $('#loadpage').removeClass("hidden");
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('customer/customerdetail'); ?>",
            dataType: 'json',
            data:{
                'nama_customer':$('#nama_customer').val(),
                'tgl_lahir':$("#tgl_lahir").val(),
                'no_ktp'   :$('#no_ktp').val()
            },
            success: function (result) {
                
                //console.log(result);
                if (result.status == true){
                    $.each(result.message, function (index, d) {
                        $("#nama_customer").val(d.NAMA_CUSTOMER);
                        $('#kd_customer').val(d.KD_CUSTOMER);
                        $('#alamat').val(d.ALAMAT_SURAT);
                        $('#kd_propinsi').val(d.KD_PROPINSI).select();
                        loadData("kd_kabupaten",d.KD_PROPINSI,d.KD_KOTA);
                        loadData('kd_kecamatan',d.KD_KOTA,d.KD_KECAMATAN);
                        loadData('kd_desa',d.KD_KECAMATAN,d.KELURAHAN);
                        $('#hp_customer').val(d.NO_HP);
                        $('#email_customer').val(d.EMAIL);
                        $('#kd_gender').val(d.JENIS_KELAMIN).select();
                        $('#kd_sales').val(d.ID_REFFERAL).select();
                        $('#tgl_lahir').val(convertDate(d.TGL_LAHIR));
                        $('#l_alamat').html("");
                        $('#loadpage').addClass("hidden");
                    })
                }else{
                    //alert("Nomor KTP / Tgl Lahir harus di isi");
                    $('#l_alamat').html("");
                    $('#loadpage').addClass("hidden");
                }
            }/*,
             fail:function(jqXHR, textStatus, errorThrown){
             $('#l_alamat').html(""); 
             $('#kd_propinsi').val('0').select();
             loadData('kd_kabupaten','0','0');
             loadData('kd_kecamatan','0','0');
             loadData('kd_desa','0','0');
             }*/
        })
    }
    jQuery.fn.LoadSibling=function(id, select){
        $(this).on('change',function(){
            loadData(id, $(this).val(), select);
        })
    }
    function loadData(id, value, select) {
        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = http + "/customer/" + param;
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

</script>