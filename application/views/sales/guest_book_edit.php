<script type="text/javascript">
    function __getdata_warna(kd_item){
        $("#kd_item2_wm").val('');
        var kw =(kd_item)?'': $("#kd_item2_wm").val();
        $("#kd_items_wm #cls_wm").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
                $.ajax({
                    url:'<?php echo base_url("purchasing/listmotor");?>',
                    type:"POST",
                    dataType: "html",
                    data:{"keyword":kw,"lst":'2',"kd_type":kd_item,'lok':'_wm'},
                    success:function(result){
                        $("#list_wm tbody").html("");
                        $("table#list_wm tbody").append(result);
                        $("#kd_items_wm #cls_wm").html("");
                        //$("#kd_items_wm").click();
                    }

                });
                return false;
    }
</script>
<?php
    if (!isBolehAkses()) {
          redirect(base_url() . 'auth/error_auth');
      }

      $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
      $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
      $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
      $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
    $defaultDealer=$this->session->userdata("kd_dealer");
    $dataguest="";
    $kd_customer    = "";$nama_customer  = "";$no_ktp="";
    $kd_dealer      = "";$kd_typecustomer= "";$tgl_lahir="";
    $kd_typemotor   = "";$kd_warna       = "";
    $kd_propinsi    = "";$kd_kabupaten   = "";
    $kd_kecamatan   = "";$kd_desa        = "";
    $alamat         = "";$no_hp          = "";
    $email          = "";$tgl_visit      = "";
    $status_deal    = "";$Keterangan     = "";$no_guest="";
    $test_drive     = "";$kd_sales       = "";$jenis_kelamin  = "";$nama_pekerjaan  = "";

    if($guestbook){
        if(is_array($guestbook->message)){
            foreach ($guestbook->message as $key => $value) {
                $kd_customer    = $value->KD_CUSTOMER;
                $nama_customer  = $value->NAMA_CUSTOMER;
                $kd_dealer      = $value->KD_DEALER;
                $kd_typecustomer= $value->KD_TYPECUSTOMER;
                $kd_typemotor   = $value->KD_TYPEMOTOR;
                $kd_warna       = $value->KD_WARNA;
                $kd_propinsi    = $value->KD_PROPINSI;
                $kd_kabupaten   = $value->KD_KOTA;
                $kd_kecamatan   = $value->KD_KECAMATAN;
                $kd_desa        = $value->KELURAHAN;
                $alamat         = $value->ALAMAT_SURAT;
                $no_hp          = $value->NO_HP;
                $email          = $value->EMAIL;
                $tgl_visit      = $value->TANGGAL;
                $status_deal    = $value->STATUS;
                $Keterangan     = ltrim(rtrim($value->KETERANGAN));
                $test_drive     = $value->TEST_DRIVE;
                $kd_sales       = $value->KD_SALES;
                $jenis_kelamin  = $value->JENIS_KELAMIN;
                $no_guest       = $value->GUEST_NO;
                $no_ktp         = $value->NO_KTP;
                $tgl_lahir      = $value->TGL_LAHIR;
                $tgl_test       = $value->TGL_TEST;
                $kesan_test     = $value->KET_TESTDRIVE;
                $nama_pekerjaan = $value->NAMA_PEKERJAAN;
            }
        }
    }
    //print_r($guestbook->message);
    $tgl_lahir=(substr($tgl_lahir, 0,4)<1920)?"":tglfromSql($tgl_lahir);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-user-md fa-fw" aria-hidden="true"></i> Edit Guest Book</h4>
</div>

