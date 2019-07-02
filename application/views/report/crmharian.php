<?php
if (!isBolehAkses()) {
   redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' );
//$status_p = '';
$tipe=($this->input->get("tp"))?$this->input->get("tp"):"0";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$kd_lokasidealer = "";
$tgl_trans = "";
$no_trans = "";
$nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";
if (isset($list)) {
  if(($list->totaldata > 0)) {
    foreach ($list->message as $key => $value) {
      $no_pkb = $value->NO_PKB;
      $tgl_pkb = $value->TANGGAL_PKB;
      $no_polisi = $value->NO_POLISI;
      
    }
  }
}
?>


<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

             <a class="btn btn-default <?php echo $status_p;?>" href="<?php echo base_url('report/crmharian_xls?tgl_awal=' . $this->input->get("tgl_awal") . '&tgl_akhir=' . $this->input->get("tgl_akhir"));  ?>">
                <i class="fa fa-file-text fa-fw"></i> File Excel
            </a>
        </div>

    </div>

   <div class="col-lg-12 padding-left-right-10 ">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            Laporan CRM Harian
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" >
            <form id="filterFormz" action="<?php echo base_url('report/crmharian') ?>" class="bucket-form" method="get">
               <div class="row">
                  <div class="col-xs-12 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select name="kd_dealer" class="form-control" <?php echo ($this->session->userdata("kd_group") == "Root") ? "" : "disabled"; ?>>
                           <option value="0">--Pilih Dealer--</option>
                           <?php
                           $namadealer=NamaDealer($defaultDealer);
                           if ($dealer) {
                              if (is_array($dealer->message)) {
                                 foreach ($dealer->message as $key => $value) {
                                    $select = ($this->session->userdata('kd_dealer') == $value->KD_DEALER) ? "selected" : "";
                                    $select = ($this->input->get("kd_dealer") == $value->KD_DEALER) ? "selected" : $select;
                                    echo "<option value='" . $value->KD_DEALER . "' " . $select . ">" . $value->NAMA_DEALER . "</option>";
                                    $namadealer=($defaultDealer == $value->KD_DEALER)?NamaDealer($value->KD_DEALER):$namadealer;
                                 }
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>

                  <div class="col-xs-12 col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="date">Tanggal Awal</label>
                        <div class="input-group input-append date">
                           <input class="form-control" name="tgl_awal" placeholder="DD/MM/YYYY" value="<?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d/m/Y', strtotime('first day of this month')); ?>" type="text"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                     </div>
                  </div>

                  <div class="col-xs-12 col-sm-3">
                     <div class="form-group">
                        <label class="control-label" for="date">Tanggal Akhir</label>
                        <div class="input-group input-append date">
                           <input class="form-control" name="tgl_akhir" placeholder="DD/MM/YYYY" value="<?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?>" type="text"/>
                           <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
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

   <div class="col-lg-12 padding-left-right-20">
      <div class="panel panel-default">
         <div class="table-responsive h350">

            <table class="table table-stripped table-hover table-bordered" style="font-size: 12px">
               <thead>
                  <tr>
                     <th class="text-center" style="width:40px;" >NO</th>
                     <th class="text-center">No. PKB</th>
                     <th class="text-center">Tanggal</th>
                     <th class="text-center">Nopol</th>
                     <th class="text-center">Type Motor</th>
                     <th class="text-center">Tahun</th>
                     <th class="text-center">No Mesin</th>
                     <th class="text-center">No Rangka</th>
                  </tr>

                 

               </thead>

               <tbody>
                  <?php
                  if (isset($list)) {
                     $no = 0;
                     if (($list->totaldata >0 )) {
                        foreach ($list->message as $key => $value) {
                        # code...
                           $no++;
                           ?>
                           <tr>
                              <td class='table-nowarp'><?php echo $no; ?></td>
                              <td class='table-nowarp'><?php echo ($value->NO_PKB);?></td>
                              <td class='table-nowarp'><?php echo tglFromSql($value->TANGGAL_PKB);?></td>
                              <td class='table-nowarp'><?php echo ($value->NO_POLISI);?></td>
                              <td class='table-nowarp'><?php echo ($value->NAMA_TYPEMOTOR);?></td>
                              <td class='text-right'><?php echo ($value->TAHUN); ?></td>
                              <td class='text-right'><?php echo ($value->NO_MESIN); ?></td>
                              <td class='text-right'><?php echo ($value->NO_RANGKA); ?></td>
                           </tr>
                           <?php
                        }
                     }
                  }
                  ?>
               </tbody>
            </table>
         </div>
         <footer class="panel-footer">
            <div class="row">
               <div class="col-sm-5">
                  <small class="text-muted inline m-t-sm m-b-sm"> 
                     <?php echo ($list) ? ($list->totaldata == '' ? "" : "<i>Total Data " . $list->totaldata . " items</i>") : '' ?>
                        
                     </small>
               </div>
               <div class="col-sm-7 text-right text-center-xs">                
                  <?php echo $pagination; ?>    
               </div>
            </div>
         </footer>
      </div>
   </div>
</section>