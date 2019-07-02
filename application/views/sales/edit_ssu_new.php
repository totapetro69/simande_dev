<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
   }
   $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
   $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
    $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
   $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
   $defaultDealer=$this->session->userdata("kd_dealer");
   $tabaktife = $this->input->get("tab");
   $spk_id='';$status_spk   ='';   $kd_dealer    ='';$no_rangka    ='';
   $no_mesin1    ='';$no_mesin2    ='';   $no_mesin     ='';$nama_bpkb    ='';
   $alamat_bpkb  ='';$nama_kelurahan  ='';   $nama_kecamatan  ='';$kd_kabupaten ='';
   $kode_pos  ='';$kd_propinsi  ='';   $jenis_pembelian ='';$kd_leasing   ='';$uang_muka='';$jangka_waktu ='';
   $honda_id  ='';$kd_posahm ='';$ktp_bpkb  ='';$email_bpkb   ='';$jml_angsuran="";
   $jn_beli ="";
   if(isset($udstk)){
      if($udstk->totaldata > 0){
         foreach ($udstk->message as $key => $value) {
            $spk_id    = $value->SPK_ID;
            $status_spk= $value->STATUS_SPK;
            $kd_dealer = $value->KD_DEALER;
            $no_rangka = $value->NO_RANGKA;
            $no_mesin1 = $value->NO_MESIN1;
            $no_mesin2 = $value->NO_MESIN2;
            $no_mesin  = $value->NO_MESIN;
            $nama_bpkb = $value->NAMA_BPKB;
            $alamat_bpkb= $value->ALAMAT_BPKB;
            $nama_kelurahan= $value->NAMA_KELURAHAN;
            $nama_kecamatan= $value->NAMA_KECAMATAN;
            $kd_kabupaten= $value->KD_KABUPATEN;
            $kode_pos= $value->KODE_POS;
            $kd_propinsi= $value->KD_PROPINSI;
            $jenis_pembelian= $value->JENIS_PEMBELIAN;
            $jn_beli =($value->JENIS_PEMBELIAN=='1')?"CASH":"KREDIT";
            $kd_leasing= $value->KD_LEASING;
            $uang_muka= $value->UANG_MUKA;
            $jangka_waktu= $value->JANGKA_WAKTU;
            $honda_id= $value->HONDA_ID;
            $kd_posahm= $value->KD_POSAHM;
            $ktp_bpkb= $value->KTP_BPKB;
            $email_bpkb= $value->EMAIL_BPKB;
            $jml_angsuran = $value->JUMLAH_ANGSURAN;
         }
      }
   }   

?>
<section class="wrapper">
   <!-- breadcrume -->
   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb();?>
      <!-- Button Header -->
      
      <div class="bar-nav pull-right ">
         <a class="btn btn-default hidden-xs" onclick="__update_ssu();" role="button"><i class="fa fa-cogs"> Update SSU</i></a>
         <a class="btn btn-default" href="<?php echo base_url("ssu/transaksi_ssu");?>" role="button"><i class='fa fa-list-ul'></i> List SSU</a>
      </div>
   </div>
   <!-- SSU Header -->
