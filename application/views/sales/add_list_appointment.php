<?php
if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $status_ce= (isBolehAkses('c') || isBolehAkses('e'))? '' : ' disabled-action';

$defaultDealer = ($this->input->get('kd_dealer'))?$this->input->get("kd_dealer"):$this->session->userdata("kd_dealer");
    $dataappointment = "";    $kd_customer     = "";    $nama_customer    = ""; 
    $kd_dealer      = "";     $kd_propinsi    = "";    $kd_kecamatan   = "";
    $alamat         = "";    $tanggal        = date("d/m/Y");    $no_hp          ="";
    $no_trans       ="";    $kd_sales     = "";    $tanggal_janji =date("d/m/Y");
    $nama_customer  = "";    $jam_janji    =date("H:i");    $kd_kabupaten   = "";    $kd_desa        = "";
    $jenis_appointment ="";    $hubungi_via = "";    $keterangan = "";

    if($this->input->get('n')!=''){
        if($list){
            if($list->totaldata>0){
                foreach ($list->message as $key => $value) {
                    $kd_customer     = $value->KD_CUSTOMER;
                    $nama_customer  = $value->NAMA_CUSTOMER; 
                    $defaultDealer  = $value->KD_DEALER;
                    $kd_propinsi    = $value->KD_PROPINSI;
                    $kd_kabupaten   = $value->KD_KABUPATEN;
                    $kd_kecamatan   = $value->KD_KECAMATAN;
                    $kd_desa        = $value->KD_DESA;
                    $alamat         = $value->ALAMAT;
                    $no_hp          = $value->NO_HP;
                    $tanggal        = tglfromSql($value->TANGGAL);
                    $no_trans       = $value->NO_TRANS;
                    $kd_sales     = $value->KD_SALES;
                    $tanggal_janji  = tglfromSql($value->TANGGAL_JANJI);
                    $jam_janji      = $value->JAM_JANJI;
                    $jenis_appointment = $value->JENIS_APPOINTMENT;
                    $hubungi_via    = $value->HUBUNGI_VIA;
                    $keterangan      = $value->KETERANGAN;
                }
            }
        }
        //print_r($guestbook->message)
        $alamat =preg_replace('/(\r\n|\r|\n)+/', "\n", $alamat);
        $alamat =preg_replace('/\s+/', ' ', $alamat);
    }
    $lock=($no_trans)?'disabled-action':'';
?>

