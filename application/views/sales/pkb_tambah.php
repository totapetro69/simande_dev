<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
$defaultDealer = ($this->session->userdata("kd_dealer"));
$status_n = ($this->session->userdata("kd_group") == "root") ? "" : "disabled='disabled'";
$no_trans = base64_decode($this->input->get("t"));
// from get kode sa
$kd_sa = "";
$no_polisi = "";
$no_rangka = "";
$no_mesin = "";
$km_motor = "";
$bbm = "";
$nama_typemotor = "";
$saran = "";
$saran_sa = "";
$kd_item = "";
$kd_typemotor = "";
// plus from get no pkb
$id="";
$no_pkb = "";
$kd_dealer = "";
$tahun = "";
$nama_mekanik = "";
$no_antrian = $antrian->totaldata + 1;
$jenis_kpb = "";
$jenis_pit = "";
$estimasi_mulai = "";
$estimasi_selesai = "";
$pembelian_motor = "";
$alasan_ke_ahass = "";
$hubungan_dengan_pembawa = "";
$service_sebelumnya = "";
$keterangan = "";
$tanggal_pkb = date('d/m/Y');
$tgl_beli = "";
$kpb_sebelumnya = "";
$status_pkb = "";
$status_approval = "";
$final_confirmation = "";
$all_picking_status = "false";
$lokasi_dealer = "";
if (isset($sa)) {
    if (($sa->totaldata > 0)) {
        foreach ($sa->message as $key => $value) {
            $kd_typemotor = substr($value->KD_TYPEMOTOR, 0, 3);
            $kd_sa = $value->KD_SA;
            $no_polisi = $value->NO_POLISI;
            $no_rangka = $value->NO_RANGKA;
            $no_mesin = $value->NO_MESIN;
            $km_motor = $value->KM_MOTOR;
            $bbm = $value->BENSIN_SAATINI;
            $nama_typemotor = $value->NAMA_PASAR;
            $saran_sa = $value->SARAN_MEKANIK;
            $defaultDealer = $value->KD_DEALER;
            $kd_item = $value->KD_TYPEMOTOR;
            $service_sebelumnya = tglfromSql($value->TANGGAL_PKB);
            $kpb_sebelumnya = $value->JENIS_KPB;
            $jenis_kpb = ($kpb != ''? $kpb : $value->JENIS_KPB);
            $lokasi_dealer = $value->KD_LOKASIDEALER;
            $tgl_beli = tglfromSql($value->TGL_TERIMA);
        }
    }
}
if (isset($pkb)) {
    if (($pkb->totaldata > 0)) {
        foreach ($pkb->message as $key => $value) {
            $kd_typemotor = substr($value->KD_ITEM, 0, 3);
            $kd_sa = $value->KD_SA;
            $no_polisi = $value->NO_POLISI;
            $no_rangka = $value->NO_RANGKA;
            $no_mesin = $value->NO_MESIN;
            $km_motor = $value->KM_MOTOR;
            $bbm = $value->BBM;
            $nama_typemotor = $value->NAMA_TYPEMOTOR;
            $saran = $value->SARAN_MEKANIK;
            $defaultDealer = $value->KD_DEALER;
            $id = $value->ID;
            $no_pkb = $value->NO_PKB;
            $kd_dealer = $value->KD_DEALER;
            $tahun = $value->TAHUN;
            $nama_mekanik = $value->NAMA_MEKANIK;
            $no_antrian = $value->NO_ANTRIAN;
            $jenis_pit = $value->JENIS_PIT;
            $estimasi_mulai = $value->ESTIMASI_MULAI;
            $estimasi_selesai = $value->ESTIMASI_SELESAI;
            $pembelian_motor = $value->PEMBELIAN_MOTOR;
            $alasan_ke_ahass = $value->ALASAN_KE_AHASS;
            $hubungan_dengan_pembawa = $value->HUBUNGAN_DENGAN_PEMBAWA;
            $service_sebelumnya = tglfromSql($value->SERVICE_SEBELUMNYA);
            $keterangan = $value->KETERANGAN;
            $tanggal_pkb = tglfromSql($value->TANGGAL_PKB);
            $kd_item = $value->KD_ITEM;
            $kpb_sebelumnya = $value->JENIS_KPB;
            $jenis_kpb = $value->JENIS_KPB;
            $status_pkb = $value->STATUS_PKB;
            $status_approval = $value->STATUS_APPROVAL;
            $final_confirmation = $value->FINAL_CONFIRMATION;
            $all_picking_status = $value->ALL_PICKING_STATUS;
            $lokasi_dealer = $value->KD_LOKASI;
            $tgl_beli = tglfromSql($value->TGL_TERIMA);
            // $jenis_kpb = ($kpb != ''? $kpb : $value->JENIS_KPB);
        }
    }
}
$cetak_nota = $status_pkb >= 4 && $all_picking_status == 'true'?$status_e:'disabled-action';
$edit = $final_confirmation == 1?'disabled-action':$status_e;
$hide_data = $final_confirmation == 1 ?'hide':'';
?>
<style type="text/css">
    .floatThead-container{
        overflow: inherit !important;
    }