<form id="frm_ssu" class="bucket-form ">
   <div class="col-xs-12 col-md-12 col-sm-12 padding-left-right-10 ">
      <div class="panel margin-bottom-10">
         <div class="panel-heading panel-custom">
            <div class="row">
               <div class="col-sm-4">
                  <h4 class="panel-title pull-left" style="padding-top: 10px;">
                     <i class='fa fa-file-o fa-fw'></i> SSU Header
                  </h4>
               </div>
            </div>
         </div>
         <div class="panel-body panel-body-border hidden-xs ">
            <!-- <form id="addForm" action="#" class="bucket-form" method="post"> -->
               <div class="row">
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>Dealer <span id="lgd"></span></label>
                        <select name="kd_dealer" id="kd_dealer" class="form-control disabled-action"  required="true">
                           <option value="">-- Pilih Dealer --</option>
                           <?php
                           $defaultDealer=$defaultDealer;
                           if($dealer){
                             if(is_array($dealer->message)){
                               foreach ($dealer->message as $key => $value){
                                 $select=($defaultDealer==$value->KD_DEALER )?" selected":"";
                                 echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                               }
                             }
                           }
                           ?>
                        </select>
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>No Mesin</label>
                        <input type="text" name="no_mesin" id="no_mesin" value='<?php echo $no_mesin;?>' class="form-control disabled-action" placeholder="Masukkan No Mesin">
                     </div>
                  </div>
                  <div class="col-xs-6 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>No Rangka</label>
                        <input type="text" value='<?php echo $no_rangka;?>' id="no_rangka" name="no_rangka" class="form-control disabled-action" placeholder="Masukkan No Rangka">
                     </div>
                  </div>
                  <input type="hidden" name="spk_id" value='<?php echo $spk_id;?>'>
                  <input type="hidden" name="status_spk" value='<?php echo $status_spk;?>'>
                  <input type="hidden" name="no_mesin1" value='<?php echo $no_mesin1;?>'>
                  <input type="hidden" name="no_mesin2" value='<?php echo $no_mesin2;?>'>
               </div>
            <!-- </form> -->
         </div>
      </div>
   </div>
   <!-- UDSTK -->
   <div class="col-xs-12 col-md-12 col-sm-12 padding-left-right-10 hidden-xs">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            <i class="fa fa-file-o"></i> SSU Detail
            <span class="tools pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
         </div>
         <div class="panel-body panel-body-border">
            
            <!-- button bar  -->
            <input type="hidden" id="tabaktif" value="<?php echo $tabaktife;?>">
            <input type="hidden" id="autogb" value="<?php echo $this->input->get("g");?>">
            <!-- Nav Bar / tab -->
            <div class="row">
               <div class="col-sm-12">
                  <ul class="nav nav-tabs" role="tablist">
                     <li role="presentation" <?php echo ($tabaktife=="" || $tabaktife=="1")? " class='active tbs'":" tbs";?>>
                        <a href="#tabs-1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-envelope fa-fw"></i> UDSTK </a>
                     </li>
                     <li role="presentation" <?php echo ($tabaktife=="2")? " class='active tbs'":" tbs";?>>
                        <a href="#tabs-2" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-star fa-fw"></i> CDDB </a>
                     </li>
                     <li role="presentation" <?php echo ($tabaktife=="3")? " class='active tbs'":" tbs";?>>
                        <a href="#tabs-3" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-pencil fa-fw"></i> UDPRG</a>
                     </li>
                    
                  </ul>
                  <input type="hidden" name="tabaktif" id="tabaktif">
               </div>
            </div>
            <!-- </form> -->
            <!-- Tab panes -->
            <div class="tab-content spklock">
               <!-- panel UDSTK -->
               <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="" || $tabaktife=="1")? "active":"";?>" id="tabs-1">
                  <fieldset>
                     <!-- <form id="frm_udstk" action="<?php echo base_url('transaksi/update_udstk') ?>" class="bucket-form" method="post"> -->
                        <!-- baris ke 1 -->
                        <div class="row">                      
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Nama di BPKB&nbsp;</label>
                                 <input type="text" name="nama_udstk" id="nama_dibpkb" class="form-control" placeholder="Masukkan nama sesuai ktp" value="<?php echo $nama_bpkb;?>">
                              </div>
                           </div>
                           <div class="col-xs-hidden col-sm-6 col-md-6">
                              <div class="form-group">
                                 <label>Alamat BPKB &nbsp;<span id="alamat_lg"></span></label>
                                 <textarea name="alamat_udstk" id="alamat_bpkb" rows="1" class="form-control" placeholder="Masukkan Alamat Customer"  required="required"><?php echo $alamat_bpkb;?></textarea>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Kelurahan/Desa <span id="l_desa"></span></label>
                                 <input name="kd_desa_udstk" id="kd_desa" value="<?php echo $nama_kelurahan;?>" class="form-control" title="desa" required="required">
                              </div>
                           </div>
                        </div>
                        <!-- Baris ke 2 -->
                        <div class="row">
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Propinsi</label>
                                 <select name="kd_propinsi_udstk" id="kd_propinsi" class="form-control" required="required">
                                    <option value="0">-- Pilih Propinsi --</option>
                                    <?php
                                    if ($propinsi) {
                                       if (is_array($propinsi->message)) {
                                          foreach ($propinsi->message as $key => $value) {
                                             $pilih =($kd_propinsi == $value->KD_PROPINSI)?'selected':'';
                                             echo "<option value='" . $value->KD_PROPINSI . "' ".$pilih.">" . $value->NAMA_PROPINSI . "</option>";
                                          }
                                       }
                                    }
                                    ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                             <div class="form-group">
                               <label>Kabupaten/Kota<span id="l_kabupaten"></span></label>
                               <select name="kd_kabupaten_udstk" id="kd_kabupaten" class="form-control" title="kabupaten" required="required">
                                 <option value="0">-- Pilih Kabupaten/Kota --</option>
                                 <!-- <option value="">Tobasa</option> -->
                               </select>
                             </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                             <div class="form-group">
                               <label>Kecamatan<span id="l_kecamatan"></span></label>
                               <input name="kd_kecamatan_udstk" id="kd_kecamatan" value="<?php echo $nama_kecamatan;?>" class="form-control" title="kecamatan" required="required">
                             </div>
                           </div>
                           <div class="col-xs-3 col-sm-1 col-md-1">
                              <div class="form-group">
                                 <label>Kode Pos</label>
                                 <input type="text" name="kode_pos_udstk" id="kode_pos" value="<?php echo $kode_pos;?>" data-mask="00000" class="form-control" placeholder="Masukkan kode Pos">
                              </div>  
                           </div>
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode PosAHM</label>
                                 <input type="text" name="kd_posahm_udstk" id="kd_posahm" value="<?php echo $kd_posahm;?>"  class="form-control" placeholder="Masukkan Kode Pos AHM">
                              </div>
                           </div>
                        </div>
                         <!-- baris ke 4 -->
                        <div class="row">
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>HondaID</label>
                                 <input type="mail" name="hondaid_udstk" id="hondaid" value="<?php echo $honda_id;?>" class="form-control" placeholder="Masukkan Honda ID">
                              </div>
                           </div>
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Jenis Pembelian</label>
                                 <div class="input-group">
                                    <input type="text" name="jenis_beli_udstk" value="<?php echo $jenis_pembelian;?>" id="jenis_beli" class="form-control" placeholder="Masukkan Jenis Pembelian">
                                    <span class="input-group-addon"><?php echo $jn_beli;?></span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>No KTP BPKB</label>
                                 <input type="text" name="ktp_bpkb_udstk" value="<?php echo $ktp_bpkb;?>" class="form-control">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-3 col-sm-3">
                              <div class="form-group">
                                 <label>e-Mail</label>
                                 <input type="text" name="email_udstk" value="<?php echo $email_bpkb;?>" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Leasing</label>
                                 <input type="mail" name="kd_leasing_udstk" id="kd_leasing" value='<?php echo $kd_leasing;?>'  class="form-control" placeholder="Masukkan Kode Leasing">
                              </div>
                           </div>
                           <div class="col-xs-3 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Jangka Waktu</label>
                                 <input type='text' id="jangka_waktu_udstk" name="jangka_waktu" value="<?php echo $jangka_waktu;?>" class="form-control">
                              </div>
                           </div>
                           <div class="col-xs-3 col-md-2 col-sm-2">
                             <div class="form-group">
                                 <label>Uang Muka</label>
                                 <input type="text" name="uang_muka_udstk" value="<?php echo $uang_muka;?>" id="uang_muka" class="form-control" placeholder="Masukkan Uang Muka" >                   
                              </div>
                           </div>
                           <div class="col-xs-4 col-sm-2 col-md-2">
                             <div class="form-group">
                               <label>Jml Angsuran</label>
                               <input type="text" name="jml_angsuran_udstk" id="jml_angsuran" value="<?php echo $jml_angsuran;?>" class="form-control" placeholder="Masukkan Jumlah Angsuran">
                             </div>
                           </div>
                        </div>
                     <!-- </form> -->
                  </fieldset>
               </div>
               <!-- panel detail CDDB -->
               <?php
                  $spk_id="";$status_spk="";$kd_dealer="";$no_rangka="";$no_mesin1="";$no_mesin2="";$no_mesin="";$no_ktp="";
                  $kd_customer="";$jenis_kelamin="";$tgl_lahir="";$alamat_surat="";$kd_desa="";$nama_desa_cddb="";
                  $nama_kecamatan_cddb="";$kd_kecamatan="";$kd_agama="";$email="";$status_rumah="";$status_hp="";
                  $status_dihubungi="";$akun_fb="";$twitter="";$instagram="";$youtube="";$hobi="";$keterangan="";
                  $kartu_keluarga="";$wni="";$reff_id="";$robb_id="";$pekerjaan="";$pengeluaran="";
                  $pendidikan="";$pic_perusahaan="";$no_hp="";$no_telp="";$informasi_baru="";$merk_motor="";
                  $jenis_motor="";$digunakan_untuk="";$yang_menggunakan="";$kd_sales="";$kode_pos="";
                  $nama_customer=$nama_bpkb;

                  if(isset($cddb)){
                     if($cddb->totaldata > 0){
                        foreach ($cddb->message as $key => $value) {
                           $spk_id= $value->SPK_ID;
                           $status_spk= $value->STATUS_SPK;
                           $kd_dealer= $value->KD_DEALER;
                           $no_rangka= $value->NO_RANGKA;
                           $no_mesin1= $value->NO_MESIN1;
                           $no_mesin2= $value->NO_MESIN2;
                           $no_mesin= $value->NO_MESIN;
                           $no_ktp= $value->NO_KTP;
                           $kd_customer= $value->KD_CUSTOMER;
                           $nama_customer =(trim($nama_customer))?$nama_customer: $value->NAMA_CUSTOMER;
                           $jenis_kelamin= $value->JENIS_KELAMIN;
                           $tgl_lahir= $value->TGL_LAHIR;
                           $alamat_surat= $value->ALAMAT_SURAT;
                           $nama_desa_cddb= $value->NAMA_DESA;
                           $nama_kecamatan_cddb= $value->NAMA_KECAMATAN;
                           //$kd_kota= $value->KD_KOTA;
                           //$kd_propinsi= $value->KD_PROPINSI;
                           $kd_agama= $value->KD_AGAMA;
                           $email= $value->EMAIL;
                           $status_rumah= $value->STATUS_RUMAH;
                           $status_hp= $value->STATUS_HP;
                           $status_dihubungi= $value->STATUS_DIHUBUNGI;
                           $akun_fb= $value->AKUN_FB;
                           $twitter= $value->TWITTER;
                           $instagram= $value->INSTAGRAM;
                           $youtube= $value->YOUTUBE;
                           $hobi= $value->HOBI;
                           $keterangan= $value->KETERANGAN;
                           $kartu_keluarga = $value->KARTU_KELUARGA;
                           $wni= $value->WNI;
                           $reff_id= $value->REFF_ID;
                           $robb_id= $value->ROBB_ID;
                           $pekerjaan= $value->PEKERJAAN;
                           $pengeluaran= $value->PENGELUARAN;
                           $pendidikan= $value->PENDIDIKAN;
                           $pic_perusahaan= $value->PIC_PERUSAHAAN;
                           $no_hp= $value->NO_HP;
                           $no_telp= $value->NO_TELP;
                           $informasi_baru= $value->INFORMASI_BARU;
                           $merk_motor= $value->MERK_MOTOR;
                           $jenis_motor= $value->JENIS_MOTOR;
                           $digunakan_untuk= $value->DIGUNAKAN_UNTUK;
                           $yang_menggunakan= $value->YANG_MENGGUNAKAN;
                           $kd_sales= $value->KD_SALES;
                           $kode_pos = $value->KODE_POS;
                        }
                     }
                  }
               ?>
               <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="2")? "active":"";?>" id="tabs-2">
                  <fieldset>
                     <!-- <form id="frm_cddb" action="<?php echo base_url('transaksi/update_cddb') ?>" class="bucket-form" method="post"> -->
                        <!-- baris ke 1 -->
                        <div class="row">
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Nama Customer</label>
                                 <input type="text" id="nama_customer" name="nama_cddb" value="<?php echo $nama_customer;?>" class="form-control disabled-action">
                                 <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $kd_customer;?>">
                                 <input type="hidden" id="guest_no" name="guest_no" value="">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Nomor KTP/Identitas</label>
                                 <input type="text" name="nomor_ktp_cddb" id="nomor_ktp" class="form-control" placeholder="Masukkan Nomor KTP/Identitas" value='<?php echo $no_ktp;?>' minlength="16" maxlength="16"  required="required">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-4 col-md-4">
                              <div class="form-group">
                                 <label>Alamat Customer &nbsp;<span id="alamat_lg"></span></label>
                                 <textarea name="alamat_cddb" id="alamat_cust" rows="1" class="form-control" placeholder="Masukkan Alamat Customer"  required="required"><?php echo $alamat_surat;?></textarea>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Kelurahan/Desa <span id="l_desa"></span></label>
                                 <input name="kd_desa_cddb" id="kd_desa_cddb" value="<?php echo $nama_desa_cddb;?>" class="form-control" title="desa">
                              </div>
                           </div>
                        </div>
                        <!-- Baris ke 3 -->
                        <div class="row">
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Provinsi</label>
                                 <select name="kd_propinsi_cddb" id="kd_propinsi" class="form-control" required="required">
                                    <option value="0">-- Pilih Provinsi --</option>
                                    <?php
                                    if ($propinsi) {
                                       if ($propinsi->totaldata >0) {
                                          foreach ($propinsi->message as $key => $value) {
                                             $pilih =($kd_propinsi==$value->KD_PROPINSI)?"selected":"";
                                             echo "<option value='" . $value->KD_PROPINSI . "' ".$pilih.">" . $value->NAMA_PROPINSI . "</option>";
                                          }
                                       }
                                    }
                                   ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kabupaten/Kota<span id="l_kabupaten"></span></label>
                                 <select name="kd_kabupaten_cddb" id="kd_kabupaten_cddb" class="form-control" title="kabupaten" required="required">
                                    <option value="0">-- Pilih Kabupaten/Kota --</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Kecamatan<span id="l_kecamatan"></span></label>
                                 <input name="kd_kecamatan_cddb" id="kd_kecamatan_cddb" value="<?php echo $nama_kecamatan_cddb;?>" class="form-control" title="kecamatan">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Pos</label>
                                 <input type="text" name="kode_pos_cddb" id="kode_pos" value="<?php echo $kode_pos;?>" data-mask="00000" class="form-control" placeholder="Masukkan kode Pos">
                              </div>  
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Agama</label>
                                 <select name="kd_agama_cddb" id="kd_agama" class="form-control">
                                    <option value="">-- Pilih Agama --</option>
                                    <?php
                                    if($agamas){
                                       if($agamas->totaldata){
                                          foreach ($agamas->message as $key => $value) {
                                             $pilih =($kd_agama == $value->KD_AGAMA)?"selected":"";
                                             echo "<option value='".$value->KD_AGAMA."' ".$pilih.">".$value->NAMA_AGAMA."</option>";
                                          }
                                       }
                                    }
                                    ?>
                                 </select>
                              </div>
                           </div>
                        </div>
                        <!-- baris ke 4 -->
                        <div class="row">
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Tanggal Lahir</label>
                                 <input type="text" name="tgl_lahir_cddb" id="tgl_lahir" value="<?php echo $tgl_lahir;?>" data-mask="00/00/0000" class="form-control"  placeholder="dd/mm/yyyy" value=''>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Jenis Kelamin</label>
                                 <select name="kd_jeniskelamin_cddb" id="kd_jeniskelamin" class="form-control">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="1" <?php echo ($jenis_kelamin=="1")?'selected':'';?>>Laki - Laki</option>
                                    <option value="2" <?php echo ($jenis_kelamin=="2")?'selected':'';?>>PEREMPUAN</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Email</label>
                                 <input type="mail" name="email_customer_cddb" id="email_customer" value='<?php echo $email;?>' class="form-control" placeholder="Masukkan Email">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Nomor HP</label>
                                 <input type="text" name="no_hp_cddb" id="no_hp" value="<?php echo $no_hp;?>" class="form-control" placeholder="Masukkan Nomor Telepon/HP">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Nomor Telpon</label>
                                 <input type="text" name="no_telp_cddb" id="no_hp" value="<?php echo $no_telp;?>" class="form-control" placeholder="Masukkan Nomor Telepon/HP">
                              </div>
                           </div>
                        </div>
                        <!-- baris ke 5 -->
                        <div class="row">
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>PIC Perusahaan</label>
                                 <input type="text" name="pic_perusahaan_cddb" id="pic_perusahaan" value="<?php echo $pic_perusahaan;?>" class="form-control" placeholder="Masukkan PIC Perusahaan">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Status Rumah</label>
                                 <select type="text" name="status_rumah_cddb" id="status_rumah"  class="form-control">
                                    <option value="0">--Pilih Status--</option>
                                    <option value="1" <?php echo ($status_rumah=="1")?'selected':'';?>>Milik Sendiri</option>
                                    <option value="2" <?php echo ($status_rumah=="2")?'selected':'';?>>Milik Orang Tua</option>
                                    <option value="3" <?php echo ($status_rumah=="3")?'selected':'';?>>Kontrak</option>
                                    <option value="4" <?php echo ($status_rumah=="4")?'selected':'';?>>Lainnya</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Status HP</label>
                                 <select type="text" name="status_hp_cddb" id="status_hp" class="form-control">
                                    <option value="">-- Pilih Status HP --</option>
                                    <option value="1" <?php echo ($status_hp=="1")?'selected':'';?>>Pra Bayar (Isi Ulang)</option>
                                    <option value="2" <?php echo ($status_hp=="2")?'selected':'';?>>Pasca Bayar /Billing/Tagihan</option>
                                </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>WNI/WNA</label>
                                 <div class="input-group">
                                    <input type="text" name="kebangsaan_cddb" id="kebangsaan" value="<?php echo $wni;?>" class="form-control" placeholder="Masukkan Kode WNI / WNA">
                                    <span class="input-group-addon"><?php echo ($wni=='2')?'WNA':'WNI';?></span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>NO Kartu Keluarga</label>
                                 <input type="text" name="kartu_keluarga_ccdb" id="no_kk" value="<?php echo $kartu_keluarga;?>" class="form-control" placeholder="Masukkan NO KK" maxlength="16">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-6 col-md-3 col-sm-3">
                              <div class="form-group">
                                <label>Facebook</label>
                                <span class="icon-search"></span>
                                <input type="text" name="kd_facebook_cddb" id="kd_facebook" value="<?php echo $akun_fb;?>" class="form-control" placeholder="Masukkan URL Facebook">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-3 col-sm-3">
                              <div class="form-group">
                                <label>Twitter</label>
                                <input type="text" name="kd_twiter_cddb" id="kd_twiter" value="<?php echo $twitter;?>" class="form-control" placeholder="Masukkan URL Twitter">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-2 col-sm-2">
                              <div class="form-group">
                                <label>Instagram</label>
                                <input type="text" name="kd_instagram_cddb" id="kd_instagram" value="<?php echo $instagram;?>" class="form-control" placeholder="Masukkan URL Instagram">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-2 col-sm-2">
                              <div class="form-group">
                                <label>Youtube</label>
                                <input type="text" name="kd_youtube_cddb" id="kd_youtube" value="<?php echo $youtube;?>" class="form-control" placeholder="Masukkan URL Youtube">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-2 col-sm-2">
                              <div class="form-group">
                                <label>Hobby</label>
                                <select name="kd_hobby_cddb" id="kd_hobby_cddb" class="form-control" required="true">
                                  <option value=""> -- Pilih Hobby --</option>
                                  <?php 
                                    if(isset($hobbyne)){
                                      if($hobbyne->totaldata > 0){
                                        foreach ($hobbyne->message as $key => $value) {
                                          $select=($hobi==$value->KD_HOBBY)?"selected":"";
                                          echo "<option value='".$value->KD_HOBBY."' ".$select.">".$value->NAMA_HOBBY." [".$value->KD_HOBBY."]</option>";
                                        }
                                      }
                                    }
                                  ?>
                                </select>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Pekerjaan</label>
                                 <select name="pekerjaan_cddb" id="pekerjaan" class="form-control">
                                   <option value="">-- Pilih Data Pekerjaan --</option>
                                   <?php
                                     if($pekerjaans){
                                       if(is_array($pekerjaans->message)){
                                         foreach ($pekerjaans->message as $key => $value) {
                                           $select=($pekerjaan==$value->KD_PEKERJAAN)?"selected":"";
                                          echo "<option value='".$value->KD_PEKERJAAN."' ".$select.">".$value->NAMA_PEKERJAAN."</option>";
                                         }
                                       }
                                     }
                                   ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Pengeluaran / Bulan</label>
                                 <select name="pengeluaran_cddb" id="pengeluaran" class="form-control">
                                   <option value="">-- Pilih Pengeluaran Bulanan --</option>
                                     <option value="1" <?php echo ($pengeluaran=="1")?"selected":"";?>><= 900.000</option>
                                     <option value="2" <?php echo ($pengeluaran=="2")?"selected":"";?>>Rp. 900.001 s/d Rp. 1.250.000</option>
                                     <option value="3" <?php echo ($pengeluaran=="3")?"selected":"";?>>Rp. 1.250.001 s/d Rp. 1.759.000</option>
                                     <option value="4" <?php echo ($pengeluaran=="4")?"selected":"";?>>Rp. 1.759.001 s/d Rp. 2.500.000</option>
                                     <option value="5" <?php echo ($pengeluaran=="5")?"selected":"";?>>Rp. 2.500.001 s/d Rp. 4.000.000</option>
                                     <option value="6" <?php echo ($pengeluaran=="6")?"selected":"";?>>Rp. 4.000.001 s/d Rp. 6.000.000</option>
                                     <option value="7" <?php echo ($pengeluaran=="7")?"selected":"";?>> 6.000.000</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Pendidikan</label>
                                 <select name="pendidikan_cddb" id="pendidikan" class="form-control">
                                   <option value="">-- Pilih Pendidikan Terakhir --</option>
                                   <?php
                                    if(isset($pendidikans)){
                                       if($pendidikans->totaldata >0){
                                         foreach ($pendidikans->message as $key => $value) {
                                             $pilih =($pendidikan== $value->KD_PENDIDIKAN)?'selected':'';
                                             echo "<option value='".$value->KD_PENDIDIKAN."' ".$pilih.">".$value->NAMA_PENDIDIKAN."</option>";
                                         }
                                       }
                                    }
                                   ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-3 col-sm-3">
                              <div class="form-group">
                                 <label>Bersedia Dihubungi</label>
                                 <select id="siap_dihubungi_cddb" name="siap_dihubungi_cddb" class="form-control">
                                    <option value="N" <?php echo ($informasi_baru=="N")?"selected":"";?>>Tidak</option>
                                    <option value="Y" <?php echo ($informasi_baru=="Y")?"selected":"";?>>Bersedia</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Merk Motor Sebelumnya</label>
                                 <select name="merk_motor_cddb" id="merk_motor" class="form-control">
                                    <option value="">-- Pilih Merk Motor --</option>
                                    <?php
                                       if(isset($merke_motor)){
                                          if($merke_motor->totaldata >0){
                                             foreach ($merke_motor->message as $key => $value) {
                                                $pilih=($merk_motor == $value->ID)?'selected':'';
                                                echo "<option value='".$value->ID."' ".$pilih.">".$value->MERK_MOTOR."</option>";
                                             }
                                          }
                                       }
                                    ?>
                                 </select>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Jenis Motor Sebelumnya</label>
                                 <select name="jenis_motor_cddb" id="jenis_motor" class="form-control">
                                    <option value="">-- Pilih Jenis Motor --</option>
                                    <?php
                                       if(isset($jenise_motor)){
                                          if($jenise_motor->totaldata >0){
                                             foreach ($jenise_motor->message as $key => $value) {
                                                $pilih=($jenis_motor == $value->ID)?'selected':'';
                                                echo "<option value='".$value->ID."' ".$pilih.">".$value->JENIS_MOTOR."</option>";
                                             }
                                          }
                                       }
                                    ?>
                                 </select>
                              </div>
                           </div>                       
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Akan Digunakan Untuk</label>
                                 <select name="digunakan_untuk_cddb" id="digunakan_untuk" class="form-control">
                                    <option value="">-- Pilih Penggunaan --</option>
                                    <?php
                                       if(isset($kegunaane)){
                                          if($kegunaane->totaldata >0){
                                             foreach ($kegunaane->message as $key => $value) {
                                                $pilih=($digunakan_untuk == $value->ID)?'selected':'';
                                                echo "<option value='".$value->ID."' ".$pilih.">".$value->KEGUNAAN."</option>";
                                             }
                                          }
                                       }
                                    ?>
                                 </select>
                               </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Yang Akan Menggunakan</label>
                                 <select name="yang_menggunakan_cddb" id="yang_menggunakan" class="form-control">
                                    <option value="">-- Pilih Pemakai --</option>
                                    <?php
                                       if(isset($penggunane)){
                                          if($penggunane->totaldata >0){
                                             foreach ($penggunane->message as $key => $value) {
                                                $pilih=($yang_menggunakan == $value->ID)?'selected':'';
                                                echo "<option value='".$value->ID."' ".$pilih.">".$value->PENGGUNA."</option>";
                                             }
                                          }
                                       }
                                    ?>
                                 </select>
                               </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-xs-6 col-sm-5 col-md-5">
                              <div class="form-group">
                                 <label>Keterangan</label>
                                 <textarea name="keterangan_cddb" id="keterangan" rows="1" class="form-control" placeholder="Masukkan Keterangan"><?php echo $keterangan;?></textarea>
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Sales Honda ID</label>
                                 <input type="text" name="kd_salesAhm_cddb" id="kd_salesAhm" value="<?php echo $kd_sales;?>" class="form-control" placeholder="Masukkan Kode Motor">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Refensi ID</label>
                                 <input type="text" name="reff_id_cddb" id="reff_id" value="<?php echo $reff_id;?>" class="form-control" placeholder="Masukkan REFF ID">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-3 col-md-3">
                              <div class="form-group">
                                <label>RO BD ID</label>
                                <input type="text" name="robb_id_cddb" id="robb_id" value="<?php echo $robb_id;?>" class="form-control" placeholder="Masukkan ROBD ID">
                              </div>
                           </div>
                        </div>
                     <!-- </form> -->
                  </fieldset>
               </div>
               <!--panel UDPRG-->
               <?php
                  $spk_id="";$status_spk="";$kd_dealer="";$no_rangka="";$no_mesin="";$kd_leasing="";$kd_salesprogram="";
                  $telp_kosong="";$hp_kosong="";$tgl_beli="";$kd_salesprogramahm="";$lokal_sp="";$uang_muka="";$jenis_beli="";$asal_jual="";
                  $kd_lokasi="";$sales_force="";$kd_desa="";$kd_kecamatan="";$dp_setor="";$sub_ahm="";$sub_md="";$sub_dlr="";$sub_fin="";
                  $split_otr="0";$ro="";$ro_mesin="";$jenis_customer="";$of_tr="";$kelurahan_surat="";$kecamatan_surat="";$jml_angsuran="";

                  if(isset($udprg)){
                     if($udprg->totaldata > 0){
                        foreach ($udprg->message as $key => $value) {
                           $spk_id= $value->SPK_ID;
                           $status_spk= $value->STATUS_SPK;
                           $kd_dealer= $value->KD_DEALER;
                           $no_rangka= $value->NO_RANGKA;
                           $no_mesin= $value->NO_MESIN;
                           $kd_leasing= $value->KD_LEASING;
                           $kd_salesprogram= $value->KD_SALESPROGRAM;
                           $telp_kosong= $value->TELP_KOSONG;
                           $hp_kosong= $value->HP_KOSONG;
                           $tgl_beli= $value->TGL_BELI;
                           $kd_salesprogramahm= $value->KD_SALESPROGRAMAHM;
                           $lokal_sp= $value->LOKAL_SP;
                           $uang_muka= $value->UANG_MUKA;
                           $jenis_beli= $value->JENIS_BELI;
                           $asal_jual= $value->ASAL_JUAL;
                           $kd_lokasi= $value->KD_LOKASI;
                           $sales_force= $value->SALES_FORCE;
                           $kd_desa= $value->KD_DESA;
                           $kd_kecamatan= $value->KD_KECAMATAN;
                           $dp_setor= $value->DP_SETOR;
                           $sub_ahm= $value->SUSB_AHM;
                           $sub_md= $value->SUB_MD;
                           $sub_dlr= $value->SUB_DLR;
                           $sub_fin= $value->SUB_FIN;
                           $split_otr = (trim($value->SPLIT_OTR))?$value->SPLIT_OTR:"0";
                           $ro= $value->RO;
                           $ro_mesin= $value->RO_MESIN;
                           $jenis_customer= $value->JENIS_CUSTOMER;
                           $of_tr= $value->OF_TR;
                           $kelurahan_surat= $value->KELURAHAN_SURAT;
                           $kecamatan_surat= $value->KECAMATAN_SURAT;
                           $jml_angsuran= $value->JML_ANGSURAN;
                        }
                     }
                  }
               ?>
               <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="3")? "active":"";?>" id="tabs-3">
                  <fieldset>
                     <!-- <form id="frm_udprg" action="<?php echo base_url('spk/simpancs_spk') ?>" class="bucket-form" method="post"> -->
                        <!-- baris ke 1 -->
                        <div class="row">
                           <div class="col-xs-hidden col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Leasing</label>
                                 <input type="text" name="kd_leasing_udprg" id="kd_leasing"  value="<?php echo $kd_leasing;?>" class="form-control" placeholder="Masukkan Kode Leasing">
                              </div>
                           </div>
                           <div class="col-xs-hidden col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>SalesProgram ID</label>
                                 <input type="text" name="kd_salesprogram_udprg" id="kd_salesprogram" value="<?php echo $kd_salesprogram;?>" class="form-control" placeholder="Masukkan Kode Sales Program"
                                 data-toggle="popover" data-content="<?php echo NamaSalesProgram(''.$kd_salesprogram.'');?>" >
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>IDSalesProgram AHM</label>
                                 <input type="text" name="sp_ahm_udprg" id="spkid" value="<?php echo $kd_salesprogramahm;?>"  class="form-control" placeholder="Masukkan SPK ID">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>IDSalesProgrm DLR</label>
                                 <input type="text" name="sp_dlr_udprg" id="local_spkid" value="<?php echo $lokal_sp;?>"  class="form-control" placeholder="Masukkan Local SPK ID">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Tanggal Beli</label>
                                 <input type="text" name="tgl_beli_udprg" id="tgl_beli" value="<?php echo $tgl_beli;?>" data-mask="00/00/0000" class="form-control"  placeholder="dd/mm/yyyy" >
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Jenis Beli</label>
                                 <input type="text" name="jenis_beli_udprg" id="jenis_beli" value="<?php echo $jenis_beli;?>" class="form-control" placeholder="Masukkan Jenis Beli">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-2 col-sm-2">
                              <div class="form-group">
                                 <label>Uang Muka</label>
                                 <input type="text" name="uang_muka_udprg" id="uang_muka_udprg" value="<?php echo $uang_muka;?>" class="form-control" placeholder="Masukkan Uang Muka">
                              </div>
                           </div>
                           <div class="col-xs-6 col-md-2 col-sm-2">
                              <div class="form-group">
                                 <label>Jumlah Angsuran</label>
                                 <input type="text" name="jml_angsuran_udprg" id="jml_angsuran_udprg" value="<?php echo $jml_angsuran;?>" class="form-control" placeholder="Masukkan Uang Muka">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>DP Setor</label>
                                 <input type="text" name="dp_setor_udprg" id="dp_setor" value="<?php echo $dp_setor;?>" class="form-control" placeholder="Masukkan Split OTR">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Subsidi AHM</label>
                                 <input type="text" name="sub_ahm_udprg" id="sub_ahm" value="<?php echo $sub_ahm;?>" class="form-control" placeholder="Masukkan Subs Main Dealer">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Subsidi MD</label>
                                 <input type="text" name="sub_md_udprg" id="sub_md" value="<?php echo $sub_md;?>" class="form-control" placeholder="Masukkan Subs Main Dealer">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Subsidi DLR</label>
                                 <input type="text" name="sub_dealer_udprg" id="sub_dealer" value="<?php echo $sub_dlr;?>" class="form-control" placeholder="Masukkan Subs Dealer">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Subsidi FIN</label>
                                 <input type="text" name="sub_fin_udprg" id="sub_fin" value="<?php echo $sub_fin;?>" class="form-control" placeholder="Masukkan Subs Fin">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Split OTR</label>
                                 <input type="text" name="split_otr_udprg" id="split_otr" value="<?php echo $split_otr;?>" class="form-control" placeholder="Masukkan Split OTR">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Asal Penjualan</label>
                                 <input type="text" name="asal_jual_udprg" id="asal_jual" value="<?php echo $asal_jual;?>"  class="form-control" placeholder="Masukkan Asal Penjualan">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Lokasi</label>
                                 <input type="text" name="kd_lokasi_udprg" id="kd_lokasi" value="<?php echo $kd_lokasi;?>"  class="form-control" placeholder="Masukkan Kode Lokasi">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Sales Force</label>
                                 <input type="text" name="sales_force_udprg" id="sales_force" value="<?php echo $sales_force;?>"  class="form-control" placeholder="Masukkan Sales Force">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>RO</label>
                                 <input type="text" name="ro_udprg" id="ro" value="<?php echo $ro;?>" class="form-control" placeholder="Masukkan RO">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>RO Mesin</label>
                                 <input type="text" name="ro_mesin_udprg" id="ro_mesin" value="<?php echo $ro_mesin;?>" class="form-control" placeholder="Masukkan RO Mesin">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Jenis Customer</label>
                                 <input type="text" name="jenis_cust_udprg" id="jenis_cust" value="<?php echo $jenis_customer;?>" class="form-control" placeholder="Masukkan Jenis Customer">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Off The Road</label>
                                 <input type="text" name="of_tr_udprg" id="of_tr" value="<?php echo $of_tr;?>"class="form-control" placeholder="Masukkan OF TR">
                              </div>
                           </div>
                           <div class="col-xs-hidden col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Alasan Telp Kosong</label> 
                                 <input name="alasan_telp_kosong_udprg" id="alasan_telp_kosong" value="<?php echo $telp_kosong;?>" class="form-control">
                              </div>
                           </div>
                           <div class="col-xs-hidden col-sm-3 col-md-3">
                              <div class="form-group">
                                 <label>Alasan HP Kosong</label> 
                                 <input name="alasan_hp_kosong_udprg" id="alasan_hp_kosong" value="<?php echo $hp_kosong;?>" class="form-control">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Kecamatan<span id="l_kecamatan"></span></label>
                                 <input name="kd_kecamatan_udprg" id="kd_kecamatan" class="form-control" title="kecamatan" value="<?php echo $kd_kecamatan;?>" data-toggle="popover" data-content="<?php echo NamaWilayah('Kecamatan',''.$kd_kecamatan.'');?>">
                           
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kode Kelurahan <span id="l_desa"></span></label>
                                 <input name="kd_desa_udprg" id="kd_desa" class="form-control" title="desa" value="<?php echo $kd_desa;?>" data-toggle="popover" data-content="<?php echo NamaWilayah('Desa',''.$kd_desa.'');?>">
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kecamatan Surat</label>
                                 <input name="kd_kecamatan_surat_udprg" id="kd_kecamatan_surat_udprg" class="form-control" value="<?php echo $kecamatan_surat;?>" >
                              </div>
                           </div>
                           <div class="col-xs-6 col-sm-2 col-md-2">
                              <div class="form-group">
                                 <label>Kelurahan Surat</label>
                                 <input name="kd_desa_surat_udprg" id="kd_desa_surat" class="form-control" value="<?php echo $kelurahan_surat;?>" >
                              </div>
                           </div>
                        </div>
                        
                     <!-- </form> -->
                  </fieldset>
               </div>
               <!--end of panel quis-->
            </div>
         </div>
      </div>
   </div>
