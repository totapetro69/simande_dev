 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$reff_source = $reff;
$reff_link = ($reff == 1 ? 'STNK' : 'BPKB');

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
      
      <a class="btn btn-default" href="<?php echo base_url('stnk/approval_biaya/'.$reff_link); ?>">
        <i class="fa fa-file-o fa-fw"></i> Baru
      </a>

      <a id="approve-btn" class="btn btn-default" role="button">
        <i class="fa fa-check-square-o fa-fw"></i> Approve
      </a>

      <a id="deny-btn" class="btn btn-default" role="button">
        <i class="fa fa-times fa-fw"></i> Tolak
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> List <?php echo $reff_link;?>
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="approveForm" action="<?php echo base_url('stnk/approval_pengurusan') ?>" class="bucket-form" method="get">


          <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER; ?>">
          <input type="hidden" id="status_stnk" name="status_stnk" value="<?php echo $status_stnk; ?>">
          <input type="hidden" id="approve_by" name="approve_by" value="<?php echo $this->session->userdata('user_id');?>">

          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/stnk_typeahead');?>"></div> -->

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


            <div class="col-xs-12 col-sm-7">

              <div class="form-group">
                  <label>No. Transaksi <?php echo $reff_link;?> <span class="load-form"></span></label>

                  <select id="no_trans" name="no_trans" class="form-control">
                    <option value="null">- Pilih No Trans <?php echo $reff_link;?> -</option>
                    <?php if($stnk_header && (is_array($stnk_header->message) || is_object($stnk_header->message))): foreach ($stnk_header->message as $key => $list_row):?>
                      <option value="<?php echo $list_row->NO_TRANS;?>"><?php echo $list_row->NO_TRANS;?></option>
                    <?php endforeach; endif;?>
                  </select>

              </div>

            </div>
<!-- 
            <div class="col-xs-12 col-sm-3">
              <div class="form-group">
                  <label>Di approve oleh</label>
                  <input type="text" id="approve_by" name="approve_by" value="" class="form-control" placeholder="Nama Pengurus" required>
              </div>
            </div> -->


            <div class="col-xs-12 col-sm-3">

              <div class="form-group">

                <label class="control-label" for="date">Tanggal Approve</label>
                <div class="input-group input-append date">
                    <input type="text" id="tgl_approve" name="tgl_approve" class="form-control" value="<?php echo date('d/m/Y'); ?>" disabled>
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
        <th rowspan="2" style="width:50px; vertical-align: middle;"></th>
        <th colspan="2">No Transaksi <?php echo $reff_link;?></th>
        <th colspan="4">Nama Pengurus</th>
        </tr>

        <tr>
        <th>No. Rangka</th>
        <th>No. Mesin</th>
        <th>Nama Pemilik</th>
        <th style="width: 70px;">Jumlah</th>
        <th style="width: 150px;">Biaya <?php echo $reff_link;?></th>
        <th style="width: 150px;">Biaya Disetujui</th>
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
