<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  if($list){
    if($list->totaldata>0){
        foreach ($list->message as $key => $value) {
           $kd_customer     = $value->KD_CUSTOMER;
           $nama_customer   = $value->NAMA_CUSTOMER;
           $nama_lama       = $value->NAMA_CUSTOMER;
           $alamat          = $value->ALAMAT_SURAT;
           $no_ktp          = $value->NO_KTP;
           $kd_propinsi     = $value->KD_PROPINSI;
           $kd_kabupaten    = $value->KD_KOTA;
           $kd_kecamatan    = $value->KD_KECAMATAN;
           $kd_desa         = $value->KELURAHAN;
           $tgl_lahir       = tglFromSql($value->TGL_LAHIR);
           $no_npwp         = $value->NO_NPWP;
           $kd_agama        = $value->KD_AGAMA;
           $kd_pekerjaan    = $value->KD_PEKERJAAN;
           $no_hp           = $value->NO_HP;
           $no_hplama       = $value->NO_HP;
           $kode_possurat   = $value->KODE_POS;
           $pengeluaran     = $value->PENGELUARAN;
           $status_nohp     = $value->STATUS_NOHP;
           $status_rumah    = $value->STATUS_RUMAH;
           $nama_metode     = $value->STATUS_DIHUBUNGI;
           $akun_fb         = $value->AKUN_FB;
           $akun_twitter    = $value->AKUN_TWITTER;
           $akun_instagram  = $value->AKUN_INSTAGRAM;
           $hobi            = $value->HOBI;
           $karakteristik   = $value->KARAKTERISTIK_KONSUMEN;
           $akun_youtube    = $value->AKUN_YOUTUBE;
           $nama_penanggungjawab=$value->NAMA_PENANGGUNGJAWAB;
           $email           = $value->EMAIL;
           $kd_pendidikan   = $value->KD_PENDIDIKAN;
           $no_telepon      = $value->NO_TELEPON;
           $kd_gender       = $value->JENIS_KELAMIN;
           $kd_sales        = $value->ID_REFFERAL;
           $upline          = $value->UPLINE;
        }
    }
  }
  /*var_dump($list);
  exit();*/
?>

