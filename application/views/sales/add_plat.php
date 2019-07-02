 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}


$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 

$status_udstk = ($udstk == true ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_udstk : 'disabled-action' ); 

$KD_DEALER = '';
$KD_MAINDEALER = '';
$ID = '';
$BBNKB = '';
$PKB = '';
$SWDKLLJ = '';
$STCK = '';
$PLAT_ASLI = '';
$ADMIN_SAMSAT = '';
$BPKB = '';
$SS = '';
$NO_RANGKA = '';
$NO_MESIN = '';
$NO_STNK = '';
$NO_PLAT = '';
$NO_BPKB = '';
$STATUS_PLAT = '';
$STNKDETAIL_ID = '';
$BPKBDETAIL_ID = '';
$KD_CUSTOMER = '';

$ID_PLAT='';$NO_RANGKA_PLAT='';$DIRECTORY_BUKTITERIMA_PLAT='';$NAMA_PENERIMA_PLAT='';$TGL_PENERIMA_PLAT='';$ALAMAT_PLAT='';$NOHP_PLAT='';$STATUS_PENERIMA_PLAT='';$DIRECTORY_BUKTIPENYERAHAN_PLAT='';$DATA_NOMOR_PLAT='';$KETERANGAN_PLAT='';

$ID_HCC='';$NO_RANGKA_HCC='';$DIRECTORY_BUKTITERIMA_HCC='';$NAMA_PENERIMA_HCC='';$TGL_PENERIMA_HCC='';$ALAMAT_HCC='';$NOHP_HCC='';$STATUS_PENERIMA_HCC='';$DIRECTORY_BUKTIPENYERAHAN_HCC='';$DATA_NOMOR_HCC='';$KETERANGAN_HCC='';

$ID_STNK='';$NO_RANGKA_STNK='';$DIRECTORY_BUKTITERIMA_STNK='';$NAMA_PENERIMA_STNK='';$TGL_PENERIMA_STNK='';$ALAMAT_STNK='';$NOHP_STNK='';$STATUS_PENERIMA_STNK='';$DIRECTORY_BUKTIPENYERAHAN_STNK='';$DATA_NOMOR_STNK='';$KETERANGAN_STNK='';$STATUS_STNK='';

$ID_BPKB='';$NO_RANGKA_BPKB='';$DIRECTORY_BUKTITERIMA_BPKB='';$NAMA_PENERIMA_BPKB='';$TGL_PENERIMA_BPKB='';$ALAMAT_BPKB='';$NOHP_BPKB='';$STATUS_PENERIMA_BPKB='';$DIRECTORY_BUKTIPENYERAHAN_BPKB='';$DATA_NOMOR_BPKB='';$KETERANGAN_BPKB='';$STATUS_BPKB='';