<div class="modal-body">
    <form class="bucket-form" id="addForm" method="post" action="<?php echo base_url("customer/guestbook_update");?>">
        <div class="row table-responsive">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <!-- nama dealer -->
                <div class="form-group">
                    <label>Nama Dealer</label>
                    <select class="form-control" id="kd_dealer" name="kd_dealer" disabled="disabled">
                        <option value="0">--Pilih Dealer--</option>
                        <?php
                            if($dealer){
                                if(is_array($dealer->message)){
                                    foreach ($dealer->message as $key => $value) {
                                        $aktif=($defaultDealer==$value->KD_DEALER)?"selected":"";
                                        $aktif=($this->input->get("kd_delaer")==$value->KD_DEALER)?"selected":$aktif;
                                        echo "<option value='".$value->KD_DEALER."' ".$aktif.">".$value->NAMA_DEALER."</option>";
                                    }
                                }
                            }
                        ?> 
                    </select>
                </div>
                <!-- nama customer -->
                <div class="form-group">
                    <div id="ajax-url-customer" url="<?php echo base_url('customer/customer_typeahead'); ?>"></div>
                    <label>Nama Customer</label>
                    <input type="text" name="nama_customer" disabled="disabled" value="<?php echo $nama_customer;?>" id="nama_customer" class="form-control" placeholder="Masukkan Nama Customer">
                    <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer;?>">
                     <input type="hidden" id="guest_no" name="guest_no" value="<?php echo $no_guest;?>">
                </div>
                <!-- alamat -->
                <div class="form-group">
                    <label>Alamat <span id="l_alamat"></span></label>
                    <textarea type="text"  name="alamat" id="alamat" class="form-control" placeholder="Masukkan Nama Alamat" ><?php echo $alamat;?></textarea>

                </div>
                <!-- propinsi -->
                <div class="form-group">
                    <label>Propinsi</label>
                    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                        <option value="0">--Pilih Propinsi--</option>
                        <?php
                        /*var_dump($propinsi);
                        exit();*/
                            if($propinsi){
                                if(is_array($propinsi->message)){
                                    foreach ($propinsi->message as $key => $value) {
                                        $select=($kd_propinsi==$value->KD_PROPINSI)?"selected":"";
                                        echo "<option value='".$value->KD_PROPINSI."' ".$select.">".$value->NAMA_PROPINSI."</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
                <!-- kabupaten -->
                <div class="form-group">
                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                        <option value="0">--Pilih Kabupaten--</option>
                    </select>
                </div>
                <!-- kecamatan -->
                <div class="form-group">
                    <label>Kecamatan <span id="l_kecamatan"></span></label>
                    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                        <option value="0">--Pilih Kecmatan--</option>
                    </select>
                </div>
                <!-- kelurahan -->
                <div class="form-group">
                    <label>Kelurahan <span id="l_desa"></span></label>
                    <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                        <option value="0">--Pilih Kelurahan--</option>
                    </select>
                </div>
                <!-- email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email_customer" id="email_customer" value="<?php echo $email;?>" class="form-control" placeholder="Masukkan Email">
                </div>
                <!-- nama sales -->
                <div class="form-group">
                    <label>Nama Sales</label>
                    <select class="form-control" name="kd_sales" id="kd_sales">
                        <option value='0'>--Pilih Nama Sales--</option>
                        <?php
                            if($sales){
                                if(is_array($sales->message)){
                                    foreach ($sales->message as $key => $value) {
                                        $select=($kd_sales==$value->KD_SALES)?" selected":"";
                                        echo "<option value='".$value->KD_SALES."'".$select.">".$value->NAMA_SALES."</option>";
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
                         if($typecustomer){
                            if(is_array($typecustomer->message)){
                                foreach ($typecustomer->message as $key => $value) {
                                    $select=($kd_typecustomer==$value->KD_TYPECUSTOMER)?" selected":"";
                                     echo "<option value='".$value->KD_TYPECUSTOMER."'".$select.">".$value->NAMA_TYPECUSTOMER."</option>";
                                }
                            }
                         }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                <!-- tanggal kunjungan -->
                <div class="form-group">
                    <label class="control-label" for="date">Tanggal</label>
                    <div class="input-group input-append date">
                        <input class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" placeholder="DD/MM/YYYY" value="<?php echo ($tgl_visit!='')?tglfromSql($tgl_visit): date('d/m/Y');?>" type="text"/>
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <!-- nomor ktp -->
                <div class="form-group">
                    <label>No. KTP</label>
                    <input type="text" class="form-control" readonly="readonly" id="no_ktp" name="no_ktp" value="<?php echo $no_ktp;?>" placeholder="Masukan nomor ktp">
                </div>
                <!-- tgl lahir -->
                <div class="form-group">
                    <label>Tgl. Lahir</label>
                    <div class="input-group input-append date" id="datex">
                        <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?php echo $tgl_lahir;?>" placeholder="dd/mm/yyyy" />
                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <!-- nomor telepon -->
                <div class="form-group">
                    <label>No. Telphone/HP</label>
                    <input type="text" name="hp_customer" id="hp_customer" value="<?php echo $no_hp;?>" class="form-control" placeholder="Masukkan nomor telpon atau HP">
                </div>
                <!-- jenis kelamin -->
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select class="form-control" id="kd_gender" name="kd_gender">
                        <option value="0">--Pilih Jenis Kelamin--</option>
                        <?php
                            if($gender){
                                if(is_array($gender->message)){
                                    foreach ($gender->message as $key => $value) {
                                        $select=($jenis_kelamin==$value->KD_GENDER)?"selected":"";
                                        echo "<option value='".$value->KD_GENDER."' ".$select.">".$value->NAMA_GENDER."</option>";
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
                <!-- type motor -->
                <div class="form-group">
                    <label>Type Motor</label>
                    <?php echo DropDownMotor(true,$kd_typemotor);?>
                </div>
                <!-- warna motor -->
                <div class="form-group">
                    <label>Warna Motor</label>
                      <?php echo DropDownWarnaMotor($kd_warna);?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control" placeholder="Masukkan Nama Pekerjaan" required="required" value="<?php echo $nama_pekerjaan; ?>">
                            </div>
                        </div>
                <!-- status deale -->
                <div class="form-group">
                    <label>Status Deal</label>
                    <select class="form-control" id="kd_status" name="kd_status">
                        <option value="">--Pilih Status--</option>
                        <option value="Deal" <?php echo ($status_deal=="Deal")?"selected":"";?>>Deal</option>
                        <option value="Not Deal" <?php echo ($status_deal=="Not Deal")?"selected":"";?>>Not Deal</option>
                    </select>
                </div>
                <!-- keterangan deale -->
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea type="text" name="alasan_nodeal" id="alasan_nodeal" class="form-control" placeholder="Masukkan Keterangan" ><?php echo $Keterangan;?></textarea>
                </div>
                <!-- test drive -->
                <div class="form-group">
                    <label>Test Drive</label>
                    <select class="form-control" name="test_drive" id="test_drive">
                        <option value="Tidak" <?php echo ($test_drive=="Tidak")?"selected":"";?>>Tidak</option>
                        <option value="Ya" <?php echo ($test_drive=="Ya")?"selected":"";?>>Ya</option>
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
                                        <input type="text" class="form-control" id="tgl_test" name="tgl_test" value="<?php echo ($tgl_test)?tglFromSql($tgl_test):date("d/m/Y");?>" placeholder="dd/mm/yyyy" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kesan Experience</label>
                                    <textarea class="form-control" id="kesan_test" name="kesan_test"><?php echo $kesan_test;?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        </div>
    </form>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> Keluar</button>
    <button type="button" class="btn btn-danger <?php echo $status_e;?>" onclick="addData()" id="submit-btn"><i class="fa fa-save" aria-hidden="true"></i> Update Data</button>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var date = new Date();
        date.setDate(date.getDate());

        $('#date, #datex').datepicker({ 
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted:"0",
            autoclose: true/*,
            setDate: date*/
        });

        var ajaxUrls = $("#ajax-url-customer").attr("url");

        if(ajaxUrls != null){
            $.getJSON(ajaxUrls, function(data, status){
                if(status == 'success')
                {
                    $("#nama_customer").typeahead({
                        source: data.keyword,
                        autoSelect:false
                    });
                }

            });
        }
        if($('#test_drive').val()=="Ya"){
                $('#ridingpanel').removeClass("hidden");
                //$("#kesan_test").focus().select();
                $("#kesan_test").attr('required','required')
            }else{
                 $('#ridingpanel').addClass("hidden");
                 $("#kesan_test").removeAttr('required','required')
            }
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
        /*pilihan propinsi*/
        $('#kd_propinsi').on('change',function(){
            loadData('kd_kabupaten',$('#kd_propinsi').val(),'0')
        })
        $('#kd_kabupaten').on('change',function(){
            loadData('kd_kecamatan',$(this).val(),'0')
        })
        $('#kd_kecamatan').on('change',function(){
            loadData('kd_desa',$(this).val(),'0')
        })
        /* load data customer */
        /*$('#nama_customer').on('focusout',function(){
            __getcustomerdetail();
        })*/
         loadData('kd_kabupaten','<?php echo $kd_propinsi;?>','<?php echo $kd_kabupaten;?>');
         loadData('kd_kecamatan','<?php echo $kd_kabupaten;?>','<?php echo $kd_kecamatan;?>'); 
         loadData('kd_desa','<?php echo $kd_kecamatan;?>','<?php echo $kd_desa;?>'); 
    });
    
   function __getcustomerdetail(){
    $('#l_alamat').html("<i class='fa fa-spinner fa-spin'></i>");
    $.ajax({
            type: 'POST',
            url: "<?php echo base_url('customer/customerdetail');?>",
            dataType: 'json',
            data:{
                'nama_customer':$('#nama_customer').val()
            },
            success:function(result){
                /*if(result.status==false){
                   $('#l_alamat').html(""); 
                   $('#kd_propinsi').val('0').select();
                   loadData('kd_kabupaten','0','0');
                   loadData('kd_kecamatan','0','0');
                   loadData('kd_desa','0','0');
                   //return;
                }*/
                if(result.status==true){
                     $.each(result.message, function (index, d) {
                        $("#nama_customer").val(d.NAMA_CUSTOMER);
                        $('#kd_customer').val(d.KD_CUSTOMER);
                        $('#alamat').val(d.ALAMAT_SURAT);
                        $('#kd_propinsi').val(d.KD_PROPINSI).select();
                        loadData('kd_kabupaten', d.KD_PROPINSI, d.KD_KOTA);
                        loadData('kd_kecamatan', d.KD_KOTA, d.KD_KECAMATAN);
                        loadData('kd_desa', d.KD_KECAMATAN, d.KELURAHAN);
                        $('#hp_customer').val(d.NO_HP);
                        $('#email_customer').val(d.EMAIL);
                        $('#kd_gender').val(d.JENIS_KELAMIN).select();
                        $('#kd_sales').val(d.ID_REFFERAL).select();
                        $('#tgl_lahir').val(convertDate(d.TGL_LAHIR));
                        $('#l_alamat').html("");
                        $('#no_ktp').val(d.NO_KTP)
                        $('#loadpage').addClass("hidden");
                    })
                }
            },
        })
   }
   function loadData(id, value, select) {

        var param = $('#' + id + '').attr('title');
        $('#l_' + param + '').html("<i class='fa fa-spinner fa-spin'></i>");
        var urls = "<?php echo base_url(); ?>customer/" + param;
        var datax = {"kd": value};
        $('#' + id + '').attr('disabled','disabled');
        $.ajax({
            type: 'GET',
            url: urls,
            data: datax,
            typeData: 'html',
            success: function (result) {
                $('#' + id + '').empty();
                $('#' + id + '').html(result);
                $('#' + id + '').val(select).select();
                $('#l_' + param + '').html('');
                $('#' + id + '').removeAttr('disabled');
            }
        });
    }
   
</script>