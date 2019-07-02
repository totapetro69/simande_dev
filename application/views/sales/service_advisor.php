<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$no_trans = base64_decode(urldecode($this->input->get("n")));
$kd_lokasi = ($this->input->get("kd_lokasidealer"))?$this->input->get("kd_lokasidealer"):$this->session->userdata("kd_lokasi");

// var_dump($kd_lokasi);exit;
$tanggal=date('d/m/Y');
$status_sa="0";$no_polisi="";$no_stnk="";$no_mesin="";$no_rangka="";
$km_saatini="";$jenis_service="";$keluhan="";$advice="";
$nama_pemilik="";$no_hp="";$alamat_lengkap="";
$tipe_coming="";$nama_coming="";$hp_coming="";$alamat_pembawa="";$tipe_service="";
$kd_propinsi    = "";
$kd_kabupaten   = "";
$kd_kecamatan   = "";
$kd_kelurahan   = "";
$kd_propinsi_comingcustomer     = "";
$kd_kabupaten_comingcustomer    = "";
$kd_kecamatan_comingcustomer    = "";
$kd_kelurahan_comingcustomer    = "";
$kd_typemotor = "";
$tahun = "";
$tgl_beli = "";

if(isset($list)){
    if($list->totaldata >0){
        foreach ($list->message as $key => $value) {
            $kd_lokasi      = $value->KD_LOKASIDEALER;
            $no_trans       = $value->KD_SA;
            $nama_pemilik   = $value->NAMA_PEMILIK;
            $no_hp          = $value->NO_HP;
            $no_stnk        = $value->NO_STNK;
            $no_polisi      = $value->NO_POLISI;
            $no_mesin       = $value->NO_MESIN;
            $no_rangka      = $value->NO_RANGKA;
            $km_saatini     = $value->KM_SAATINI;
            $jenis_service  = $value->KD_TIPEPKB;
            $keluhan        = $value->KEBUTUHAN_KONSUMEN;
            $advice         = $value->HASIL_ANALISA_SA;
            $alamat_lengkap = $value->ALAMAT;
            $tipe_coming    = $value->KD_TYPECOMINGCUSTOMER;
            $nama_coming    = $value->NAMA_COMINGCUSTOMER;
            $tanggal        = TglFromSql($value->TANGGAL_SA);
            $status_sa      = $value->STATUS_SA;
            $hp_coming      = $value->HP_COMINGCUSTOMER;
            $alamat_pembawa = $value->ALAMAT_COMINGCUSTOMER;
            $tipe_service   = $value->KD_TYPESERVICE;
            $kd_propinsi    = $value->KD_PROPINSI;
            $kd_kabupaten   = $value->KD_KABUPATEN;
            $kd_kecamatan   = $value->KD_KECAMATAN;
            $kd_kelurahan   = $value->KD_KELURAHAN;
            $kd_propinsi_comingcustomer     = $value->KD_PROPINSI_COMINGCUSTOMER;
            $kd_kabupaten_comingcustomer    = $value->KD_KABUPATEN_COMINGCUSTOMER;
            $kd_kecamatan_comingcustomer    = $value->KD_KECAMATAN_COMINGCUSTOMER;
            $kd_kelurahan_comingcustomer    = $value->KD_KELURAHAN_COMINGCUSTOMER;
            $kd_typemotor = $value->KD_TYPEMOTOR;
            $tahun = $value->TAHUN;
            $tgl_beli = TglFromSql($value->TGL_BELI);
        }
    }
}
$dsb=((int)$status_sa>0)?'disabled-action':'';
$lock = ($no_trans)?'disabled-action':'';

