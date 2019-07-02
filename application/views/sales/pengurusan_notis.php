 <?php if(!isBolehAkses()){ redirect(base_url().'auth/error_auth');}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' ); 
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' ); 
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' ); 
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$KD_DEALER = '';
$KD_MAINDEALER = '';

$urus_plat = $this->input->get('keterangan')==3?'':'disabled-action';

?>
<section class="wrapper">


<!-- <div style="margin-left:20px;margin-top:50px;"> -->
<!-- </div>  -->

  <div class="breadcrumb margin-bottom-10">
    <?php echo breadcrumb();?>

    <div class="bar-nav pull-right ">
      
      <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('notis/notis_xls?jenis='.$this->input->get("jenis").'&tahun='.$this->input->get("tahun")); ?>">
          <i class="fa fa-file-text fa-fw"></i> File Excel
      </a>

      <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('notis/notis_pdf?jenis='.$this->input->get("jenis").'&tahun='.$this->input->get("tahun")); ?>" target="_blank">
          <i class="fa fa-print fa-fw"></i> Cetak Notis
      </a>

    </div>
    <!-- </li> -->
  </div>


  <div class="col-lg-12 padding-left-right-10">

    <div class="panel margin-bottom-10">

      <div class="panel-heading">
          <i class="fa fa-list fa-fw"></i> Pengurusan Notis
          <span class="tools pull-right">
              <a class="fa fa-chevron-down" href="javascript:;"></a>
          </span>
      </div>

      <div class="panel-body panel-body-border" style="display: show;">

        <form id="filterForm" action="<?php echo base_url('notis/pengurusan_notis') ?>" class="bucket-form" method="get">


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


            <div class="col-xs-12 col-sm-7">
              <div class="form-group">
                  <label>Jenis notis</label>
                  <select name="jenis" id="jenis" class="form-control">
                    <!-- <option value="2" <?php echo ($this->input->get('keterangan')==2)?" selected":" ";?> >
                      BPKB
                    </option> -->
                    <!-- <option>-- Pilih Notis --</option> -->
                    <option value="1" <?php echo ($this->input->get('jenis')==1?" selected":" ");?>>Notis Belum Dimohonkan</option>
                    <option value="2" <?php echo ($this->input->get('jenis')==2?" selected":" ");?>>Notis Belum Selesai</option>
                    <option value="3" <?php echo ($this->input->get('jenis')==3?" selected":" ");?>>Notis Sudah Selesai</option>
                    <option value="4" <?php echo ($this->input->get('jenis')==4?" selected":" ");?>>Notis Belum Diserahkan</option>
                    <option value="5" <?php echo ($this->input->get('jenis')==5?" selected":" ");?>>Lead Time Pendaftaran Notis Pajak</option>
                    <option value="6" <?php echo ($this->input->get('jenis')==6?" selected":" ");?>>Lead Time Penyerahan Notis Pajak</option>
                    <option value="7" <?php echo ($this->input->get('jenis')==7?" selected":" ");?>>Notis Belum Diserahkan, STNK Sudah Diserahkan</option>

                  </select>
              </div>
            </div>

            <div class="col-xs-12 col-sm-2 col-sm-offset-1">

              <div class="form-group">

                <label class="control-label" for="date">Tahun</label>
                <div class="input-group input-append date">
                    <input class="form-control" id="tahun" name="tahun" placeholder="DD/MM/YYYY" value="<?php echo $this->input->get('tahun')?$this->input->get('tahun'):date('Y'); ?>" type="text"/>
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
      <div class="table-responsive">


        <?php echo $list_detail;?>
        
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

<!-- <script type="text/javascript" src="<?php echo base_url("assets/js/external/stnk.js");?>"></script> -->
<script type="text/javascript">

$(document).ready(function(){

  var date = new Date();
  date.setDate(date.getDate());


  $('.date').datepicker({
      format: 'yyyy',
      viewMode: "years", 
      minViewMode: "years",
      endDate: date,
      autoclose: true
  });


})
  
</script>
