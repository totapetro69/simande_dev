 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$print_plat = $print->totaldata > 0 ? $status_p : 'disabled-action';

$KD_DEALER = '';
$KD_MAINDEALER = '';

/*if($list && (is_array($list->message) || is_object($list->message))):

  foreach ($list->message as $key => $value) {
    $KD_DEALER = $value->KD_DEALER;
    $KD_MAINDEALER = $value->KD_MAINDEALER;
    # code...
  }

endif;*/


switch ($this->input->get('keterangan')) {
    case 1:
        $jenis = 'STNK';
        break;
    case 2:
        $jenis = 'BPKB';
        break;
    case 3:
        $jenis = 'PLAT';
        break;
    case 4:
        $jenis = 'NOTIS';
        break;
    case 5:
        $jenis = 'SRUT';
        break;
    default:
        $jenis = 'BPKB';
        break;
}

$urus_plat = $tipe_trans == 'pengajuan_plat'?'':'disabled-action';

$url = $jenis == 'SRUT' ? base_url('stnk/store_detailsrut'):base_url('stnk/store_detailbukti');

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      
      <?php if($tipe_trans == 'pengajuan_plat'):?>
      <a class="btn btn-default <?php echo $print_plat;?>" id="modal-button" onclick='addForm("<?php echo base_url('stnk/print_plat'); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static"><i class='fa fa-print'></i> Cetak Daftar Plat</a>

      <a id="pengajuan-btn" class="btn btn-default <?php echo $urus_plat;?>" role="button">
        <i class="fa fa-clipboard fa-fw"></i> Pengajuan Plat
      </a>

      <?php else:?>

      <a id="proses-btn" class="btn btn-default" role="button" data-url="<?php echo $url;?>">
        <i class="fa fa-save fa-fw"></i> Proses
      </a>

      <?php endif;?>

     <!--  <a class="btn btn-default" id="modal-button" onclick='addForm("<?php echo base_url('stnk/lead_time'); ?>");'  role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
          <i class="fa fa-clock-o fa-fw"></i> STNK Lead Time
      </a> -->