if(base64_decode(urldecode($this->input->get("n")))){

  if($platheader && (is_array($platheader->message) || is_object($platheader->message))):
    foreach ($platheader->message as $key => $value) {
      $ID = $value->ID;
      $BBNKB = $value->BBNKB;
      $PKB = $value->PKB;
      $SWDKLLJ = $value->SWDKLLJ;
      $STCK = $value->STCK;
      $PLAT_ASLI = $value->PLAT_ASLI;
      $ADMIN_SAMSAT = $value->ADMIN_SAMSAT;
      $BPKB = $value->BPKB;
      $SS = $value->SS;
      $NO_RANGKA = $value->NO_RANGKA;
      $NO_MESIN = $value->NO_MESIN;
      $NO_STNK = $value->NO_STNK;
      $NO_PLAT = $value->NO_PLAT;
      $NO_BPKB = $value->NO_BPKB;
      $STATUS_PLAT = $value->STATUS_PLAT;
      $STNKDETAIL_ID = $value->STNKDETAIL_ID;
      $BPKBDETAIL_ID = $value->BPKBDETAIL_ID;
      $KD_CUSTOMER = $value->KD_CUSTOMER;
    }
  endif;

  if($plat && (is_array($plat) || is_object($plat))):

  $ID_PLAT=$plat->ID;$NO_RANGKA_PLAT=$plat->NO_RANGKA;$DIRECTORY_BUKTITERIMA_PLAT=$plat->DIRECTORY_BUKTITERIMA;$NAMA_PENERIMA_PLAT=$plat->NAMA_PENERIMA;$TGL_PENERIMA_PLAT=$plat->TGL_PENERIMA;$ALAMAT_PLAT=$plat->ALAMAT;$NOHP_PLAT=$plat->NOHP;$STATUS_PENERIMA_PLAT=$plat->STATUS_PENERIMA;$DIRECTORY_BUKTIPENYERAHAN_PLAT=$plat->DIRECTORY_BUKTIPENYERAHAN;$DATA_NOMOR_PLAT=$plat->DATA_NOMOR;$KETERANGAN_PLAT=$plat->KETERANGAN;
  endif;
  
  if($hcc && (is_array($hcc) || is_object($hcc))):
  $ID_HCC=$hcc->ID;$NO_RANGKA_HCC=$hcc->NO_RANGKA;$DIRECTORY_BUKTITERIMA_HCC=$hcc->DIRECTORY_BUKTITERIMA;$NAMA_PENERIMA_HCC=$hcc->NAMA_PENERIMA;$TGL_PENERIMA_HCC=$hcc->TGL_PENERIMA;$ALAMAT_HCC=$hcc->ALAMAT;$NOHP_HCC=$hcc->NOHP;$STATUS_PENERIMA_HCC=$hcc->STATUS_PENERIMA;$DIRECTORY_BUKTIPENYERAHAN_HCC=$hcc->DIRECTORY_BUKTIPENYERAHAN;$DATA_NOMOR_HCC=$hcc->DATA_NOMOR;$KETERANGAN_HCC=$hcc->KETERANGAN;
  endif;

  if($stnk && (is_array($stnk) || is_object($stnk))):
  $ID_STNK=$stnk->ID;$NO_RANGKA_STNK=$stnk->NO_RANGKA;$DIRECTORY_BUKTITERIMA_STNK=$stnk->DIRECTORY_BUKTITERIMA;$NAMA_PENERIMA_STNK=$stnk->NAMA_PENERIMA;$TGL_PENERIMA_STNK=$stnk->TGL_PENERIMA;$ALAMAT_STNK=$stnk->ALAMAT;$NOHP_STNK=$stnk->NOHP;$STATUS_PENERIMA_STNK=$stnk->STATUS_PENERIMA;$DIRECTORY_BUKTIPENYERAHAN_STNK=$stnk->DIRECTORY_BUKTIPENYERAHAN;$DATA_NOMOR_STNK=$stnk->DATA_NOMOR;$KETERANGAN_STNK=$stnk->KETERANGAN;$STATUS_STNK=$stnk->STATUS_STNK;
  endif;

  if($bpkb && (is_array($bpkb) || is_object($bpkb))):
  $ID_BPKB=$bpkb->ID;$NO_RANGKA_BPKB=$bpkb->NO_RANGKA;$DIRECTORY_BUKTITERIMA_BPKB=$bpkb->DIRECTORY_BUKTITERIMA;$NAMA_PENERIMA_BPKB=$bpkb->NAMA_PENERIMA;$TGL_PENERIMA_BPKB=$bpkb->TGL_PENERIMA;$ALAMAT_BPKB=$bpkb->ALAMAT;$NOHP_BPKB=$bpkb->NOHP;$STATUS_PENERIMA_BPKB=$bpkb->STATUS_PENERIMA;$DIRECTORY_BUKTIPENYERAHAN_BPKB=$bpkb->DIRECTORY_BUKTIPENYERAHAN;$DATA_NOMOR_BPKB=$bpkb->DATA_NOMOR;$KETERANGAN_BPKB=$bpkb->KETERANGAN;$STATUS_BPKB=$bpkb->STATUS_STNK;
  endif;
}

$BUKTIPLAT_DISABLED = $ID_PLAT == '' && $ID != ''?'':'disabled';
$BUKTIHCC_DISABLED  = $ID_HCC  == '' && $ID != ''?'':'disabled';
$BUKTISTNK_DISABLED = $ID_STNK == '' && $ID != '' && $STNKDETAIL_ID != ''?'':'disabled';
$BUKTIBPKB_DISABLED = $ID_BPKB == '' && $ID != '' && $BPKBDETAIL_ID != ''?'':'disabled';

