<?php
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );

  $defaultDealer=$this->session->userdata("kd_dealer");
  $defultCust="R";$nama_customer="";$kd_hobby="";
  $nomorspk=""; $jenispenjualan="";$antardealer="";$diskon="";
  $kddealer=""; $typejual="";$tipecustomer="";$tglspk="";$leasingID=0;
  $jenisharga=""; $groupsales=""; $namasales="";$kdguest="";$diskon=0;
  $survey_leasing="";$keterangan="";$status_spk=0;$nama_leasing="";$required="";
  $no_hp="";$no_telp="";$kd_pendidikan="";$kd_pekerjaan="";$pengeluaran="";$tglspk_asli="";$chanel="";$spkid=$this->input->get("id");
  $kd_fincoy="";$merk_motor="";
  $tgl_kirim=date('d/m/Y');
  $jam_kirim=date('H:i');
  if($this->input->get("g")){
    if(isset($guestbook)){
      if($guestbook->totaldata>0){
        foreach ($guestbook->message as $key => $value) {
          $typejual       = $value->CARA_BAYAR;
          $tipecustomer   = $value->KD_TYPECUSTOMER;
          $groupsales     = substr($value->KD_SALES, 3,2);
          $namasales      = stripslashes($value->NAMA_SALES);
          $nama_customer  = stripslashes($value->NAMA_CUSTOMER);
          $kd_customer    = $value->KD_CUSTOMER;
          $kdguest        = $value->ID;
        }
      }
    }
  }
  if($this->input->get("id")){
    if(isset($spkview)){
      if(($spkview->totaldata>0)){
        foreach ($spkview->message as $key => $value) {
          $nomorspk       = $value->NO_SPK;
          $jenispenjualan = $value->JENIS_PENJUALAN;
          $antardealer    = $value->JENIS_P_ANTARDEALER;
          $kddealer       = $value->KD_DEALER;
          $defaultDealer  = $value->KD_DEALER;
          $typejual       = $value->TYPE_PENJUALAN;
          $tipecustomer   = $value->KD_TYPECUSTOMER;
          $defultCust     = $value->KD_TYPECUSTOMER;
          $tglspk         = tglFromSql($value->TGL_SPK);
          $jenisharga     = $value->JENIS_HARGA;
          $namasales      = $value->KD_SALES;
          $kdguest        = $value->GUESTID;
          $groupsales     = $value->PENJUALAN_VIA;
          $tglspk_asli    = str_replace("-","",substr($value->TGL_SPK,0,10));
          $status_spk     = ($value->STATUS_SPK)?$value->STATUS_SPK:"0";
          $leasingID      = $value->LEASINGID;
          $no_hp          = $value->NO_HP;
          $no_telp        = $value->NO_TELEPON;
          $kd_pekerjaan   = $value->KD_PEKERJAAN;
          $kd_pendidikan  = $value->KD_PENDIDIKAN;
          $pengeluaran    = $value->PENGELUARAN;
          $survey_leasing = $value->HASIL;
          $nama_leasing   = $value->NAMA_LEASING;
          $keterangan     = $value->KET_LEASING;
          $kd_fincoy      = $value->KD_FINCOY;
          $chanel         = $value->CHANEL;
          $nama_customer  = stripslashes($value->NAMA_CUSTOMER);
        }
      }
    }
    //print_r($spkview);
  }
  $nama_dibpkb="";$alamat_dibpkb="";$ktp_bpkb="";$email_bpkb="";$tgl_lahir_bpkb="";
  $nama_kecamatan="";$nama_kelurahan="";$kd_propinsi_bpkb="";$kd_kabupaten_bpkb="";
  //var_dump($spk_bpkb);
  if(isset($spk_bpkb)){
    if($spk_bpkb->totaldata > 0){
      foreach ($spk_bpkb->message as $key => $value) {
         $nama_dibpkb = stripslashes($value->NAMA_BPKB);
         $alamat_dibpkb = stripslashes($value->ALAMAT_BPKB);
         $ktp_bpkb = stripslashes($value->KTP_BPKB);
         $email_bpkb = $value->EMAIL_BPKB;
         $kd_propinsi_bpkb = $value->KD_PROPINSI;
         $kd_kabupaten_bpkb = $value->KD_KABUPATEN;
         $nama_kecamatan = $value->NAMA_KECAMATAN;
         $nama_kelurahan = $value->NAMA_KELURAHAN;
         $tgl_lahir_bpkb = ($value->TGL_LAHIR_BPKB)?tglFromSql($value->TGL_LAHIR_BPKB):"";
      }
    }
  }
 $statusspk=((int)$status_spk >1 || strlen($survey_leasing))?"disabled-action":"";
 $required=($typejual=="CREDIT")?"required='required'":"";
 $leasing_disable=($typejual=="CREDIT")?"":"disabled='disabled'";
 $show_approval=$this->input->get("l");

?>

<script type="text/javascript">
  function __getsalesmandatamk(kdtp){
      var kw = $("#nama_sales").val();
      $("#cls").html("<i class=\'fa fa-refresh fa-spin fa-fw\'></i>");
      var pilih=""
      $.ajax({
        url:'<?php echo base_url("spk/makelar");?>',
        type:"POST",
        dataType: "html",
        data:{"keyword":kw,"group_sales":kdtp,"lok":''},
        success:function(result){
          $("#listsalesman tbody").html("");
          $("table#listsalesman tbody").append(result);
          $("#nama_sales").html("");
          $("#cls").html("");
          if(pilih==''){
            $("#kd_sales").click();
          }

        }

      });
       return false;
  }
  
</script>

