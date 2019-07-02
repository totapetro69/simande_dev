<?php
    if (!isBolehAkses()) { redirect(base_url() . 'auth/error_auth'); }
  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $mode="disabled-action";
  $no_trans=base64_decode(urldecode($this->input->get("n")));
  $jenis_order="";$tgl_po="";$nama_konsumen="";$kd_konsumen="";$no_telp="";$alamat_konsumen="";$kd_customer="";
  $kota_konsumen="";$vor="";$jrs="";$bulan="";$tahun="";$kd_typemotor="";$tahun_motor="";$approval=0;
  $tgl_trans=date("d/m/Y");$tipecustomer=$this->input->get("jenis_customer"); $booking=0;$order_to="";$kemana="";
  $SudahDiApprove=($approval=="1")?"disabled-action":"";$reff_doc="";$so_status="0";
  $defultCust="R";$kd_pos="";$kd_kabupaten="";$kd_kecamatan="";$kd_desa="";$no_polisi="";$kd_typemotor="";
  $jenis_so="";
  $defaultDealer= $this->session->userdata("kd_dealer");$no_polisi="";
  $defaultLokasi= $this->session->userdata("kd_lokasi");
  $kd_propinsi=$this->session->userdata("kd_propinsi");

  $nomor_sa="";

  if($this->input->get('s')){
    $nomor_sa = base64_decode(urldecode($this->input->get('s')));
    // $nomor_sa = urlencode(base64_encode($this->input->get('s')));
  }


  if(isset($soh)){
    if($soh->totaldata>0){
        foreach ($soh->message as $key => $value) {
            $tipecustomer = $value->TYPE_CUSTOMER;
            $defaultDealer =$value->KD_DEALER;
            $tgl_trans = tglFromSql($value->TGL_TRANS);
            $booking =$value->BOOKING_ORDER;
            $order_to =$value->ORDER_TO;
            $kd_customer = $value->KD_CUSTOMER;
            $kd_typemotor = $value->KD_TYPEMOTOR;
            $vor = $value->VOR;
            $jrs = $value->JR;
            $tahun_motor = $value->TAHUN_MOTOR;
            $reff_doc = $value->REFF_DOC;
            $so_status = ($value->SO_STATUS)?$value->SO_STATUS:"0";
            $defaultLokasi = $value->KD_LOKASI;
            $jenis_so = $value->JENIS_PART;
        }
    }
  }
  switch ($booking) {
      case '1':
          switch ($order_to) {
              case 'T10':
                  $kemana="MAIN DEALER";
                  break;
              case '':
                $kemana ="AHM";
                break;
              default:
                  $kemana=$order_to;
                  break;
          }
          break;
      default:
          # code...
          break;
  }
  //if($booking=="1" && $kd_customer!=''){
    $data=(isset($socs))?$socs:null;//infoCustomer($kd_customer,true);
    if($data){
        if($data->totaldata>0){
            foreach ($data->message as $key => $value) {
                $nama_konsumen = $value->NAMA_CUSTOMER;
                $alamat_konsumen = $value->ALAMAT_SURAT;
                $kd_kecamatan =$value->KD_KECAMATAN;
                $kd_kabupaten = $value->KD_KABUPATEN;
                $kd_propinsi = $value->KD_PROPINSI;
                $no_telp = $value->NO_HP;
                $kd_desa = $value->KD_DESA;
                $kd_pos = $value->KODE_POS;
                $no_polisi = $value->NO_POLISI;
                $kd_typemotor = $value->KD_TYPEMOTOR;
                $thn_motor = $value->TAHUN_MOTOR;
            }
        }
    }
  //}
 if($this->session->userdata("nama_group")!="Root"){
    if($this->session->userdata("kd_dealer")!=$defaultDealer){
        redirect(base_url("cashier/listsop"));
    }
 }
 $mode=($no_trans)?$mode:"";
 $mode_print=((int)$so_status >0 && $no_trans)?'':'disabled-action';
 $mode_edit =((int)$so_status ==0 && $no_trans)?'':$mode;
 /**
  * SO PART STATUS :
    0: input SO
    1: PICKING
    2: Kasir
  */
    $txt_sts="";
    switch($so_status){ case "1":$txt_sts='PICKING';break; case '2': $txt_sts='PAID';  break; case "0": $txt_sts='OPEN';break;}
