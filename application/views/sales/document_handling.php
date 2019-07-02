 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 

$udh = ($udh->totaldata>0 ? '':'disabled-action' ); 
$status_p = (isBolehAkses('p') ? $udh : 'disabled-action' ); 

$KD_DEALER = '';
$KD_MAINDEALER = '';

/*if($list && (is_array($list->message) || is_object($list->message))):

  foreach ($list->message as $key => $value) {
    $KD_DEALER = $value->KD_DEALER;
    $KD_MAINDEALER = $value->KD_MAINDEALER;
    # code...
  }

endif;*/

$tgl_awal   = $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month'));
$tgl_akhir  = $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y');

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      <!-- 
      <a id="download-btn" class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('stnk/createfile_udh');?>" role="button">
          <i class="fa fa-download fa-fw"></i> Download File .UDH
      </a> -->


      <a id="download-btn" class="download-btn btn btn-default <?php echo $status_p;?>" url="<?php echo base_url('stnk/createfile_udh');?>" role="button">
          <i class="fa fa-download fa-fw"></i> Download File .UDH
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

        <form id="filterForm" action="<?php echo base_url('stnk/document_handling') ?>" class="bucket-form" method="get">


          <input type="hidden" id="tgl_trans" name="tgl_trans" value="<?php echo date('d/m/Y'); ?>">
          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">

          <div id="ajax-url" url="<?php echo base_url('stnk/dochand_typeahead');?>"></div>


          <!-- <div id="pengurus-url" url="<?php echo base_url('stnk/pengurus_typeahead');?>"></div> -->

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
                  <label>Keyword</label>
                  <input type="text" id="keyword" name="keyword" value="<?php echo $this->input->get('keyword'); ?>" class="form-control" placeholder="cari berdasarkan kd item atau nama pemilik" autocomplete="off">
                 
              </div>

            </div>

            <div class="col-xs-12 col-sm-2">
              <div class="form-group">
                  <label>Keterangan</label>
                  <select name="keterangan" id="keterangan" class="form-control">
                    <option value="1" <?php echo ($this->input->get('keterangan')==1)?" selected":" ";?> >
                      STNK
                    </option>
                    <option value="2" <?php echo ($this->input->get('keterangan')==2)?" selected":" ";?> >
                      BPKB
                    </option>

                  </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Awal</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_awal" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo $tgl_awal; ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>

              </div>

            </div>

            <div class="col-xs-12 col-sm-2">

              <div class="form-group">

                <label class="control-label" for="date">Periode Akhir</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tgl_akhir" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo $tgl_akhir; ?>" type="text"/>
                    <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>

                </div>

              </div>

            </div>

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
        <tr class="no-hover"><th colspan="9" ><i class="fa fa-list fa-fw"></i> List Pengajuan</th></tr>

        <tr>
        <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
        <th rowspan="2">Pemilik</th>
        <th rowspan="2">Nama Item</th>
        <th rowspan="2">NO MESIN</th>
        <th rowspan="2">NO RANGKA</th>
        <th rowspan="2">Status</th>
        <th rowspan="2">Nomor</th>
        <th colspan="2" style="text-align: center;">Tanggal</th>
        </tr>

        <tr>
        <th>Terima</th>
        <th>Penyerahan</th>
          
        </tr>
        </thead>
        <tbody>

        <?php
          $no = $this->input->get('page');
          if($list):
            if(is_array($list->message) || is_object($list->message)):
            foreach($list->message as $key=>$row): 

            switch ($row->STATUS_STNK) {
              case 0:
                  $status = 'Input Pengurusan';
                  break;
              case 1:
                  $status = 'Approval Pengurusan';
                  break;
              case 2:
                  $status = 'Pengajuan Biaya';
                  break;
              case 3:
                  $status = 'Approval Biaya';
                  break;
              case 4:
                  $status = 'Approval Kasir';
                  break;
              case 5:
                  $status = 'Penerimaan';
                  break;
              case 6:
                  $status = 'Penyerahan';
                  break;
              case 7:
                  $status = 'Cetak UDH';
                  break;
            }

            $no ++;
            ?>

              <tr>
                <td><?php echo $no;?></td>
                <td><?php echo $row->NAMA_PEMILIK;?></td>
                <td><?php echo $row->NAMA_PASAR;?></td>
                <td><?php echo $row->NO_MESIN;?></td>
                <td><?php echo $row->NO_RANGKA;?></td>
                <td><?php echo $status;?></td>
                <td><?php echo $row->DATA_NOMOR;?></td>
                <td><?php echo tglfromSql($row->TGL_PENERIMA);?></td>
                <td><?php echo tglfromSql($row->TGL_PENYERAHAN);?></td>
              </tr>

            <?php   

            endforeach;
            else:
          ?>
            <tr>
                <td>&nbsp;<i class="fa fa-info-circle"></i></td>
                <td colspan="8"><b><?php echo ($list->message); ?></b></td>
            </tr>
        <?php
            endif;
          else:
        
            belumAdaData(9);

          endif;
        ?>

        </tbody>
        </table>
      </div>


      <footer class="panel-footer">
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
      </footer>

    </div>
  </div>

</section>