<section class="wrapper">
  <!-- breadcrume -->
  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>
    <!-- Button Header -->
      <?php $asal_view=$this->input->get("f");?>
      <div class="bar-nav pull-right ">
        <div class="<?php echo ($asal_view)?"hidden":"";?>">
          <a class="btn btn-default" href="<?php echo base_url('spk/add_spk'); ?>"><i class="fa fa-file-o fa-fw"></i> SPK Baru</a>
          <?php if(!$this->input->get("id")):?>
          <a class="btn btn-default <?php echo $status_c;?><?php echo $status_e;?><?php echo $statusspk;?>" id="btn-simpan_spk"><i class="fa fa-save"></i> Simpan</a>
          <?php endif;?>
          <a class="btn btn-default hidden <?php echo $status_p;?>"><i class="fa fa-print fa-fw"></i> Cetak </a>
          <a class="btn btn-default <?php echo $status_v;?>" href="<?php echo base_url("spk/spk/");?>"><i class="fa fa-list"> List SPK</i></a>
        </div>
        <div class="<?php echo ($asal_view)?"":"hidden";?>">
            <a class="btn btn-default" href="<?php echo base_url("spk/approval_spk/").$spkid;?>/SPK"><i class="fa fa-cog"></i> Approve SPK</a>
            <a class="btn btn-default" href="<?php echo base_url("spk/approval_spk/").$nomorspk."/SPK/b";?>"><i class="fa fa-trash"></i> Batal SPK</a>
            <a class="btn btn-default" href="<?php echo base_url("cashier/approval_ds/");?>"><i class="fa fa-list-ul"></i> Approval List</a>
        </div>
      </div>
  </div>
   <!-- SPK Header -->
  <div class="col-xs-12 padding-left-right-10 ">
    <div class="panel margin-bottom-10">
      <div class="panel-heading panel-custom">
        <div class="row">
          <div class="col-sm-4">
            <h4 class="panel-title pull-left" style="padding-top: 10px;">
              <i class='fa fa-file-o fa-fw'></i> SPK Header <!-- :: &nbsp;&nbsp;&nbsp; KODE DEALER :<span class="" id="kd_dealer"><?php echo $defaultDealer;?></span> -->
            </h4>
          </div>
          <div class="col-sm-7">
            <form class="form-inline">
              <div class="input-group input-group-sm">
                <span class="input-group-addon" id="sizing-addon2">Nomor SPK</span>
                <input type="text" id="spk_no" class="form-control" placeholder="No SPK Auto Generate" aria-describedby="sizing-addon2" readonly="readonly" value="<?php echo $nomorspk;?>">
                
              </div>
            </form>
          </div>
          <div class="col-sm-1">
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
              <input type="hidden" id="chanel" value="<?php echo $chanel;?>">
           </span>
         </div>
        </div>
      </div>
      <div class="panel-body panel-body-border <?php echo ($asal_view)?'disabled-action':'';?>">
        <form id="addForm" action="<?php echo base_url('spk/simpan_spk') ?>" class="bucket-form" method="post">
          <div class="row">
            <input type="hidden" id="spkid" name="spkid" value="<?php echo ($this->input->get('id'))?$this->input->get('id'):"0";?>">
            <input type="hidden" id="nospk" name="nospk" value="<?php echo $nomorspk;?>">
            <input type="hidden" id="stsspk" name="stsspk" value="<?php echo ($status_spk)?$status_spk:'0';?>">
            <!-- colom 1 -->
            <!-- <fieldset <?php echo ($nomorspk!='')? "disabled":"";?> id="header_frm"> -->
              <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                  <label>Penjualan <?php //echo $jenispenjualan;?></label>
                  <select name="jenis_penjualan" id="jenis_penjualan" class="form-control header_frm" required="true">
                    <option value="1" <?php echo ($jenispenjualan=="1")?"selected":"";?>>Penjualan Dealer Sendiri</option>
                    <option value="2" <?php echo ($jenispenjualan=="2")?"selected":"";?>>Penjualan Antar Dealer</option>
                  </select>
                </div>
                <div class="form-group">
                    <label>Antar Dealer<?php //echo ($antardealer);?></label>
                    <select name="jp_antardealer" id="jp_antardealer" class="form-control header_frm" disabled="disabled">
                      <option value="" <?php echo ($antardealer=="")?"selected":"";?>>&nbsp;</option>
                      <option value="1" <?php echo ($antardealer=="1")?"selected":"";?>>Antar Dealer</option>
                      <option value="2" <?php echo ($antardealer=="2")?"selected":"";?>>Direct</option>
                    </select>
                </div>
                <div class="form-group">
                  <label>Dealer/Non Chanel <span id="lgd"></span></label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control header_frm" disabled="disabled" required="true">
                    <option value="">-- Pilih Dealer/Non Chanel --</option>
                    <?php 
                        $defaultDealer=((int)$antardealer>0)? $chanel:$defaultDealer;
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
              <?php //echo $defaultDealer;?>
              <!-- colom 2 -->
              <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                  <label>Tipe Penjualan</label>
                  <select name="type_penjualan" id="type_penjualan" class="form-control header_frm" required="true">
                    <option value="" <?php echo ($typejual=="")?"selected":"";?>>-- Pilih Tipe Penjualan --</option>
                    <option value="CASH" <?php echo ($typejual=="CASH" || $typejual=="")?"selected":"";?>>CASH</option>
                    <option value="CREDIT" <?php echo ($typejual=="CREDIT")?"selected":"";?>>CREDIT</option>
                  </select>
                  <input type='hidden' value="<?php echo $typejual;?>" id="carajual">
                </div>
                <div class="form-group">
                  <label>Tipe Customer</label>
                  <select name="kd_typecustomer" id="kd_typecustomer" class="form-control header_frm" required="true">
                    <option value="">-- Pilih Tipe Customer --</option>
                    <?php
                       if($typecustomer){
                          if(is_array($typecustomer->message)){
                              foreach ($typecustomer->message as $key => $value) {
                                $select=($defultCust==$value->KD_TYPECUSTOMER)?"selected":"";
                                echo "<option value='".$value->KD_TYPECUSTOMER."' ".$select.">".$value->NAMA_TYPECUSTOMER."</option>";
                              }
                          }
                       }
                      ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Jenis Harga</label>
                  <select name="jenis_harga" id="jenis_harga" class="form-control header_frm" required="true">
                    <option value="On The Road" <?php echo ($jenisharga=="On The Road")?"selected":"";?>>On The Road</option>
                    <option value="Off The Road" <?php echo ($jenisharga=="Off The Road")?"selected":"";?>>Off The Road</option>
                  </select>
                </div>
              </div>
            <!-- </fieldset> -->
              <!-- colom 3 -->
              <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="form-group">
                    <label>Tanggal SPK</label>
                      <div class="input-group input-append date">
                        <input type="text" class="form-control header_frm" id="tgl_spk" required="true" name="tgl_spk" value="<?php echo ($tglspk=='')? date("d/m/Y"):$tglspk;?>">
                        <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                      </div>
                </div>
                <fieldset class="<?php echo ($status_spk>0)?'disabled-action':'';?>">
                <div class="form-group">
                  <label>Penjualan Melalui<?php //echo $groupsales;?></label>
                  <select name="kd_groupsales" id="kd_groupsales" class="form-control" required="true">
                    <option value="SC" <?php echo ($groupsales=="SC")?"selected":"";?>>SALES COUNTER</option>
                    <option value="SM" <?php echo ($groupsales=="SM")?"selected":"";?>>SALESMAN</option>
                    <option value="MK" <?php echo ($groupsales=="MK")?"selected":"";?>>MAKELAR</option>
                    <option value="TP" <?php echo ($groupsales=="TP")?"selected":"";?>>TANPA PERANTARA</option>
                  </select>
                </div>
                <div class="form-group">
                    <label>Nama Sales</label>
                    <?php 
                      echo ($namasales=='')? DropdownSalesman($this->session->userdata("kd_dealer")):
                                             DropdownSalesman($this->session->userdata("kd_dealer"),'',$namasales);
                    ?>
                    <input type="text" id="kd_salesman_tp" name="kd_salesman_tp" value="<?php echo($groupsales=='TP') ?($namasales):"";?>" class="form-control hidden">
                </div>
                </fieldset>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- SPKdetail -->
  <div class="col-xs-12 padding-left-right-10 <?php echo ($asal_view)?'disabled-action':'';?>">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
          <i class="fa fa-file-o"></i> SPK Detail
          <span class="tools pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
      </div>
        <div class="panel-body panel-body-border">
          <!-- button bar  -->
            <?php $tabaktife=$this->input->get("tab");?>
            <input type="hidden" id="tabaktif" value="<?php echo $tabaktife;?>">
            <input type="hidden" id="autogb" value="<?php echo $this->input->get("g");?>">
            <!-- Nav Bar / tab -->
            <div class="row">
              <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" <?php echo ($tabaktife=="" || $tabaktife=="1")? " class='active tbs'":" tbs";?>>
                    <a href="#tabs-1" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-envelope fa-fw"></i> Data Customer </a>
                  </li>
                  <li role="presentation" <?php echo ($tabaktife=="2")? " class='active tbs'":" tbs";?>>
                    <a href="#tabs-2" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-star fa-fw"></i> Data Kendaraan </a>
                  </li>
                  <li role="presentation" <?php echo ($tabaktife=="3")? " class='active tbs'":" tbs";?>>
                    <a href="#tabs-3" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-pencil fa-fw"></i> Kuisioner</a>
                  </li>
                  <li role="presentation" <?php echo ($tabaktife=="4")? " class='active tbs'":" tbs";?>>
                    <a href="#tabs-4" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-money fa-fw"></i> Titipan Uang Muka</a>
                  </li>
                  <li role="presentation" <?php echo ($tabaktife=="5")? " class='active tbs'":" tbs";?>>
                    <a href="#tabs-5" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-users fa-fw"></i> Data Anggota Keluarga</a>
                  </li>
                  <li id="tab-header" class="pull-right sticky spklock">
                    <fieldset <?php echo ($show_approval=='1')?"disabled":"";?>>
                      <div class="pull-right">
                        <button role="button" id="btn-simpan_cs" class="btn btn-default <?php echo $status_c;?><?php echo $status_e;?> disabled <?php echo ($tabaktife=="" || $tabaktife=="1")? "":"hidden";?>"><i class="fa fa-save fa-fw"></i> Simpan Customer</button>
                        <button role="button" id="btn-simpan_motor" class="hdd btn btn-default <?php echo $status_c.' '.$statusspk;?><?php echo $status_e;?> disabled <?php echo ($tabaktife=="2")? "":"hidden";?>"><i class="fa fa-save fa-fw"></i> Simpan Kendaraan</button>
                        <button role="button" id="btn-simpan_quiz" class="btn btn-default <?php echo $status_c;?><?php echo $status_e;?> disabled <?php echo ($tabaktife=="3")? "":"hidden";?>"><i class="fa fa-save fa-fw"></i> Simpan Kuisioner</button>
                        <button role="button" id="btn-simpan_kk" class="btn btn-default <?php echo $status_c;?><?php echo $status_e;?> disabled <?php echo ($tabaktife=="5")? "":"hidden";?>"><i class="fa fa-save fa-fw"></i> Simpan Data KK</button>
                    </div>
                    </fieldset>
                  </li>
                </ul>
                <input type="hidden" name="tabaktif" id="tabaktif">
              </div>
            </div>
          <!-- </form> -->
          <!-- Tab panes -->
          <?php $kunci= ((int)$status_spk==0)?'':'disabled-action';?>
          <div class="tab-content spklock">
              <!-- panel customer -->
              <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="" || $tabaktife=="1")? "active":"";?>" id="tabs-1">
                  <fieldset <?php echo ($show_approval=='1')?"disabled":"";?>>
                  <form id="addForm_cs" action="<?php echo base_url('spk/simpancs_spk') ?>" class="bucket-form <?php echo $kunci;?>" method="post">
                    <!-- baris ke 1 -->
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label>Nama Customer <?php //echo $kdguest;?></label>
                          <select name="kd_guest" id="kd_guest" class="form-control <?php echo ($this->input->get('id'))?'disabled-action':'';?>" required="required">
                            <option value="">-- Pilih Nama Customer --</option>
                            <?php

                              if($guestbook){
                                if($guestbook->totaldata>0){
                                  foreach ($guestbook->message as $key => $value) {
                                    $select=($kdguest==$value->ID)?"selected":"";
                                    ?>
                                      <option value="<?php echo $value->ID;?>" <?php echo $select;?>><?php echo stripslashes($value->NAMA_CUSTOMER);?></option>
                                    <?php
                                  }
                                }
                              }
                              if($this->input->get('id')){
                                if($spkview){
                                  if(is_array($spkview->message)){
                                    foreach ($spkview->message as $key => $value) {
                                      $select=($kdguest==$value->GUESTID)?"selected":"";
                                      echo "<option value='".$value->GUESTID."' ".$select.">".stripslashes($value->NAMA_CUSTOMER)."</option>";
                                    }
                                  }
                                }
                              }
                            ?>
                          </select>
                          <input type="hidden" id="nama_customer" name="nama_customer" value="">
                          <input type="hidden" id="kd_customer" name="kd_customer" value="">
                          <input type="hidden" id="guest_no" name="guest_no" value="">
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <?php $sama=($nama_customer==$nama_dibpkb)?"checked='true'":"";?>
                          <label>Nama di BPKB&nbsp;<sup><abbr title="Nama Sesuai KTP"><i class='fa fa-info-circle' style="color: red"></i></abbr></sup></label> &nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo $sama;?> id="likeNama"><small><i> = Nama Customer</i></small> 
                          <input type="text" name="nama_dibpkb" id="nama_dibpkb" class="form-control" placeholder="Masukkan nama sesuai ktp" value="<?php echo $nama_dibpkb;?>"  required="required">
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label><span id="identitas">Nomor KTP/Identitas</span></label>
                          <input type="text" name="nomor_ktp" id="nomor_ktp" class="form-control number" placeholder="Masukkan Nomor KTP/Identitas" value='<?php echo $ktp_bpkb;?>' required="required" maxlength="16" minlength="16">
                        </div>
                      </div>
                    </div>
                    <!-- baris ke 2 -->
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label>Alamat Customer &nbsp;<span id="alamat_lg"></span></label>
                          <textarea name="alamat_cust" id="alamat_cust" class="form-control" placeholder="Masukkan Alamat Customer"  required="required"></textarea>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                          <label>Alamat di BPKB&nbsp;<sup><abbr title="Alamat Sesuai KTP"><i class='fa fa-info-circle' style="color: red"></i></abbr></sup></label>  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" <?php echo $sama;?> id="likeAlamat"><small><i> Sama dengan Alamat Customer</i></small>
                          <textarea name="alamat_dibpkb" id="alamat_dibpkb" class="form-control" placeholder="Masukkan Alamat sesuai ktp" required="required"><?php echo $alamat_dibpkb;?></textarea>
                        </div>
                      </div>
                    </div>
                    <!-- Baris ke 3 -->
                    <div class="row">
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Provinsi</label>
                          <select name="kd_propinsi" id="kd_propinsi" class="form-control" required="required">
                            <option value="0">-- Pilih Provinsi --</option>
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
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Kabupaten/Kota<span id="l_kabupaten"></span></label>
                          <select name="kd_kabupaten" id="kd_kabupaten" class="form-control" title="kabupaten" required="required">
                            <option value="0">-- Pilih Kabupaten/Kota --</option>
                            <!-- <option value="">Tobasa</option> -->
                          </select>
                        </div>
                      </div>
                      <!-- alamat u bpkb -->
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Provinsi</label>
                          <select name="kd_propinsi_bpkb" id="kd_propinsi_bpkb" class="form-control" required="required">
                            <option value="0">-- Pilih Provinsi --</option>
                           <?php
                              if ($propinsi) {
                                 if (is_array($propinsi->message)) {
                                    foreach ($propinsi->message as $key => $value) {
                                        $pilih=($kd_propinsi_bpkb == $value->KD_PROPINSI)?"selected":"";
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
                          <label>Kabupaten/Kota<span id="l_kabupaten_bpkb"></span></label>
                          <select name="kd_kabupaten_bpkb" id="kd_kabupaten_bpkb" class="form-control" title="kabupaten" required="required">
                            <option value="0">-- Pilih Kabupaten/Kota --</option>
                            <!-- <option value="">Tobasa</option> -->
                          </select>
                        </div>
                      </div>
                      <!-- end of alamat untuk bpkb -->
                    </div>
                    <!-- baris ke 4 -->
                    <div class="row">
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Kecamatan<span id="l_kecamatan"></span></label>
                          <select name="kd_kecamatan" id="kd_kecamatan" class="form-control" title="kecamatan" required="required">
                            <option value="">-- Pilih Kecamatan --</option>
                            <!-- <option value="">Balige</option> -->
                          </select>
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Kelurahan/Desa <span id="l_desa"></span></label>
                          <select name="kd_desa" id="kd_desa" class="form-control" title="desa" required="required">
                            <option value="0">-- Pilih Kelurahan/Desa --</option>
                            <!-- <option value="">Brastagi</option> -->
                          </select>
                        </div>
                      </div>
                      <!-- kelurahan bpkb -->
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Kecamatan BPKB<span id="l_kecamatan_bpkb"></span></label>
                          <select name="kecamatan_bpkb" class="form-control" id="kd_kecamatan_bpkb" title="kecamatan">
                            <option value="">--Pilih kecamatan--</option>
                          </select>
                          <!-- <input type="text" id="kecamatan_bpkb" value="<?php echo $nama_kecamatan;?>" name="kecamatan_bpkb" class="form-control upper" placeholder="isi sesuai ejaan di ktp"> -->
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label>Kelurahan BPKB <span id="l_kelurahan_bpkb"></span></label>
                          <select name="kelurahan_bpkb" class="form-control" id="kd_kelurahan_bpkb" title="desa">
                            <option value="">--Pilih kelurahan--</option>
                          </select>
                          <!-- <input type="text" id="kelurahan_bpkb" value="<?php echo $nama_kelurahan;?>" name="kelurahan_bpkb" class="form-control upper" placeholder="isi sesuai ejaan di ktp"> -->
                        </div>
                      </div>
                      <!-- end of kelurahan bpkb -->
                    </div>
                    <div class="row">
                      <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                          <label>Kode Pos</label>
                          <input type="text" name="kode_pos" id="kode_pos" data-mask="00000" class="form-control" placeholder="Masukkan kode Pos">
                        </div>  
                      </div>
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label id="npwp">Nomor NPWP Customer</label>
                          <input type="text" name="npwp_customer" id="npwp_customer" maxlength="16" class="form-control" placeholder="">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                          <label>Kode Pos BPKB</label>
                          <input type="text" name="kode_posbpkb" id="kode_posbpkb" data-mask="00000" class="form-control" placeholder="Masukkan kode Pos">
                        </div>  
                      </div>
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label>Email</label>
                          <input type="mail" name="email_customer" id="email_customer" value='<?php echo $email_bpkb;?>' class="form-control" placeholder="Masukkan Email">
                        </div>
                      </div>
                    </div>
                    <!-- baris ke 5 -->
                    <div class="row">
                      <div class="col-xs-12 col-sm-2 col-md-2">
                        <div class="form-group">
                          <label>Agama</label>
                            <select name="kd_agama" id="kd_agama" class="form-control">
                              <option value="">-- Pilih Agama --</option>
                              <?php 
                                if($agama){
                                  if(($agama->totaldata>0)){
                                    foreach ($agama->message as $key => $value) {
                                      echo "<option value='".$value->KD_AGAMA."'>".$value->NAMA_AGAMA."</option>";
                                    }
                                  }
                                }
                              ?>
                            </select>
                          </div>
                      </div>
                      <div class="col-xs-12 col-sm-4 col-md-4">
                        <div class="form-group">
                          <label>Nomor Telpon/HP</label>
                          <input type="text" name="no_hp" id="no_hp" class="form-control number" placeholder="Masukkan Nomor Telepon/HP">
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <label id="tgl">Tanggal Lahir</label>
                          <!-- <div class="input-group input-append date" id="date"> -->
                            <input type="text" name="tgl_lahir" id="tgl_lahir" data-mask="00/00/0000" class="form-control"  placeholder="dd/mm/yyyy" value='<?php echo $tgl_lahir_bpkb;?>'>
                           <!--  <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div> -->
                        </div>
                      </div>
                      <div class="col-xs-6 col-sm-3 col-md-3">
                        <div class="form-group">
                          <fieldset id="reguler">
                            <label>Jenis Kelamin</label>
                            <select name="kd_jeniskelamin" id="kd_jeniskelamin" class="form-control">
                              <option value="">-- Pilih Jenis Kelamin --</option>
                              <?php 
                                if($gender){
                                  if(($gender->totaldata>0)){
                                    foreach ($gender->message as $key => $value) {
                                      echo "<option value='".$value->KD_GENDER."'>".$value->NAMA_GENDER."</option>";
                                    }
                                  }
                                }
                              ?>
                            </select>
                          </fieldset>
                          <fieldset id="gc" class="hidden">
                              <label>Penanggung Jawab</label>
                              <input type="text" class="form-control" name="penanggung_jawab">
                          </fieldset>
                        </div>
                      </div>
                    </div>
                    <!-- baris ke 6 -->
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group hidden">
                          <label>Group Customer</label>
                          <select class="form-control" name="kd_groupcustomer" id="kd_groupcustomer">
                            <option value="">-- Pilih Group Customer --</option>
                            <?php
                              /*if($groupcustomer){
                                if(is_array($groupcustomer->message)){
                                  foreach ($groupcustomer->message as $key => $value) {
                                    # code...
                                  }
                                }
                              }*/
                            ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <hr class="hidden">
                    <!-- baris ke 7 -->
                    <div class="row">
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="panel margin-bottom-10">
                          <div class="panel-heading">
                              <i class='fa fa-share-alt'></i> Sosial Media  
                          </div>
                          <div class="panel-body panel-body-border">
                              <div class="form-group">
                                <label>Facebook</label>
                                <span class="icon-search"></span>
                                <input type="text" name="kd_facebook" id="kd_facebook"  class="form-control" placeholder="Masukkan URL Facebook">
                              </div>
                              <div class="form-group">
                                <label>Twitter</label>
                                <input type="text" name="kd_twiter" id="kd_twiter" class="form-control" placeholder="Masukkan URL Twitter">
                              </div>
                              <div class="form-group">
                                <label>Instagram</label>
                                <input type="text" name="kd_instagram" id="kd_instagram" class="form-control" placeholder="Masukkan URL Instagram">
                              </div>
                              <div class="form-group">
                                <label>Youtube</label>
                                <input type="text" name="kd_youtube" id="kd_youtube" class="form-control" placeholder="Masukkan URL Youtube">
                              </div>
                              <div class="form-group">
                                <label>Hobby</label>
                                <!-- <input type="text" name="kd_hobby" id="kd_hobby" class="form-control" placeholder="Masukkan Hobby" required="true"> -->
                                <select name="kd_hobby" id="kd_hobby" class="form-control" required="true">
                                  <option value=""> -- Pilih Hobby --</option>
                                  <?php 
                                    if(isset($hobbyne)){
                                      if($hobbyne->totaldata > 0){
                                        foreach ($hobbyne->message as $key => $value) {
                                          $select=($kd_hobby==$value->KD_HOBBY)?"selected":"";
                                          echo "<option value='".$value->KD_HOBBY."' ".$select.">".$value->NAMA_HOBBY." [".$value->KD_HOBBY."]</option>";
                                        }
                                      }
                                    }
                                  ?>
                                </select>
                                <input type="hidden" id="upline" name="upline" value="">
                              </div>
                          </div>
                        </div>
                      </div>
                    
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="panel margin-bottom-10">
                          <div class="panel-heading">
                            <div class="row">
                              <div class="col-sm-3"><i class="fa fa-bars"></i> Alamat Surat</div>
                              <div class="col-sm-1">&nbsp;</div>
                              <div class="col-sm-7"><input type="checkbox" checked="false" id="alamat_sama" > Sama dengan Alamat Rumah</div>
                            </div>
                            <!-- <span class="pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span> -->
                          </div>

                          <div class="panel-body panel-body-border" id="almt_sama">
                            <div class="col-xs-12">
                              <div class="form-group">
                                <label>Alamat</label>
                                <textarea rows="1" name="alamat_surat" id="alamat_surat" class="form-control" placeholder="Masukkan Alamat" required="true"></textarea>
                              </div>
                            </div> 
                            <!-- <div class="row" > -->
                              <div class="col-xs-12">
                                <div class="form-group">
                                  <label>Provinsi</label>
                                  <select name="kd_propinsi_surat" id="kd_propinsi_surat" class="form-control">
                                    <option value="0">-- Pilih Provinsi --</option>
                                    <?php 
                                      if($propinsi){
                                        if(is_array($propinsi->message)){
                                          foreach ($propinsi->message as $key => $value) {
                                            echo "<option value='".$value->KD_PROPINSI."'>".$value->NAMA_PROPINSI."</option>";
                                          }
                                        }
                                      }
                                    ?>
                                  </select>
                                </div>
                              </div>

                              <div class="col-xs-12">
                                <div class="form-group">
                                  <label>Kabupaten<span id="l_kabupaten"></span></label>
                                  <select class="form-control" name="kd_kabupaten_surat" id="kd_kabupaten_surat" title="kabupaten">
                                    <option value="0">-- Pilih Kabupaten --</option>
                                    <!-- <option value="">Sleman</option> -->
                                  </select>
                                </div>
                              </div>
                            <!-- </div> -->

                            <!-- <div class="row"> -->
                              <div class="col-xs-12">
                                <div class="form-group">
                                  <label>Kecamatan<span id="l_kecamatan"></span></label>
                                  <select class="form-control" name="kd_kecamatan_surat" id="kd_kecamatan_surat" title="kecamatan">
                                    <option value="0">-- Pilih Kecamatan --</option>
                                    <!-- <option value="">Yogyakarta</option> -->
                                  </select>
                                </div>
                              </div>

                              <div class="col-xs-12 col-sm-7 col-md-7">
                                <div class="form-group">
                                  <label>Desa/Kelurahan<span id="l_desa"></span></label>
                                  <select class="form-control" name="kd_desa_surat" id="kd_desa_surat" title="desa">
                                    <option value="0">-- Pilih Kelurahan --</option>
                                    <!-- <option value="">Sleman</option> -->
                                  </select>
                                </div>
                              </div>
                            <!-- </div> -->

                            <!-- <div class="row"> -->
                              <div class="col-xs-12 col-sm-5 col-md-5">
                                <div class="form-group">
                                  <label>Kode Pos</label>
                                  <input type="text" name="kode_possurat" id="kode_possurat" class="form-control" placeholder="Masukkan Kode Pos">
                                </div>
                              </div>
                            <!-- </div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  </fieldset>
              </div>
              <!-- panel detail kenadaraan -->
              <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="2")? "active":"";?>" id="tabs-2">
                <form id="addForm_motor" action="<?php echo base_url('spk/simpanmotor_spk') ?>" class="bucket-form" method="post">
                  <!-- row 1 panel finansial dan sales program -->
                  <input type="hidden" name="espekaid" id="espekaid" value="<?php echo $this->input->get('id');?>">
                  <input type="hidden" name="asal" id="asal" value="<?php echo $this->input->get('l');?>">
                  <input type="hidden" id="nospkne" name="nospkne" value="<?php echo $nomorspk;?>">
                  <!-- leasing proses -->
                  <!-- financial perusahaan -->
                  <div class="row">
                    <fieldset <?php echo $leasing_disable;?> id="fld_leasing">
                      <!--panel finansial col 1-->
                      <div class="col-xs-12 col-sm-6 col-md-6">
                          <div class="panel margin-bottom-10">
                            <div class="panel-headings">
                              <i class='fa fa-qrcode'></i> Finansial Perusahaan
                              <span class="pull-right">
                                <?php
                                if((isBolehAkses('e')|| isBolehAkses('v'))&& $leasingID>0){
                                  ?>
                                  <div class="btn-group hidden">
                                  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cog fa-fw text-info"></i> Status Credit : <?php echo $survey_leasing;?> <span class="caret"></span></button>
                                    <?php
                                      if($survey_leasing==''){ ?>
                                      <ul class="dropdown-menu">
                                        <li><a href="#" onclick="ApproveCredit('Approve')">Approved</a></li>
                                        <li><a href="#" onclick="ApproveCredit('Un Approve')">Un Approved</a></li>
                                      </ul>
                                      <?php } ?>
                                  </div>
                                  <?php
                                }
                                ?>
                                </span>
                            </div>
                            <!-- panel body finansial -->
                            <input type="hidden" id="kode_leasing" value="<?php echo $kd_fincoy;?>">
                            <input type="hidden" id="hasil_survey" value="<?php echo $survey_leasing;?>">
                            <input type="hidden" id="show_approval" name="show_approval" value='<?php echo $show_approval;?>'>
                            <div class="panel-body panel-body-border">
                              <fieldset <?php echo ($typejual=='CASH' || strlen(trim($survey_leasing))>0)?" disabled":"";?> id="fincoms">
                                  <div class="row">
                                    <div class="col-xs-12 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label>Nama Finansial Perusahaan</label>
                                        <select name="kd_fincom" id="kd_fincom" class="form-control" <?php echo $required;?> <?php echo ($leasingID>0)?"disabled:'disabled'":"";?>>
                                          <option value="">--Pilih Nama Finansial Perusahaan--</option>
                                          <?php
                                            if($fincom){
                                              if(is_array($fincom->message)){
                                                foreach ($fincom->message as $key => $value) {
                                                  $pilih=($kd_fincoy==$value->KD_LEASING)?"selected":"";
                                                  echo "<option value='".$value->KD_LEASING."' ".$pilih.">[".$value->KD_LEASING."] ".$value->NAMA_LEASING."</option>";
                                                }
                                              }
                                            }
                                          ?>
                                        </select>
                                        <input type="hidden" name="alasan_maksa" id="alasan_maksa" value="">
                                      </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-sm-6">
                                      <div class="form-group">
                                        <label>Uang Muka</label>
                                        <div class="input-group input-append">
                                          <input type="text" name="uang_muka" id="uang_muka" class="form-control" placeholder="Masukkan Uang Muka" <?php echo $required;?>>
                                          <span class="input-group-addon " style="cursor: pointer;" id="detailAngsuran"><span class="fa fa-chevron-down" title="Click untuk detail angsuran" ></span></span>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <fieldset id="detail_lsg" class="<?php echo ($leasingID!='' && $show_approval=='1')?"hidden":"";?>">
                                    <div class="row">
                                      <div class="col-xs-12 col-md-3 col-sm-3">
                                        <div class="form-group">
                                          <label>Biaya Adm</label>
                                          <input type="text" name="biaya_adm" id="biaya_adm" class="form-control" placeholder="Masukkan Biaya Administrasi">
                                        </div>
                                      </div>
                                      <div class="col-xs-12 col-md-3 col-sm-3">
                                        <div class="form-group">
                                          <label>Type Credit</label>
                                          <select class="form-control" id="type_credit" name="type_credit" <?php echo $required;?>>
                                            <option value="CREDIT">CREDIT</option>
                                            <option value="CASH TEMPO">CASH TEMPO</option>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-xs-12 col-md-3 col-sm-3">
                                        <div class="form-group">
                                          <label>Jangka Waktu</label>
                                          <!-- <select class="form-control" id="jangka_waktu" name="jangka_waktu" <?php echo $required;?>>
                                            <option value="0">-- Pilih Jangka Waktu --</option>
                                            <option value="12" selected="selected">12 Bulan</option>
                                            <option value="24">24 Bulan</option>
                                            <option value="35">35 Bulan</option>
                                            <option value="48">48 Bulan</option>
                                          </select> base on request 13/02/2018 chenga to manual input with number only-->
                                          <input type='text' id="jangka_waktu" name="jangka_waktu" class="form-control" <?php echo $required;?>>
                                        </div>
                                      </div>
                                      <div class="col-xs-12 col-md-3 col-sm-3">
                                        <div class="form-group">
                                          <label>Bunga/Tahun</label>
                                          <input type="text" name="bunga" id="bunga" class="form-control" placeholder="Masukkan Bunga/Tahun %">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                          <div class="form-group">
                                            <label>Jumlah Angsuran</label>
                                            <input type="text" name="jumlah_angsuran" id="jumlah_angsuran" class="form-control" placeholder="Masukkan Jumlah Angsuran" <?php echo $required;?>>
                                          </div>
                                        </div>

                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                          <div class="form-group">
                                            <label>Tanggal Jatuh Tempo</label>
                                            <div class="input-group input-append date" id="date">
                                              <input type="text" name="jatuh_tempo" class="form-control" id="jatuh_tempo" placeholder="dd/mm/yyyy" value="<?php echo date("d/m/Y");?>" <?php echo $required;?> />
                                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar">
                                              </span></span>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                  </fieldset>
                              </fieldset>
                              <fieldset class="<?php echo ($show_approval=='1' && $leasingID!='')?'':'hidden';?>" id="popup" style="padding:5px">
                                <!-- <form id="frm_xx" method="post" action=""> -->
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                      <div class="form-group">
                                        <label><i class='fa fa-cog'></i> Approval Pengajuan Pembiayaan <?php //echo $show_approval.$leasingID;?></label>
                                      </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-5 col-md-5">
                                      <div class="form-group">
                                        <label>Status Pengajuan <?php //echo ($survey_leasing);?></label>
                                        <select id="app_status" name="app_status" class="form-control" <?php echo ($survey_leasing!='')? "disabled":"";?> <?php echo ($survey_leasing!='')?"required":"";?>>
                                          <option value='Approve' <?php echo ($survey_leasing=="Approve" || $survey_leasing=='' || $survey_leasing=='0')?"selected":"";?>>Approve</option>
                                          <option value='Un Approve' <?php echo ($survey_leasing=="Un Approve")?"selected":"";?>>Un Approve</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-7 col-md-7 hidden" id="als_not_app">
                                      <div class="form-group hidden">
                                        <label>PO Leasing</label>
                                        <input type="text" id="po_leasing" name="po_leasing" class="form-control" placeholder="No PO Leasing">
                                      </div>
                                      <div class="form-group hidden" >
                                        <label>Alasan</label>
                                        <select id="alasan" name="alasan" class="form-control disabled-action" <?php echo ($survey_leasing!='')? "disabled":"";?>>
                                          <option value="">--Pilih alasan--</option>
                                          <option value="Tolakan / Blacklist FIF">Tolakan / Blacklist FIF</option>   
                                          <option value="Tolakan / Blacklist ADR">Tolakan / Blacklist ADR</option> 
                                          <option value="Tolakan / Blacklist MCF">Tolakan / Blacklist MCF</option> 
                                          <option value="Tolakan / Blacklist Others">Tolakan / Blacklist Others</option>   
                                          <option value="Diluar Area FIF">Diluar Area FIF</option>         
                                          <option value="Diluar Area FIF">Diluar Area ADR</option>             
                                          <option value="Diluar Area MCF">Diluar Area MCF</option>
                                          <option value="Diluar Area Others">Diluar Area Others</option>        
                                          <option value="RO FIF">RO FIF</option>   
                                          <option value="RO ADR">RO ADR</option>        
                                          <option value="RO MCF">RO MCF</option>      
                                          <option value="RO Others">RO Others</option>    
                                          <option value="Bawaan CMO Non FIF">Bawaan CMO Non FIF</option>
                                          <option value="Lainnya" selected="true">Lainnya</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-7 col-md-7 hidden" id="ket_lain">
                                      <div class="form-group ">
                                        <label id="alasane">Alasan Lainnya</label>
                                        <input type="text" id="ket_alasan" name="ket_alasan" class="form-control" placeholder="Keterangan alasan lainnya" value=""/>
                                      </div> 
                                    </div>
                                    <?php
                                    if((isBolehAkses('e')|| isBolehAkses('v'))&& $leasingID>0):?>
                                    <div class="form-group">
                                      <div class="pull-right" style="padding-right: 20px;padding-top: 20px">
                                        <a class="btn btn-default <?php echo (($survey_leasing=='' || $leasingID==''))? "":"hidden";?>" id="upd_app" role="button"><i class="fa fa-save"></i> Simpan</a>
                                        <a class="btn btn-default <?php echo ($survey_leasing!='Un Approve'/* && $leasingID=='' && $show_approval==''*/)? "hidden":"";?>" id="change_lsd" role="button">Ganti Leasing</a>
                                      </div>
                                    </div>
                                  <?php endif;?>
                                  
                                  </div>
                                <!-- </form> -->
                              </fieldset>
                            </div>
                          </div>
                            <!-- end of panel body finansial -->
                      </div> <!-- end of panel margin botom 10 -->
                      <!-- </div> -->
                      <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="panel margin-bottom-10">
                          <div class="panel-headings">
                            <i class="fa fa-list-ul fa-fw"></i> Prosentase Leasing
                            <span class="pull-right">
                              <a id="modal-button" class="btn btn-default btn-xs <?php echo ($show_approval=='1')?'hidden':'';?>" onclick='addForm("<?php echo base_url('setup/leasing_komposisi'); ?>")' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-cog" title="setup prosentase leasing"></i> Setup Leasing</a>
                            </span>
                          </div>
                          <div class="panel-body panel-body-border" style="padding: 3px !important; overflow: auto">
                            <table class="table table-striped table-bordered" style="width: 100%" id="komposisi">
                              <thead>
                                <tr>
                                  <th style="width:10%">No.</th>
                                  <th style="width:10%">Kode</th>
                                  <th style="width:40%">Nama Dealer</th>
                                  <th style="width:15%">Target (%)</th>
                                  <th style="width:10%">T.Sls</th>
                                  <th style="width:15%">Achv (%)</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $n=0;
                                  if($prosensales){
                                    if($prosensales->totaldata>0){
                                      foreach ($prosensales->message as $key => $value) {
                                        $n++;
                                        echo "<tr id='".$value->KD_LEASING."'>
                                              <td class='text-center'>".$n."</td>
                                              <td class='text-center' title='".$value->NAMA_LEASING."'>".$value->KD_LEASING."</td>
                                              <td class='td-overflow' title='".$value->NAMA_LEASING."'>".$value->NAMA_LEASING."</td>
                                              <td class='text-center'>".number_format(((double)$value->TARGET_LEASING*100),0)."</td>
                                              <td class='text-center' title='Pencapaian penjualan berdasarkan SPK yang sudah terkirim'>".number_format(((double)$value->TOTAL_SALES),0)."</td>
                                              <td class='text-center'>".number_format(((double)$value->ACHIEVE*100),0)."</td>
                                              </tr>";
                                      }
                                    }
                                  }
                                ?>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colspan="6"></td>
                                </tr>
                                <?php 
                                  $x=0;
                                  if($hist_leasing){
                                    if((int)$hist_leasing->totaldata>1){
                                      echo "<tr class='info'><td colspan='6'>History Penolakan Leasing</td></tr>";
                                      foreach ($hist_leasing->message as $key => $value) {
                                        $x++;
                                        if((int)$value->ROW_STATUS>0){
                                          echo "<tr><td class='text-center'>$x</td>
                                               <td>".$value->KD_FINCOY."</td>
                                               <!--td class='td-overflow-20' title='".$value->NAMA_LEASING."'>".$value->NAMA_LEASING."</td-->
                                               <td colspan='3' class='' title='".$value->KETERANGAN."'>".str_replace("Lainnya:", " ", $value->KETERANGAN)."</td>
                                               <td class='text-center'>".tglFromSql($value->TANGGAL)."</td>
                                               </tr>";
                                        }
                                      }
                                    }
                                  }
                                ?>
                              </tfoot>
                            </table>

                          </div>
                        </div>
                      </div>
                    </fieldset>
                  </div>
                  <!-- row 2 detail kendaraan -->
                  <div class="row <?php echo ($status_spk>0)?'disabled-action':'';?>" >
                    <fieldset <?php echo ($show_approval=='1')?"disabled":"";?>>
                    <!-- Detail Motor -->
                    <div class="clearfix divider" role="separator"></div>
                      <div class="col-xs-12 col-sm-12 col-lg-12">
                        <div class="panel margin-bottom-10">
                          <div class="panel-heading">
                            <i class='fa fa-list-ul fa-fw'></i> Detail Kendaraan
                            <span class="pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
                          </div>
                          <div class="panel-body panel-body-border">
                            <table class="table table-striped table-bordered <?php echo ((int)$status_spk > 1 || $survey_leasing!='')?"hidden":"";?>" id="lstx_motor">
                              <thead>
                                <tr class="warning">
                                  <!-- <td>&nbsp;</td> -->
                                  <td class="table-nowarp" style="width: 30%"><?php echo DropDownMotorWithViewStock(false,true);?></td>
                                  <td style="width: 12%"><input type="text" id="harga_jual" name="harga_jual" readonly="readonly" class="form-control"></td>
                                  <td style="width: 8%"><input type="text" id="qty" name="qty" class="form-control" value="1" readonly="readonly"></td>
                                  <td style="width: 12%"><input type="text" id="biaya_stnk" name="biaya_stnk" readonly="readonly" class="form-control"></td>
                                  <td style="width: 12%"><input type="text" id="diskon" name="diskon" class="form-control">
                                    <input type="hidden" id="tp_diskon" name="tp_diskon"></td>
                                  <td style="width: 12%"><input type="text" id="total" name="total" readonly="readonly" class="form-control"></td>
                                  <td class="hidden" style="width: 12%"><input type="text" id="harga_dealer" name="harga_dealer" class="form-control"></td>
                                  <td class="hidden" style="width: 12%"><input type="text" id="harga_dealerd" name="harga_dealerd" class="form-control"></td>
                                  <td style="width: 6%"><a class="btn disabled-action" role="button" id="tmbh" onclick="__cekItemOnKupon();"><span id='clsx'><i class="fa fa-save fa-fw danger" ></i></span></a></td>
                                </tr>
                              </thead>
                            </table>
                            <div class="clearfix"></div>
                            <table class="table table-striped table-bordered" id="lst_motor">
                              <thead>
                              <!-- <thead> -->
                                <tr class=" text-center">
                                  <th class=" text-center" style="width: 30%">Kode Item</th>
                                  <th class=" text-center" style="width: 12%">Harga</th>
                                  <th class=" text-center" style="width: 8%">Qty</th>
                                  <th class=" text-center" style="width: 12%">Biaya STNK</th>
                                  <th class=" text-center" style="width: 12%"><abbr title="Di isi jika subsidi dealer tidak mengikuti Sales Program">Subsidi</abbr></th>
                                  <th class=" text-center" style="width: 12%">Jumlah</th>
                                  <th class=" text-center" style="width: 6%"></th>
                                </tr>
                              </thead> 
                              <tbody>
                                <?php 
                                $kd_tpm="";
                                if($this->input->get('id')!='' && $typejual=='CASH'){
                                    if($spk_motor){
                                      if(($spk_motor->totaldata>0)){
                                        $bariske=0;
                                        foreach ($spk_motor->message as $key => $value) {
                                          echo "<tr>
                                                <td class='table-nowarp'>".$value->KD_ITEM." [ ".$value->NAMA_ITEM." ]</td>
                                                <td class='text-right'>".number_format(($value->HARGA_OTR-$value->BBN),0)."</td>
                                                <td class='text-right'>1</td>
                                                <td class='text-right'>".number_format(($value->BBN),0)."</td>
                                                <td class='text-right'>".number_format(($value->DISKON),0)."</td>
                                                <td class='text-right'>".number_format(($value->HARGA_OTR-$value->DISKON),0)."</td>
                                                <td class='text-right hidden'>".number_format(($value->HARGA_DEALER),0)."</td>
                                                <td class='text-right hidden'>".number_format(($value->HARGA_DEALERD),0)."</td>
                                                <td class='text-center'><a class='$statusspk' onclick=\"hapus('".$bariske."');\"><i class='fa fa-trash'></i></a></td>
                                                </tr>";
                                                $kd_tpm .=$value->KD_ITEM;
                                                $kd_tpm .=($bariske==count($spkview->message))?"":",";
                                                $bariske++;
                                                $diskon = $value->DISKON;
                                        }
                                      }
                                    }
                                }
                                ?>
                              </tbody>
                              <tfoot>
                                <tr class="info hidden">
                                  <td colspan="5" class="text-right"> Total Harga</td>
                                  <td class="text-right"><span class="bold" id="ttharga"></span></td>
                                  <td></td>
                                </tr>
                              </tfoot>
                            </table><input type="hidden" id='kd_typemotore' name="kd_typemotore" value="">
                          </div>
                        </div>
                      </div>
                      <!-- end of detail motor -->
                    </fieldset>
                  </div>
                    <?php 
                      $item_guest="";
                        if($saleskupon2){
                          if(is_array($saleskupon2->message)){
                            foreach ($saleskupon2->message as $key => $value) {
                              $item_guest .= $value->KD_ITEM.",";
                            }
                          }
                        }
                    ?>
                  <input type="hidden" id="kdItemGuest" value="<?php echo $item_guest;?>">
                  <input type="hidden" id="diskount" value="<?php echo $diskon;?>">
                  <!-- end of row2 -->
                  <!-- bundling program,sales program dan saleskupon -->
                  <div class="row <?php echo ($status_spk>0)?'disabled-action':'';?>" id="panelsales">
                    <!-- Panel bundling program  col 2-->
                    <div class="col-xs-12 col-sm-6 col-md-6">
                      <fieldset <?php echo ((int)$status_spk >0 || $show_approval=='1')?" disabled":"";?>>
                        <div class="panel margin-bottom-10">
                          <div class="panel-heading">
                            <i class="fa fa-gift fa-fw"></i> Sales Program <?php echo $status_spk;?>
                              <span class="tools pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
                          </div>
                          <div class="panel-body panel-body-border">
                            <div class="form-group">
                              <label>Bundling Program</label>
                              <select class="form-control" name="kd_bundling" id="kd_bundling">
                                <option value="">-- Pilih Bundling Program --</option>
                                <?php
                                  if($bundling){
                                    if(is_array($bundling->message)){
                                      foreach ($bundling->message as $key => $value) {
                                         echo "<option value='".$value->KD_BUNDLING."'>".$value->NAMA_BUNDLING." [ ".$value->KD_TYPEMOTOR." - ".$value->KD_WARNA." ]</option>";
                                      }
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Sales Program <span id="program_lg" style="color: red;"></span></label>
                              <select class="form-control" name="kd_salesprogram" id="kd_salesprogram">
                                <option value="">-- Pilih Sales Program --</option>
                                <?php
                                  if(isset($salesprogram)){
                                    if($salesprogram->totaldata>0){
                                      foreach ($salesprogram->message as $key => $value) {
                                         echo "<option value='".$value->KD_SALESPROGRAM."'>[".$value->KD_SALESPROGRAM."] ".$value->NAMA_SALESPROGRAM."</option>";
                                      }
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                            <div class="form-group disabled-action" id="kuponsales">
                            <label>Sales Kupon<span id="kupon_lg"></span></label>
                            <select name="kd_saleskupon" id="kd_saleskupon" class="form-control">
                              <option value="">-- Pilih Sales Kupon --</option>
                              <?php
                                $kodene=array();
                                //print_r($saleskupon);
                                if($saleskupon){
                                  if(is_array($saleskupon)){
                                  sort($saleskupon);
                                  foreach ($saleskupon as $key => $value) {
                                      if(!in_array($value->KD_SALESKUPON,$kodene)){
                                        echo "<option value='".$value->KD_SALESKUPON."'>[".$value->KD_SALESKUPON."] ".$value->NAMA_SALESKUPON."</option>";
                                      }
                                      $kodene[]=$value->KD_SALESKUPON;
                                    }
                                  }
                                }
                                // sort($kodene);
                                /*print_r(array_unique($kodene));*/
                              ?>
                            </select>
                          </div>
                            <div class="row hidden">
                              <div class="col-xs-12">
                                <div class="form-group">
                                    <input type="checkbox" name="crm"  id="crm" value="" /> Program CRM &nbsp; &nbsp;
                                </div>
                              </div>
                              <div class="col-xs-12"> 
                                <div class="form-group">
                                  <input type="checkbox" name="hadiah" id="hadiah" value="" /> Program Hadiah &nbsp; &nbsp; &nbsp;
                                </div>
                              </div>
                            </div>
                            
                        </div> <!-- end of panel -->
                      </fieldset>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                      <div class="panel margin-bottom-10">
                        <div class="panel-headings">
                          <i class="fa fa-gift"></i> List Sales Program yang di ikuti <span id="ldsp" style="color: red;"></span>
                        </div>
                        <div class="panel-body panel-body-border">
                          <fieldset class="<?php echo ($status_spk>0)?'disabled-action':'';?>">  
                            <div class="form-group">
                              <!-- <h6> -->
                                <ul id="sls_kupon" class="list-group no-bottom">
                                </ul>

                              <!-- </h6> -->
                              <input type="hidden" name="kd_saleskupon_grp" id="kd_saleskupon_grp">
                              <input type="hidden" name="kd_program_grp" id="kd_program_grp">
                              <input type="hidden" name="kd_bundling_grp" id="kd_bundling_grp">
                            </div>
                          </fieldset>
                            <ul id="sls_prg" class="list-group no-bottom">
                              <!-- proses ini diganti dengan js __getDetailSalesProgram() -->
                                <?php 
                                  /*if(isset($salesprg)){
                                    if($salesprg->totaldata >0){
                                      $total=0;
                                      echo "<li class='list-group-item active'> Detail Subsidi Sales Program</li>";
                                      foreach ($salesprg->message as $key => $value) {
                                        if($typejual=='CASH'){
                                          $diskon = ((double)$diskon>0)? $diskon : $value->MIN_SC_SD;
                                          echo "<li class='list-group-item'> Subsidi AHM <span class='pull-right'>".number_format($value->SC_AHM,0)."</li>";
                                          echo "<li class='list-group-item'> Subsidi MD <span class='pull-right'>".number_format($value->SC_MD,0)."</li>";
                                          echo "<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>".number_format($diskon,0)."</li>";
                                          $total += $value->SC_AHM;
                                          $total += $value->SC_MD;
                                          $total += $diskon;
                                        }else{
                                          $diskon = ((double)$diskon>0)? $diskon : $value->MIN_SK_SD;
                                          echo "<li class='list-group-item'> Subsidi AHM <span class='pull-right'>".number_format($value->SK_AHM,0)."</li>";
                                          echo "<li class='list-group-item'> Subsidi MD <span class='pull-right'>".number_format($value->SK_MD,0)."</li>";
                                          echo "<li class='list-group-item'> Subsidi DEALER <span class='pull-right'>".number_format($diskon,0)."</li>";
                                          echo "<li class='list-group-item'> Subsidi FINANCE <span class='pull-right'>".number_format($value->SK_FINANCE,0)."</li>";
                                          $total += $value->SK_AHM;
                                          $total += $value->SK_FINANCE;
                                          $total += $value->SK_MD;
                                          $total += $diskon;
                                        }
                                      }
                                      echo "<li class='list-group-item info'> Total Subsidi <span class='pull-right'><b><em>".number_format($total,0)."</em></b></span></li>";
                                    }
                                  }*/
                                ?>
                            </ul>
                        </div>
                      </div>
                    </div>
                    <!-- end of panel bundling progran -->
                  </div>
                  <!-- end of row 1 -->
                  
                  <div class="clearfix divider" role="separator"></div>
                  <!-- row 3 keterangan tambahan -->
                  <div class="row <?php echo ($status_spk>0)?'disabled-action':'';?>">
                    <fieldset <?php echo ($show_approval=='1')?"disabled":"";?> id="ket_tambahan">
                    <div class="col-xs-12 col-sm-12 col-lg-12">
                      <div class="panel margin-bottom-10">
                        <div class="panel-heading">
                          <i class="fa fa-bookmark-o"></i> Keterangan Tambahan
                          <span class="tools pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
                        </div>
                        <div class="panel-body panel-body-border">
                          <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                              <div class="row">
                                <div class="col-sm-4 col-md-4 col-xs-6">
                                  <div class="form-group">
                                    <label>Tanggal Kirim</label>
                                    <div class="input-group input-append date" id="datex">
                                      <input type="text" name="tgl_kirim" id="tgl_kirim" class="form-control" required="true" placeholder="dd/mmm/yyyy" value='<?php echo $tgl_kirim;?>'>
                                      <span class="input-group input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-6">
                                  <div class="form-group">
                                      <label>Jam Kirim</label>
                                      <div class="input-group input-append datetime-mulai" id="datetime">
                                          <input class="form-control" id="jam_kirim" name="jam_kirim" placeholder="HH:MM" value="<?php echo $jam_kirim;?>" type="text"/>
                                          <span class="input-group-addon add-on"><span class="glyphicon glyphicon-time"></span></span>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-sm-5 col-md-5 col-xs-6">
                                    <div class="form-group">
                                        <label>Nama Penerima</label>
                                        <input type="text" id="nama_penerima" name="nama_penerima" class="form-control" required>
                                    </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <label>Alamat Pengiriman</label>
                                    <input type="radio" name="like_alamat" id="like_alamatrumah" value="Rumah" /> Alamat Rumah
                                    <input type="radio" name="like_alamat" id="like_alamatsurat" value="Surat" /> Alamat Surat
                                    <input type="radio" name="like_alamat" id="lainnya" value="Lainnya" /> Alamat Lainnya
                                     <textarea id="alamat_pengiriman" rows="4" name="alamat_pengiriman" class="form-control" required></textarea>
                                  </div>
                                </div>
                              </div>
                              
                            </div>
                            
                            <div class="col-xs-12 col-sm-6 col-md-6">
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label>Estimasi STNK</label>
                                    <div class="input-group input-append date datex" id="datex">
                                      <input type="text" name="tgl_stnk" id="tgl_stnk" class="form-control" required="true" placeholder="dd/mmm/yyyy" value='<?php echo date("d/m/Y",strtotime("$tglspk_asli +1 Month"));?>'>
                                      <span class="input-group input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label>Estimasi BPKB</label>
                                    <div class="input-group input-append date datex" id="datex">
                                      <input type="text" name="tgl_bpkb" id="tgl_bpkb" class="form-control" required="true" placeholder="dd/mmm/yyyy" value='<?php echo date("d/m/Y",strtotime("$tglspk_asli +1 Month"));?>'>
                                      <span class="input-group input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                    <label>Keterangan Tambahan</label>
                                    <textarea name="keterangan_tambahan" id="keterangan_tambahan" class="form-control" placeholder="Masukkan Keterangan Tambahan"></textarea>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                    <label>Nomor HP Penerima</label>
                                    <input type="text" name="no_hp_surat" id="no_hp_surat" class="form-control" placeholder="Masukkan Nomor HP" required>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  </div>
                  <!-- end of row3 -->
                </form>
              </div>
              <!--panel quis-->
              <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="3")? "active":"";?>" id="tabs-3">
                <fieldset <?php echo ($show_approval=='1')?"disabled":"";?>>
                  <form id="addForm_quiz" action="<?php echo base_url('spk/simpanquiz_spk') ?>" class="bucket-form" method="post">
                    <input type="hidden" name="spkid_quiz" id="spkid_quiz" value="<?php echo $this->input->get('id');?>">
                      <div class="panel-heading">
                        <i class="fa fa-question-circle-o"></i> Data Kuis
                        <span class="tools pull-right"> <a class="fa fa-chevron-down" href="javascript:;"></a></span>
                      </div>

                      <div class="panel-body panel-body-border">
                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              1. Pekerjaan
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="1_data_pekerjaan" id="1_data_pekerjaan" class="form-control">
                                <option value="">-- Pilih Data Pekerjaan --</option>
                                <?php
                                  if($pekerjaan){
                                    if(is_array($pekerjaan->message)){
                                      foreach ($pekerjaan->message as $key => $value) {
                                        $select=($kd_pekerjaan==$value->KD_PEKERJAAN)?"selected":"";
                                       echo "<option value='".$value->KD_PEKERJAAN."' ".$select.">".$value->NAMA_PEKERJAAN."</option>";
                                      }
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              2. Pengeluaran / Bulan
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="2_pengeluaran_bulanan" id="2_pengeluaran_bulanan" class="form-control">
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
                        </div>

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              3. Pendidikan Terakhir
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="3_pendidikan_terakhir" id="3_pendidikan_terakhir" class="form-control">
                                <option value="">-- Pilih Pendidikan Terakhir --</option>
                                <?php
                                  if(isset($pendidikan)){
                                    if($pendidikan->totaldata >0){
                                      foreach ($pendidikan->message as $key => $value) {
                                        $pilih =($kd_pendidikan== $value->KD_PENDIDIKAN)?'selected':'';
                                       echo "<option value='".$value->KD_PENDIDIKAN."' ".$pilih.">".$value->NAMA_PENDIDIKAN."</option>";
                                      }
                                    }
                                  }
                                ?>
                                <<!-- option value="SD" <?php echo ($kd_pendidikan=="SD")?"selected":"";?>>SD</option>
                                <option value="SLTP" <?php echo ($kd_pendidikan=="SLTP")?"selected":"";?>>SLTP</option>
                                <option value="SLTA" <?php echo ($pengeluaran=="SLTA")?"selected":"";?>>SLTA</option>
                                <option value="DIPLOMA" <?php echo ($kd_pendidikan=="DIPLOMA")?"selected":"";?>>DIPLOMA</option>
                                <option value="S1" <?php echo ($kd_pendidikan=="S1")?"selected":"";?>>STRATA 1</option>
                                <option value="S2" <?php echo ($kd_pendidikan=="S2")?"selected":"";?>>STRATA 2</option>
                                <option value="S3" <?php echo ($kd_pendidikan=="S3")?"selected":"";?>>STRATA 3</option> -->
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              &nbsp; &nbsp; &nbsp;Nomor Telephone
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <input type="text" name="31_no_telpon" id="31_no_telpon" class="form-control" placeholder="Masukkan Nomor Telepon" value="<?php echo $no_telp;?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              &nbsp; &nbsp; &nbsp;Nomor HP/GSM
                            </div>
                          </div>

                          <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                              <input type="text" name="32_no_hp" id="32_no_hp" class="form-control" placeholder="Masukkan Nomor HP/GSM" value="<?php echo $no_telp;?>">
                            </div>
                          </div>

                          <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                              <select name="34_status_hp" id="34_status_hp" class="form-control">
                                <option value="">-- Pilih Status Kepemilikan HP --</option>
                                <option value="1">Pra Bayar (Isi Ulang)</option>
                                <option value="2">Pasca Bayar /Billing/Tagihan</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              4. Apakah bersedia dikirimkan informasi terbaru program Honda ?
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="form-group">
                              <select name="4_informasi_terbaru" id="4_informasi_terbaru" class="form-control">
                                <option value="Y">Ya</option>
                                <option value="N"> Tidak </option>
                              </select>
                            </div>
                          </div>
                          <div class="col-xs-12 col-sm-2 col-md-2">
                            <div class="form-group">
                              Jenis Customer
                            </div>
                          </div>
                          <div class="col-xs-6 col-sm-3 col-md-3">
                            <div class="form-group">
                              <input type="hidden" id="jenis_cust" value="">
                              <select id="41_jenis_customer" name ="41_jenis_customer" class="form-control">
                                <option value="">--Pilih Jenis Customer--</option>
                                    <option value="W">Walk In</option>
                                    <!-- <option value="Gathering" >Gathering</option> -->
                                    <option value="E">Exhibition</option>
                                    <option value="C">Canvasing</option>
                                    <!-- <option value="Roadshow">Roadshow</option> -->
                              </select>
                            </div>
                          </div>
                        </div> 


                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              5. Apakah merk motor yang anda milik sebelumnya ?
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="5_merk_motor" id="5_merk_motor" class="form-control">
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
                                <!-- <option value="Honda">Honda </option>
                                <option value="Yamaha">Yamaha </option>
                                <option value="Suzuki">Suzuki </option>
                                <option value="Kawasaki">Kawasaki </option>
                                <option value="Motor China">Motor China </option>
                                <option value="Lainnya">Lainnya </option> -->
                              </select>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              6. Jenis motor yang mana anda miliki sebelumnya ?
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="6_jenis_motor" id="6_jenis_motor" class="form-control">
                                <option value="">-- Pilih Jenis Motor --</option>
                                <option value="Sport">Sport</option>
                                <option value="Cub">Cub (Bebek)</option>
                                <option value="AT">AT(Automatic)</option>
                                <option value="Tidak">Belum pernah memiliki</option>
                              </select>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              7. Sepeda motor yang anda beli ini digunakan untuk ?
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <!-- <input type="text" name="7_digunakan_untuk" id="7_digunakan_untuk" class="form-control" placeholder="Masukkan jawaban"> -->
                              <select name="7_digunakan_untuk" id="7_digunakan_untuk" class="form-control">
                                <option value="">-- Pilih Penggunaan --</option>
                                <option value="Berdagang">1. Berdagang</option>
                                <option value="Pemakaian jarak dekat">2. Pemakaian jarak dekat</option>
                                <option value="Ke Sekolah / Kampus">3. Ke Sekolah / Kampus</option>
                                <option value="Rekreasi / Olahraga">4. Rekreasi / Olahraga</option>
                                <option value="Kebutuhan Keluarga">5. Kebutuhan Keluarga</option>
                                <option value="Lain - Lain">6. Lain - Lain</option>
                                <option value="Bekerja">7. Bekerja</option>
                              </select>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              8. Yang menggunakan sepeda motor anda?
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <select name="8_yang_menggunakan" id="8_yang_menggunakan" class="form-control">
                                <option value="">-- Pilih Pemakai --</option>
                                <option value="Sendiri">Saya Sendiri</option>
                                <option value="Anda">Anak</option>
                                <option value="Pasangan">Pasangan</option>
                                <option value="Lainnya">Lainnya</option>
                              </select>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              9. Jenis Penjualan
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7 <?php echo $kunci;?>">
                            <div class="form-group">
                              <select name="9_jenis_penjualan" id="9_jenis_penjualan" class="form-control">
                                <option value="">--Jenis Penjualan --</option>
                                <option value="CASH" <?php echo ($typejual=="CASH")?"selected":"";?>>CASH</option>
                                <option value="CREDIT" <?php echo ($typejual=="CREDIT")?"selected":"";?>>CREDIT</option>
                              </select>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                              10. ID Sales Person
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-5 col-md-5 <?php echo $kunci;?>">
                            <div class="form-group">
                              <?php echo DropdownSales("_10_sales_quiz",$this->session->userdata("kd_dealer"),$namasales);?>
                            </div>
                          </div>
                        </div> 

                        <div class="row">
                          <div class="col-xs-12 col-sm-5 col-md-5">
                            <div class="form-group">
                             &nbsp; &nbsp; &nbsp; Keterangan</div>
                          </div>

                          <div class="col-xs-12 col-sm-7 col-md-7">
                            <div class="form-group">
                              <textarea name="101_keterangan_quiz" id="101_keterangan_quiz" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                  </form>
                </fieldset>
              </div>
              <!--end of panel quis-->
              <!-- panel titipan uang muka unit -->
              <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="4")? "active":"";?>" id="tabs-4">
                  <div class="table-responsive h250">
                    <div class="panel-heading">
                      <i class="fa fa-fingerprint"></i> Data Titipan Uang Muka
                    </div>
                    <div class="panel-body panel-body-border">
                      <form id="frmTitipan" method="post" action="" class="bucket-form" >
                        <div class="col-xs-6 col-md-4 col-sm-4 <?php echo ((int)$status_spk >0)?'disabled-action':"";?>">
                          <div class="form-group">
                            <label>Input Jumlah Titipan</label>
                            <input type="text" class="form-control text-right" id="jml_titipan" name="jml_titipan" required>
                          </div>
                        </div>
                        <div class="col-xs-12 col-md-8 col-sm-8">
                          <div class="form-group">
                            <br>
                            <button id="simpan_tipu" class="btn btn-info <?php echo ($nomorspk)?'':'disabled-action';?>" type="button" onclick="__tipum_simpan('<?php echo $nomorspk;?>');"><i class="fa fa-save"></i> Simpan</button> 
                          </div>
                        </div>
                      </form>
                      <!-- </div> -->

                      <table class="table table-bordered table-hover table-striped">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>&nbsp;</th>
                            <th>Tgl Titipan</th>
                            <th>No.Kwitansi</th>
                            <th>No.SPK</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $n=0;
                            if(isset($tipun)){
                              if($tipun->totaldata >0){
                                foreach ($tipun->message as $key => $value) {
                                  $n++;
                                  $status="";
                                  switch($value->STATUS_TITIPAN){
                                    case "1": $status="Kasir";break;
                                    case "2": $status="Close";break;
                                    default: $status ="Counter";break;
                                  }
                                  ?>
                                    <tr>
                                      <td class='text-center'><?php echo $n;?></td>
                                      <td class='text-center'><a onclick="_tipum_delete('<?php echo $value->NO_TRANS;?>');" class='<?php echo $status_e;?> <?php echo ($value->STATUS_TITIPAN >0)?'hidden':"";?>'><i class='fa fa-trash'></i></a></td>
                                      <td class='text-center'><?php echo tglFromSql($value->TGL_TRANS);?></td>
                                      <td class='text-center table-nowarp'><?php echo $value->NO_KWITANSI;?></td>
                                      <td class='text-center table-nowarp'><?php echo $value->NO_REFF;?></td>
                                      <td class='text-right table-nowarp'><?php echo number_format($value->JUMLAH_TITIPAN,0);?></td>
                                      <td class='table-nowarp'><?php echo $status;?></td>
                                    </tr>
                                  <?php
                                }
                              }
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
              </div>
              <!-- end of titipan uang muka unit -->
              <!-- data anggota keluarga -->
              <div role="tabpanel" class="tab-pane <?php echo ($tabaktife=="5")? "active":"";?>" id="tabs-5">
                  <div class="table-responsive">
                    <div class="panel-heading">
                      <i class="fa fa-users"></i> Data Anggota Keluarga
                    </div>
                    <div class="panel-body panel-body-border">
                        <form id="addForm_kk" method="post" action="<?php echo base_url('spk/simpankk_spk') ?>" class="bucket-form">
                           <div class="row">
                              <div class="col-md-4 col-sm-4 col-xs-4">
                                 <div class="form-group">
                                    <label>Nomor KK <span id="l_nokk"></span></label>
                                    <input type="text" class="form-control number" id="no_kk" name="no_kk" placeholder="Masukan No Kartu keluarga" required="true" minlength="16" maxlength="16" pattern="^[0–9]$">
                                 </div>
                              </div>
                              <div class="col-xs-6 col-md-6 col-sm-6">
                                 <div class="form-group">
                                    <label>Alamat Sesuai KK</label>
                                    <input type="text" class="form-control" id="alamat_kk" name="alamat_kk" placeholder="Alamat Sesuai KK">
                                 </div>
                              </div>
                              <div class="col-xs-2 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    <label>RT/RW</label>
                                    <input type="text" class="form-control" id="rtrw_kk" name="rtrw_kk" placeholder="RT/RW">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-xs-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>Propinsi <span id="l_propinsi_kk"></span></label>
                                    <select id="propinsi_kk" name="propinsi_kk" class="form-control" title="propinsi">
                                       <option value="">--Pilih Propinsi--</option>
                                       <?php
                                          if ($propinsi) {
                                             if (is_array($propinsi->message)) {
                                                foreach ($propinsi->message as $key => $value) {
                                                    $pilih=($kd_propinsi_bpkb == $value->KD_PROPINSI)?"selected":"";
                                                    echo "<option value='" . $value->KD_PROPINSI . "' ".$pilih.">" . $value->NAMA_PROPINSI . "</option>";
                                                }
                                             }
                                          }
                                       ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>Kabupaten <span id="l_kabupaten_kk"></span></label>
                                    <select id="kabupaten_kk" name="kabupaten_kk" class="form-control" title="kabupaten">
                                       <option value="">--Pilih Kabupaten--</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>Kecamatan <span id="l_kecamatan_kk"></span></label>
                                    <select id="kecamatan_kk" name="kecamatan_kk" class="form-control" title="kecamatan">
                                       <option value="">--Pilih Kecamatan--</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>Kelurahan/Desa <span id="l_desa_kk"></span></label>
                                    <select id="desa_kk" name="desa_kk" class="form-control" title="desa">
                                       <option value="">--Pilih Kelurahan--</option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <h5 style="margin-top:2px;"><i>List Anggota Keluara</i></h5>
                           <div class="row">
                              <div class="col-xs-6 col-md-4 col-sm-4">
                                 <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" id="nama_kk_l" name="nama_kk_l" placeholder="Nama Anggota">
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select id="sex_kk_l" name="sex_kk_l" class="form-control">
                                       <option value=''>--Pilih--</option>
                                       <?php 
                                       if($gender){
                                          if(($gender->totaldata>0)){
                                             foreach ($gender->message as $key => $value) {
                                               echo "<option value='".$value->KD_GENDER."'>".$value->NAMA_GENDER."</option>";
                                             }
                                           }
                                         }
                                       ?> 
                                    </select>
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-2 col-sm-2">
                                 <div class="form-group">
                                    <label>Tgl Lahir</label>
                                    <input type="text" class="form-control" id="bod_kk_l" name="bod_kk_l" placeholder="dd/mm/yyyy" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" data-mask="00/00/0000">
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-3 col-sm-3">
                                 <div class="form-group">
                                    <label>NIK</label>
                                    <input type="text" class="form-control number" id="nik_kk_l" name="nik_kk_l" placeholder="Nomor Induk Kependudukan" maxlength="16">
                                 </div>
                              </div>
                              <div class="col-xs-3 col-md-1 col-sm-1">
                                 <div class="form-group">
                                    <br>
                                    <button class='btn btn-info' onclick="__addItem();" type="button"><i class='fa fa-plus'></i> Add</button>
                                 </div>
                              </div>
                           </div>
                        </form>
                        <div class="row">
                           <table class='table table-striped table-bordered' id="lst_kk">
                              <thead>
                                 <tr>
                                    <th style="width:5%">No</th>
                                    <th style="width:6%">&nbsp;</th>
                                    <th style="width:25%">Nama Anggota</th>
                                    <th style="width:5%">Gender</th>
                                    <th style="width:10%">Tgl Lahir</th>
                                    <th style="width:15%">NIK</th>
                                    <th>&nbsp;</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 
                              </tbody>
                           </table>
                    </div>
                  </div>
              </div>
              <!-- end of data anggota keluarga -->
          </div>
        </div>
    </div><button id="myBtn" class="hidden">Test</button>
<?php echo loading_proses();?>
<div class="modalx" id="myModal_alasan">
  <div class="modal-contentx">
    <div class="list-group">
      <div class="list-group-item active">Pilih Alasan menggunakan Leasing ke 2 <span class="badge pull-right closex" role="button" onclick="keluar()">&times;</span></div>
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio1" value="Tolakan / Blacklist FIF"> Tolakan / Blacklist FIF</div>   
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio2" value="Tolakan / Blacklist ADR"> Tolakan / Blacklist ADR</div> 
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio3" value="Tolakan / Blacklist MCF"> Tolakan / Blacklist MCF</div> 
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio4" value="Tolakan / Blacklist Others"> Tolakan / Blacklist Others</div>   
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio5" value="Diluar Area FIF"> Diluar Area FIF</div>         
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio6" value="Diluar Area FIF"> Diluar Area ADR</div>             
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio7" value="Diluar Area MCF"> Diluar Area MCF</div>
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio8" value="Diluar Area Others"> Diluar Area Others</div>        
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio9" value="RO MCF"> RO MCF</div>      
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio10" value="RO Others"> RO Others</div>    
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio11" value="Bawaan CMO Non FIF"> Bawaan CMO Non FIF</div>
      <div class="list-group-item "><input style="cursor: pointer" type="radio" name="radiox" id="radio12" value="Lainnya"> Lainnya</div>
     <div class="pull-right">
      <!-- <hr> -->
      <a class="btn btn-default" style="margin-top: 12px !important" onclick="keluar()" role="button"><i class="fa fa-close"></i> Batal</a>
        <a class="btn btn-info" style="margin-top: 12px !important" onclick="__simpanAlasan();"  role="button"><i class="fa fa-close"></i> Simpan</a>
      </div>
      
    </div>
  </div>
  <div class="modal-footer hidden">
    <!-- <li class="info" style="height: 50px"> -->
        <!-- <p class="pull-right" style="padding-top: 8px; padding-right: 10px"> -->
        <!-- <a class="btn btn-default" onclick="keluar()" role="button"><i class="fa fa-close"></i> Batal</a>
        <a class="btn btn-info" onclick="__simpanAlasan();"  role="button"><i class="fa fa-close"></i> Simpan</a> -->
      <!-- </p> -->
    <!-- </li> -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var nospk="<?php echo $nomorspk;?>"
    if(nospk!=''){
      $('.header_frm').attr("disabled","disabled");
    }

  })
  window.onload=function(){
    var tab="<?php echo $this->input->get('tab');?>"
    if($("#kd_groupsales").val()=="SC" || $("#kd_groupsales").val()=="SM"){
      $('#nama_sales').attr('required',true);
    } else{
      $('#nama_sales').attr('required',false);
    }
    if($('#kd_typemotore').val()!=''){
      $('#kd_item2').val('');
    }

    var jpjual="<?php echo $antardealer;?>";
    console.log(jpjual);
    if(jpjual){ $('#jp_antardealer').trigger('change');}
  }
  
</script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/spk.js");?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/external/spk_kk.js");?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.number').keypress(validateNumber);
    });
    
  var modal = document.getElementById('myModal_alasan');
  // Get the button that opens the modal
  var btn = document.getElementById("myBtn");
  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("closex")[0];
  // When the user clicks on the button, open the modal 
  btn.onclick = function() {
      modal.style.display = "block";
      //document.getElementById('fade').style.display='block'
  }

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
      modal.style.display = "none";
  }

  function keluar(){
    $('#kd_fincom').prop('selectedIndex','0');
    modal.style.display = "none";
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

