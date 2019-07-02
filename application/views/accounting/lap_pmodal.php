<?php
//echo isBolehAkses();
  if (!isBolehAkses()) {
      redirect(base_url() . 'auth/error_auth');
  }

  $status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
  $status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
  $status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
  $status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
  $usergroup=$this->session->userdata("kd_group");
  $tahun=($this->input->get("tahun"))?$this->input->get("tahun"):date("Y");
?>
<section class="wrapper">
  <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <!-- <div class="bar-nav pull-right ">

            <a href="<?php echo base_url('report/cetak_lap'); ?> "target="_blank" class="<?php echo $status_p?>">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Harian Bengkel" ></i> Cetak
            </a>
        </div> -->

    </div>

  <div class="col-lg-12 padding-left-right-10">
    <div class="panel margin-bottom-10">
      <div class="panel-heading">
        LAPORAN PERUBAHAN MODAL
        <span class="tools pull-right">
          <a class="fa fa-chevron-up" href="javascript:;"></a>
        </span>
      </div>

      <div class="panel-body panel-body-border" style="display: block;">
        <form id="frmCriteria" action="<?php echo base_url('report/lap_pmodal') ?>" class="bucket-form" method="get">

          <div class="row">
            <div class="col-sm-4 col-md-4 col-xs-12">

              <div class="form-group">
                <label>Nama Dealer</label>
                <select name="kd_dealer" id="kd_dealer" class="form-control" <?php echo($usergroup!=='0')?" disabled='disabled'":""?>">
                  <option value="">--Pilih Dealer--</option>
                  <?php
                  if($dealer){
                    if(is_array($dealer->message)){
                      foreach ($dealer->message as $key => $value) {
                        $select=($this->session->userdata('kd_dealer')==$value->KD_DEALER)?"selected":"";
                        $select=($this->input->get("kd_dealer")==$value->KD_DEALER)?"selected":$select;
                        echo "<option value='".$value->KD_DEALER."' ".$select.">".$value->NAMA_DEALER."</option>";
                      }
                    }
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-3 col-md-3 col-sm-3">
              <div class="form-group">
                <label>Periode Bulan</label>
                <select id="bulan" name="bulan" class="form-control">
                  <option value="">--Pilih Bulan</option>
                  <?php 
                  for($i=1;$i<=12; $i++){
                    $pilih=(date("m")==$i)?"selected":"";
                    $pilih=((int)$this->input->get("bulan")==$i)?"selected":"";
                    echo "<option value='".$i."' ".$pilih.">".nBulan($i)."</option>";
                  }
                  ?>
                </select>
              </div>
            </div>

            <div class="col-xs-3 col-md-2 col-sm-2">
              <div class="form-group">
                <label>Tahun</label>
                <select id="tahun" name="tahun" class="form-control">
                  <option value="">--Pilih Tahun</option>
                  <?php 
                  if(isset($tahun)){
                    if($tahun->totaldata>0){
                      foreach ($tahun->message as $key => $value) {
                        $pilih=(date("Y")==$value->TAHUN)?"selected":"";
                        $pilih=($this->input->get("tahun")==$value->TAHUN)?"selected":$pilih;
                        echo "<option value='".$value->TAHUN."' $pilih>".$value->TAHUN."</option>";
                      }
                    }else{
                       echo "<option value='".date("Y")."' selected>".date("Y")."</option>";
                     }
                   }
                   ?>
                 </select>
               </div>
             </div>

             <div class="col-xs-3 col-md-1 col-sm-1">
              <div class="form-group">
                <br>
                <button type="submit" class="btn btn-info"><i class='fa fa-search'></i> Preview</button>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>

   </div>

   <div class="clearfix"></div>

   <div class="col-lg-12 padding-left-right-10" >
      <div class="">
            <table class="table table-striped table-hover table-bordered">
                  <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2"><center>Modal Saham</center></th>
                    <th rowspan="2"><center>Saldo Laba</center></th>
                    <th rowspan="2"><center>Jumlah</center></th>
                 </tr>

                <tr>
                  <!-- <td></td> -->
                </tr>
                <tr>
                    <th>Saldo <?php echo date('d-m-Y');?></th>
                    <td>Rp. xxxxxx</td>
                    <td>Rp. xxxxxx</td>
                    <td>Rp. xxxxxx</td>
                </tr>
                <tr>
                    <th>Tambahan Modal Saham</th>
                    <td>%</td>
                    <td>%</td>
                    <td>%</td>
                </tr>
                <tr>
                    <th>Dividen</th>
                    <td>%</td>
                    <td>%</td>
                    <td>%</td>
                </tr>
                <tr>
                    <th>Laba Bersih</th>
                    <td>%</td>
                    <td>%</td>
                    <td>%</td>
                </tr>
                <tr>
                    <th>Koreksi Laba Rugi Tahun Lalu</th>
                    <td>%</td>
                    <td>%</td>
                    <td>%</td>
                </tr>
            </table>
        </div>
    </div>
</section>
<!-- <script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#print_l').addClass('hidden').on('click',function(){
         printJS({
                printable:'printarea',
                type:'html',
                targetStyles:'*'
            });
        })
    })
</script> -->