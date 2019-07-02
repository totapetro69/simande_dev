<?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 


$KD_MAINDEALER="";
$KD_DEALER="";
$NO_SJMASUK="";
$NO_TERIMASJM="";
$TGL_TRANS="";

$TGL_SJMASUK="";
$EXPEDISI="";
$NOPOL="";

$NO_FAKTUR="";
$NO_PO="";

if(base64_decode(urldecode($this->input->get("n")))){
// foreach ($rmheader->message as $key => $value) {
    // $URL_RM = $url_rm;
    $KD_MAINDEALER = $rmheader->KD_MAINDEALER;
    $KD_DEALER = $rmheader->KD_DEALER;
    $NO_SJMASUK = $rmheader->NO_SJMASUK;
    $NO_TERIMASJM = $rmheader->NO_TERIMASJM;
    $TGL_TRANS = tglfromSql($rmheader->TGL_TRANS);
    $TGL_SJMASUK = tglfromSql($rmheader->TGL_SJMASUK);
    $EXPEDISI = $rmheader->EXPEDISI;
    $NOPOL = $rmheader->NOPOL;
    $NO_FAKTUR = $rmheader->NO_FAKTUR;
    $NO_PO = $rmheader->NO_PO;
  // }
} 
?>
<section class="wrapper">


    <div class="breadcrumb margin-bottom-10">
        <?php echo breadcrumb();?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-default" href="<?php echo base_url('umsl/addpenerimaan'); ?>" role="button">
                <i class="fa fa-file-o fa-fw"></i> Baru
            </a>

            <a id="store-btn" class="btn btn-default" onclick="<?php echo ($NO_TERIMASJM == '')?'addRm()':'updateRm()';?>" role="button">
                <i class="fa fa-save fa-fw"></i> Simpan
            </a>

            <a id="modal-button" class="btn btn-default " onclick='' role="button" data-toggle="modal" data-target="#myModalLg" data-backdrop="static">
                <i class="fa fa-print fa-fw"></i> Cetak
            </a>

            <a class="btn btn-default" href="<?php echo base_url('umsl/terimamotor'); ?>" role="button">
                <i class="fa fa-table fa-fw"></i> List
            </a>

        </div>

    </div>


    <div class="col-lg-12 padding-left-right-10">

      <div class="panel margin-bottom-10">

        <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Delivery Order
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
        </div>

        <div class="panel-body panel-body-border">

          <form id="penerimaanForm" action="#" class="bucket-form" method="get">

            <input type="hidden" id="kd_maindealer" name="kd_maindealer" value="<?php echo $KD_MAINDEALER;?>">
            <input type="hidden" id="nopol" name="nopol" value="<?php echo $NOPOL;?>">

            <div class="row">

              <div class="col-xs-12 col-sm-2">
                
                  <div class="form-group">
                      <label>Dealer</label>
                      <select name="kd_dealer" id="kd_dealer" class="form-control" disabled="disabled" required="true">
                        <option value="">- Pilih Dealer -</option>
                        <?php foreach ($dealer->message as $key => $group) : 
                          if($KD_DEALER!=''):
                            $default=($KD_DEALER==$group->KD_DEALER)?" selected":" ";
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
                    <label>No. Sales Order</label>

                    <?php if($NO_SJMASUK == ''):?>
                    <select id="no_sjmasuk" name="no_sjmasuk" class="form-control">
                      <option value="null">- Pilih No SO -</option>
                      <?php if($list && (is_array($list->message) || is_object($list->message))): foreach ($list->message as $key => $list_row):?>
                        <option value="<?php echo $list_row->NO_SJMASUK;?>"><?php echo $list_row->NO_SJMASUK;?></option>
                      <?php endforeach; endif;?>
                    </select>
                    <?php else:?>
                    <input type="text" id="no_sjmasuk" name="no_sjmasuk" class="form-control" value="<?php echo $NO_SJMASUK;?>" placeholder="No. Terima SJ Masuk" disabled>
                    <?php endif;?>
                </div>

              </div>
              
              <div class="col-xs-12 col-sm-3">

                <div class="form-group">
                    <label>Nama Customer <span class="load-form"></span></label>
                    <input type="text" id="" name="" class="form-control" value="" placeholder="" disabled>
                </div>

              </div>
              
              <div class="col-xs-12 col-sm-3">

                <div class="form-group">
                    <label>Alamat <span class="load-form"></span></label>
                    <input type="text" id="" name="" class="form-control" value="" placeholder="Alamat Customer" disabled>
                </div>

              </div>
              
              <div class="col-xs-12 col-sm-6">

                <div class="form-group">
                    <label>Alamat Tujuan Lengkap</label>
                    <textarea class="form-control" placeholder="Alamat Tujuan Lengkap" disabled></textarea>
                </div>

              </div>

              <div class="col-xs-12 col-sm-3">

                <div class="form-group">
                    <label>Tujuan <span class="load-form"></span></label>
                    <input type="text" id="" name="" class="form-control" value="" placeholder="" disabled>
                </div>

              </div>

              <div class="col-xs-12 col-sm-3">

                <div class="form-group">
                    <label>Expedisi <span class="load-form"></span></label>
                    <input type="text" id="expedisi" name="expedisi" class="form-control" value="<?php echo $EXPEDISI;?>" placeholder="Expedisi" disabled>
                </div>

              </div>


            </div>

          </form>

        </div>

      </div>

    </div>

    <div class="col-lg-12 padding-left-right-10">

      <div class="panel panel-default">

          <div class="table-responsive">
            <table class="table table-bordered table-hover b-t b-light">
            <thead>
                <tr class="no-hover"><th colspan="7" ><i class="fa fa-list fa-fw"></i> List Detail Kendaraan</th></tr>
                <tr>
                    <th style="width:40px;">No.</th>
                    <!-- <th>No Terima SJ</th> -->
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>No. Mesin</th>
                    <th>No. Rangka</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
            </table>


            <table class="table table-bordered table-hover b-t b-light">
            <thead>
                <tr class="no-hover"><th colspan="7" ><i class="fa fa-list fa-fw"></i> List KSU</th></tr>
                <tr>
                    <th style="width:40px;">No.</th>
                    <!-- <th>No Terima SJ</th> -->
                    <th>Nama KSU</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>


            <table class="table table-bordered table-hover b-t b-light">
            <thead>
                <tr class="no-hover"><th colspan="7" ><i class="fa fa-list fa-fw"></i> List Hadiah</th></tr>
                <tr>
                    <th style="width:40px;">No.</th>
                    <!-- <th>No Terima SJ</th> -->
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            </table>

          </div>
      </div>
    </div>
</section>


<script type="text/javascript">
  
</script>