$edit = $status_sa == 1?'disabled-action':$status_e;
$hide_data = $status_sa == 1 ?'hide':'';
$allow_formsa = $status_sa == 1 ?$status_v:'disabled-action';
?>
<section class="wrapper">
        <div class="breadcrumb margin-bottom-10">
            <?php echo breadcrumb(); ?>
            <div class="bar-nav pull-right ">
                <div class="btn-group">
                    <a id="baru" type="button" class="btn btn-default baru ">
                        <i class="fa fa-file-o fa-fw"></i> Add SA
                    </a>
                </div>
                <div class="btn-group">
                    <a id="submit-btn" type="button" class="btn btn-default submit-btn <?php echo $status_c." ".$dsb; ?>">  
                        <i class="fa fa-save fa-fw"></i> Simpan
                    </a>
                </div>
                <div class="btn-group">
                    <a role="button" href="<?php echo base_url("customer_service/service_advisor_list"); ?>" class="btn btn-default <?php echo $status_v; ?>"><i class="fa fa-list-ul"></i> List SA</a>
                </div>
                <div class="btn-group">
                    <a role="button" href="<?php echo base_url("customer_service/cetak_sa?n=".urlencode(base64_encode($no_trans))); ?>" target="_blank" class="btn btn-default <?php echo $allow_formsa; ?>"><i class="fa fa-print"></i> Form SA</a>
                </div>

            </div>

        </div>

    <form class="bucket-form" id="addFormz" method="post" action="<?php echo base_url("customer_service/simpan_service_advisor"); ?>" autocomplete="off">
        <div class="col-lg-12 padding-left-right-10">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Form SA
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <div class="panel-body panel-body-border" style="display: block;">



                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Nama Dealer</label>
                                <select class="form-control <?php echo $lock;?>" id="kd_dealer" name="kd_dealer">
                                    
                                    <?php
                                    if (isset($dealer)) {
                                        if ($dealer->totaldata > 0) {
                                            foreach ($dealer->message as $key => $value) {
                                                $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                                $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                                echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Lokasi</label>
                                <select class="form-control disabled-action <?php echo($kd_lokasi=="0")?"": $lock;?>" id="kd_lokasidealer" name="kd_lokasidealer" required="true">
                                    <option value="">--Pilih Lokasi Dealer--</option>
                                     <?php
                                        if (isset($lokasidealer)) {
                                          if (($lokasidealer->totaldata >0)) {
                                            foreach ($lokasidealer->message as $key => $value) {
                                              $aktif = ($kd_lokasi == $value->KD_LOKASI) ? "selected" : '';
                                              echo "<option value='" . $value->KD_LOKASI . "' " . $aktif . ">[".$value->KD_LOKASI."] ". strtoupper($value->NAMA_LOKASI)."</option>";
                                            }
                                          }
                                        }
                                    ?>  
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="col-xs-2 col-md-2 col-sm-2">
                            <div class="form-group">
                                <div class="checkbox <?php echo $lock;?>">
                                    <label><input type="checkbox" id="load4booking"> Load Booking</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-md-5 col-sm-5">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group input-append date" id="date">
                                    <input class="form-control <?php echo $lock;?>" id="tanggal_sa" name="tanggal_sa" placeholder="DD/MM/YYYY" value="<?php echo $tanggal; ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-md-5 col-sm-5">
                            <div class="form-group">
                                <label>No. Transaksi</label>
                                <input type="text" class="form-control <?php echo $lock;?>" id="kd_sa" autocomplete="off" name="kd_sa" placeholder="AUTO GENERATE" value="<?php echo $no_trans; ?>" readonly="true">
                            </div>
                        </div>
                    </div>
                </div>


                <hr>



                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No. Polisi <span id="load-form" class="load-form"></span></label>
                                <!-- <label>No. Polisi <span id="load-form" class="load-form" style="color:red"></span></label> -->
                                <input type="text" name="no_polisi" id="no_polisi" value='<?php echo $no_polisi;?>' class="form-control <?php echo $dsb;?> <?php echo $lock;?>" style="text-transform: uppercase;" placeholder="AB-1234-XX" autocomplete="off" required="true">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No. Rangka <span class="load-form"></span></label>
                                <input type="text" name="no_rangka" id="no_rangka"  value='<?php echo $no_rangka;?>'class="form-control" style="text-transform: uppercase;" placeholder="Nomor Rangka" >
                            </div>
                        </div>


                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tipe Motor <span class="load-form"></span></label>
                                <input type="text" name="kd_typemotor" id="kd_typemotor" class="form-control disabled" value="<?php echo $kd_typemotor; ?>" placeholder="Nama Type Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>KM Motor</label>
                                <input type="text" name="km_saatini" id="km_saatini" value='<?php echo ($km_saatini)? number_format($km_saatini,0):"";?>' class="form-control qurency" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tahun Motor <span class="load-form"></span></label>
                                <input type="text" name="tahun" id="tahun" class="form-control disabled tahun" value="<?php echo $tahun;?>" placeholder="Tahun" required>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Keluhan Konsumen</label>
                                <textarea type="text" name="kebutuhan_konsumen" id="kebutuhan_konsumen" class="form-control" placeholder="Deskripsi masalah motor yang butuh di perbaiki" required><?php echo $keluhan;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No. Mesin <span class="load-form"></span>    </label>
                                <input type="text" name="no_mesin" id="no_mesin" class="form-control"  value='<?php echo $no_mesin;?>'style="text-transform: uppercase;" required="true" placeholder="Nomor Mesin" >
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No STNK <span class="load-form"></span></label>
                                <input type="text" name="no_stnk" id="no_stnk" value='<?php echo $no_stnk;?>' class="form-control" style="text-transform: uppercase;" placeholder="Masukkan No STNK" autocomplete="off" >
                            </div>
                        </div>


                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tgl Beli <span class="load-form"></span></label>
                                <div class="input-group input-append date" id="">
                                    <input class="form-control" id="tgl_beli" name="tgl_beli" placeholder="DD/MM/YYYY" value="<?php echo $tgl_beli; ?>" type="text"/>
                                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Jenis Service</label>
                                <select class="form-control " id="kd_tipepkb" name="kd_tipepkb" required>
                                    <option value=''>-- Pilih Jenis Service --</option>
                                    <?php 
                                    if(isset($tipepkb)){
                                        if($tipepkb->totaldata >0){
                                            foreach ($tipepkb->message as $key => $value) {
                                                $pilih =($jenis_service== $value->KD_TIPEPKB)?'selected':'';
                                                ?>
                                                    <option value="<?php echo $value->KD_TIPEPKB; ?>" <?php echo $pilih;?>><?= $value->KD_TIPEPKB; ?> - <?= $value->NAMA_TIPEPKB; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tipe Service Mekanik</label>
                            <!-- <input type="text" name="kd_typeservice" id="kd_typeservice" class="form-control" placeholder=""> -->
                                <select class="form-control" id="kd_typeservice" name="kd_typeservice" >
                                    <option value="">- Pilih Tipe Service Mekanik -</option>
                                    <?php
                                    if (isset($tipeservice)){
                                        if($tipeservice->totaldata >0){
                                            foreach ($tipeservice->message as $key => $value) {
                                                $pilih=($tipe_service==$value->KD_TIPESERVICEMEKANIK)?'selected':'';
                                                ?>
                                                <option value="<?= $value->KD_TIPESERVICEMEKANIK; ?>" <?php echo $pilih;?>><?= $value->NAMA_TIPESERVICEMEKANIK; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Hasil Analisa SA</label>
                                <textarea type="text" name="hasil_analisa_sa" id="hasil_analisa_sa" class="form-control" placeholder="Deskripsi masalah motor yang di lihat SA" required><?php echo $advice;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!--hidden-->
                <div class="form-group hidden">
                    <input type="text" name="kd_customer" id="kd_customer" class="form-control" placeholder="">
                    <input type="text" name="kd_maindealer" id="kd_maindealer" class="form-control" placeholder="">
                    <input type="text" name="kd_pembawamotor" id="kd_pembawamotor" class="form-control" placeholder="">
                    <input type="text" name="kd_pemakaimotor" id="kd_pemakaimotor" class="form-control" placeholder="">
                    <input type="text" name="kd_honda" id="kd_honda" class="form-control" placeholder="">
                    <input type="text" name="kd_jenispit" id="kd_jenispit" class="form-control" placeholder="">
                    <input type="text" name="foto_konsumen" id="foto_konsumen" class="form-control" placeholder="">
                    <input type="text" name="dokumen" id="dokumen" class="form-control" placeholder="">
                    <input class="form-control" id="estimasi_pendaftaran" name="estimasi_pendaftaran" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                    <input class="form-control" id="estimasi_pengerjaan" name="estimasi_pengerjaan" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                    <input class="form-control" id="estimasi_selesai" name="estimasi_selesai" placeholder="DD/MM/YYYY" value="<?php echo date('d/m/Y'); ?>" type="text"/>
                    <input type="text" name="saran_mekanik" id="saran_mekanik" class="form-control" placeholder="">
                    <input type="text" name="kd_pekerjaan" id="kd_pekerjaan" class="form-control" placeholder="">
                    <input type="text" name="part_number" id="part_number" class="form-control" placeholder="">
                    <input type="text" name="total_frt" id="total_frt" class="form-control" placeholder="">
                    <input type="text" name="amount" id="amount" class="form-control" placeholder="">
                    <input type="text" name="no_pit" id="no_pit" class="form-control" placeholder="">
                    <input type="text" name="kd_setuppembayaran" id="kd_setuppembayaran" class="form-control" placeholder="">
                    <input type="text" name="catatan_tambahan" id="catatan_tambahan" class="form-control" placeholder="">
                    <input type="text" name="bensin_saatini" id="bensin_saatini" class="form-control" placeholder="">
                    <input type="text" name="konfirmasi_pekerjaantambahan" id="konfirmasi_pekerjaantambahan" class="form-control" placeholder="">
                    <input type="text" name="no_buku" id="no_buku" class="form-control" placeholder="">
                    <input type="text" name="status_sa" id="status_sa" class="form-control" placeholder="">
                </div>

                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Pemilik
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <div class="panel-body panel-body-border" style="display: block;">

                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Pemilik <span class="load-form"></span></label>
                                <input type="text" name="nama_pemilik" id="nama_pemilik" value='<?php echo $nama_pemilik;?>' class="form-control" placeholder="Masukkan Nama Pemilik" >
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>No. HP <span class="load-form"></span></label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control" value='<?php echo $no_hp;?>' placeholder="Nomor HP" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Propinsi <span class="load-form"></span></label>
                                <input type="text" name="kd_propinsi" id="kd_propinsi" value="<?php echo $kd_propinsi;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kabupaten <span class="load-form"></span></label>
                                <input type="text" name="kd_kabupaten" id="kd_kabupaten" value="<?php echo $kd_kabupaten;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kecamatan <span class="load-form"></span></label>
                                <input type="text" name="kd_kecamatan" id="kd_kecamatan" value="<?php echo $kd_kecamatan;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kelurahan <span class="load-form"></span></label>
                                <input type="text" name="kd_kelurahan" id="kd_kelurahan" value="<?php echo $kd_kelurahan;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Alamat <span class="load-form"></span></label>
                                <textarea type="text" name="alamat" id="alamat" class="form-control"><?php echo $alamat_lengkap;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-6">
            <div class="panel margin-bottom-10">
                <div class="panel-heading"><i class='fa fa-list-ul'></i> Coming Customer
                    <span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
                </div>
                <div class="panel-body panel-body-border" style="display: block;">

                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Tipe Coming Customer</label>
                                <select class="form-control" id="kd_typecomingcustomer" name="kd_typecomingcustomer" >
                                    <option value="" >- Pilih Tipe Coming Customer -</option>
                                    <?php
                                    if (isset($customer)){
                                        if($customer->totaldata >0){
                                            foreach ($customer->message as $key => $value) {
                                                $pilih=($tipe_coming==$value->KD_TYPECOMINGCUSTOMER)?'selected':'';
                                                ?>
                                                <option value="<?= $value->KD_TYPECOMINGCUSTOMER; ?>" <?php echo $pilih;?>><?= $value->NAMA_TYPECOMINGCUSTOMER; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5">
                            <div class="form-group">
                                <label>Nama Coming Customer</label>
                                <input type="text" name="nama_comingcustomer" id="nama_comingcustomer"  value='<?php echo $nama_coming;?>' class="form-control" placeholder="Masukkan Nama Customer" >
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <label>No. HP</label>
                                <input type="text" name="nohp_pembawa" id="nohp_pembawa" class="form-control" value="<?php echo $hp_coming;?>" placeholder="Masukkan No. HP Coming Customer" >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Propinsi <span class="load-form"></span></label>
                                <input type="text" name="kd_propinsi_comingcustomer" id="kd_propinsi_comingcustomer" value="<?php echo $kd_propinsi_comingcustomer;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kabupaten <span class="load-form"></span></label>
                                <input type="text" name="kd_kabupaten_comingcustomer" id="kd_kabupaten_comingcustomer" value="<?php echo $kd_kabupaten_comingcustomer;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kecamatan <span class="load-form"></span></label>
                                <input type="text" name="kd_kecamatan_comingcustomer" id="kd_kecamatan_comingcustomer" value="<?php echo $kd_kecamatan_comingcustomer;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Nama Kelurahan <span class="load-form"></span></label>
                                <input type="text" name="kd_kelurahan_comingcustomer" id="kd_kelurahan_comingcustomer" value="<?php echo $kd_kelurahan_comingcustomer;?>" class="form-control" placeholder="Masukkan Kode Motor" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12" >
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea type="text" name="alamat_pembawa" id="alamat_pembawa" class="form-control" placeholder="Masukkan Alamat Coming Customer" ><?php echo $alamat_pembawa;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>


    <div id="form-additem" class="col-xs-12 col-sm-12 padding-left-right-10 <?php echo $hide_data;?> disabled-action">
        <div class="panel margin-bottom-10">
            <div class="panel-body panel-body-border-top">
                <input type="hidden" id="part_desc" name="part_desc" class="form-control">
                <input type="hidden" id="kategori_item" name="kategori_item" class="form-control">
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                            <label>Kategori <span class="hddetail-loading"></label>
                            <select name="kategori" id="kategori" class="form-control">
                                <option value="Jasa">Jasa</option>
                                <option value="Part">Part</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-sm-5 col-md-5">
                        <div class="form-group">
                            <label>Keterangan <span class="hddetail-loading"></label>
                            <input type="text" id="kd_part" name="kd_part" class="form-control">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <label>Qty <span class="detail-loading"></span></label>
                            <input type="text" name="qty" id="qty" class="form-control qurency text-center" placeholder="Qty">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label>Harga <span class="detail-loading"></span></label>
                            <div class="input-group">

                                <input type="text" name="harga_sp" id="harga_sp" class="form-control qurency text-right disabled-action" value="" placeholder="Harga Part">
                                <!-- <input type="text" name="harga_sp" id="harga_sp" class="form-control qurency text-right" value="" placeholder="Harga Part" data-mask="#.##0" data-mask-reverse="true"> -->
                                <span class="input-group-btn">
                                    <button class="btn btn-primary <?php echo $status_c;?>" onclick="__addItem();" type="button" id="btn-add-sp"><i class="fa fa-plus"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel panel-default">
            <div class="table-responsive">
                <table id="pkb_list" class="table table-bordered table-hover b-t b-light">
                    <thead>
                        <tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List SA Detail</th></tr>
                        <tr>
                            <!-- <th style="width:40px;">No.</th> -->
                            <th style="width:50px;">Aksi</th>
                            <th>Keterangan</th>
                            <th class="text-center" style="width:80px;">Qty</th>
                            <th class="text-justify" style="width:120px;">Harga</th>
                            <th class="text-justify" style="width:150px;">Total Harga</th>
                            <th style="width:100px;">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($this->input->get('n')):
                            if($list->message[0]->DETAIL_ID != ''):
                            foreach ($list->message as $key => $list_row):
                        ?>
                            <tr>
                                <td class='hidden'></td>
                                <td class='text-center'>
                                    <a id="<?php echo $list_row->DETAIL_ID; ?>" class='hapus2-item <?php echo $edit;?>' role='button'><i class='fa fa-trash'></i></a>
                                </td>
                                <td><?php echo $list_row->KD_PEKERJAAN." - ".$list_row->PART_DESKRIPSI;?></td>
                                <td class='text-right'><?php echo $list_row->QTY;?></td>
                                <td class='text-right qurency'><?php echo number_format($list_row->HARGA_SATUAN);?></td>
                                <td class='text-right qurency'><?php echo number_format($list_row->TOTAL_HARGA);?></td>
                                <td class='text-right'><?php echo $list_row->KATEGORI;?></td>
                            </tr>
                        <?php
                            endforeach;
                            endif;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php echo loading_proses(); ?>
</section>

<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];

$(document).ready(function () {
        var date = new Date();
        date.setDate(date.getDate());

        $('#baru').click(function () {
            document.location.href="<?php echo base_url('customer_service/add_service_advisor');?>"
        })
        $('#load4booking').click(function(){
            if($(this).is(':checked')){
                __loadBooking();
            }else{
                document.location.reload();
            }
        })
        $("#no_polisi").on('keypress',function (e) {
            if(e.which===13){
                var no_polisi = $(this).val();
                var url = "<?php echo base_url() . 'customer_service/get_datanopol/true'; ?>";
                $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
                $.getJSON(url, {'n': no_polisi }, function (data, status) {
                    console.log(data);
                    if (data.status) {
                        $.each(data.message,function(e,d){
                            $('#no_stnk').val(d.NO_STNK);
                            $('#no_rangka').val(d.NO_RANGKA);
                            $('#no_mesin').val(d.NO_MESIN);
                            $('#nama_pemilik').val(d.NAMA_PENERIMA);
                            $('#no_hp').val(d.NOHP);
                            $('#alamat').val(d.ALAMAT);
                            $('#kd_customer').val(d.KD_CUSTOMER);
                            $('#kd_typemotor').val(d.KD_TYPEMOTOR);
                        })
                    }else{
                        __loadDetailCustomer(no_polisi);
                    }
                    $(".load-form").html('');
                 });
            }
        });
        $('#date,#datex, #date_fu, #date_fu1, #date_fu2').datepicker({
            format: 'dd/mm/yyyy',
            daysOfWeekHighlighted: "0",
            autoclose: true,
            todayHighlight: true
        });
        $('.qurency').mask('000.000.000.000.000', {reverse: true});
        $('#no_polisi').mask('AZ-0001-AAZ',{'translation': {
          A: {pattern: /[A-Za-z]/},
          Z: {pattern: /[A-Za-z]/,optional:true},
          0: {pattern: /[0-9]/},
          1: {pattern: /[0-9]/,optional:true}
        }})
        $("#submit-btn").on('click', function (event) {
            var formId = '#addFormz';
            var btnId = '#' + this.id;
            $('#loadpage').removeClass("hidden");
            $('.qurency').unmask();

            $(formId).valid();

            if (jQuery(formId).valid()) {
                // Do something
                event.preventDefault();

                storeData(formId, btnId);

            } else {

                $('#loadpage').addClass("hidden");

            }
        });
        $('#kd_typecomingcustomer').change(function(){
            if($(this).val()==='M'){
                $('#nama_comingcustomer').val($('#nama_pemilik').val());
                $('#nohp_pembawa').val($('#no_hp').val());
                $('#alamat_pembawa').val($('#alamat').val());

                $('#kd_propinsi_comingcustomer').val($('#kd_propinsi').val());
                $('#kd_kabupaten_comingcustomer').val($('#kd_kabupaten').val());
                $('#kd_kecamatan_comingcustomer').val($('#kd_kecamatan').val());
                $('#kd_kelurahan_comingcustomer').val($('#kd_kelurahan').val());
            }else{
               $('#nama_comingcustomer').val('');
                $('#nohp_pembawa').val('');
                $('#alamat_pembawa').val(''); 

                $('#kd_propinsi_comingcustomer').val('');
                $('#kd_kabupaten_comingcustomer').val('');
                $('#kd_kecamatan_comingcustomer').val('');
                $('#kd_kelurahan_comingcustomer').val('');
            }

            kd_propinsi_comingcustomer();
            kd_kabupaten_comingcustomer();
            kd_kecamatan_comingcustomer();
            kd_kelurahan_comingcustomer();

        });
        $('#kd_dealer').change(function(){
            __getLokasiDealer($(this).val());
        });

        kd_propinsi();
        kd_kabupaten();
        kd_kecamatan();
        kd_kelurahan();

        kd_propinsi_comingcustomer();
        kd_kabupaten_comingcustomer();
        kd_kecamatan_comingcustomer();
        kd_kelurahan_comingcustomer();

        __getMotor();
        __getBarangSP(); 

        generateStock();

        $('#kategori, #jenis_kpb').on('change', function () {
          $('#kd_part').val("");
          $('#qty').val("");
          $('#harga_sp').val("");
          __getBarangSP();
        });


        $("#kd_part").on("change",function(e){
          
            var kd_kategori = $('#kategori').val();
            var data_number= $.trim($(this).val());
            var url = http+"/pkb/part_jasa/"+kd_kategori+"/true";

            $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
            // var url = (kd_kategori == 'Part' ? http+"/sparepart/hargapart/true":http+"/pkb/hargajasa");
            $.getJSON(url,{"data_number":data_number},function(result){
              console.log(result);
              $.each(result,function(e,d){
                var harga_jual=0;
                // var cek_oli = $(".data-OLI").length;
                var jenis_kpb = $("#kd_tipepkb").val();

                harga_jual = d.DATA_HARGA;

                $('#kategori_item').val($("#kategori").val());
                $('#part_desc').val(d.DATA_DESKRIPSI);
                $('#qty').val("1");
                $('#harga_sp').attr('min',parseFloat(harga_jual));     
                $('#harga_sp').val(parseFloat(harga_jual));     
                
                $(".detail-loading").html("");

              })
            })

        });


        $('.hapus2-item').click(function(){
          var detailId = this.id;
          if(detailId != '')
          {
            $.getJSON(http+'/customer_service/delete_csa_detail',{id:detailId}, function(data, status) {
                if (data.status == true) {
                  $("#"+detailId).parents('tr').remove();
                }
            });
          }
        });


        $('#pkb_list').on('click', '.hapus-item', function(){
            $(this).parents('tr').remove();
        });

        $('#kd_propinsi').change(function(){
            $('#kd_kabupaten').val('');
            $('#kd_kecamatan').val('');
            $('#kd_kelurahan').val('');

            kd_kabupaten();
            kd_kecamatan();
            kd_kelurahan();
        });
        $('#kd_kabupaten').change(function(){
            $('#kd_kecamatan').val('');
            $('#kd_kelurahan').val('');

            kd_kecamatan();
            kd_kelurahan();

        });
        $('#kd_kecamatan').change(function(){
            $('#kd_kelurahan').val('');

            kd_kelurahan();

        });
        $('#kd_propinsi_comingcustomer').change(function(){
            $('#kd_kabupaten_comingcustomer').val('');
            $('#kd_kecamatan_comingcustomer').val('');
            $('#kd_kelurahan_comingcustomer').val('');

            kd_kabupaten_comingcustomer();
            kd_kecamatan_comingcustomer();
            kd_kelurahan_comingcustomer();

        });
        $('#kd_kabupaten_comingcustomer').change(function(){
            $('#kd_kecamatan_comingcustomer').val('');
            $('#kd_kelurahan_comingcustomer').val('');

            kd_kecamatan_comingcustomer();
            kd_kelurahan_comingcustomer();

        });
        $('#kd_kecamatan_comingcustomer').change(function(){
            $('#kd_kelurahan_comingcustomer').val('');

            kd_kelurahan_comingcustomer();

        });


        $('#no_polisi').focusout(function(){
            var no_polisi = $(this).val();
            var url = http+"/customer_service/get_detailcustomer";
            
            $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");

            $.getJSON(url,{"no_polisi":no_polisi},function(result){
                if(result.list.totaldata > 0){
                    // console.log(result.list.message[0]);

                    var tgl_beli = tgl_fromsql(result.list.message[0].TGL_BELI);

                    $('#no_rangka').val(result.list.message[0].SPK_NORANGKA);
                    $('#no_mesin').val(result.list.message[0].SPK_NOMESIN);
                    $('#no_stnk').val(result.list.message[0].NO_STNK);
                    $('#kd_typemotor').val(result.list.message[0].KD_TYPEMOTOR+'-'+result.list.message[0].KD_WARNA);
                    $('#tahun').val(result.list.message[0].THN_PERAKITAN);
                    $('#tgl_beli').val(tgl_beli);
                    $('#nama_pemilik').val(result.list.message[0].NAMA_PEMILIK);
                    $('#no_hp').val(result.list.message[0].NOHP);
                    $('#kd_propinsi').val(result.list.message[0].KD_PROPINSI);
                    $('#kd_kabupaten').val(result.list.message[0].KD_KABUPATEN);
                    $('#kd_kecamatan').val(result.list.message[0].KD_KECAMATAN);
                    $('#kd_kelurahan').val(result.list.message[0].KD_KELURAHAN);
                    $('#alamat').val(result.list.message[0].ALAMAT_CUSTOMER);

                    __getMotor();
                    __getBarangSP();
                    kd_propinsi();
                    kd_kabupaten();
                    kd_kecamatan();
                    kd_kelurahan();

                    // alert(result.list.message[0].SPK_NOMESIN +'|'+ result.list.message[0].KM_MOTOR +'|'+ result.list.message[0].TGL_BELI);

                    // if(result.list.message[0].SPK_NOMESIN && result.list.message[0].KM_MOTOR && result.list.message[0].TGL_BELI){
                    $.getJSON(http+"/customer_service/cek_kpb/",
                        {"no_mesin":result.list.message[0].SPK_NOMESIN, "km_motor":result.list.message[0].KM_MOTOR, "tgl_terima":result.list.message[0].TGL_BELI, },
                        function(results){

                            // console.log(results);
                            $('#kd_tipepkb').val(results);

                    });
                    // }
                }
                else{

                    $('#no_rangka').val('');
                    $('#no_mesin').val('');
                    $('#no_stnk').val('');
                    $('#kd_typemotor').val('');
                    $('#tahun').val('');
                    $('#tgl_beli').val(tgl_beli);
                    $('#nama_pemilik').val('');
                    $('#no_hp').val('');
                    $('#kd_propinsi').val('');
                    $('#kd_kabupaten').val('');
                    $('#kd_kecamatan').val('');
                    $('#kd_kelurahan').val('');
                    $('#alamat').val('');
                    $('#kd_tipepkb').val('');

                    __getMotor();
                    __getBarangSP();
                    kd_propinsi();
                    kd_kabupaten();
                    kd_kecamatan();
                    kd_kelurahan();
                }
                $(".load-form").html("");
            });
            // $('#kd_tipepkb').val('KPB1');
        });
    })


    function generateStock()
    {
        $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
        $(".hddetail-loading").html("<i class='fa fa-spinner fa-spin'></i>");
        
        $.getJSON(http+"/pkb/parts4gen",function(result, status){
            if(status == 'success'){
                $('#form-additem').removeClass('disabled-action');
                $(".detail-loading").html("");
                $(".hddetail-loading").html("");
            }
            else{
                generateStock();
            }
        });
    }

    function __getMotor()
    {
        var url = http+"/pkb/tipe_motor";
        var kd_typemotor = $("#kd_typemotor").val();

        $('#kd_typemotor').inputpicker({
          url:url,
          urlParam:{"kd_item":kd_typemotor},
          fields:['KD_TYPEMOTOR','NAMA_PASAR', 'KET_WARNA'],
          fieldText:'NAMA_PASAR',
          fieldValue:'KD_ITEM',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 15,
          pageCurrent: 1,
          urlDelay:2
        })
        .on("change",function(){
            __getBarangSP();
        })
    }


    function __getBarangSP(){
      var kd_kategori = $('#kategori').val();
      var lokasi_dealer = $('#kd_lokasidealer').val();
      var item = $("#kd_typemotor").val();
      // console.log(item);
      var kd_item = 'null';

      $("#kd_part").val('');
      if(item != undefined && item != ''){
        var split = item.split("-");
        kd_item = split[0];
        var url_kategori = http+"/pkb/part_jasa/"+kd_kategori+"?kd_typemotor="+kd_item+"&lokasi_dealer="+lokasi_dealer;
        $('#kd_part').inputpicker({
          url:url_kategori,
          fields:['DATA_NUMBER','DATA_DESKRIPSI'],
          fieldText:'DATA_DESKRIPSI',
          fieldValue:'DATA_NUMBER',
          filterOpen: true,
          headShow:true,
          pagination: true,
          pageMode: '',
          pageField: 'p',
          pageLimitField: 'per_page',
          limit: 15,
          pageCurrent: 1,
          urlDelay:2
        })
      }
    }


    function __addItem()
    {
      var bariskes=0;
      var total_bayar=0;
      var total_beli=0;
      var html="";

      var jenis_item = $('#jenis_item').val();
      
      $('#harga_sp').unmask();
      bariskes = $('#lst_sp > tbody > tr').length;
      //var diskon = $('#diskon').val();
      total_beli = $('#qty').val() * $('#harga_sp').val();
      $('#harga_sp').mask('#.##0', {reverse: true});
      if(total_beli != 0){
        // html +="<tr><td class='text-center'>"+(bariskes+1)+"</td>";
        html +="<tr class='data-"+$('#jenis_tr').val()+"'>";
        html +="<td class='hidden'>"+$('#kd_part').val()+"</td>";
        html +="<td class='text-center'><a class='hapus-item' role='button'><i class='fa fa-trash'></i></a></td>"; 
        html +="<td>"+ $('#kd_part').val() + " - " + $('#part_desc').val() +"</td>";
        html +="<td class='text-right'>"+ $('#qty').val() +"</td>";
        html +="<td class='text-right qurency'>"+ $('#harga_sp').val() +"</td>";
        html +="<td class='text-right qurency'>"+ total_beli +"</td>";
        html +="<td class='text-right'>"+ $('#kategori_item').val() +"</td>";
        html +="</tr>";
        if($('#kategori_item').val() == 'Jasa'){
          $('#pkb_list > tbody').prepend(html);
        }
        else{
          $('#pkb_list > tbody').append(html);
        }
        var bariskex=0;

        $("#kd_part").val('');
        $("#qty").val('');
        $('#qty').removeAttr('readonly');
        $("#harga_sp").val('');

      }
      else{
        $('.error').animate({top: "0"}, 500);
        $('.error').html("data tidak boleh kosong atau 0").fadeIn();
        setTimeout(function () {
            hideAllMessages();
        }, 2000);
      }
    }


    function kd_propinsi()
    {
        var url = "<?php echo base_url('customer_service/wilayah_customer/propinsi');?>";

        $('#kd_propinsi').inputpicker({
          url:url,
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })
    }
    function kd_kabupaten()
    {
        var kd_propinsi = $("#kd_propinsi").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kabupaten');?>";

        $('#kd_kabupaten').inputpicker({
          url:url,
          urlParam:{"kd_propinsi":kd_propinsi},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_kecamatan()
    {
        var kd_kabupaten = $("#kd_kabupaten").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kecamatan');?>";

        $('#kd_kecamatan').inputpicker({
          url:url,
          urlParam:{"kd_kabupaten":kd_kabupaten},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_kelurahan()
    {
        var kd_kecamatan = $("#kd_kecamatan").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kelurahan');?>";

        $('#kd_kelurahan').inputpicker({
          url:url,
          urlParam:{"kd_kecamatan":kd_kecamatan},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_propinsi_comingcustomer()
    {
        var url = "<?php echo base_url('customer_service/wilayah_customer/propinsi');?>";

        $('#kd_propinsi_comingcustomer').inputpicker({
          url:url,
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_kabupaten_comingcustomer()
    {
        var kd_propinsi = $("#kd_propinsi_comingcustomer").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kabupaten');?>";

        $('#kd_kabupaten_comingcustomer').inputpicker({
          url:url,
          urlParam:{"kd_propinsi":kd_propinsi},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_kecamatan_comingcustomer()
    {
        var kd_kabupaten = $("#kd_kabupaten_comingcustomer").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kecamatan');?>";

        $('#kd_kecamatan_comingcustomer').inputpicker({
          url:url,
          urlParam:{"kd_kabupaten":kd_kabupaten},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }
    function kd_kelurahan_comingcustomer()
    {
        var kd_kecamatan = $("#kd_kecamatan_comingcustomer").val();
        var url = "<?php echo base_url('customer_service/wilayah_customer/kelurahan');?>";

        $('#kd_kelurahan_comingcustomer').inputpicker({
          url:url,
          urlParam:{"kd_kecamatan":kd_kecamatan},
          fields:['KODE','NAMA'],
          fieldText:'NAMA',
          fieldValue:'KODE',
          filterOpen: true,
        })

    }

    function __data()
    {
      var bariskex=0;
      bariskex = $('#pkb_list > tbody > tr').length;
      var dataxx=[];
      for(iz=0;iz< bariskex;iz++){
        var kdStatus = $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(0)").text();
        $(".qurency").unmask();
        if(kdStatus != ''){
          dataxx.push({
            'kd_pekerjaan' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(0)").text(),
            'kategori'  : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(6)").text(),
            'qty': $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(3)").text(),
            'harga_satuan' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(4)").text(),
            'total_harga' : $("#pkb_list > tbody > tr:eq(" + iz + ") td:eq(5)").text(),
          })
        }
        $(".qurency").mask('#.##0', {reverse: true});
      }
      // console.log('jmlbaris: '+bariskex)
      // console.log(dataxx)
      return dataxx;
    }

    function storeData(formId, btnId){
        // alert(formId);
        var data_form=__data();
        var kd_sa = $('#kd_sa').val();
        var defaultBtn = $(btnId).html();

        $(btnId).addClass("disabled");
        $(btnId).html("<i class='fa fa-spinner fa-spin'></i> Loading");
        $(".alert-message").fadeIn();

        $(formId + " select").removeAttr("disabled");
        $(formId + " select").removeClass("disabled-action");
        var formData = $(formId).serialize();
        var act = $(formId).attr('action');

        // console.log(data_form);

        $.ajax({
            url: act,
            type: 'POST',
            data: formData+"&detail="+JSON.stringify(data_form),
            dataType: "json",
            success: function (result) {

                if (result.status == true) {

                    $('.success').animate({
                        top: "0"
                    }, 500);
                    $('.success').html(result.message);


                    if (result.location != null) {
                        setTimeout(function () {
                            location.replace(result.location)
                        }, 1000);
                    } else {
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                } else {

                    $('.error').animate({
                        top: "0"
                    }, 500);
                    $('.error').html(result.message);

                    setTimeout(function () {
                        hideAllMessages();
                        $(btnId).removeClass("disabled");
                        $(btnId).html(defaultBtn);
                        $('#loadpage').addClass("hidden");
                    }, 2000);


                }
            }

        });

        return false;
    }
    function __loadBooking(){
        var datax=[];
        $('#load-form').html("<i class='fa fa-spinner fa-spin'></i>");
        $.getJSON("<?php echo base_url('reminder_booking/service_booking/true');?>",function(result){
            console.log(result);
            if(result.status){
                $.each(result.message,function(e,d){
                    datax.push({
                        'NO_POLISI':d.NO_POLISI.toUpperCase(),
                        'NAMA_CUSTOMER' :$.ucwords(d.NAMA_CUSTOMER),
                        'TGL_BOOKING':d.TGL_TRANS.toLocaleString(),
                        'NO_TRANS'  :d.NO_TRANS
                    })
                })
                if(datax){
                    $('#no_polisi').inputpicker({
                        data:datax,
                        fields :['NO_POLISI','NAMA_CUSTOMER','TGL_BOOKING'],
                        fieldText :'NO_POLISI',
                        fieldValue :'NO_POLISI',
                        filterOpen: true,
                        headerShow:true
                    }).on('change',function(e){
                        var dx=datax.findIndex(obj => obj['NO_POLISI'] == $(this).val());
                        __loadDetailCustomer($(this).val());
                    })
                }
                $('#load-form').html("");
            }
        })
    }
    function __loadDetailCustomer(id){
        $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
        $.getJSON("<?php echo base_url('customer_service/get_datacsa/true');?>",{'n':id},function(result){
            var alamat_lengkap="";
            if(result.status){
                $.each(result.message,function(e,d){
                    alamat_lengkap +=d.ALAMAT;
                    // alamat_lengkap +=d.NAMA_DESA +" "+d.NAMA_KECAMATAN+", "
                    // alamat_lengkap +=d.NAMA_KABUPATEN +" "+d.NAMA_PROPINSI+" "+d.KODE_POS;
                    $('#no_stnk').val('');
                    $('#no_rangka').val(d.NO_RANGKA);
                    $('#no_mesin').val(d.NO_MESIN);
                    $('#nama_pemilik').val(d.NAMA_PEMILIK);
                    $('#no_hp').val(d.NO_HP);
                    $('#alamat').val(alamat_lengkap);
                    $('#kd_customer').val(d.KD_CUSTOMER);
                    $('#kd_typemotor').val(d.KD_TYPEMOTOR);
                })
                $(".load-form").html("");
            }else{
                $(".load-form").html("");
            }
        })
    }
    function __getLokasiDealer(kd_dealer){
        var option ="<option value=''>--Pilih Lokasi--</option>";
        $.getJSON("<?php echo base_url('dealer/lokasi_dealer/true/true');?>",{'kd_dealer':kd_dealer},function(result){
            if(result){
                $.each(result,function(e,d){
                    option +="<option value='"+d.KD_LOKASI+"'>"+d.NAMA_LOKASI+"</option>";
                })
            }
            $('#kd_lokasidealer').html(option);
        })
    }


    function tgl_fromsql(date)
    {
       var newDate = '';
       if(date != null){
         var datepkb = new Date(date),
             yr      = datepkb.getFullYear(),
             month   = (datepkb.getMonth()+1) < 10 ? '0' + (datepkb.getMonth()+1) : (datepkb.getMonth()+1),
             day     = datepkb.getDate()  < 10 ? '0' + datepkb.getDate()  : datepkb.getDate(),
             newDate = day + '/' + month + '/' + yr;;
       }

       return newDate;

    }
</script>