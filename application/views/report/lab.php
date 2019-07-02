<?php
if (!isBolehAkses()) {
   redirect(base_url() . 'auth/error_auth');
}

$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '$status_detail' : 'disabled-action' );
$tipe=($this->input->get("tp"))?$this->input->get("tp"):"0";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$kd_lokasidealer = "";
$tgl_trans = "";
$tgl_awal =($this->input->get("tgl_awal"))?$this->input->get("tgl_awal"):date("d/m/Y", strtotime("first day of this month"));
$tgl_akhir = ($this->input->get("tgl_akhir"))?$this->input->get("tgl_akhir"):date("d/m/Y");
$no_trans = "";
$nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";
if (isset($list)) {
  if(($list->totaldata > 0)) {
    foreach ($list->message as $key => $value) {
      $tgl_pkb = $value->TANGGAL_PKB;
    }
  }
}
?>


<section class="wrapper">
   <div class="breadcrumb margin-bottom-10">

        <?php echo breadcrumb(); ?>

        <div class="bar-nav pull-right ">

            <a class="btn btn-info" href="<?php echo base_url('report/lab?p=1&kd_dealer='.$defaultDealer.'&tgl_awal=' .$tgl_awal. '&tgl_akhir=' .$tgl_akhir); ?> "target="_blank" class="<?php echo $status_p?>">
                <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan Harian Bengkel" ></i> Cetak
            </a>
        </div>

    </div>

   <div class="col-lg-12 padding-left-right-10 ">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            Laporan Akumulasi Bengkel
            <span class="tools pull-right">
               <a class="fa fa-chevron-up" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" >
            <form id="filterFormz" action="<?php echo base_url('report/lab') ?>" class="bucket-form" method="get">
               <div class="row">
                  <div class="col-xs-12 col-sm-3 col-md-3">
                     <div class="form-group">
                        <label>Nama Dealer</label>
                        <select class="form-control" id="kd_dealer" name="kd_dealer">
                           <option value="0">--Pilih Dealer--</option>
                           <?php
                           if ($dealer) {
                             if (($dealer->totaldata > 0)) {
                               foreach ($dealer->message as $key => $value) {
                                 $aktif = ($defaultDealer == $value->KD_DEALER) ? "selected" : "";
                                 echo "<option value='" . $value->KD_DEALER . "' " . $aktif . ">" . $value->NAMA_DEALER . "</option>";
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
                     <th class="text-center" rowspan="3" style="width:40px;" >NO</th>
                     <th class="text-center" rowspan="3">Tanggal</th>
                     <th class="text-center" colspan="3">Kredit</th>
                     <th class="text-center" colspan="4">Tunai</th>
                     <th class="text-center" rowspan="3">Total</th>
                  </tr>

                  <tr>
                     <th class="text-center">NJB</th>
                     <th class="text-center">NSC</th>
                     <th class="text-center"></th>
                     <th class="text-center">NJB</th>
                     <th class="text-center" colspan="2">NSC</th>
                     <th class="text-center"></th>
                  </tr>

                  <tr>
                     <th class="text-center">Jasa</th>
                     <th class="text-center">Oli</th>
                     <th class="text-center">Subtotal</th>
                     <th class="text-center">Jasa</th>
                     <th class="text-center">Oli</th>
                     <th class="text-center">Part</th>
                     <th class="text-center">Subtotal</th>
                  </tr>

               </thead>

               <tbody>
                  <?php
                  if (isset($list)) {
                     $no = $this->input->get("page");
                     if (($list->totaldata >0 )) {
                        foreach ($list->message as $key => $value) {
                        # code...
                           $no++;
                           ?>
                           <tr>
                              <td class='table-nowarp'><?php echo $no; ?></td>
                              <td class='table-nowarp'><?php echo tglFromSql($value->TANGGAL_PKB);?></td>
                              <td class='text-right'><?php echo number_format(($value->JASA_K),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->OLI_K),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->SUBTOTAL_K),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->JASA_T),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->OLI_T),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->PART_T),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->SUBTOTAL_T),0);?></td>
                              <td class='text-right'><?php echo number_format(($value->GRANDTOTAL),0);?></td>
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
                  <?php echo ($pagination)?$pagination:""; ?>    
               </div>
            </div>
         </footer>
      </div>
   </div>
</section>