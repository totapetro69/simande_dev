 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 

$reff_source = $reff;
$reff_link = ($reff == 1 ? 'STNK' : 'BPKB');
$status_udstk = ($udstk == true ? '' : 'disabled-action');
$status_p = (isBolehAkses('p') ? $status_udstk : 'disabled-action' ); 

$KD_DEALER = '';
$KD_MAINDEALER = '';
$NO_TRANS = '';
$ID = '';
$NAMA_PENGURUS = '';

if($stnk_header && (is_array($stnk_header->message) || is_object($stnk_header->message))):
  foreach ($stnk_header->message as $key => $value) {
    $KD_DEALER = $value->KD_DEALER;
    $KD_MAINDEALER = $value->KD_MAINDEALER;
    $NO_TRANS = $value->NO_TRANS;
    $ID = $value->ID;
    $NAMA_PENGURUS = $value->NAMA_PENGURUS;
    # code...
  }
endif;

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      
      <?php if($status_stnk == 0): ?>
      <a class="btn btn-default" href="<?php echo base_url('stnk/approval_pengurusan/'.$reff_link); ?>">
      <?php else:?>
      <a class="btn btn-default" href="<?php echo base_url('stnk/pengajuan_biaya/'.$reff_link); ?>">
      <?php endif;?>
          <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

      <?php if($status_stnk == 0): ?>
      <a id="store-btn" class="btn btn-default" role="button">
          <i class="fa fa-check-square-o fa-fw"></i> Approve
      </a>

      <a id="rejectaprv-btn" class="btn btn-default" role="button">
        <i class="fa fa-times fa-fw"></i> Tolak
      </a>
      <?php else:?>
      <a id="add-btn" class="btn btn-default" role="button">
          <i class="fa fa-share fa-fw"></i> Ajukan
      </a>
      <?php endif;?>

      <!-- <?php if($status_stnk == 0 && $reff_source == 1): ?>
      <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('stnk/createfile_udstk');?>" role="button">
          <i class="fa fa-download fa-fw"></i> Download File .UDSTK
      </a>
      <?php endif;?> -->

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
      <?php if($status_stnk == 0): ?>
          <i class="fa fa-list fa-fw"></i> Approval Pengurusan <?php echo $reff_link;?>
      <?php else:?>
          <i class="fa fa-list fa-fw"></i> Biaya <?php echo $reff_link;?>
      <?php endif;?>
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="approveForm" action="<?php echo base_url('stnk/approval_pengurusan') ?>" class="bucket-form" method="get">


          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">
          <input type="hidden" id="status_stnk" name="status_stnk" value="<?php echo $status_stnk; ?>">
          <input type="hidden" id="reff_source" name="reff_source" value="<?php echo $reff_source; ?>">

          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/stnk_typeahead');?>"></div> -->

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


            <div class="col-xs-12 col-sm-4">

              <div class="form-group">
                    <label>No. Transaksi <?php echo $reff_link;?></label>
                    <select id="no_trans" name="no_trans" class="form-control">
                    <option value="null">- Pilih No Trans <?php echo $reff_link;?> -</option>

                    <?php if($stnk_header && (is_array($stnk_header->message) || is_object($stnk_header->message))): foreach ($stnk_header->message as $key => $list_row):?>
                      <option value="<?php echo $list_row->NO_TRANS;?>"><?php echo $list_row->NO_TRANS;?></option>
                    <?php endforeach; endif;?>
                  </select>

              </div>

            </div>

            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label>Nama Pengurus <span class="load-form"></span></label>
                  <input type="text" id="nama_pengurus" name="nama_pengurus" value="" class="form-control" placeholder="Nama Pengurus" disabled>
              </div>
            </div>


            <div class="col-xs-12 col-sm-3">

              <div class="form-group">

                <label class="control-label" for="date">Tanggal Pengajuan</label>
                <div class="input-group input-append date">
                    <input type="text" id="tgl_trans" name="tgl_trans" class="form-control" value="<?php echo date('d/m/Y'); ?>" disabled>
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
        <tr class="no-hover"><th colspan="8" ><i class="fa fa-list fa-fw"></i> List Approval</th></tr>

        <tr>
        <th rowspan="2" style="width:45px; vertical-align: middle;">No</th>
        <th rowspan="2" style="width:50px; vertical-align: middle;"><input id="stnk_all" class="stnk_all" name="stnk_all" value="1" type="checkbox"></th>
        <?php if($status_stnk == 0): ?>
          <th colspan="2">No Transaksi <?php echo $reff_link;?></th>
          <th colspan="1">Nama Pengurus</th>
          <th colspan="2">Tgl Mulai Pengurusan</th>
          <th colspan="1">Tgl Selesai Pengurusan</th>
        <?php else:?>

          <th colspan="2">No Transaksi <?php echo $reff_link;?></th>
          
          <th colspan="4">Nama Pengurus</th>
        <?php endif;?>
          
        </tr>

        <tr>
        <th>No. Rangka</th>
        <th>No. Mesin</th>
        <th>Nama Pemilik</th>
        <th>Alamat</th>
        <th>Kode POS</th>
        <th><?php echo ($status_stnk == 0?'Nama Item':'Biaya '.$reff_link);?></th>
        </tr>
        </thead>
        <tbody>

                <?php echo $list_approval; ?>

        </tbody>
        </table>
        </div>



    </div>
  </div>

</section>

<script type="text/javascript" src="<?php echo base_url("assets/js/external/stnk.js");?>"></script>
