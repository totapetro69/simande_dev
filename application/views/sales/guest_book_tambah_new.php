<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$status_ce = (isBolehAkses('c') || isBolehAkses('e')) ? '' : ' disabled-action';
$defaultDealer = $this->session->userdata("kd_dealer");
$prop = KotaDealer($defaultDealer,null,"KD_PROPINSI");
$dataguest = "";$carabayar = "";$tgl_test = date("d/m/Y");$kd_customer = "";
$nama_customer = "";$no_ktp = "";$kd_dealer = "";$kd_typecustomer = "";$tgl_lahir = "";
$kd_typemotor = "";$kd_warna = "";$gb_source = "";$kd_propinsi = $prop;$kd_kabupaten = "";
$nexfu = "";$kd_kecamatan = "";$kd_desa = "";$ket_lainnya = "";$alamat = "";
$no_hp = "";$cust_sts = "";$email_customer = "";$tgl_visit = date("d/m/Y");$ket_warna = "";
$status_deal = "";$Keterangan = "";$no_guest = "";$test_drive = "";
$kd_sales = "";$jenis_kelamin = "";$kesan_test = "";$kd_pekerjaan = "";$sudah_spk="";
$no_appointment=""; $created_by="";$upline = "";$kd_events="";
$disabled_action="";
if ($this->input->get('n') != '') {
    if ($guestbook) {
        if (($guestbook->totaldata>0)) {
            foreach ($guestbook->message as $key => $value) {
                $kd_customer    = $value->KD_CUSTOMER;
                $nama_customer  = str_replace("\\", "", $value->NAMA_CUSTOMER);
                $kd_dealer      = $value->KD_DEALER;
                $defaultDealer  = $value->KD_DEALER;
                $kd_typecustomer= $value->KD_TYPECUSTOMER;
                $kd_typemotor   = $value->KD_TYPEMOTOR;
                $kd_warna       = $value->KD_WARNA;
                $ket_warna      = $value->KET_WARNA;
                $kd_propinsi    = $value->KD_PROPINSI;
                $kd_kabupaten   = $value->KD_KOTA;
                $kd_kecamatan   = $value->KD_KECAMATAN;
                $kd_desa        = $value->KELURAHAN;
                $alamat         = str_replace("\\", "", $value->ALAMAT_SURAT);
                $no_hp          = $value->NO_HP;
                $email_customer = $value->EMAIL;
                $tgl_visit      = tglfromSql($value->TANGGAL);
                $status_deal    = $value->STATUS;
                $Keterangan     = ($value->KETERANGAN);
                $test_drive     = $value->TEST_DRIVE;
                $kd_sales       = $value->KD_SALES;
                $jenis_kelamin  = $value->JENIS_KELAMIN;
                $no_guest       = $value->GUEST_NO;
                $no_ktp         = $value->NO_KTP;
                $tgl_lahir      = $value->TGL_LAHIR;
                $tgl_test       = tglfromSql($value->TGL_TEST);
                $kesan_test     = $value->KET_TESTDRIVE;
                $ket_lainnya    = $value->KETERANGAN;
                $gb_source      = $value->GB_SOURCE;
                $nexfu          = $value->RENCANA_FU;
                $cust_sts       = $value->CUST_STATUS;
                $carabayar      = $value->CARA_BAYAR;
                $defaultDealer  = $value->KD_DEALER;
                // $nama_pekerjaan = $value->NAMA_PEKERJAAN;
                 $sudah_spk     = $value->HAS_SPK;
                 $kd_pekerjaan  = $value->KD_PEKERJAAN;
                 $created_by    = $value->CREATED_BY;
                 $upline        = $value->UPLINE;
                $kd_events      = $value->KD_EVENT;
            }
        }
    }
    //print_r($guestbook->message);
    $created_by = explode("|", $created_by);
    $no_appointment=(count($created_by)==2)?$created_by[1]:"";
    $tgl_lahir = (substr($tgl_lahir, 0, 4) < 1920) ? "" : tglfromSql($tgl_lahir);
    $alamat = preg_replace('/(\r\n|\r|\n)+/', "\n", $alamat);
    $alamat = preg_replace('/\s+/', ' ', $alamat);
    $nexfu = ($nexfu != '') ? tglfromSql($nexfu) : "";
    $Keterangan = (strlen($Keterangan) > 1) ? "5" : $Keterangan;
    $ket_lainnya = (strlen($ket_lainnya) > 1) ? $ket_lainnya : "";
    $disabled_action = ($kd_customer)?"disabled-action":"";
}
$lock = ($this->input->get('n'))?'disabled-action':'';
$saleEvent="";
if(isset($event)){
    if($event->totaldata >0){
        $saleEvent= $event->message;
    }
}