<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <a class="btn btn-default" id="baru" role="buttton"><i class="fa fa-file-o"></i> Baru</a>
            <?php if($this->input->get("n")){ ?>
                <a role="button" onclick="simpan_list_appointment();" class="btn btn-default submit-btn <?php echo $status_e;?>"><i class="fa fa-save"></i> Update Appointment</a>
            <?php }else{ ?>
                <a role="button" onclick="simpan_list_appointment();" class="btn btn-default submit-btn <?php echo $status_ce;?>"><i class="fa fa-save"></i> Simpan Appointment</a>
            <?php } ?>
            <a role="button" href="<?php echo base_url("customer/list_appointment");?>" class="btn btn-default <?php echo $status_v;?>"><i class="fa fa-list-ul"></i> List Appointment</a>
        </div>
    </div>
        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-users'></i> List Appointment <?php echo ($no_trans)?"[ ".$no_trans." ]":"";?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>

                </span>
            </div>
            <div class="panel-body panel-body-border">
                <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("customer/simpan_list_appointment"); ?>">
                    <!-- baris ke 1 -->
                    <div class="row">
                        <!-- nama customer -->
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Dealer</label>
                                    <select class="form-control" id="kd_dealer" name="kd_dealer" required="true">
                                        <option value="0">--Pilih Dealer--</option>
                                        <?php
                                        if ($dealer) {
                                            if (is_array($dealer->message)) {
                                                foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>No. Trans</label>
                                    <input type="text" name="no_trans" id="no_trans" placeholder="AUTO GENERATE" readonly class="form-control">
                                </div>
                            </div>

                        </div>      
                    </div>

                    <!-- baris ke 2 -->
                    <div class="row">    
                        <!-- alamat -->
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <!-- <div id="ajax-url-customer" url="<?php echo base_url('customer/customer_autocomplete/'.$defaultDealer.'/'); ?>"></div> -->
                                    <label>Nama Customer </label>
                                    <div class="input-group">
                                        <input type="text" name="nama_customer" value="<?php echo $nama_customer;?>" id="nama_customer" class="form-control <?php echo $lock;?>" placeholder="Masukkan Nama Customer" autocomplete="off" required >
                                        <span class="input-group-btn disabled-action" id="cari">
                                            <button type="button" id="modal-button-3" class='btn btn-info' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer;?>">
                                    <input type="hidden" id="no_trans" name="no_trans" value="<?php echo $no_trans;?>">
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Jenis Appointment</label>
                                    <select name="jenis_appointment" id="jenis_appointment" class="form-control"  required="true">
                                        <option value="">--Pilih Jenis Appointment--</option>
                                        <option value="Riding Test" <?php echo($jenis_appointment=="Riding Test")?"selected":"";?>>Riding Test</option>
                                        <option value="Pembelian Unit" <?php echo($jenis_appointment=="Pembelian Unit")?"selected":"";?>>Pembelian Unit</option>
                                        <option value="Ambil BPKB" <?php echo($jenis_appointment=="Ambil BPKB")?"selected":"";?>>Ambil BPKB</option>
                                        <option value="Dll" <?php echo($jenis_appointment=="Dll")?"selected":"";?>>Dan Lain-Lain</option>
                                    </select>
                                </div>      
                            </div>

                        </div>
                    </div>

                    <!--Baris Ke-3 -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Alamat <span id="l_alamat"></span></label>
                                    <textarea type="text" rows="4"  name="alamat" id="alamat" class="form-control" placeholder="Masukkan Nama Alamat" required="required" autocomplete="off"><?php echo ucwords($alamat);?></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Via Penghubung</label>
                                    <select class="form-control" id="nama_metode" name="nama_metode"  required="true">
                                        <option value="" >- Pilih  -</option>
                                        <?php
                                        if(isset($metodefus)){
                                            if($metodefus->totaldata >0){
                                                foreach ($metodefus->message as $key => $value) {
                                                    $pilih=($hubungi_via==$value->NAMA_METODE)?'selected':'';
                                                    echo "<option value='".$value->NAMA_METODE."' ".$pilih.">".$value->NAMA_METODE."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Sales</label>
                                    <select class="form-control" name="kd_sales" id="kd_sales" required="true">
                                        <option value=''>--Pilih Nama Sales--</option>
                                        <?php
                                        if (isset($sales)) {
                                            if ($sales->totaldata >0) {
                                                foreach ($sales->message as $key => $value) {
                                                    $pilih =($kd_sales==$value->KD_SALES)?'selected':'';
                                                    echo "<option value='" . $value->KD_SALES . "' ".$pilih.">" . $value->NAMA_SALES . "</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--Baris ke-4-->
                        <!-- propinsi -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Propinsi</label>
                                    <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
                                        <option value="">--Pilih Propinsi--</option>
                                        <?php
                                        if(isset($propinsi)){
                                            if(($propinsi->totaldata>0)){
                                                foreach ($propinsi->message as $key => $value) {
                                                    $select=($kd_propinsi==$value->KD_PROPINSI)?"selected":"";
                                                    echo "<option value='".$value->KD_PROPINSI."' ".$select.">".$value->NAMA_PROPINSI."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Kabupaten <span id="l_kabupaten"></span></label>
                                    <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten"  required="true">
                                        <option value="">--Pilih Kabupaten--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Tanggal Janji</label>
                                    <div class="input-group input-append date" id="date">
                                        <input required type="text" class="form-control" id="tanggal_janji" name="tanggal_janji" value="<?php echo $tanggal_janji; ?>" placeholder="dd/mm/yyyy" />
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Jam Janji</label>
                                    <div class="input-group input-append datetime-mulai" id="datetime">
                                        <input class="form-control" id="jam_janji" name="jam_janji" placeholder="HH:MM" value="<?php echo $jam_janji?>" type="text"/>
                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="col-sm-3">
                               <div class="form-group">
                                    <label>Kecamatan <span id="l_kecamatan"></span></label>
                                    <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan"  required="true">
                                        <option value="">--Pilih Kecamatan--</option>
                                    </select>
                                </div> 
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Kelurahan <span id="l_desa"></span></label>
                                    <select class="form-control" id="kd_desa" name="kd_desa" title="desa"  required="true">
                                        <option value="">--Pilih Kelurahan--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>No. Telphone/HP</label>
                                    <input type="text" name="hp_customer" id="hp_customer" class="form-control" placeholder="Masukkan nomor telpon atau HP" value="<?php echo $no_hp; ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                     <label>Keterangan</label>
                                     <textarea class="form-control" rows="3" id="keterangan" name="keterangan"><?php echo $keterangan;?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            
                        </div>
                    </div>
                </form>
            </div>
          </div>
      </div>
<?php echo loading_proses();?>
<div id="">
    
</div>
</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/appointment.js");?>"></script>
<script type="text/javascript">
    $(document).ready(function () {

        

        $('.qurency').mask('000.000.000.000.000', {reverse: true});
        $('.tahun').mask('0000', {reverse: true});
        $('.meter').mask('000.000.000.000.000', {reverse: true});

        $("#submit-main-button").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");

            $('.qurency').unmask();
            $('.tahun').unmask();

            $(formId).validate({
                highlight: function (element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function (element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                addValid(formId, btnId);

            } else {
                $('#loadpage').addClass("hidden");
                $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
            }
        });

        $("#submit-btn").on('click', function (event) {
            var formId = '#' + $(this).closest('form').attr('id');
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");

            $('.qurency').unmask();
            $('.tahun').unmask();

            $(formId).valid();

            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                storeData(formId, btnId);
                

            } else {

                $('#loadpage').addClass("hidden");

            }
        });

        $('#modal-button-3').on('click',function(){
            __caridata();
        })
        $('#nama_customer')
        .on("keypress",function(e){
            if(e.which===13){
                 $("#modal-button-3").click();
            }
            $('#cari').removeClass('disabled-action');
        })
        .on("keydown",function(){
            $('#cari').removeClass('disabled-action');
        })
        var customer="<?php echo $kd_customer;?>";
        var no_trans="<?php echo $no_trans;?>";
        $('#kd_pos').focus(function(){

        })
        if(no_trans){
            loadData('kd_kabupaten', $('#kd_propinsi').val(),'<?php echo $kd_kabupaten;?>');
            loadData('kd_kecamatan', '<?php echo $kd_kabupaten;?>','<?php echo $kd_kecamatan;?>');
            loadData('kd_desa', '<?php echo $kd_kecamatan;?>','<?php echo $kd_desa;?>');
        }
    })
</script>