<section class="wrapper">
    <form id="addFormx" action="<?php echo base_url('customer/update_customer/'.$kd_customer); ?>" method="post">
        <div class="breadcrumb margin-bottom-10">
            <?php echo breadcrumb();?>
            <div class="bar-nav pull-right">
                <a class="btn btn-default" href="<?php echo base_url('customer/add_customer');?>"><i class="fa fa-file-o fa-fw"></i> Tambah Customer </a>
                <a id="submit-btn" type="button" class="btn btn-default submit-btn $status_c"><i class="fa fa-save fa-fw"></i> Update</a>
                <a href="<?php echo base_url('customer/customer');?>" class="btn btn-default $status_v"><i class="fa fa-list"></i> List Customer</a>
            </div>
        </div>
        <div class="col-xs-12 padding-left-right-10">
            <div class="row">
                
                    <div class="col-sm-6">
                        <div class="panel margin-bottom-10">
                            <div class="panel-heading panel-custom">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <h4 class="panel-title pull-left" style="padding-top: 10px;">
                                              <i class="fa fa-list fa-fw"></i> Data Customer
                                        </h4>
                                    </div>
                                    <div class="col-sm-8">
                                        <form class="form-inline">
                                            <div class="input-group">
                                                <span class="input-group-addon" id="addon">Kode Customer</span>
                                                <input type="text" name="kd_customer" id="kd_customer" class="form-control" placeholder="AUTO GENERATE" readonly aria-describedby="addon" value="<?php echo $kd_customer;?>">
                                            </div>
                                        </form>
                                    </div>
                                    <!-- <div class="col-sm-5">
                                        <span class="tools pull-right">
                                            <a class="fa fa-chevron-down" href="javascript:;"></a>
                                        </span>
                                    </div> -->
                                </div>
                            </div>
                            <div class="panel-body panel-body-border">
                                <!-- <form id="formCS" action="<?php echo base_url('customer/add_customer_simpan'); ?>" method="post"> -->
                                    <div class="row">
                                        <div class="col-xs-6 col-sm-6 col-md-6">
                                            <!-- nama customer -->
                                            <div class="form-group">
                                                <label>Nama Customer</label>
                                                <input type="text" name="nama_customer" id="nama_customer" class="form-control" placeholder="Masukkan Nama Customer" required="required" value="<?php echo $nama_customer;?>">
                                                <input type="hidden" name="nama_customerlama" value="<?php echo $nama_lama;?>">
                                                <input type="hidden" name="no_hplama" value="<?php echo $no_hplama;?>">
                                            </div>
                                            <!-- tgl npwp -->
                                            <div class="form-group hidden">
                                                <label>Tgl. Pembuatan NPWP</label>
                                                <div class="input-group input-append date" id="date">
                                                    <input type="text" class="form-control" id="tgl_pembuatan_npwp" name="tgl_pembuatan_npwp" value="" placeholder="dd/mm/yyyy" />
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <!-- alamat -->
                                            <div class="form-group">
                                                <label>Alamat Surat</label>
                                                <textarea name="alamat_surat" id="alamat_surat" rows="3" class="form-control" placeholder="Masukkan Alamat Surat" ><?php echo $alamat;?></textarea>
                                            </div>
                                            <!-- propinsi -->
                                            <div class="form-group">
                                                <label>Propinsi</label>
                                                <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                                                    <option value="0">--Pilih Propinsi--</option>
                                                    <?php
                                                    if ($propinsi) {
                                                        if (is_array($propinsi->message)) {
                                                            foreach ($propinsi->message as $key => $value) {
                                                                $select=($kd_propinsi==$value->KD_PROPINSI)?"selected":"";
                                                                echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">" . $value->NAMA_PROPINSI . "</option>";
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
                                                    <option value="">--Pilih Kabupaten--</option>
                                                </select>
                                            </div>
                                            <!-- kecamatan -->
                                            <div class="form-group">
                                                <label>Kecamatan <span id="l_kecamatan"></span></label>
                                                <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                                                    <option value="">--Pilih Kecamatan--</option>
                                                </select>
                                            </div>
                                            <!-- kelurahan -->
                                            <div class="form-group">
                                                <label>Kelurahan <span id="l_desa"></span></label>
                                                <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                                                    <option value="">--Pilih Desa/Kelurahan--</option>
                                                </select>
                                            </div>
                                            <!-- kodepos -->
                                            <div class="form-group">
                                                <label>Kode Pos</label>
                                                <input type="text" name="kode_possurat" id="kode_possurat" class="form-control input-number" placeholder="Masukkan Kode Pos" value="<?php echo $kode_possurat;?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-6 col-md-6">
                                            <!-- no ktp -->
                                            <div class="form-group">
                                                <label>No. KTP</label>
                                                <input type="text" name="no_ktp" id="no_ktp" class="form-control input-number" maxlength="16" placeholder="Masukkan No. KTP" required="required" value="<?php echo $no_ktp;?>">
                                            </div>
                                            <!-- tgl lahir -->
                                            <div class="form-group">
                                                <label>Tgl. Lahir</label>
                                                <div class="input-group input-append date" id="datex">
                                                    <input type="text" class="form-control" id="tgl_lahir" name="tgl_lahir" placeholder="dd/mm/yyyy" required="required" value="<?php echo $tgl_lahir;?>" />
                                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <!-- jeniskelamin -->
                                            <div class="form-group">
                                                <label>Jenis Kelamin</label>
                                                <select class="form-control" id="kd_gender" name="kd_gender">
                                                    <option value="" >- Pilih Jenis Kelamin -</option>
                                                    <?php
                                                    if ($genders):
                                                        foreach ($genders->message as $key => $gender) :
                                                            $select=($kd_gender==$gender->KD_GENDER)?"selected":"";
                                                            ?>
                                                            <option value="<?php echo $gender->KD_GENDER; ?>" <?php echo $select;?>><?php echo $gender->NAMA_GENDER; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- nomor npwp -->
                                            <div class="form-group">
                                                <label>No. NPWP</label>
                                                <input type="text" name="no_npwp" id="no_npwp" class="form-control input-number" placeholder="Masukkan No. NPWP" value="<?php echo $no_npwp;?>">
                                            </div>
                                            
                                            <!-- agama -->
                                            <div class="form-group">
                                                <label>Agama</label>
                                                <select class="form-control" id="kd_agama" name="kd_agama" >
                                                    <option value="" >- Pilih Agama -</option>
                                                    <?php
                                                    if ($agamas):
                                                        foreach ($agamas->message as $key => $agama) :
                                                            $select=($kd_agama==$agama->KD_AGAMA)?"selected":"";
                                                            ?>
                                                            <option value="<?php echo $agama->KD_AGAMA; ?>" <?php echo $select;?> ><?php echo $agama->NAMA_AGAMA; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- pekerjaan -->
                                            <div class="form-group">
                                                <label>Pekerjaan</label>
                                                <select class="form-control" id="kd_pekerjaan" name="kd_pekerjaan" >
                                                    <option value="" >- Pilih Pekerjaan -</option>
                                                    <?php
                                                    if ($pekerjaans):
                                                        foreach ($pekerjaans->message as $key => $pekerjaan) :
                                                            $select=($kd_pekerjaan==$pekerjaan->KD_PEKERJAAN)?"selected":"";
                                                            ?>
                                                            <option value="<?php echo $pekerjaan->KD_PEKERJAAN; ?>" <?php echo $select;?> ><?php echo $pekerjaan->NAMA_PEKERJAAN; ?></option>
                                                            <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- nomor hp -->
                                            <div class="form-group">
                                                <label>No. HP</label>
                                                <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan No. HP" value="<?php echo $no_hp;?>" >
                                            </div>
                                        </div>
                                    </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel margin-bottom-10">
                            <div class="panel-heading panel-custom">
                                <h4 class="panel-title" style="padding-top: 10px">
                                    <i class="fa fa-cog"></i> Data Detail
                                </h4>
                            </div>
                            <div class="panel-body panel-body-border">
                                <!-- <form class="formCSD" method="post" action=""> -->
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <!-- pengeluaran -->
                                        <div class="form-group">
                                            <label>Pengeluaran</label>
                                            <select name="pengeluaran" id="pengeluaran" class="form-control">
                                                <option value="" <?php echo ($pengeluaran=="")?"selected":"";?>> -- Pilih Pengeluaran Bulanan --</option>
                                                <option value="1" <?php echo ($pengeluaran=="1")?"selected":"";?>><= 900.000</option>
                                                <option value="2" <?php echo ($pengeluaran=="2")?"selected":"";?>>Rp. 900.001 s/d Rp. 1.250.000</option>
                                                <option value="3" <?php echo ($pengeluaran=="3")?"selected":"";?>>Rp. 1.250.001 s/d Rp. 1.759.000</option>
                                                <option value="4" <?php echo ($pengeluaran=="4")?"selected":"";?>>Rp. 1.759.001 s/d Rp. 2.500.000</option>
                                                <option value="5" <?php echo ($pengeluaran=="5")?"selected":"";?>>Rp. 2.500.001 s/d Rp. 4.000.000</option>
                                                <option value="6" <?php echo ($pengeluaran=="6")?"selected":"";?>>Rp. 4.000.001 s/d Rp. 6.000.000</option>
                                                <option value="7" <?php echo ($pengeluaran=="7")?"selected":"";?>>> 6.000.000</option>
                                            </select>
                                        </div>
                                        <!-- nomor telpon rumah -->
                                        <div class="form-group">
                                            <label>No. Telpon rumah</label>
                                            <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="Masukkan No. Telp" value="<?php echo $no_telepon;?>" >
                                        </div>
                                        <!-- status dihubungi -->
                                        <div class="form-group">
                                            <label>Status Di Hubungi</label>
                                            <select class="form-control" id="nama_metode" name="nama_metode" >
                                                <option value="" >- Pilih Status Di Hubungi -</option>
                                                <?php
                                                if ($status) {
                                                    if (is_array($status->message)) {
                                                        foreach ($status->message as $key => $value) {
                                                            $select=(strtolower($nama_metode)==strtolower($value->NAMA_METODE))?"selected":"";
                                                            echo "<option value='" . $value->NAMA_METODE . "' ".$select.">" . $value->NAMA_METODE . "</option>";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <!-- status no hp -->
                                        <div class="form-group">
                                            <label>Status No. HP</label>
                                            <select name="status_nohp" id="status_nohp" class="form-control">
                                                <option value="" <?php echo ($status_nohp=="")?"selected":"";?>>-- Pilih Status No. HP --</option>
                                                <option value="Aktif" <?php echo ($status_nohp=="Aktif")?"selected":"";?>>Aktif</option>
                                                <option value="Tidak" <?php echo ($status_nohp=="Tidak")?"selected":"";?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <!-- facebook -->
                                        <div class="form-group">
                                            <label>Facebook</label>
                                            <input type="text" name="akun_fb" id="akun_fb" class="form-control" placeholder="Masukkan Facebook"  value="<?php echo $akun_fb;?>">
                                        </div>
                                        <!-- twittefr -->
                                        <div class="form-group">
                                            <label>Twitter</label>
                                            <input type="text" name="akun_twitter" id="akun_twitter" class="form-control" placeholder="Masukkan Twitter" value="<?php echo $akun_twitter;?>" >
                                        </div>
                                        <!-- hobi -->
                                        <div class="form-group">
                                            <label>Hobi</label>
                                            <input type="text" name="hobi" id="hobi" class="form-control" placeholder="Masukkan Hobi"
                                            value="<?php echo $hobi;?>" >
                                        </div>
                                        <!-- karakteristik -->
                                        <div class="form-group">
                                            <label>Karakteristik Konsumen</label>
                                            <input type="text" name="karakteristik_konsumen" id="karakteristik_konsumen" class="form-control" placeholder="Masukkan Karakteristik Konsumen" value="<?php echo $karakteristik;?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">

                                        <!-- pendidikan -->
                                        <div class="form-group">
                                            <label>Pendidikan</label>
                                            <select name="kd_pendidikan" id="kd_pendidikan" class="form-control">
                                                <option value="" <?php echo ($kd_pendidikan=="")?"selected":"";?>>-- Pilih Pendidikan Terakhir --</option>
                                                <option value="SD" <?php echo ($kd_pendidikan=="SD")?"selected":"";?>>SD</option>
                                                <option value="SLTP" <?php echo ($kd_pendidikan=="SLTP")?"selected":"";?>>SLTP</option>
                                                <option value="SLTA" <?php echo ($kd_pendidikan=="SLTA")?"selected":"";?>>SLTA</option>
                                                <option value="DIPLOMA" <?php echo ($kd_pendidikan=="DIPLOMA")?"selected":"";?>>DIPLOMA</option>
                                                <option value="S1" <?php echo ($kd_pendidikan=="S1")?"selected":"";?>>STRATA 1</option>
                                                <option value="S2" <?php echo ($kd_pendidikan=="S2")?"selected":"";?>>STRATA 2</option>
                                                <option value="S3" <?php echo ($kd_pendidikan=="S3")?"selected":"";?>>STRATA 3</option>
                                            </select>
                                        </div>
                                        <!-- penanggung jawab -->
                                        <div class="form-group">
                                            <label>Nama Penanggung Jawab</label>
                                            <input type="text" name="nama_penanggungjawab" id="nama_penanggungjawab" class="form-control" placeholder="Masukkan Nama Penanggung Jawab" value="<?php echo $nama_penanggungjawab;?>" >
                                        </div>
                                        <!-- status rumah -->
                                        <div class="form-group">
                                            <label>Status Rumah</label>
                                            <select name="status_rumah" id="status_rumah" class="form-control">
                                                <option value="">-- Pilih Status Rumah --</option>
                                                <option value="Rumah Sendiri" <?php echo ($status_rumah=="Rumah Sendiri")?"selected":"";?>>Rumah Sendiri</option>
                                                <option value="Rumah Orang Tua / Keluarga" <?php echo ($status_rumah=="Rumah Orang Tua / Keluarga")?"selected":"";?>>Rumah Orang Tua / Keluarga</option>
                                                <option value="Rumah Sewa" <?php echo ($status_rumah=="Rumah Sewa")?"selected":"";?>>Rumah Sewa</option>
                                            </select>
                                        </div>
                                        <!-- email -->
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="<?php echo $email;?>" placeholder="Masukkan Email"  >
                                        </div>
                                        <!-- instagram -->
                                        <div class="form-group">
                                            <label>Instagram</label>
                                            <input type="text" name="akun_instagram" id="akun_instagram" value="<?php echo $akun_instagram;?>" class="form-control" placeholder="Masukkan Instagram" >
                                        </div>
                                        <!-- yutube -->
                                        <div class="form-group">
                                            <label>Youtube</label>
                                            <input type="text" name="akun_youtube" id="akun_youtube" value="<?php echo $akun_youtube;?>" class="form-control" placeholder="Masukkan Youtube" >
                                        </div>
                                        <!-- referal -->
                                        <div class="form-group">
                                            <label>ID Refferal</label>
                                            <select class="form-control hidden" name="kd_sales" id="kd_sales">
                                                <option value='0'>--Pilih Nama Sales--</option>
                                                <?php
                                                if ($sales) {
                                                    if (is_array($sales->message)) {
                                                        foreach ($sales->message as $key => $value) {
                                                            $select=($kd_sales==$value->KD_SALES)?"selected":"";
                                                            echo "<option value='" . $value->KD_SALES . "' ".$select.">" . $value->NAMA_SALES . "</option>";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <input type="text" class="form-control" id="upline" name="upline" value='<?php echo $upline;?>'>
                                        </div>
                                    </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                <!-- </form> -->
            </div>
        </div>
    </form>
    <?php echo loading_proses();?>
</section>

   <script type="text/javascript">
        $(document).ready(function(){

            var date = new Date();
            date.setDate(date.getDate());

            $('#datex').datepicker({
                format: 'dd/mm/yyyy',
                daysOfWeekHighlighted: "0",
                autoclose: true,
                todayHighlight:true,
                onClose:function(){
                    checkumur();
                }
            });
            $('#kd_propinsi').change();
            loadData('kd_kabupaten',"<?php echo $kd_propinsi;?>","<?php echo $kd_kabupaten;?>");
            loadData('kd_kecamatan',"<?php echo $kd_kabupaten;?>", "<?php echo $kd_kecamatan;?>");
            loadData('kd_desa', "<?php echo $kd_kecamatan;?>","<?php echo $kd_desa;?>")
            $('#tgl_lahir').change(function(e){
                checkumur(e);
            })
            /*pilihan propinsi*/
            $('#kd_propinsi').on('change', function () {
                loadData('kd_kabupaten', $('#kd_propinsi').val(), "<?php echo $kd_kabupaten;?>")
            })
            $('#kd_kabupaten').on('change', function () {
                loadData('kd_kecamatan', $(this).val(), "<?php echo $kd_kecamatan;?>")
            })
            $('#kd_kecamatan').on('change', function () {
                loadData('kd_desa', $(this).val(), "<?php echo $kd_desa;?>")
            })
            listCustomer();
            $("#submit-btn").on('click',function(event){
                var formId = '#'+$(this).closest('form').attr('id');
                var btnId = '#'+this.id;
                $('#loadpage').removeClass("hidden");

                $(formId).validate({
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function(error, element) {
                        if(element.parent('.input-group').length) {
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

                }else{
                    $('#loadpage').addClass("hidden");
                    $(window).scrollTop($('.form-group').hasClass('has-error').offset().top);
                }
            });
        })
        jQuery.fn.LoadSibling=function(id, select){
            $(this).on('change',function(){
                loadData(id, $(this).val(), select);
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
        function checkumur(e){
            var today = new Date(), 
            birthday = $('#datex').datepicker("getDate"),
            age = ((today.getMonth() > birthday.getMonth())||
                   (today.getMonth() == birthday.getMonth() && today.getDate() >= birthday.getDate())) ? 
                    today.getFullYear() - birthday.getFullYear() : today.getFullYear() - birthday.getFullYear()-1;
            e.preventDefault();
            if(parseInt(age) < 17){
                 alert(" Minimal sudah ber umur 17 Tahun");
                 return false;
            }

            return false;
        }
        function listCustomer(){
            var datax=[];
            $.getJSON("<?php echo base_url("customer/customer/false/true");?>",
                {'kd_dealer':"<?php echo $this->session->userdata("kd_dealer");?>"},
                function(result){
                    if(result.totaldata >0){
                        $.each(result.message,function(e,d){
                            datax.push({
                                'KODE':d.KD_CUSTOMER,
                                'NAMA_CUSTOMER':d.NAMA_CUSTOMER
                            })
                        })
                    }
                    console.log(datax);
                    $('#upline').inputpicker({
                        data :datax,
                        fields:["KODE","NAMA_CUSTOMER"],
                        fieldValue:"KODE",
                        fieldText:"NAMA_CUSTOMER",
                        headShow:true,
                        filterOpen: true
                    })
                }
            )
        }
    </script>