?>
<script type="text/javascript">
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
                console.log(result);
                $("#list_wm tbody").html("");
                $("table#list_wm tbody").append(result);
                $("#kd_items_wm #cls_wm").html("");
                var edit = "<?php echo $no_guest; ?>";
                if (edit == "") {
                    $("#kd_items_wm").click();
                } else {
                    dropdown_item_wm('<?php echo $kd_warna; ?>', '<?php echo $ket_warna; ?>');
                }

            }

        });
        return false;
    }
</script>

<section class="wrapper">

    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right ">
            <a class="btn btn-default" id="baru" role="buttton"><i class="fa fa-file-o"></i> Baru</a>
            <?php if ($this->input->get("n")) { ?>
                <a role="button" onclick="simpan_guest();" class="btn btn-default submit-btn <?php echo $status_e; ?>"><i class="fa fa-save"></i> Update Guest</a>
                <?php if(strlen($sudah_spk)==0 && $status_deal=='Deal'){
                    ?>
                        <a role="button" id="create_spk" class="btn btn-default" href="<?php echo base_url()."spk/add_spk?g=".$this->input->get('n');?>"><i class='fa fa-cog'></i> Buat SPK</a>
                    <?php
                }
            } else { ?>
                <a role="button" onclick="simpan_guest();" class="btn btn-default submit-btn <?php echo $status_ce; ?>"><i class="fa fa-save"></i> Simpan Guest</a>
            <?php } ?>
            <a role="button" href="<?php echo base_url("customer/guest_book"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List Guest Book</a>
        </div>
    </div>

    <div class="col-lg-12 padding-left-right-10">

        <div class="panel margin-bottom-10">

            <div class="panel-heading" style="height: 45px !important;padding-top: 5px !important;">
                <div class="col-xs-6 col-md-4 col-sm-4">
                    <i class='fa fa-users'></i> Guest Book <?php echo ($no_guest) ? "[ " . $no_guest . " ]" : ""; ?>
                    
                </div>
                <div class="col-xs-6 col-md-7 col-sm-7" style="vertical-align: middle !important; ">
                    <div class="form-group hidden ">
                        <div class="input-group">
                            <input class="form-control<?php echo $disabled_action;?>" id="cari" name="cari" placeholder="find by : Nama or No Hp or no KTP " autocomplete="off" aria-describedby='cr'>
                            <span class='input-group-btn' id='cr'>
                                <button id="modal-button" class='btn btn-info disabled-action' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static" >
                                    <i class='fa fa-search'></i> Cari
                                </button>
                            </span>
                            <!-- onclick="addForm('<?php echo base_url('customer/customer/true?');?>')" -->
                        </div>
                        <!-- <span class="fa fa-search inner-icon"></span> -->
                    </div>
                </div>
            </div>

            <div class="panel-body panel-body-border">
                <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("customer/simpan_guest"); ?>" autocomplete="off">
                    <!-- baris ke 1 -->
                    <div class="row">
                        <!-- nama customer -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label><abbr title="Nama yang muncul di list diambil dari data master customer sesuai dengan Kabupaten/Kota Dealer <?php echo $defaultDealer;?>">Nama Customer</abbr></label>
                                <fieldset id="noappoint">
                                    <input autocomplete="off" type="text" name="nama_customer" id="nama_customer" value="<?php echo $nama_customer; ?>" class="form-control <?php echo ($this->input->get("n"))?'disabled-action':"";?>" placeholder="Masukkan Nama Customer" required >
                                </fieldset>
                                <fieldset id="appoint" class="hidden">
                                    <input autocomplete="off" class="form-control disabled-action" type="text" name="nama_customer_a" id="nama_customer_app" value="<?php echo $nama_customer; ?>">
                                    <input type="hidden" name="no_appointment" id="no_appointment" value='<?php echo $no_appointment;?>'>
                                </fieldset>
                                <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer; ?>">
                                <input type="hidden" id="guest_no" name="guest_no" value="<?php echo $no_guest; ?>">
                            </div>
                        </div>
                        <!-- tambahan load appointment -->
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Load From Appointment</label>
                                <input type="text" id="appdata" name="appdata" class="form-control" role="button" value="<?php echo $nama_customer; ?>" placeholder="Appointment Data">
                            </div>
                        </div>
                        <!-- nama dealer -->
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control" id="kd_dealer" name="kd_dealer">
                                    <option value="0">--Pilih Dealer--</option>
                                    <?php
                                    //var_dump($dealer);
                                    if (isset($dealer)) {
                                        if (($dealer->totaldata>0)) {
                                            foreach ($dealer->message as $key => $value) {
                                                $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                                //$aktif = ($kd_dealer == $value->KD_DEALER) ? "selected" :$aktif;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?> 
                                </select>
                            </div>

                        </div>

                        <!-- tanggal kedatangan  -->
                        <div class="col-xs-6 col-sm-2 col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="date">Tgl Kedatangan</label>
                                <div class="input-group input-append date" id="date">
                                    <input class="form-control" id="tgl_kunjungan" name="tgl_kunjungan" placeholder="DD/MM/YYYY" value="<?php echo $tgl_visit; ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- baris ke 2 -->
                    <div class="row <?php echo $lock;?>">

                        <!-- nomor ktp -->
                        <div class="col-xs-6 col-sm-4 col-md-4">

                            <div class="form-group">
                                <label>No. KTP</label>
                                <input type="text" autocomplete="false" class="form-control number" id="no_ktp"  name="no_ktp" placeholder="Masukan nomor ktp" value="<?php echo $no_ktp; ?>" maxlength="16" minlength="16">
                            </div>
                        </div>

                        <!-- nomor telephon -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>No. Telphone/<abbr title="Validasi akan dilakukan setelah input No HP dan akan di check kecocokan antaran Nama Dan No HP">HP</abbr></label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control number" autocomplete="off" placeholder="Masukkan nomor telpon atau HP" required="required" value="<?php echo $no_hp; ?>">
                            </div>
                        </div>

                        <!-- tgl lahir -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tgl. Lahir</label>
                                <div class="input-group input-append date" id="datex">
                                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?php echo $tgl_lahir; ?>" placeholder="dd/mm/yyyy" data-mask="00/00/0000" />
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- baris ke 3 -->
                    <div class="row <?php echo $lock;?>">    

                        <!-- alamat -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Alamat <span id="l_alamat"></span></label>
                                <textarea type="text" rows="4" autocomplete="off"  name="alamat" id="alamat" class="form-control" placeholder="Masukkan Nama Alamat" required="required"><?php echo ucwords($alamat); ?></textarea>
                            </div>
                        </div>

                        <!-- propinsi -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Propinsi</label>
                                <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi" required="true">
                                    <option value="">--Pilih Propinsi--</option>
                                    <?php
                                    if ($propinsi) {
                                        if (is_array($propinsi->message)) {
                                            foreach ($propinsi->message as $key => $value) {
                                                $terpilih = ($kd_propinsi == $value->KD_PROPINSI) ? "selected" : "";
                                                //$terpilih = ($this->session->userdata("kd_wilayah") == $value->KD_WILAYAH && $terpilih == "") ? "selected" : $terpilih;
                                                echo "<option value='" . $value->KD_PROPINSI . "' " . $terpilih . ">" . $value->NAMA_PROPINSI . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- kabupaten -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Kabupaten <span id="l_kabupaten"></span></label>
                                <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten" required="true">
                                    <option value="">--Pilih Kabupaten--</option>
                                </select>
                            </div>
                        </div>

                        <!-- kecamatan -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Kecamatan <span id="l_kecamatan"></span></label>
                                <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan" required="true">
                                    <option value="">--Pilih Kecamatan--</option>
                                </select>
                            </div>
                        </div>

                        <!-- keluarahan -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Kelurahan <span id="l_desa"></span></label>
                                <select class="form-control" id="kd_desa" name="kd_desa" title="desa" required="true">
                                    <option value="">--Pilih Kelurahan--</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- baris ke 4 -->
                    <div class="row">

                        <!-- jenis kelamin -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" id="kd_gender" name="kd_gender" required="true">
                                    <option value="">--Pilih Jenis Kelamin--</option>
                                    <?php
                                    if ($gender) {
                                        if (is_array($gender->message)) {
                                            foreach ($gender->message as $key => $value) {
                                                $select = ($jenis_kelamin==$value->KD_GENDER)?'selected':"";
                                                echo "<option value='" . $value->KD_GENDER . "' ".$select.">" . $value->NAMA_GENDER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- email -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email_customer" id="email_customer" class="form-control" placeholder="Masukkan Email" value="<?php echo $email_customer;?>">
                            </div>
                        </div>

                        <!-- nama sales -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Nama Sales</label>
                                <select class="form-control" name="kd_sales" id="kd_sales" required="true">
                                    <option value="">--Pilih Nama Sales--</option>
                                    <?php
                                    if ($sales) {
                                        if (is_array($sales->message)) {
                                            foreach ($sales->message as $key => $value) {
                                                $select =($kd_sales==$value->KD_SALES)?'selected':"";
                                                echo "<option value='" . $value->KD_SALES . "' ".$select.">" . $value->NAMA_SALES . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- baris ke 5 -->
                    <div class="row">

                        <!-- type motoe -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Type Motor</label>
                                <?php echo DropDownMotor(true, $kd_typemotor); ?>
                            </div>
                        </div>

                        <!-- warna motor -->
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Warna Motor <?php echo $kd_warna; ?></label>
                                <?php echo DropDownWarnaMotor($kd_warna); ?>
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Pekerjaan</label>
                                <select class="form-control" name="kd_pekerjaan" id="kd_pekerjaan" required="true">
                                    <option value=''>--Pilih Nama Pekerjaan--</option>
                                    <?php
                                    if ($pekerjaan) {
                                        if (is_array($pekerjaan->message)) {
                                            foreach ($pekerjaan->message as $key => $value) {
                                                $select=($kd_pekerjaan==$value->KD_PEKERJAAN)?"selected":"";
                                                echo "<option value='" . $value->KD_PEKERJAAN . "' ".$select.">" . $value->NAMA_PEKERJAAN . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- baris ke 6 -->
                    <div class="row">

                        <!-- Source / asal customer dapat -->
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Source</label>
                                <select class="form-control" name="gb_source" id="gb_source" required="true">
                                    <option value="">--Pilih Source--</option>
                                    <option value="Walk In" <?php echo ($gb_source == "Walk In") ? "selected" : ""; ?>>Walk In</option>
                                    <option value="Sales Event" <?php echo ($gb_source == "Sales Event") ? "selected" : ""; ?>>Sales Event</option>
                                    <option value="Gathering" <?php echo ($gb_source == "Gathering") ? "selected" : ""; ?>>Gathering</option>
                                    <option value="Exhibition" <?php echo ($gb_source == "Exhibition") ? "selected" : ""; ?>>Exhibition</option>
                                    <option value="Canvasing" <?php echo ($gb_source == "Canvasing") ? "selected" : ""; ?>>Canvasing</option>
                                    <option value="Roadshow" <?php echo ($gb_source == "Roadshow") ? "selected" : ""; ?>>Roadshow</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group <?php echo ($no_guest)?'':'disabled-action';?>" id="kd_eventing">
                                <label>Kode Event <span id="ldg"></span></label>
                                <input class="form-control" name="kd_event" id="kd_event" placeholder="Pilih event" value="<?php echo $kd_events;?>">
                                    
                            </div>
                        </div>
                        <!-- type customer -->
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Type Customer</label>
                                <select class="form-control" name="kd_typecustomer" id="kd_typecustomer">
                                    <option value=''>--Pilih Type Customer--</option>
                                    <?php
                                    if ($typecustomer) {
                                        if (is_array($typecustomer->message)) {
                                            foreach ($typecustomer->message as $key => $value) {
                                                $terpilih = ($kd_typecustomer == $value->KD_TYPECUSTOMER) ? "selected" : "";
                                                echo "<option value='" . $value->KD_TYPECUSTOMER . "' " . $terpilih . ">" . $value->NAMA_TYPECUSTOMER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- rencana pembayaran -->
                        <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>Rencana Pembayaran</label>
                                <select id="carabayar" name="carabayar" class="form-control" required="true">
                                    <option value="">--Pilih Cara bayar</option>
                                    <option value="CASH"<?php echo ($carabayar == "CASH") ? " selected" : ""; ?>>CASH</option>
                                    <option value="CREDIT"<?php echo ($carabayar == "CREDIT") ? " selected" : ""; ?>>CREDIT</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <!-- status deal -->
                            <!-- <div class="col-xs-6 col-sm-6 col-md-6"> -->
                            <div class="form-group">
                                <label>Status Deal <?php echo ($status_deal); ?></label>
                                <select class="form-control" id="kd_status" name="kd_status" required>
                                    <option value="">--Pilih Status--</option>
                                    <option value="Deal"<?php echo ($status_deal == "Deal") ? " selected" : ""; ?>>Deal</option>
                                    <option value="Deal Indent"<?php echo ($status_deal == "Deal Indent") ? " selected" : ""; ?>>Deal Indent</option>
                                    <option value="Pending"<?php echo ($status_deal == "Pending") ? " selected" : ""; ?>>Pending</option>
                                    <option value="Not Deal"<?php echo ($status_deal == "Not Deal") ? " selected" : ""; ?>>Not Deal</option>
                                </select>
                            </div>

                            <!-- </div> -->
                            <!-- status customer class="hidden" -->
                            <div id="sts_deal" class="<?php echo ($no_guest && $status_deal != 'Deal') ? "" : "hidden"; ?>" >
                                <div class="panel margin-botom-10">
                                    <div class="panel-heading"><i class="fa fa-list"></i> Follow UP</div>
                                    <div class="panel-body">
                                        <div class="col-xs-6 col-sm-6 col-md-6 <?php echo ($status_deal == "Pending") ? "" : " hidden"; ?>">
                                            <div class="form-group">
                                                <label>FU Ke</label>
                                                <select class="form-control" id="fu_ke" name="fu_ke">
                                                    <option value="1">FU Ke 1</option>
                                                    <option value="2">FU Ke 2</option>
                                                    <option value="3">FU Ke 3</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-6 col-md-6<?php echo ($status_deal == "Pending") ? "" : " hidden"; ?>">
                                            <div class="form-group">
                                                <label class="control-label" for="date">Rencana FU</label>
                                                <div class="input-group input-append date" id="date_fu">
                                                    <input class="form-control" id="rencana_fu1" name="rencana_fu1" placeholder="DD/MM/YYYY" value="<?php echo $nexfu; ?>" type="text"/>
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="hidden">
                                            <!-- methode fu -->
                                            <div class="col-xs-6 col-sm-6 col-md-6 hidden">
                                                <div class="form-group">
                                                    <label>Metode FU</label>
                                                    <select class="form-control" id="metode_fu" name="metode_fu">
                                                        <option>--Pilih Metode FU</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- tanggal fu -->
                                            <div class="col-xs-6 col-sm-6 col-md-6 hidden">
                                                <div class="form-group">
                                                    <label class="control-label" for="date_fu1">Tanggal FU</label>
                                                    <div class="input-group input-append date" id="date">
                                                        <input class="form-control" id="tgl_fu" name="tgl_fu" placeholder="DD/MM/YYYY" value="" type="text"/>
                                                        <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- status fu -->
                                            <div class="col-xs-6 col-sm-6 col-md-6 hidden">
                                                <div class="form-group">
                                                    <label>Status FU</label>
                                                    <select class="form-control" id="status_fu" name="status_fu">
                                                        <option>--Pilih Status FU--</option>
                                                        <option value="Terhubung">Terhubung</option>
                                                        <option value="Tidak Terhubung">Tidak Terhubung</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- hasil metode -->
                                            <div class="col-xs-6 col-sm-6 col-md-6 hidden">
                                                <div class="form-group">
                                                    <label>Hasil Metode</label>
                                                    <select class="form-control" id="hasil_metode" name="hasil_metode">
                                                        <option value="">--Pilih Hasil Metode--</option>
                                                        <option value="Deal">Deal</option>
                                                        <option value="Deale Indent">Deal Indent</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Not Deal">Not Deal</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- tidak terhubung -->
                                            <div id="tdkterhubung" class="hidden">
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Status Tidak terhubung</label>
                                                        <select id="sts_tdkterhubung" name="sts_tdkterhubung" class='form-control'>
                                                            <option value="Failed">Failed</option>
                                                            <option value="Unreachable">Unreachable</option>
                                                            <option value="Rejected">Rejected</option>
                                                            <option value="Workload">Workload</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- klasifikasi -->
                                                <div class="col-xs-6 col-sm-6 col-md-6 hidden">
                                                    <div class="form-group">
                                                        <label>Klasifikasi</label>
                                                        <select id="klasifikasi" name="klasifikasi" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <!-- status customer -->
                                        <div class="col-xs-6 col-sm-6 col-md-6<?php echo ($status_deal == "Pending") ? "" : " hidden"; ?>">
                                            <div class="form-group">
                                                <label>Status Customer</label>
                                                <select class="form-control" id="statuse" name="statuse">
                                                    <option value="">--Pilih Status--</option>
                                                    <option value="Hot"<?php echo ($cust_sts == "Hot") ? " selected" : ""; ?>>Hot</option>
                                                    <option value="Low"<?php echo ($cust_sts == "Low") ? " selected" : ""; ?>>Low</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- keterangan -->
                                        <div class="col-xs-6 col-sm-12 col-md-12 <?php echo ($status_deal == "Not Deal") ? "" : "hidden"; ?>" id="nodeal">
                                            <div class="form-group">
                                                <label>Keterangan Not Deal <?php echo ($Keterangan); ?></label>
                                                <select id="ket_notdeal" name="ket_notdeal" class="form-control">
                                                    <option value="">--Pilih Keterangan No Deal</option>
                                                    <option value="1"<?php echo ($Keterangan == "1") ? " selected" : ""; ?>>Sudah Beli di Dealer Lain</option>
                                                    <option value="2"<?php echo ($Keterangan == "2") ? " selected" : ""; ?>>Sudah Beli di Kompetitor</option>
                                                    <option value="3"<?php echo ($Keterangan == "3") ? " selected" : ""; ?>>Tidak Ada Stock Dealer</option>
                                                    <option value="4"<?php echo ($Keterangan == "4") ? " selected" : ""; ?>>Ditolak oleh Leasing</option>
                                                    <option value="4"<?php echo ($Keterangan == "6") ? " selected" : ""; ?>>Belum Ada Minat</option>
                                                    <option value="4"<?php echo ($Keterangan == "7") ? " selected" : ""; ?>>Sudah Ada Minat</option>
                                                    <option value="5"<?php echo ($Keterangan == "5") ? " selected" : ""; ?>>Lainnya...</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-12 col-md-12 <?php echo ($status_deal == "Not Deal" && ($Keterangan == "5")) ? "" : "hidden"; ?>" id="nodeal_2">
                                            <div class="form-group">
                                                <label class="control-label" for="date">Keterangan Lainya</label>
                                                <textarea class="form-control" id="ket_notdeal_5" name="ket_notdeal_5" placeholder="Jelaskan Alasan pilih lainnya"><?php echo $ket_lainnya; ?></textarea>
                                            </div>
                                            <?php echo $ket_lainnya; ?>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <!-- test drive -->
                            <div class="form-group">
                                <label>Test Drive</label>
                                <select class="form-control" name="test_drive" id="test_drive">
                                    <option value="Tidak" <?php echo ($test_drive == "Tidak") ? "selected" : ""; ?>>Tidak</option>
                                    <option value="Ya" <?php echo ($test_drive == "Ya") ? "selected" : ""; ?>>Ya</option>
                                </select>
                            </div>
                             <div id="ridingpanel" class="<?php echo ($test_drive == "Ya") ? "" : "hidden"; ?>">
                                <div class="panel margin-botom-10">
                                    <div class="panel-heading"><i class="fa fa-list"></i> Data Riding Test</div>
                                    <div class="panel-body">
                                        <div class="col-xs-6 col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Test</label>
                                                <div class="input-group input-append date" id="date">
                                                    <input type="text" class="form-control" id="tgl_test" name="tgl_test" value="<?php echo $tgl_test; ?>" placeholder="dd/mm/yyyy" />
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Kesan Experience</label>
                                                <textarea class="form-control" id="kesan_test" name="kesan_test"><?php echo $kesan_test; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Refferal</label>
                                <input type="text" class="form-control" id="upline" name="upline" value='<?php echo $upline;?>'>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
    <?php //echo KotaDealer($defaultDealer,null,"KD_PROPINSI");?>
    <?php echo loading_proses(); ?>
</section>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/guestbook.js"); ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        listCustomer();
             saEvent();
       $("#gb_source").on('change',function(){
        console.log($(this).val());
            if($(this).val()=='Sales Event'){
                $('#kd_eventing').removeClass("disabled-action");
                $('#kd_event').attr('required','required');
            }else{
                $('#kd_eventing').addClass("disabled-action");
                $('#kd_event').removeAttr('required');
            }
       })
       $('.number').keypress(validateNumber);
    });
    function saEvent(){
         var datax=[];
        $('#ldg').html("<i class='fa fa-spinner fa-spin'></i>");
        $.getJSON("<?php echo base_url("customer/add_guest_book/1");?>",function(event){
            console.log(event);
            if(event.totaldata>0){
                $.each(event.message,function(e,d){
                    datax.push({
                        'KodeEvent' : d.ID_EVENT,
                        'JenisEvent': d.JENIS_EVENT,
                        'NamaEvent': d.NAMA_EVENT,
                        'Lokasi': d.LOC_EVENT
                    })
                })

                $('#kd_event').inputpicker({
                    data : datax,
                    fields :["KodeEvent","JenisEvent","NamaEvent","Lokasi"],
                    fieldValue :"KodeEvent",
                    fieldText : "KodeEvent",
                    headShow :true,
                    filterOpen:true
                })
                $('#ldg').html('');
            }else{
                $('#ldg').html('');
            }
        })
    }
    function listCustomer(){
            var datax=[];
            $.getJSON("<?php echo base_url("customer/customer/false/true");?>",
                {'kd_dealer':"<?php echo $this->session->userdata("kd_dealer");?>"},
                function(result){
                    if(result.totaldata >0){
                        $.each(result.message,function(e,d){
                            datax.push({
                                'KODE_CUSTOMER':d.KD_CUSTOMER,
                                'NAMA_CUSTOMER':d.NAMA_CUSTOMER
                            })
                        })
                    }
                    //console.log(datax);
                    $('#upline').inputpicker({
                        data :datax,
                        fields:["KODE_CUSTOMER","NAMA_CUSTOMER"],
                        fieldValue:"KODE_CUSTOMER",
                        fieldText:"NAMA_CUSTOMER",
                        headShow:true,
                        filterOpen: true
                    })
                }
            )
        }
        
    function validateNumber(event) {
        var key = window.event ? event.keyCode : event.which;
        if (event.keyCode === 8 || event.keyCode === 46) {
            return false;
        } else if ( key < 48 || key > 57 ) {
            return false;
        } else {
            return true;
        }
    }
</script>