?>
<section class="wrapper">
    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>
        <div class="bar-nav pull-right ">
            <a id="modal-button-1" class="btn btn-default hidden" href="<?php echo base_url('inventori/viewstock'); ?>" role="button">
                <i class="fa fa-search fa-fw"></i> View Stock
            </a>
            <a  class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('cashier/addsop'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Add SO Baru
            </a>
            <a id="simpan-data" class="btn btn-default <?php echo $status_c;?> <?php echo $mode_edit;?>" role="button" onclick="__simpanData();">
                <i class="fa fa-list-alt fa-fw"></i><?php echo ($no_trans)? "Update ":"Simpan";?>
            </a>
            <a class="btn btn-default hidden-xs <?php echo $mode_print;?>" id="modal-button" 
                onclick='addForm("<?php echo base_url('cashier/nota_sukucadang/'.urlencode(base64_encode($no_trans))); ?>");'  
                role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Print Nota
            </a>
            <a  class="btn btn-default hidden-xs" href="<?php echo base_url('cashier/listsop'); ?>" role="button">
                <i class="fa fa-list-ul fa-fw"></i> List Sales Order
            </a>
        </div>
    </div>
    <div class="col-lg-12 padding-left-right-10">
        <div class="panel margin-bottom-10">
            <div class="panel-heading"><i class="fa fa-file-o"></i> Input Sales Order <?php echo ($no_trans)?"<span class='badge info'>".$txt_sts."</span>":"";?>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-up" href="javascript:;"></a>
                </span>
            </div>
            <form id="frm_so" method="post" action="">

                <input type="hidden" name="nomor_sa" id="nomor_sa" value="<?php echo $nomor_sa;?>" class="form-control" readonly="true">
                
                <div class="panel-body panel-body-border" style="display: block;">
                    <div class="row <?php echo $mode_edit;?>"  class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-xs-12 col-sm-3 col-md-3 no-margin-r">
                            <div class="form-group">
                                <label>Dealer</label>
                                <select id="kd_dealer" name="kd_dealer" class="form-control">
                                    <option value="">--Pilih Dealer--</option>
                                    <?php
                                        if($dealer){
                                            if(is_array($dealer->message)){
                                                foreach ($dealer->message as $key => $value) {
                                                    $select=($defaultDealer==$value->KD_DEALER)?"selected":"";
                                                    echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                                                }
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 no-margin-r no-margin-l">
                            <div class='form-group'>
                                <label>Lokasi Penjualan <?php echo $defaultLokasi;?></label>
                                <select id="lokasi_jual" name="lokasi_jual" class="form-control" required="true">
                                    <option value="">--Pilih Lokasi--</option>
                                    <?php 
                                        if(isset($lokasi)){
                                            foreach ($lokasi->message as $key => $value) {
                                                $selected=($defaultLokasi==$value->KD_LOKASI)?"selected":"";
                                                echo "<option value='".$value->KD_LOKASI."' ".$selected.">".strtoupper($value->KD_LOKASI)." [".strtoupper($value->NAMA_LOKASI)."]</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 no-margin-r no-margin-l">
                            <div class="form-group">
                                <label>Jenis Customer</label>
                                <select id="jenis_customer" name="jenis_customer" class="form-control">
                                    <option value="">--Pilih Jenis Customer--</option>
                                    <?php
                                       if($typecustomer){
                                          if(is_array($typecustomer->message)){
                                              foreach ($typecustomer->message as $key => $value) {
                                                $select=($defultCust==$value->KD_TYPECUSTOMER)?"selected":"";
                                                $select=($tipecustomer==$value->KD_TYPECUSTOMER)?"selected":$select;
                                                   echo "<option value='".$value->KD_TYPECUSTOMER."' ".$select.">".$value->NAMA_TYPECUSTOMER."</option>";
                                              }
                                          }
                                       }
                                      ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 no-margin-r no-margin-l ">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group input-append date">
                                    <input type="text" class="form-control disabled-action" name="tgl_trans" id="tgl_trans" value="<?php echo $tgl_trans;?>">
                                    <span class="input-group-addon"><i class='glyphicon glyphicon-calendar'></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-3 no-margin-l">
                            <div class="form-group">
                                <label>No. Transaksi</label>
                                <input type="text" class="form-control disabled-action" name="no_transaksi" id="no_transaksi" value="<?php echo $no_trans;?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l no-margin-r">
                                <div class="form-group">
                                    <label>Penjualan</label>
                                    <select id="jenis_penjualan" name="jenis_penjualan" class="form-control <?php echo $mode_edit;?>">
                                        <option value="Part" <?php echo ($jenis_so=='Part')?'selected':'';?>>Spare Part</option>
                                        <option value="Barang" <?php echo ($jenis_so=='Barang')?'selected':'';?>>Non Part</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l no-margin-r">
                                <div class="form-group" id="kdtp">
                                    <label>Type Motor</label>
                                    <input type="text" name="kd_typemotor" value='<?php echo $kd_typemotor;?>' id="kd_typemotor" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Kode Type Motor">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l">
                                <div class="form-group">
                                    <label>Motor Tahun</label>
                                    <input type="text" name="thn_motor" id="thn_motor"  value='<?php echo $tahun_motor;?>' class="form-control <?php echo $SudahDiApprove;?>" placeholder="Tahun Perakitan" data-mask="0000" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <fieldset class="<?php echo $mode_edit;?>">
                               <div class="col-xs-12 col-md-6 col-sm-6 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>Part Number </label> &nbsp;&nbsp;
                                        <span class="os"><input type="checkbox" id="only_stock" name="only_stock" style="cursor: pointer;" checked="true"> Only Stock</span> <span id="csp" style="color: red"></span>
                                        <input type="text" name="part_number" id="part_number" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Input Part Number atau nama part">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-2 col-md-2 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <input type="checkbox" class="disabled-action" disabled="true"> <label><abbr title="Stock On Hand">Stock</abbr></label>
                                        <input type="text" id="stock_oh" name="stock_oh" value="" class="form-control disabled-action">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-md-4 col-sm-4 no-margin-l">
                                    <div class="form-group">
                                        <label>Jumlah</label>
                                        <div class="input-group" style="padding-top: 3px">
                                            <input type="text" name="jumlah_order" id="jumlah_order" class="form-control <?php echo $SudahDiApprove;?>" data-mask="000000" placeholder="Jumla Pesanan">
                                            <span class="input-group-btn" id="appd" title="Add Part">
                                                <button id="btn-simpan" class='btn btn-primary disabled-action' type='button' onclick="add_item();"><i class="fa fa-plus fa-fw"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-7 col-sm-7 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>Part Deskripsi</label>
                                        <input type="text" name="nama_part" id="nama_part" class="form-control" placeholder="Deskripsi part" readonly="true">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-2 col-sm-2 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>Diskon(%)</label>
                                        <input type="text" name="diskon" id="diskon" class="form-control <?php echo $mode_edit;?>" value="0">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-3 col-sm-3 no-margin-l">
                                    <div class="form-group">
                                        <label>Harga/Pcs</label>
                                        <input type="text" name="harga_sp" id="harga_sp" value="0" class="form-control" readonly="true">
                                        <input type="hidden" name="total_harga_sp" id="total_harga_sp" value="0" class="form-control" readonly="true">
                                        <input type="hidden" name="jml_stock" id="jml_stock" value="0" class="form-control" readonly="true">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <fieldset id="etainvo" class="">
                            <div class="row <?php echo $mode_edit;?>">
                                <span class="col-xs-12 col-sm-5 col-md-5 no-margin-l no-margin-r">
                                    <!-- <a class="btn btn-default" id="oth_dlr" rule="button"><i class="fa fa-info-circle"></i> Stock Di Dealer Lain <span class="badge" id="otd">0</span></a> -->
                                    <select class="form-control" id="oth_dlr" name="oth_dlr">
                                        <option>Stock Di Dealer Lain</option>
                                    </select>
                                </span>
                                <span class="col-xs-12 col-sm-4 col-md-4 no-margin-l no-margin-r">
                                    <a class="btn btn-default pull-right" rule="button"><input type="checkbox" id="on_md" name="on_md"> Stock Di MD <span class="badge" id="md">0</span></a>
                                </span>
                                <span class="col-xs-12 col-sm-3 col-md-3" style="padding-top: 8px"><?php //echo ($booking);?>
                                    <input type="checkbox" id="as_booking" name="as_booking" checked="true">&nbsp;Booking Order
                                </span>
                            </div>
                            <div class="row" style="padding-top: 5px;">
                                <span class="col-xs-12 col-sm-8 col-md-8 no-margin-l btn btn-default" style="text-align: left; padding-left: 5px">
                                    <i class="fa fa-truck"></i> ETA : <span id="eta"></span>
                                </span>
                                <span class=" col-xs-12 col-sm-4 col-md-4" id="btn_hol">
                                    <?php 
                                        if($booking=="1" && $reff_doc=='' && $kemana=='AHM'){
                                            ?>
                                                <a class="btn btn-md btn-info" href="<?php echo base_url("purchasing/posp_add?so=").urlencode(base64_encode($no_trans));?>" role="button"><i class="fa fa-cogs"></i> Create PO Hotline</a>
                                            <?php
                                        }else if($booking=="1" && $reff_doc!=''){
                                            ?>
                                                <a class="btn btn-md btn-info" href="<?php echo base_url("purchasing/posp_add?n=").urlencode(base64_encode($reff_doc));?>" role="button"><i class="fa fa-cogs"></i> <abbr title='No PO hotline <?php echo $reff_doc;?>'>View PO Hotline</abbr></a>
                                            <?php
                                        }else{
                                            ?>
                                                <input type="checkbox" id="auto_po" name="auto_po">&nbsp;Create PO Hotline
                                            <?php
                                        }
                                        ?>
                                </span>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 no-margin-r">
                        <fieldset class='xx disabled-action'>
                            <div class="row">
                                <!-- no motor -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-r">
                                    <div class="form-group">
                                        <label>No. Polisi</label>
                                        <input type="text" name="no_polisi" id="no_pol" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Nomor polisi motor" value='<?php echo $no_polisi;?>'>
                                    </div>
                                </div>
                                <!-- nama konsuman -->
                                <div class="col-xs-12 col-md-8 col-sm-8 no-margin-l">
                                    <div class="form-group">
                                        <label>Nama Konsumen</label>
                                        <div class="input-group">
                                            <input type="text" required="true" name="nama_konsumen" id="nama_konsumen" class="form-control <?php echo $SudahDiApprove;?>" placeholder="Nama Konsumen" autocomplete="off" value='<?php echo $nama_konsumen;?>'>
                                            <span class="input-group-btn disabled-action" id="cari">
                                                <button type="button" id="modal-button-3" class='btn btn-info' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                        <input type="hidden" name="kd_customer" id="kd_customer" value="<?php echo $kd_customer;?>">
                                    </div>
                                </div>
                                <!-- alamat -->
                                <div class=" col-xs-12 col-sm-8 col-md-8">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <input class="form-control <?php echo $SudahDiApprove;?>" name="alamat_konsumen" id="alamat_konsumen" placeholder="Alamat Jl" value="<?php echo $alamat_konsumen;?>">
                                    </div>
                                </div>
                                <!-- telepon -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l">
                                    <div class="form-group">
                                        <label>No. Telp</label>
                                        <input type="text" name="no_telp" id="no_telp" class="form-control <?php echo $SudahDiApprove;?>" placeholder="No telp konsumen" value='<?php echo $no_telp;?>'>
                                    </div>
                                </div>
                                <!-- propinsi -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-r">
                                    <div class="form-group">
                                        <label>Propinsi</label>
                                        <select class="form-control" name="kd_propinsi" id="kd_propinsi" title="propinsi">
                                            <option value="0">--Pilih Propinsi--</option>
                                            <?php
                                            if (isset($propinsi)) {
                                                if ($propinsi->totaldata >0) {
                                                    foreach ($propinsi->message as $key => $value) {
                                                        $select =($kd_propinsi==$value->KD_PROPINSI)?"selected":"";
                                                        echo "<option value='" . $value->KD_PROPINSI . "' ".$select.">".$value->NAMA_PROPINSI . "</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- kabupaten -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>Kabupaten <span id="l_kabupaten"></span></label>
                                        <select class="form-control" id="kd_kabupaten" name="kd_kabupaten" title="kabupaten">
                                            <option value="0">--Pilih Kabupaten--</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- kecamatan -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-l">
                                    <div class="form-group">
                                        <label>Kecamatan <span id="l_kecamatan"></span></label>
                                        <select class="form-control" id="kd_kecamatan" name="kd_kecamatan" title="kecamatan">
                                            <option value="0">--Pilih Kecamatan--</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- kelurahan -->
                                <div class="col-xs-12 col-sm-4 col-md-4 no-margin-r">
                                    <div class="form-group">
                                        <label>Kelurahan <span id="l_desa"></span></label>
                                        <select class="form-control" id="kd_desa" name="kd_desa" title="desa">
                                            <option value="0">--Pilih Desa/Kelurahan--</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- kode pos -->
                                <div class="col-xs-12 col-md-4 col-sm-4 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>Kode Pos</label>
                                        <input type="text" name="kd_pos" id="kd_pos" value='<?php echo $kd_pos;?>' class="form-control <?php echo $SudahDiApprove;?>">
                                    </div>
                                </div>
                            <!-- </div>
                            <div class="row"> -->
                                <div class="col-xs-12 col-sm-2 col-md-2 no-margin-l no-margin-r">
                                    <div class="form-group">
                                        <label>
                                            <abbr title='VOR (Vehicle Of The Road ) Pilih Y apabila selama motor diperbaiki motor tersebut menginap di bengkel/Tidak bisa di Gunakan. Pilih N jika motor tidak menginap dan bisa digunakan sementara oleh konsumen'>(VOR)</abbr>
                                        </label>
                                        <select class="form-control <?php echo $SudahDiApprove;?>" id="vor" name="vor">
                                            <option value='N' <?php echo ($vor=="N")? "selected":"";?>>N</option>
                                            <option value='Y' <?php echo ($vor=="Y")? "selected":"";?>>Y</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-2 col-sm-2 no-margin-l">
                                    <div class="form-group">
                                        <label><abbr title="Pilih JR (Job Return Service ) Y Jika Terkait Job Return">(JR)</abbr></label>
                                        <select class="form-control <?php echo $SudahDiApprove;?>" id="jr" name="jr">
                                            <option value='N' <?php echo ($jrs=="N")? "selected":"";?>>N</option>
                                            <option value='Y' <?php echo ($jrs=="Y")? "selected":"";?>>Y</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="clearfix"></div>
        <div class="col-lg-12 padding-left-right-10">
            <div class="table-responsive h350">
                <table class="table table-bordered table-striped" id="listpo">
                    <thead>
                        <tr>
                            <th style="width: 3%">No.</th>
                            <th style="width: 8%">Part Number</th>
                            <th style="width: 12%">Part Deskripsi</th>
                            <th style="width: 4%">Quantity</th>
                            <th style="width: 8%">Harga</th>
                            <th style="width: 5%">Diskon</th>
                            <th style="width: 10%">Total Harga</th>
                            <th style="width: 12%">ETA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_item=0;$total_harga=0;
                            if(isset($sod)){ $n=0; 
                                if($sod->totaldata > 0){
                                    foreach ($sod->message as $key => $value) {
                                        $picking=((int)$value->PICKING_STATUS >0)?'disabled-action':'';
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo ($n+1);?></td>
                                                <td class="table-nowarp"><span class='pull-left' style="padding-right: 10px">
                                                    <a class='<?php echo $picking;?>' onclick="__hapusItem('<?php echo $value->ID;?>')" title="Hapus Item"><i class="fa fa-trash"></i></a>
                                                </span><?php echo $value->PART_NUMBER;?></td>
                                                <td class='td-overflow-50' title="<?php echo($jenis_so=='Baran')? $value->PART_DESKRIPSI: NamaBarang($value->PART_NUMBER);?>"><?php echo($jenis_so=='Part')? $value->PART_DESKRIPSI: NamaBarang($value->PART_NUMBER);?></td>
                                                <td class="text-right table-nowarp" style="padding-right: 5px"><?php echo number_format($value->JUMLAH_ORDER,0);?></td>
                                                <td class="text-right table-nowarp" style="padding-right: 5px"><?php echo number_format($value->HARGA_JUAL,0);?></td>
                                                <td class="text-right table-nowarp" style="padding-right: 5px"><?php echo number_format($value->DISKON,0);?></td>
                                                <td class="text-right table-nowarp total" style="padding-right: 5px"><?php echo number_format(($value->JUMLAH_ORDER * $value->HARGA_JUAL)-$value->DISKON,0);?></td>
                                                <td class="hidden"><?php echo $value->STOCK_AWAL;?></td>
                                                <td class="table-nowarp"><span class='eta_<?php echo $n;?>'><?php echo $value->ETA;?></span><?php echo ((int)$value->PICKING_STATUS >=1)?"<span class='pull-right'><i class='fa fa-check-square-o'></i></span>":'';?></td>
                                                <td class="hidden"><?php echo ($value->PICKING_STATUS)?$value->PICKING_STATUS:"0";?></td>
                                                <td class="hidden">&nbsp;</td>
                                            </tr>
                                        <?php
                                        $n++;
                                        $total_item +=$value->JUMLAH_ORDER;
                                        $total_harga +=(($value->JUMLAH_ORDER * $value->HARGA_JUAL)-$value->DISKON);
                                    }
                                }
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="totalColumn total">
                            <td colspan="2">&nbsp;</td>
                            <td class="text-right">Total Item</td>
                            <td class="text-center"><span id="t_item"><?php echo number_format($total_item,0);?></span></td>
                            <td class="text-right">Total Harga</td>
                            <td></td>
                            <td class="text-right totalColumn"><span id="t_harga"><?php echo number_format($total_harga,0);?></span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php echo loading_proses();?>
</section> 
<script type="text/javascript" src="<?php echo base_url("assets/js/external/so_part.js?v=").date('YmdHis');?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var booking="<?php echo $booking;?>";
        var jenis_so="<?php echo $jenis_so;?>";
        if(jenis_so){ $('#jenis_penjualan').addClass('disabled-action');}
        console.log("booking:"+jenis_so);
        if(booking=="1"){
            $('#as_booking').attr("checked",true);
             //$('#as_booking').click();
        }else{
            //$('#as_booking').click();
            $('#as_booking').attr("checked",false);
        }
        $('#modal-button-3').on('click',function(){
            __caridata();
        })
        $('#nama_konsumen')
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