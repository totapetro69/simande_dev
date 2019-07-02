 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );

$file = ($list->totaldata > 0 ? '' : 'disabled-action'); 
$status_p = (isBolehAkses('p') ? $file : 'disabled-action' ); 
?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">

      <a class="btn btn-default <?php echo $status_c;?>" href="<?php echo base_url('claim/add_claimpromo'); ?>">
          <i class="fa fa-file-o fa-fw"></i> Input Claim
      </a>
      
      <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('claim/createfile_csv?kd_dealer='.$this->input->get('kd_dealer').'&jenis='.$this->input->get('jenis').'&kd_fincoy='.$this->input->get('kd_fincoy'));?>" role="button">
          <i class="fa fa-download fa-fw"></i> Download File .XLS
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> List Claim Promo
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="filterForm" action="<?php echo base_url('claim/claim_promo') ?>" class="bucket-form" method="get">


          <!-- <div id="ajax-url" url="<?php echo base_url('stnk/sj_typeahead');?>"></div> -->

          <div class="row">


            <div class="col-xs-12 col-sm-3">
                    
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

            <div class="col-xs-12 col-sm-2">
                    
              <div class="form-group">
                  <label>Jenis</label>
                  <select name="jenis" id="jenis" class="form-control" >
                    <option <?php echo $this->input->get('jenis')=='A'?" selected":" ";?> value="A">Jenis A</option>
                    <option <?php echo $this->input->get('jenis')=='B'?" selected":" ";?> value="B">Jenis B</option>
                  </select>
              </div>

            </div>


            <div class="col-xs-12 col-sm-6 col-sm-offset-1">
                    
              <div class="form-group">
                  <label>Company Leasing</label>
                  <select name="kd_fincoy" id="kd_fincoy" class="form-control">
                    <option value="">- Pilih Company -</option>
                    <?php foreach ($fincoy->message as $key => $comp) : ?>
                      <option <?php echo ($this->input->get('kd_fincoy')==$comp->KD_LEASING)?" selected":" ";?> value="<?php echo $comp->KD_LEASING;?>"><?php echo $comp->NAMA_LEASING;?></option>
                    <?php endforeach; ?>
                  </select>
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

       

      <?php echo $list_detail; ?>





    </div>
  </div>

</section>