</style>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb(); ?>
        <div class="bar-nav pull-right">
            <a class="btn btn-default" href="<?php echo base_url('pkb/add_pkb'); ?>">
              <i class="fa fa-file-o fa-fw"></i> Add PKB
            </a>
            <a id="submit-btn" type="button" class="btn btn-default <?php echo $edit; ?>">  
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>
            <a id="modal-button" type="button" class="btn btn-default <?php echo $cetak_nota; ?>" onclick='addForm("<?php echo base_url('pkb/print_nota/'.$no_pkb); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">  
                <i class="fa fa-print fa-fw"></i> Print Nota
            </a>
            <a role="button" href="<?php echo base_url("pkb/pkb_list"); ?>" class="btn btn-default <?php echo $status_v; ?>">
                <i class="fa fa-list-ul"></i> List PKB
            </a>
        </div>
    </div>
    <form id="pkbForm" method="post" action="<?php echo base_url("pkb/simpan_pkb"); ?>" autocomplete="off">
    <input type="hidden" id="kd_typemotor" name="kd_typemotor" value="<?php echo $kd_typemotor; ?>">
    <input type="hidden" id="nama_typemotor" name="nama_typemotor" value="<?php echo $nama_typemotor; ?>">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
    <input type="hidden" id="status_pkb" name="status_pkb" value="<?php echo $status_pkb; ?>">
    <input type="hidden" id="status_approval" name="status_approval" value="<?php echo $status_approval; ?>">
    <input type="hidden" id="final_confirmation" name="final_confirmation" value="<?php echo $final_confirmation; ?>">
    <input type="hidden" id="lokasi_dealer" name="lokasi_dealer" value="<?php echo $lokasi_dealer; ?>">
    <div class="col-xs-12 col-sm-4 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-headings">
                <i class="fa fa-motorcycle"></i> Data Motor
            </div>
            <div class="panel-body panel-body-border">
                <div class="row">
                    <div class="col-xs-6 col-sm-5 col-md-5">
                        <div class="form-group">
                            <label>No. PKB</label>
                            <input type="text" class="form-control" id="no_pkb" autocomplete="off" name="no_pkb" placeholder="AUTO GENERATE" value="<?php echo $no_pkb; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-7 col-md-7">
                        <div class="form-group">
                            <label>Kode SA <span class='fd'></span></label>
                            <input type="text" name="kd_sa" value="<?php echo $kd_sa; ?>" <?php echo $kd_sa ? 'readonly' : 'id="kd_sa"';?> class="form-control" placeholder="Masukkan Kode SA" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>No. Polisi <span class="load-form"></span></label>
                            <input type="text" name="no_polisi" id="no_polisi" class="form-control" value="<?php echo $no_polisi; ?>" style="text-transform: uppercase;" placeholder="AB-1234-XX" required>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>No. Rangka <span class="load-form"></span></label>
                            <input type="text" name="no_rangka" id="no_rangka" class="form-control disabled" value="<?php echo $no_rangka; ?>" placeholder="Nomor Rangka" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">    
                        <div class="form-group">
                            <label>No. Mesin <span class="load-form"></span></label>
                            <input type="text" name="no_mesin" id="no_mesin" class="form-control disabled" value="<?php echo $no_mesin; ?>" placeholder="Nomor Mesin" required>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">    
                        <div class="form-group">
                            <label>KM. Motor <span class="load-form"></span></label>
                            <input type="number" name="km_motor" id="km_motor" class="form-control meter" value="<?php echo $km_motor; ?>" placeholder="Kilometer Motor" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-8 col-md-8">    
                        <div class="form-group">
                            <label>Nama Type Motor <span class="load-form"></span></label>
                            <input type="text" name="kd_item" id="kd_item" class="form-control disabled <?php echo $kd_sa ? 'disabled-action' : '';?>" value="<?php echo $kd_item; ?>" placeholder="Nama Type Motor" required>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4">    
                        <div class="form-group">
                            <label>Tahun <span class="load-form"></span></label>
                            <input type="text" name="tahun" id="tahun" class="form-control disabled tahun" value="<?php echo $tahun;?>" placeholder="Tahun" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Tanggal PKB <span class="load-form"></span></label>
                            <div class="input-group input-append date" id="">
                                <input class="form-control" id="tanggal_pkb" name="tanggal_pkb" placeholder="DD/MM/YYYY" value="<?php echo $tanggal_pkb; ?>" type="text"/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Tanggal Pembelian <span class="load-form"></span></label>
                            <div class="input-group input-append date" id="">
                                <input class="form-control" id="tgl_beli" name="tgl_beli" placeholder="DD/MM/YYYY" value="<?php echo $tgl_beli; ?>" type="text"/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-headings">
                <i class="fa fa-wrench"></i> Data Mekanik
            </div>
            <div class="panel-body panel-body-border">
                <div class="row">
                    <div class="col-xs-6 col-sm-7 col-md-7">
                        <div class="form-group">
                            <label>Nama Mekanik</label>
                            <?php if($mekanik_ready->totaldata > 0): ?>
                            <input type="text" id="nama_mekanik" name="nama_mekanik" class="form-control" placeholder="Nama Mekanik" value="<?php echo $nama_mekanik;?>" required>
                            <?php else: ?>
                            <select class="form-control disabled-action" id="nama_mekanik" name="nama_mekanik" required readonly>
                                <option value="" >- Pilih Mekanik -</option>
                                <?php
                                if ($mekanik):
                                    foreach ($mekanik->message as $key => $value) :
                                        if($nama_mekanik!=''):
                                            $default=($nama_mekanik==$value->NIK)?" selected":" ";
                                        else:
                                            $default='';
                                        endif;
                                        ?>
                                        <option value="<?php echo $value->NIK; ?>" <?php echo $default; ?>><?php echo $value->NAMA; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-5 col-md-5">
                        <div class="form-group">
                            <label>No. Antrian</label>
                            <input type="text" name="no_antrian" id="no_antrian" value="<?php echo $no_antrian;?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Jenis KPB <span class="kpb-form"></span></label>
                            <select name="jenis_kpb" id="jenis_kpb" class="form-control <?php echo $kd_sa ? 'disabled-action' : '';?>" required>
                                <option value="">- Pilih Jenis KPB-</option>
                                <option value="NONKPB" <?php echo ($jenis_kpb == 'NONKPB'? 'selected':''); ?> >NON KPB</option>
                                <option value="KPB1" <?php echo ($jenis_kpb == 'KPB1'? 'selected':''); ?> >KPB 1</option>
                                <option value="KPB2" <?php echo ($jenis_kpb == 'KPB2'? 'selected':''); ?> >KPB 2</option>
                                <option value="KPB3" <?php echo ($jenis_kpb == 'KPB3'? 'selected':''); ?> >KPB 3</option>
                                <option value="KPB4" <?php echo ($jenis_kpb == 'KPB4'? 'selected':''); ?> >KPB 4</option>
                            </select>                                
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Jenis PIT</label>
                            <select class="form-control" id="jenis_pit" name="jenis_pit" required>
                                <option value="" >- Pilih Jenis Pit -</option>
                                <?php
                                if ($pit):
                                    foreach ($pit->message as $key => $value) :
                                        if($jenis_pit!=''):
                                            $default=($jenis_pit==$value->KD_PIT)?" selected":" ";
                                        else:
                                            $default='';
                                        endif;
                                        ?>
                                        <option value="<?php echo $value->KD_PIT; ?>" <?php echo $default;?> ><?php echo $value->NAMA_PIT; ?></option>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Est. Waktu Mulai</label>
                            <div class="input-group input-append datetime-mulai" id="datetime">
                                <input class="form-control" id="estimasi_mulai" name="estimasi_mulai" placeholder="HH:MM" value="<?php echo $estimasi_mulai
                                ;?>" type="text" required/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Est. Waktu Selesai</label>
                            <div class="input-group input-append datetime-selesai" id="datetime">
                                <input class="form-control" id="estimasi_selesai" name="estimasi_selesai" placeholder="HH:MM" value="<?php echo $estimasi_selesai;?>" type="text" required/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Keterangan Kendaraan" value="<?php echo $keterangan; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Saran Mekanik <span class="load-form"></span></label>
                            <input type="text" name="saran_mekanik" id="saran_mekanik" class="form-control" value="<?php echo $saran_sa; ?>" placeholder="Masukkan Saran Perbaikan Kendaraan" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-headings">
                <i class="fa fa-circle-o-notch"></i> Kondisi Awal SMH
            </div>
            <div class="panel-body panel-body-border">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                            <label>Pembelian Motor</label>
                            <select name="pembelian_motor" id="pembelian_motor" class="form-control" required>
                                <option value="" >- Pilih Pembelian -</option>
                                <option value="Dealer Sendiri" <?php echo ($pembelian_motor == 'Dealer Sendiri'? 'selected':''); ?> >Dealer Sendiri</option>
                                <option value="Dealer Luar" <?php echo ($pembelian_motor == 'Dealer Luar'? 'selected':''); ?> >Dealer Luar</option>
                            </select>   
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group">
                        <label>Alasan Ke AHAS</label>
                        <select name="alasan_ke_ahass" id="alasan_ke_ahass" class="form-control" required>
                        <option value="" <?php echo (isset($value->ALASAN_KE_AHASS) == "") ? "selected" : ""; ?>>- Pilih Alasan -</option>
                        <option value="Service" <?php echo ($alasan_ke_ahass == "Service") ? "selected" : ""; ?>>Service</option>
                        <option value="inisiatif sendiri" <?php echo ($alasan_ke_ahass == "inisiatif sendiri") ? "selected" : ""; ?>>inisiatif sendiri</option>
                        <option value="sms reminder" <?php echo ($alasan_ke_ahass == "sms reminder") ? "selected" : ""; ?>>sms reminder</option>
                        <option value="telepon reminder" <?php echo ($alasan_ke_ahass == "telepon reminder") ? "selected" : ""; ?>>telepon reminder</option>
                        <option value="stiker reminder" <?php echo ($alasan_ke_ahass == "stiker reminder") ? "selected" : ""; ?>>stiker reminder</option>
                        <option value="lainnya" <?php echo ($alasan_ke_ahass == "lainnya") ? "selected" : ""; ?>>lainnya</option>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Hubungan dengan Pembawa</label>
                            <input type="text" name="hubungan_dengan_pembawa" id="hubungan_dengan_pembawa" class="form-control" value="<?php echo $hubungan_dengan_pembawa;?>" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-8 col-md-8">
                        <div class="form-group">
                            <label>Service Sebelumnya</label>
                            <div class="input-group input-append date" id="">
                                <input class="form-control" id="service_sebelumnya" name="service_sebelumnya" placeholder="DD/MM/YYYY"  type="text" value="<?php echo $service_sebelumnya;?>"/> <!--value="<?php echo date('d/m/Y'); ?>"-->
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label>BBM <span class="load-form"></span></label>
                            <select name="bbm" id="bbm" class="form-control">
                                <option value="" <?php echo ($bbm == "") ? "selected" : ""; ?>>- Pilih BBM -</option>
                                <option value="10" <?php echo ($bbm == "10") ? "selected" : ""; ?>>10%</option>
                                <option value="20" <?php echo ($bbm == "20") ? "selected" : ""; ?>>20%</option>
                                <option value="30" <?php echo ($bbm == "30") ? "selected" : ""; ?>>30%</option>
                                <option value="40" <?php echo ($bbm == "40") ? "selected" : ""; ?>>40%</option>
                                <option value="50" <?php echo ($bbm == "50") ? "selected" : ""; ?>>50%</option>
                                <option value="60" <?php echo ($bbm == "60") ? "selected" : ""; ?>>60%</option>
                                <option value="70" <?php echo ($bbm == "70") ? "selected" : ""; ?>>70%</option>
                                <option value="80" <?php echo ($bbm == "80") ? "selected" : ""; ?>>80%</option>
                                <option value="90" <?php echo ($bbm == "90") ? "selected" : ""; ?>>90%</option>
                                <option value="100" <?php echo ($bbm == "100") ? "selected" : ""; ?>>100%</option>
                            </select>    
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>Saran Mekanik Sebelumnya</label>
                            <input type="text" name="saran_mekanik_sa" id="saran_mekanik_sa" class="form-control" readonly value="<?php echo $saran; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label>KPB Sebelumnya</label>
                            <input type="text" name="kpb_sebelumnya" id="kpb_sebelumnya" class="form-control" readonly value="<?php echo $kpb_sebelumnya; ?>">
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
                <input type="hidden" id="frt" name="frt" class="form-control">
                <input type="hidden" id="part_desc" name="part_desc" class="form-control">
                <input type="hidden" id="kategori_item" name="kategori_item" class="form-control">
                <input type="hidden" id="jenis_item" name="jenis_item" class="form-control">
                <input type="hidden" id="jenis_pkb" name="jenis_pkb" class="form-control">
                <input type="hidden" id="jenis_tr" name="jenis_tr" class="form-control">
                <input type="hidden" id="approval_item" name="approval_item" class="form-control">
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
                    <div class="col-xs-12 col-sm-4 col-md-4">
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
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <label>Diskon <span class="detail-loading"></span></label>
                            <input type="text" name="diskon" id="diskon" min="0" max="100" class="form-control qurency text-center" placeholder="disk%">
                        </div>
                    </div>