$BUTTON_PLAT_DISABLED = $STATUS_PENERIMA_PLAT < 1 ?'':'disabled';
$BUTTON_HCC_DISABLED = $STATUS_PENERIMA_HCC < 1 ?'':'disabled';
$BUTTON_STNK_DISABLED = $STATUS_PENERIMA_STNK < 1 && $STNKDETAIL_ID != ''?'':'disabled';
$BUTTON_BPKB_DISABLED = $STATUS_PENERIMA_BPKB < 1 && $BPKBDETAIL_ID != ''?'':'disabled';

// $BUKTISTNK_DISABLED = $ID_STNK == '' && $ID != '' && $STNKDETAIL_ID != '' ?'':'disabled';
// $BUKTIBPKB_DISABLED = $ID_BPKB == '' && $ID != '' && $BPKBDETAIL_ID != '' ?'':'disabled';

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      
      <a class="btn btn-default" href="<?php echo base_url('stnk/add_plat'); ?>">
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

      <a id="plat-btn" class="btn btn-default  <?php echo $status_c; ?>" role="button">
          <i class="fa fa-save fa-fw"></i> Simpan
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> List STNK
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="headerForm" action="<?php echo base_url('stnk/add_plat') ?>" class="bucket-form" method="get">


          <input type="hidden" id="kd_customer" name="kd_customer" value="<?php echo $KD_CUSTOMER; ?>">
          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">
          <input type="hidden" id="id" name="id" value="<?php echo $ID; ?>">
          <input type="hidden" id="stnkdetail_id" name="stnkdetail_id" value="<?php echo $STNKDETAIL_ID; ?>">
          <input type="hidden" id="bpkbdetail_id" name="bpkbdetail_id" value="<?php echo $BPKBDETAIL_ID; ?>">

          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/stnk_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-3">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" required="true">
                    
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


            <!-- <div class="col-xs-12 col-sm-4">

              <div class="form-group">
                  <label>No. Transaksi STNK</label>

                  <select id="no_trans" name="no_trans" class="form-control" <?php echo ($ID != ''?'disabled':'');?> >
                    <option value="null">- Pilih No Trans STNK -</option>
                    <?php if($stnk_header && (is_array($stnk_header->message) || is_object($stnk_header->message))): foreach ($stnk_header->message as $key => $list_row):?>
                      <option value="<?php echo $list_row->NO_TRANS;?>"><?php echo $list_row->NO_TRANS;?></option>
                    <?php endforeach; endif;?>
                  </select>

              </div>

            </div> -->


            <div class="col-xs-12 col-sm-4 col-sm-offset-1">

              <div class="form-group">
                  <label>Nomor Rangka <span class="load-form"></span></label>


                  <?php if($ID == ''): ?>
                  <select id="no_rangka" name="no_rangka" class="form-control" <?php echo ($ID != ''?'disabled':'');?> >
                    <option value="null">- Pilih No Rangka -</option>
                    <?php if($stnk_header && (is_array($stnk_header->message) || is_object($stnk_header->message))): foreach ($stnk_header->message as $key => $list_row):?>
                      <option value="<?php echo $list_row->NO_RANGKA;?>"><?php echo $list_row->NO_RANGKA;?></option>
                    <?php endforeach; endif;?>
                  </select>
                  
                  <?php else: ?>

                  <input type="text" id="no_rangka" name="no_rangka" value="<?php echo $NO_RANGKA;?>" class="form-control" placeholder="Nomor Rangka" readonly required>
                  <?php endif; ?>


              </div>

            </div>

            <div class="col-xs-12 col-sm-4">

              <div class="form-group">
                  <label>Nomor Mesin <span class="load-form-mesin"></span></label>
                  <input type="text" id="no_mesin" name="no_mesin" value="<?php echo $NO_MESIN;?>" class="form-control" placeholder="Nomor Mesin" readonly required>
              </div>

            </div>

          </div>

        </form>

      </div>
      
    </div>

  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Input Plat
      </div>

      <div class="panel-body panel-body-border">
        <!-- <div class="table-responsive"> -->

        <form id="detailForm" action="<?php echo base_url('stnk/approval_pengurusan') ?>" class="bucket-form" method="get">

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>BBNKB</label>
                  <input type="text" id="bbnkb" name="bbnkb" value="<?php echo $BBNKB;?>" class="form-control form_biaya" placeholder="Jumlah biaya BBNKB" required>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>STCK</label>
                  <input type="text" id="stck" name="stck" value="<?php echo $STCK;?>" class="form-control form_biaya" placeholder="Jumlah biaya STCK" required>
              </div>
            </div>

          </div>


          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>PKB</label>
                  <input type="text" id="pkb" name="pkb" value="<?php echo $PKB;?>" class="form-control form_biaya" placeholder="Jumlah biaya PKB" required>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>Plat Asli</label>
                  <input type="text" id="plat_asli" name="plat_asli" value="<?php echo $PLAT_ASLI;?>" class="form-control form_biaya" placeholder="Jumlah biaya Plat Asli" required>
              </div>
            </div>

          </div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>SWDKLLJ</label>
                  <input type="text" id="swdkllj" name="swdkllj" value="<?php echo $SWDKLLJ;?>" class="form-control form_biaya" placeholder="Jumlah biaya SWDKLLJ" required>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>Admin SAMSAT</label>
                  <input type="text" id="admin_samsat" name="admin_samsat" value="<?php echo $ADMIN_SAMSAT;?>" class="form-control form_biaya" placeholder="Jumlah biaya Admin SAMSAT" required>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>BPKB</label>
                  <input type="text" id="bpkb" name="bpkb" value="<?php echo $BPKB;?>" class="form-control form_biaya" placeholder="Jumlah biaya BPKB" required>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                  <label>SS</label>
                  <input type="text" id="ss" name="ss" value="<?php echo $SS;?>" class="form-control form_biaya" placeholder="Jumlah biaya SS" required>
              </div>

            </div>

          </div>

        </form>
          <?php if($ID == ''): ?>
          <div class="row">

            <div class="col-xs-12 col-sm-4">
              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input id="status_plat" name="status_plat" value="1" type="checkbox" <?php echo ($STATUS_PLAT == 1?'checked':'');?> > Lengkapi bukti fisik STCK/Plat 
                  </label>
                </div>
              </div>
            </div>

          </div>
          <?php endif; ?>
          
          <div class="row">

            <div class="col-xs-12 col-sm-12">
              <!-- <h4><i class="fa  fa-pencil-square-o"></i> Input Nomor</h4> -->
              <hr>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="panel margin-bottom-10">
              <?php if($STATUS_PENERIMA_PLAT >= '0'): ?>
              <form id="plat_form" method="post" action="<?php echo base_url('stnk/update_bukti');?>" enctype="multipart/form-data">
              <?php else: ?>
              <form id="plat_form" method="post" action="<?php echo base_url('stnk/store_bukti');?>" enctype="multipart/form-data">
              <?php endif; ?>
                <div class="panel-headings">
                  <i class="fa fa-th-large"></i> Data Plat

                  <span class="tools pull-right">
                    <div class="form-inline">

                      alamat sesuai data customer 
                      <input id="plat" name="status_plat" value="plat" class="cek_alamat" type="checkbox" <?php echo $BUTTON_PLAT_DISABLED; ?>> 
                    </div>

                  </span>
                </div>
                <div class="panel-body">


                  <div class="row">
                    <input type="hidden" name="ket_id" value="plat"> 
                    <input type="hidden" id="keterangan_plat" class="keterangan" name="keterangan" value="PLAT">
                    <input type="hidden" id="status_penerima_plat" class="status_penerima" name="status_penerima" value="<?php echo $STATUS_PENERIMA_PLAT;?>">
                    <input type="hidden" id="no_rangka_plat" class="no_rangka" name="no_rangka" value="<?php echo $NO_RANGKA;?>">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor Plat</label>

                          <input type="text" id="no_plat" head="plat" name="data_nomor" value="<?php echo $DATA_NOMOR_PLAT;?>" class="data_nomor form-control stck_abble" style="text-transform: uppercase;" placeholder="AB-1234-XX" <?php echo $BUKTIPLAT_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Tanggal Terima</label>

                          <div class="input-group input-append date">
                              <input class="form-control tgl_penerima" id="tgl_penerima_plat" name="tgl_penerima" placeholder="DD/MM/YYYY" value="<?php echo $TGL_PENERIMA_PLAT == ''?date('d/m/Y'):tglfromSql($TGL_PENERIMA_PLAT); ?>" type="text" required <?php echo $BUTTON_PLAT_DISABLED; ?>/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nama Penerima <span class="load_plat"></span></label>

                          <input type="text" id="nama_penerima_plat" name="nama_penerima" value="<?php echo $NAMA_PENERIMA_PLAT;?>" class="form-control nama_penerima" placeholder="Masukan Nama Penerima" <?php echo $BUKTIPLAT_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor HP <span class="load_plat"></span></label>

                          <input type="text" id="nohp_plat" name="nohp" value="<?php echo $NOHP_PLAT;?>" class="form-control nohp" placeholder="Masukan Nomor HP" <?php echo $BUKTIPLAT_DISABLED; ?> required>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                          <label>Alamat <span class="load_plat"></span></label>

                          <textarea id="alamat_plat" name="alamat" class="form-control alamat" rows="5" placeholder="Alamat Penerima" <?php echo $BUKTIPLAT_DISABLED; ?> required><?php echo $ALAMAT_PLAT;?></textarea>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                        <label for="exampleInputFile">Bukti Plat</label>
                        <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_plat" name="directory_buktiterima" aria-describedby="fileHelp" required <?php echo $BUTTON_PLAT_DISABLED;?>>
                        <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="panel-footer">
                  <div class="row">
                  <div class="col-xs-12 col-sm-12">
                  <button id="btn_plat" class="btn_data btn btn-default btn-block" <?php echo $BUTTON_PLAT_DISABLED;?>><?php echo ($STATUS_PENERIMA_PLAT >= '0'?'Penyerahan':'Penerimaan');?></button>
                      
                  </div>
                  </div>
                </div>
              </form>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="panel margin-bottom-10">
              <?php if($STATUS_PENERIMA_HCC >= '0'): ?>
              <form id="hcc_form" method="post" action="<?php echo base_url('stnk/update_bukti');?>" enctype="multipart/form-data">
              <?php else: ?>
              <form id="hcc_form" method="post" action="<?php echo base_url('stnk/store_bukti');?>" enctype="multipart/form-data">
              <?php endif; ?>
                <div class="panel-headings">
                  <i class="fa fa-th-large"></i> Data HCC

                  <span class="tools pull-right">
                    <div class="form-inline">

                      alamat sesuai data customer 
                      <input id="hcc" name="status_hcc" value="hcc" class="cek_alamat" type="checkbox" <?php echo $BUTTON_HCC_DISABLED; ?>> 
                    </div>

                  </span>
                </div>
                <div class="panel-body">


                  <div class="row">
                    <input type="hidden" name="ket_id" value="hcc"> 
                    <input type="hidden" id="keterangan_hcc" class="keterangan" name="keterangan" value="HCC">
                    <input type="hidden" id="status_penerima_hcc" class="status_penerima" name="status_penerima" value="<?php echo $STATUS_PENERIMA_HCC;?>">
                    <input type="hidden" id="no_rangka_hcc" class="no_rangka" name="no_rangka" value="<?php echo $NO_RANGKA;?>">


                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor HCC</label>

                          <input type="text" id="no_hcc" head="hcc" name="data_nomor" value="<?php echo $DATA_NOMOR_HCC;?>" class="data_nomor form-control stck_abble" placeholder="Masukan Nomor HCC" <?php echo $BUKTIHCC_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Tanggal Terima</label>

                          <div class="input-group input-append date">
                              <input class="form-control tgl_penerima" id="tgl_penerima_hcc" name="tgl_penerima" placeholder="DD/MM/YYYY" value="<?php echo $TGL_PENERIMA_HCC == ''?date('d/m/Y'):tglfromSql($TGL_PENERIMA_HCC); ?>" type="text" required <?php echo $BUTTON_HCC_DISABLED; ?>/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nama Penerima <span class="load_hcc"></span></label>

                          <input type="text" id="nama_penerima_hcc" name="nama_penerima" value="<?php echo $NAMA_PENERIMA_HCC;?>" class="form-control nama_penerima" placeholder="Masukan Nama Penerima" <?php echo $BUKTIHCC_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor HP <span class="load_hcc"></span></label>

                          <input type="text" id="nohp_hcc" name="nohp" value="<?php echo $NOHP_HCC;?>" class="form-control nohp" placeholder="Masukan Nomor HP" <?php echo $BUKTIHCC_DISABLED; ?> required>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                          <label>Alamat <span class="load_hcc"></span></label>

                          <textarea id="alamat_hcc" name="alamat" class="form-control alamat" rows="5" placeholder="Alamat Penerima" <?php echo $BUKTIHCC_DISABLED; ?> required><?php echo $ALAMAT_HCC;?></textarea>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                        <label for="exampleInputFile">Bukti HCC</label>
                        <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_hcc" name="directory_buktiterima" aria-describedby="fileHelp" required <?php echo $BUTTON_HCC_DISABLED;?>>
                        <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="panel-footer">
                  <div class="row">
                  <div class="col-xs-12 col-sm-12">
                  <button id="btn_hcc" class="btn_data btn btn-default btn-block" <?php echo $BUTTON_HCC_DISABLED;?>><?php echo ($STATUS_PENERIMA_HCC >= '0'?'Penyerahan':'Penerimaan');?></button>
                      
                  </div>
                  </div>
                </div>
              </form>
              </div>
            </div>
          </div>

          <div class="row">

            <div class="col-xs-12 col-sm-6">
              <div class="panel margin-bottom-10">
              <?php if($STATUS_PENERIMA_STNK >= '0'): ?>
              <form id="stnk_form" method="post" action="<?php echo base_url('stnk/update_bukti');?>" enctype="multipart/form-data">
              <?php else: ?>
              <form id="stnk_form" method="post" action="<?php echo base_url('stnk/store_bukti');?>" enctype="multipart/form-data">
              <?php endif; ?>
                <div class="panel-headings">
                  <i class="fa fa-th-large"></i> Data STNK

                  <span class="tools pull-right">
                    <div class="form-inline">

                      alamat sesuai data customer 
                      <input id="stnk" name="status_stnk" value="stnk" class="cek_alamat" type="checkbox" <?php echo $BUTTON_STNK_DISABLED; ?>> 
                    </div>

                  </span>
                </div>
                <div class="panel-body">


                  <div class="row">
                    <input type="hidden" name="ket_id" value="stnk"> 
                    <input type="hidden" id="keterangan_stnk" class="keterangan" name="keterangan" value="STNK">
                    <input type="hidden" id="status_penerima_stnk" class="status_penerima" name="status_penerima" value="<?php echo $STATUS_PENERIMA_STNK;?>">
                    <input type="hidden" id="no_rangka_stnk" class="no_rangka" name="no_rangka" value="<?php echo $NO_RANGKA;?>">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor STNK</label>

                          <input type="text" id="no_stnk" head="stnk" name="data_nomor" value="<?php echo $DATA_NOMOR_STNK;?>" class="data_nomor form-control stck_abble" placeholder="Masukan Nomor STNK" <?php echo $BUKTISTNK_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Tanggal Terima</label>

                          <div class="input-group input-append date">
                              <input class="form-control tgl_penerima" id="tgl_penerima_stnk" name="tgl_penerima" placeholder="DD/MM/YYYY" value="<?php echo $TGL_PENERIMA_STNK == ''?date('d/m/Y'):tglfromSql($TGL_PENERIMA_STNK); ?>" type="text" required <?php echo $BUTTON_STNK_DISABLED; ?>/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nama Penerima <span class="load_stnk"></span></label>

                          <input type="text" id="nama_penerima_stnk" name="nama_penerima" value="<?php echo $NAMA_PENERIMA_STNK;?>" class="form-control nama_penerima" placeholder="Masukan Nama Penerima" <?php echo $BUKTISTNK_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor HP <span class="load_stnk"></span></label>

                          <input type="text" id="nohp_stnk" name="nohp" value="<?php echo $NOHP_STNK;?>" class="form-control nohp" placeholder="Masukan Nomor HP" <?php echo $BUKTISTNK_DISABLED; ?> required>
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                          <label>Alamat <span class="load_stnk"></span></label>

                          <textarea id="alamat_stnk" name="alamat" class="form-control alamat" rows="5" placeholder="Alamat Penerima" <?php echo $BUKTISTNK_DISABLED; ?> required><?php echo $ALAMAT_STNK;?></textarea>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                        <label for="exampleInputFile">Bukti STNK</label>
                        <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_stnk" name="directory_buktiterima" aria-describedby="fileHelp" required <?php echo $BUTTON_STNK_DISABLED; ?>>
                        <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="panel-footer">
                  <div class="row">
                  <div class="col-xs-12 col-sm-12">
                  <button id="btn_stnk" class="btn_data btn btn-default btn-block" <?php echo $BUTTON_STNK_DISABLED; ?>><?php echo ($STATUS_PENERIMA_STNK >= '0'?'Penyerahan':'Penerimaan');?></button>
                      
                  </div>
                  </div>
                </div>
              </form>
              </div>
            </div>

            <div class="col-xs-12 col-sm-6">
              <div class="panel margin-bottom-10">
              <?php if($STATUS_PENERIMA_BPKB >= '0'): ?>
              <form id="nobpkb_form" method="post" action="<?php echo base_url('stnk/update_bukti');?>" enctype="multipart/form-data">
              <?php else: ?>
              <form id="nobpkb_form" method="post" action="<?php echo base_url('stnk/store_bukti');?>" enctype="multipart/form-data">
              <?php endif; ?>
                <div class="panel-headings">
                  <i class="fa fa-th-large"></i> Data BPKB

                  <span class="tools pull-right">
                    <div class="form-inline">

                      alamat sesuai data customer 
                      <input id="nobpkb" name="status_nobpkb" value="nobpkb" class="cek_alamat" type="checkbox" <?php echo $BUTTON_BPKB_DISABLED; ?>> 
                    </div>

                  </span>
                </div>
                <div class="panel-body">


                  <div class="row">
                    <input type="hidden" name="ket_id" value="nobpkb"> 
                    <input type="hidden" id="keterangan_nobpkb" class="keterangan" name="keterangan" value="BPKB">
                    <input type="hidden" id="status_penerima_nobpkb" class="status_penerima" name="status_penerima" value="<?php echo $STATUS_PENERIMA_BPKB;?>">
                    <input type="hidden" id="no_rangka_nobpkb" class="no_rangka" name="no_rangka" value="<?php echo $NO_RANGKA;?>">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor BPKB</label>

                          <input type="text" id="no_nobpkb" head="nobpkb" name="data_nomor" value="<?php echo $DATA_NOMOR_BPKB;?>" class="data_nomor form-control stck_abble" placeholder="Masukan Nomor BPKB" <?php echo $BUKTIBPKB_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Tanggal Terima</label>

                          <div class="input-group input-append datetime">
                              <input class="form-control tgl_penerima" id="tgl_penerima_nobpkb" name="tgl_penerima" placeholder="DD/MM/YYYY" value="<?php echo $TGL_PENERIMA_BPKB == ''?date('d/m/Y'):tglfromSql($TGL_PENERIMA_BPKB); ?>" type="text" required <?php echo $BUTTON_BPKB_DISABLED; ?>/>
                              <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                          </div>
                      </div>
                    </div>

                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nama Penerima <span class="load_nobpkb"></span></label>

                          <input type="text" id="nama_penerima_nobpkb" name="nama_penerima" value="<?php echo $NAMA_PENERIMA_BPKB;?>" class="form-control nama_penerima" placeholder="Masukan Nama Penerima" <?php echo $BUKTIBPKB_DISABLED; ?> required>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                          <label>Nomor HP <span class="load_nobpkb"></span></label>

                          <input type="text" id="nohp_nobpkb" name="nohp" value="<?php echo $NOHP_BPKB;?>" class="form-control nohp" placeholder="Masukan Nomor HP" <?php echo $BUKTIBPKB_DISABLED; ?> required>
                      </div>
                    </div>

                  </div>

                  <div class="row">

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                          <label>Alamat <span class="load_nobpkb"></span></label>

                          <textarea id="alamat_nobpkb" name="alamat" class="form-control alamat" rows="5" placeholder="Alamat Penerima" <?php echo $BUKTIBPKB_DISABLED; ?> required><?php echo $ALAMAT_BPKB;?></textarea>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                        <label for="exampleInputFile">Bukti BPKB</label>
                        <input type="file" class="form-control-file directory_buktiterima" id="directory_buktiterima_nobpkb" name="directory_buktiterima" aria-describedby="fileHelp" required <?php echo $BUTTON_BPKB_DISABLED; ?> >
                        <small id="fileHelp" class="form-text text-muted">*jpg, *jpag, *png.</small>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="panel-footer">
                  <div class="row">
                  <div class="col-xs-12 col-sm-12">
                  <button id="btn_nobpkb" class="btn_data btn btn-default btn-block" <?php echo $BUTTON_BPKB_DISABLED; ?> ><?php echo ($STATUS_PENERIMA_BPKB >= '0'?'Penyerahan':'Penerimaan');?> </button>
                      
                  </div>
                  </div>
                </div>
              </form>
              </div>
            </div>

          </div>
        <!-- </form> -->

        <!-- </div> -->

      </div>
      
    </div>

  </div>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/stnk.js");?>"></script>