</form>
<?php echo loading_proses();?>
</section>

<script type="text/javascript">
   $(document).ready(function(){
      __getKabupaten();
      $('#kd_salesprogram').popover({
         placement:'top',
         container:'body',
         trigger:'hover'
      });

      $('#kd_kecamatan').popover({
         placement:'top',
         container:'body',
         trigger:'hover'
      });
      $('#kd_desa').popover({
         placement:'top',
         container:'body',
         trigger:'hover'
      });
   });

   function __getKabupaten(){
      var propinsi="<?php echo $kd_propinsi;?>";
      var select="<?php echo $kd_kabupaten;?>"
      $.ajax({
         type:'GET',
         url :"<?php echo base_url('customer/kabupaten');?>",
         data:{'kd':propinsi},
         dataType:'html',
         success:function(result){
            if(result){
               $('#kd_kabupaten').html('');
               $('#kd_kabupaten').html(result);
               $('#kd_kabupaten').val(select).select();
               $('#kd_kabupaten_cddb').html('');
               $('#kd_kabupaten_cddb').html(result);
               $('#kd_kabupaten_cddb').val(select).select();
               $('#kd_kabupaten_surat').html('');
               $('#kd_kabupaten_surat').html(result);
               $('#kd_kabupaten_surat').val(select).select();
            }
         }
      });
   }
   function __update_ssu(){
      $('#loadpage').removeClass("hidden");
      $.ajax({
         type :'POST',
         url : "<?php echo base_url('ssu/update_newssu');?>",
         // data : {'u':$('#frm_udstk').serialize(),'c':$('#frm_cddb').serialize(),'p':$('#frm_udprg').serialize()},
         data : $('#frm_ssu').serialize(),
         dataType:'json',
         success : function(result){
            if(result){
               $('.success').animate({ top: "0"}, 500);
               $('.success').html('Data berhasil di simpan').fadeIn();
               setTimeout(function() {
                  document.location.reload()
               }, 2000);
               
            }else {
               $('.error').animate({top: "0"}, 500);
               $('.error').html(result.message);
               setTimeout(function () {
                  hideAllMessages();
                  $("#submit-btn").removeClass("disabled");
                  $("#submit-btn").html(defaultBtn);
                  $('#loadpage').addClass("hidden");
               }, 2000);
            }
         }
      })
   }
</script>