<!-- 
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <div class="form-group">
                            <label>Free <span class="detail-loading"></span></label>
                            <input type="checkbox" value="kpb" name="kpb_free" id="kpb_free" class="form-control checkbox-custom-form-control">
                        </div>
                    </div> -->

                    <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                            <label>Harga <span class="detail-loading"></span></label>
                            <div class="input-group">

                                <span class="input-group-addon">
                                    free <input type="checkbox" value="kpb" name="kpb_free" id="kpb_free">
                                </span>

                                <input type="text" name="harga_sp" id="harga_sp" class="form-control qurency text-right" value="" placeholder="Harga Part">
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
                        <tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List PKB Detail</th></tr>
                        <tr>
                            <!-- <th style="width:40px;">No.</th> -->
                            <th style="width:50px;">Aksi</th>
                            <th>Keterangan</th>
                            <th class="text-center" style="width:80px;">Qty</th>
                            <th class="text-justify" style="width:120px;">Harga</th>
                            <th class="text-justify" style="width:120px;">Diskon</th>
                            <th class="text-justify" style="width:150px;">Total Harga</th>
                            <th style="width:100px;">Kategori</th>
                            <th style="width:100px;">KPB Free</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($this->input->get('u')):
                            if($pkb->message[0]->DETAIL_ID != ''):
                            foreach ($pkb->message as $key => $pkb_row):
                            $picking  = $pkb_row->PICKING_STATUS >= 1? 'disabled-action' :$status_e;
                        ?>
                            <tr class="data-<?php echo ($pkb_row->JENIS_ITEM == 'OLI' && $pkb_row->JENIS_PKB == 'KPB' ? 'OLI' : $pkb_row->KATEGORI);?>">
                                <td class='hidden'></td>
                                <td class='text-center'>
                                    <a id="<?php echo $pkb_row->DETAIL_ID; ?>" class='hapus2-item <?php echo $picking.' '.$edit;?>' role='button'><i class='fa fa-trash'></i></a>
                                    <?php if($pkb_row->APPROVAL_ITEM == 0 && $approval > 0):?>
                                    <a id="approval-<?php echo $pkb_row->DETAIL_ID; ?>" class="approval-item" data-id="<?php echo $pkb_row->DETAIL_ID; ?>"><i data-toggle="tooltip" data-placement="left" title="approve" class='fa fa-check'></i></a>
                                    <?php endif;?>
                                </td>
                                <td><?php echo $pkb_row->KD_PEKERJAAN." - ".$pkb_row->PART_DESKRIPSI;?>
                                    <?php if($pkb_row->PICKING_STATUS >= 1 && $pkb_row->KATEGORI == 'Part') echo '<span class="badge pull-right" title="Stock On Hand">picking</span>';?>
                                </td>
                                <td class='text-right'><?php echo $pkb_row->QTY;?></td>
                                <td class='text-right qurency'><?php echo number_format($pkb_row->HARGA_SATUAN);?></td>
                                <td class='text-right qurency'><?php echo number_format($pkb_row->DISKON);?></td>
                                <td class='text-right qurency'><?php echo number_format($pkb_row->TOTAL_HARGA);?></td>
                                <td class='text-right'><?php echo $pkb_row->KATEGORI;?></td>
                                <td class='hidden'></td>
                                <td class='text-right hidden'><?php echo $pkb_row->JENIS_ITEM;?></td>
                                <td class='text-left'><?php echo $pkb_row->JENIS_PKB;?></td>
                                <td class='text-right hidden'><?php echo $pkb_row->APPROVAL_ITEM;?></td>
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
<script type="text/javascript" src="<?php echo base_url("assets/js/external/pkb.js");?>"></script>
<script type="text/javascript">
var path = window.location.pathname.split('/');
var http = window.location.origin + '/' + path[1];
$(document).ready(function () {
    var date = new Date();

    noSa();

    generateStock();

    date.setDate(date.getDate());
    $('.datetime-mulai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });
    $('.datetime-selesai').datetimepicker({
        format: 'LT',
        locale: 'ru'
    });

    $(".approval-item").click(function(){
        var detail_id = $(this).data('id');
        var defaultBtn = $(this).html();
        
        $(this).html("<i class='fa fa-spinner fa-spin'></i>");

        $.getJSON(http+"/pkb/approval_item",{'detail_id':detail_id},function(result){
            if(result.status == true){

                $('.success').animate({top: "0"}, 500);
                $('.success').html(result.message).fadeIn();
                setTimeout(function () {
                    $("#approval-"+detail_id).remove();
                    hideAllMessages();
                }, 1000);
            }
            else{
                $('.error').animate({top: "0"}, 500);
                $('.error').html(result.message);
                setTimeout(function () {
                    $("#approval-"+detail_id).html(defaultBtn);
                    hideAllMessages();
                }, 2000);
            }
        });
    })

    $('#kd_sa').on("change",function(){
       var kd_sa = $(this).val();
       var url = http+'/pkb/get_sa';
       $(".load-form").html("<i class='fa fa-spinner fa-spin'></i>");
       $.getJSON(url,{
         kd_sa:kd_sa
       }, function(data, status){
         if(data.sa_header.status){

            var tgl_service = tgl_fromsql(data.sa_header.message['0'].TANGGAL_PKB);
            var tgl_terima  = tgl_fromsql(data.sa_header.message['0'].TGL_TERIMA);
            var tgl_beli    = tgl_fromsql(data.sa_header.message['0'].TGL_BELI);

            var str = data.sa_header.message['0'].KD_TIPEPKB; 
            var tipe_kpb = str.replace(" ", "");
            // alert(tipe_kpb);

            if(tipe_kpb == 'KPB1' || tipe_kpb == 'KPB2' || tipe_kpb == 'KPB3' || tipe_kpb == 'KPB4'){

                tipe_kpb = tipe_kpb;

            }else{
                tipe_kpb = 'NONKPB';
            }

           console.log(data.sa_header.message['0'].KD_TYPEMOTOR);
           $('#kd_item').val(data.sa_header.message['0'].KD_TYPEMOTOR);
           $('#no_polisi').val(data.sa_header.message['0'].NO_POLISI);
           $('#no_rangka').val(data.sa_header.message['0'].NO_RANGKA);
           $('#no_mesin').val(data.sa_header.message['0'].NO_MESIN);
           $('#km_motor').val(data.sa_header.message['0'].KM_SAATINI);
           $('#bbm').val(data.sa_header.message['0'].BENSIN_SAATINI);
           $('#nama_typemotor').val(data.sa_header.message['0'].NAMA_PASAR);
           $('#saran_mekanik').val(data.sa_header.message['0'].SARAN_MEKANIK);
           $('#saran_mekanik_sa').val(data.sa_header.message['0'].SARAN_MEKANIK);
           $('#service_sebelumnya').val(tgl_service);
           $('#kpb_sebelumnya').val(data.sa_header.message['0'].JENIS_KPB);
           $('#jenis_kpb').val(tipe_kpb);
           // $('#jenis_kpb').val(data.kpb);
           $('#lokasi_dealer').val(data.sa_header.message['0'].KD_LOKASIDEALER);
           $('#tahun').val(data.sa_header.message['0'].TAHUN);

           $('#tgl_beli').val(tgl_beli);
           // alert(data.kpb);
           $('#pkb_list > tbody').html('');

            var totalDetail = data.sa_header.message.length;
            var totalResult = 0;

            // alert(data.sa_header.message['0'].KD_PEKERJAAN);
            
            __getBarangSP();

            if(data.sa_header.message['0'].KD_PEKERJAAN != null){
                $.each(data.sa_header.message,function(e,d){

                    $('#kategori').val(d.KATEGORI);
                    $('#kd_part').val(d.KD_PEKERJAAN);

                    __getBarangSP();


                    var ajaxDetali = getDetailpkb2(d.KATEGORI ,d.KD_PEKERJAAN);
                    // $(document).ajaxStop(function() {

                    // });

                })
            }




         }
         $(".load-form").html('');
         __getMotor();
       });
    });

});


function getDetailpkb2(kd_kategori ,data_number)
{
    $(".detail-loading").html("<i class='fa fa-spinner fa-spin'></i>");

    var url = http+"/pkb/part_jasa/"+kd_kategori+"/true";
    
    // alert(data_number)

    // var url = (kd_kategori == 'Part' ? http+"/sparepart/hargapart/true":http+"/pkb/hargajasa");
    $.getJSON(url,{"data_number":data_number},function(result){
      console.log(result);
      $.each(result,function(e,d){
        var harga_jual=0;
        var cek_oli = $(".data-OLI").length;
        var jenis_kpb = $("#jenis_kpb").val();

        harga_jual = d.DATA_HARGA;


        $('#kategori_item').val($("#kategori").val());
        $('#part_desc').val(d.DATA_DESKRIPSI);
        $('#kd_part').val(d.DATA_NUMBER);
        $('#qty').val("1");
        $('#harga_sp').attr('min',parseFloat(harga_jual));     
        $('#harga_sp').val(parseFloat(harga_jual));     
        // $('#harga_sp').mask("#.##0",{reverse: true});
        $('#jenis_item').val(d.JENIS_ITEM);


        $("#kpb_free").prop('checked', false);

        $('#frt').val(d.FRT);
        $('#jenis_tr').val($("#kategori").val());
        $('#approval_item').val(1);
        $('#jenis_pkb').val("REGULER");


        if((d.JENIS_ITEM == 'OLI' && cek_oli <= 0) || (d.JENIS_ITEM == 'ASS' && jenis_kpb != 'NONKPB')){
          if(d.JENIS_ITEM == 'OLI'){
            cekOli2(data_number);
          }
          else{
            $('#jenis_pkb').val("KPB");
            $("#kpb_free").prop('checked', true);
            $(".detail-loading").html("");
            __addItem();
          }
          
        }
        else{
          $('#jenis_pkb').val("REGULER");

          $(".detail-loading").html("");
            __addItem();
        }
      })
    })
}

function cekOli2(data_number)
{
  // alert(data_number);
  var item = $("#kd_item").val();
  var kd_item = 'null';
  var jenis_kpb = $("#jenis_kpb").val();

  if(item != undefined && item != ''){
    var split = item.split("-");
    kd_item = split[0];
  }
  var mesin = $("#no_mesin").val();
  var no_mesin = mesin.substr(0,5);
  if(jenis_kpb != 'NONKPB' && kd_item != 'null'){
    var url_kpb = http+"/pkb/get_kpbpart";
    var kpb_ke = jenis_kpb.slice(-1);
    
    // console.log(kd_item);
    $.getJSON(url_kpb,{'part_number':no_mesin, 'kd_typemotor':kpb_ke},function(result){
      // console.log(result);
      // alert(result.metode4.status);
      if(result.metode4.status == true){
        // alert('test');
        $.each(result.metode4.message,function(e,d){
          if(d.PART_NUMBER == data_number){
            var kategori = d.KATEGORI == 'OLI' ? 'Part' : 'Jasa';

            $('#kategori_item').val(kategori);
            $('#frt').val(0);
            $('#kd_part').val(d.PART_NUMBER);
            $('#part_desc').val(d.PART_DESKRIPSI);
            $('#qty').val(d.JUMLAH);
            $('#qty').attr('readonly','readonly');
            $('#harga_sp').attr('min',parseFloat(d.HARGA));     
            $('#harga_sp').val(parseFloat(d.HARGA));     
            // $('#harga_sp').mask("#.##0",{reverse: true});
            $('#jenis_item').val(d.KATEGORI);

            $('#jenis_pkb').val("KPB");
            $('#jenis_tr').val(d.KATEGORI);
            $("#kpb_free").prop('checked', true);
            
            $('#approval_item').val(1);
            __addItem();
          }
        });

      }
      
      $(".detail-loading").html("");
    });

  }
  else{
    $(".detail-loading").html("");
  }
  
}

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

function noSa(){

    $('#kd_sa').inputpicker({
      url:http+"/pkb/get_nosa",
      // urlParam:{"kd_item":kd_item},
      fields:['KD_SA','NO_POLISI'],
      fieldText:'KD_SA',
      fieldValue:'KD_SA',
      filterOpen: true,
      headShow:true,
      pagination: true,
      pageMode: '',
      pageField: 'p',
      pageLimitField: 'per_page',
      limit: 15,
      pageCurrent: 1,
      // urlDelay:2
    });


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