 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 

$reff_source = $reff;
$reff_link = ($reff == 1 ? 'STNK' : 'BPKB');

$status_udstk = ($udstk == true ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_udstk : 'disabled-action' ); 
?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">

      <a class="btn btn-default" href="<?php echo base_url('stnk/add_pengurusan/STNK'); ?>">
          <i class="fa fa-file-o fa-fw"></i> Input Pengurusan
      </a>
      <!-- 
      <a id="store-btn" class="btn btn-default" role="button">
          <i class="fa fa-save fa-fw"></i> Simpan
      </a>

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

        <form id="filterForm" action="<?php echo base_url('stnk/stnk_list/'.$reff_link) ?>" class="bucket-form" method="get">


          <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">

          <div id="ajax-url" url="<?php echo base_url('stnk/sj_typeahead');?>"></div>

          <div class="row">


            <div class="col-xs-12 col-sm-2">
                    
              <div class="form-group">
                  <label>Dealer</label>
                  <select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled" required="true">
                    <option value="">- Pilih Dealer -</option>
                    <?php foreach ($dealer->message as $key => $group) : 
                      if($KD_DEALER!=''):
                        $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
                      elseif($this->input->get('kd_dealer') != ''):
                        $default=($this->input->get('kd_dealer')==$group->KD_DEALER)?" selected":" ";
                      else:
                        $default=($this->session->userdata("kd_dealer")==$group->KD_DEALER)?" selected":'';
                      endif;
                    ?>
                      <option value="<?php echo $group->KD_DEALER;?>" <?php echo $default;?> ><?php echo $group->NAMA_DEALER;?></option>
                    <?php endforeach; ?>
                  </select>
              </div>

            </div>

            <div class="col-xs-12 col-sm-4">


              <div class="form-group">
                  <label>Field Cari</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="cari berdasarkan nama kendaraan dan nomor mesin" autocomplete="off">
              </div>

            </div>


            <div class="col-xs-12 col-sm-3">

              <div class="form-group">

                <label class="control-label" for="date">Periode Awal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>

            <div class="col-xs-12 col-sm-3">

              <div class="form-group">

                <label class="control-label" for="date">Periode Akhir</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y'); ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                </div>

              </div>

            </div>

          </div>

        </form>

      </div>
      
    </div>

  </div>

  <div class="col-lg-12 padding-left-right-10">

    <div class="panel panel-default">
      <!-- <div class="panel-heading">
        Responsive Table
      </div> -->

       
        <div class="table-responsive">
        <table id="list_data" class="table table-striped table-bordered">
        <thead>
        <tr class="no-hover"><th colspan="20" ><i class="fa fa-list fa-fw"></i> List Pengajuan</th></tr>
        <tr>
        <th style="width:45px; vertical-align: middle;">No</th>
        <th>No. Rangka</th>
        <th>Kd. Mesin</th>
        <th>No. Mesin</th>
        <th>Nama Pemilik</th>
        <th>Alamat</th>
        <th>Kelurahan</th>
        <th>Kecamatan</th>
        <th>Kota</th>
        <th>Kode POS</th>
        <th>Propinsi</th>
        <th>Jenis Pembayaran</th>
        <th>Kd. Dealer</th>
        <th>Kd. Fincoy</th>
        <th>DP</th>
        <th>Tenor</th>
        <th>Besar Cicilan</th>
        <th>Kd. Customer</th>
        </tr>
        </thead>
        <tbody>

                <?php echo $list_pengajuan; ?>

        </tbody>
        </table>
        </div>



    </div>
  </div>

</section>