<!-- 
      <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('stnk/createfile_udstk');?>" role="button">
          <i class="fa fa-download fa-fw"></i> Download File .UDSTK
      </a> -->

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

        <form id="filterForm" action="<?php echo base_url('stnk/plat_list/'.$tipe_trans) ?>" class="bucket-form" method="get">


          <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">
          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">

          <!-- <div id="pengurus-url" url="<?php echo base_url('stnk/pengurus_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-2">
                    
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
                  <label>Wilayah</label>
                  <select name="kd_kota" id="kd_kota" class="form-control" required="true">
                    <option value="">- Pilih Wilayah -</option>
                    <?php foreach ($kabupaten->message as $key => $group) : 
                        $default=($this->input->get('kd_kota')==$group->KD_KABUPATEN)?" selected":" ";
                    ?>
                      <option value="<?php echo $group->KD_KABUPATEN;?>" <?php echo $default;?> ><?php echo $group->NAMA_KABUPATEN;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div> -->

            <div class="col-xs-12 col-sm-4">
                    
              <div class="form-group">
                  <label>Wilayah</label>
                  <select name="kd_kota" id="kd_kota" class="form-control" required="true">
                    <option value="">- Pilih Wilayah -</option>
                    <?php foreach ($kabupaten->message as $key => $group) : 
                      if($this->input->get('kd_kota') != ''):
                        $default=($this->input->get('kd_kota')==$group->KD_KABUPATEN)?" selected":" ";
                      else:
                        $default=($this->session->userdata("kd_kabupaten")==$group->KD_KABUPATEN)?" selected":'';
                      endif;
                    ?>
                      <option value="<?php echo $group->KD_KABUPATEN;?>" <?php echo $default;?> ><?php echo $group->NAMA_KABUPATEN;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                  <label>Keterangan</label>
                  <select name="keterangan" id="keterangan" class="form-control <?php echo ($tipe_trans == 'pengajuan_plat')?"disabled-action":" ";?>" <?php echo ($tipe_trans == 'pengajuan_plat')?" readonly":" ";?>>
                    <option value="2" <?php echo ($this->input->get('keterangan')==2)?" selected":" ";?> >
                      BPKB
                    </option>
                    <option value="1" <?php echo ($this->input->get('keterangan')==1)?" selected":" ";?> >
                      STNK
                    </option>
                    <option value="3" <?php echo ($this->input->get('keterangan')==3 || $tipe_trans == 'pengajuan_plat')?" selected":" ";?> >
                      PLAT/STCK
                    </option>
                    <option value="4" <?php echo ($this->input->get('keterangan')==4)?" selected":" ";?> >
                      Notis Pajak & HCC
                    </option>

                    <?php if($tipe_trans == 'penyerahan' || $tipe_trans == 'bukti'):?>
                    <option value="5" <?php echo ($this->input->get('keterangan')==5)?" selected":" ";?> >
                      SRUT
                    </option>
                    <?php endif;?>

                  </select>
              </div>
            </div>


            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Awal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Akhir</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y'); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                </div>

              </div>

            </div>

          <?php if(($jenis == 'BPKB' || $jenis == 'SRUT') AND ($tipe_trans == 'bukti' OR $tipe_trans == 'penyerahan')):?>
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label>Jenis Penyerahan</label>
                  <div class="radio">
                    <label class="radio-inline"><input type="radio" name="jenis_penyerahan" value="customer" class="jenis_penyerahan" <?php echo ($this->input->get('jenis_penyerahan')!='leasing')?" checked":" ";?>>Per customer</label>
                    <label class="radio-inline"><input type="radio" name="jenis_penyerahan" value="leasing" class="jenis_penyerahan" <?php echo ($this->input->get('jenis_penyerahan')=='leasing')?" checked":" ";?>>Per leasing</label>
                  </div>
              </div>
            </div>


            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label></label>


                  <!-- customer -->
                  <select name="kd_penyerahan_customer" id="kd_penyerahan_customer" class="form-control" style="<?php echo $this->input->get('jenis_penyerahan') == 'leasing'?'display:none;':'';?>">
                    <option value="konsumen" <?php echo ($this->input->get('kd_penyerahan_customer')=='konsumen')?" selected":" ";?> >
                      Konsumen
                    </option>
                  </select>

                  <select name="kd_penyerahan_leasing" id="kd_penyerahan_leasing" class="form-control" style="<?php echo $this->input->get('jenis_penyerahan') == 'leasing'?'':'display:none;';?>">
                    <!-- <option value="">- Pilih leasing -</option> -->


                    <?php foreach ($leasing->message as $key => $group_leasing) : 
                      if($this->input->get('kd_penyerahan_leasing') != ''):
                        $default=($this->input->get('kd_penyerahan_leasing')==$group_leasing->KD_FINCOY)?" selected":" ";
                      endif;
                    ?>
                      <option value="<?php echo $group_leasing->KD_FINCOY;?>" <?php echo $default;?> ><?php echo $group_leasing->KD_FINCOY;?></option>
                    <?php endforeach; ?>

                  </select>




              </div>
            </div>

          <?php else:?>
            <input type="hidden" id="jenis_penyerahan" name="jenis_penyerahan" value="customer">
          <?php endif;?>

          </div>
          <!-- <div class="row">


            <div id="ajax-url-filter" url="<?php echo base_url('stnk/test_part');?>"></div>


            <div class="col-xs-12 col-sm-12">
              <div class="form-group">
                  <label>NIK atau Username</label>
                  <input type="text" id="keyword_q" name="keyword_q" value="<?php echo $this->input->get('keyword_q'); ?>" class="form-control" placeholder="test" autocomplete="off">
              </div>
            </div>
            
          </div> -->

        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">       
      <div class="table-responsive">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
        <tr class="no-hover"><th colspan="14" ><i class="fa fa-list fa-fw"></i> List Pengajuan</th></tr>
        <tr class="no-hover">
          <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
          <?php if($tipe_trans == 'pengajuan_plat'):?>
          <th rowspan="2" style="width:50px; vertical-align: middle;"></th>
          <?php endif;?>
          <th colspan="3" >No Transaksi</th>
          <th colspan="2" >Penerimaan</th>
          <th colspan="4" >Penyerahan</th>
          <th class="table-nowarp" rowspan="2" colspan="2">Bukti Terima</th>
          <?php if($tipe_trans == 'pengajuan_plat'):?>
          <th rowspan="2" style="width:150px; vertical-align: middle;">Pengajuan Plat</th>
          <?php endif;?>
          <!-- <th colspan="2" >Tanggal</th> -->
        </tr>

        <tr>
        <th style="width:50px; vertical-align: middle;">Aksi</th>
        <th>NO RANGKA</th>
        <th>NO MESIN</th>
        <th class="table-nowarp" style="width:150px;">NO <?php echo $jenis;?></th>
        <th style="width:150px;">Tgl Terima</th>  
        <th style="width:150px;">Penerima</th>
        <th style="width:150px;">NO HP</th>
        <th style="width:200px;">Alamat</th>
        <th style="width:150px;">Tgl Penyerahan</th>
        <!-- <th class="table-nowarp">Terima</th> -->
        <!-- <th class="table-nowarp">Penyerahan</th> -->
        </tr>
        </thead>
        <tbody>

                <?php echo $list_detail; ?>

        </tbody>
        </table>
      </div>


      <!-- <footer class="panel-footer">
          <div class="row">

              <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm"> 
                      <?php echo ($list)? ($list->totaldata==''?"":"<i>Total Data ". $list->totaldata ." items</i>") : '' ?>
                  </small>
              </div>
              <div class="col-sm-7 text-right text-center-xs">                
                   <?php echo $pagination;?>
              </div>

          </div>
      </footer> -->

    </div>
  </div>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/stnk.js");?>"></script>
