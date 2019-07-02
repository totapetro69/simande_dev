<?php
if (!isBolehAkses()) {
    redirect(base_url() . 'auth/error_auth');
}
$status_c = (isBolehAkses('c') ? '' : 'disabled-action' );
$status_e = (isBolehAkses('e') ? '' : 'disabled-action' );
$status_v = (isBolehAkses('v') ? '' : 'disabled-action' );
$status_p = (isBolehAkses('p') ? '' : 'disabled-action' ); 

$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$start_date =($this->input->get("start_date"))?$this->input->get("start_date"):date("d/m/Y", strtotime("first day of this month"));
$end_date = ($this->input->get("end_date"))?$this->input->get("end_date"):date("d/m/Y");

$SUM_JUAL=''; $TGL_ACT=''; $komparasi=''; $SUM_PROSPECT=''; $LOC_ACT=''; $SP_ACT=''; $SUM_UENTRY=''; $Desa=''; 
$Kecamatan=''; $Kabupaten=''; $Propinsi=''; $SUM_BUDGET='';
?>

<section class="wrapper">

   <div class="breadcrumb margin-bottom-10">
      <?php echo breadcrumb(); ?>
      <div class="bar-nav pull-right ">
      <a class="btn btn-info" href="<?php echo base_url('sales_event/salesevent_print?p=1&kd_dealer='.$defaultDealer.'&start_date=' .$start_date. '&end_date=' .$end_date); ?> "target="_blank" class="<?php echo $status_p?>">
        <i class='fa fa-print fa-fw' data-toggle="tooltip" data-placement="left" title="Print Laporan sales Event" ></i> Cetak
      </a>
      </div>
   </div>

   <div class="col-lg-12 padding-left-right-10">
      <div class="panel margin-bottom-10">
         <div class="panel-heading">
            Laporan Sales Event
            <span class="tools pull-right">
               <a class="fa fa-chevron-down" href="javascript:;"></a>
            </span>
         </div>

         <div class="panel-body panel-body-border" style="display: block;">
            <form id="filterForm" action="<?php echo base_url('sales_event/report_sm') ?>" class="bucket-form" method="get">
               <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
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

            <div class="col-xs-12 col-sm-3 col-md-3">
              <div class="form-group">
                <label>Cari</label>
                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Masukkan Nama Event / Nama PIC" autocomplete="off">
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

   <div class="col-lg-12 padding-left-right-10">
      <div class="panel panel-default">
         <div class="table-responsive">
            <table class="table table-striped b-t b-light">

               <thead>
                  <tr>
                     <th style="width:40px;">No.</th>
                     <th>Kode Event</th>
                     <th>Nama Event</th>
                     <th>Total Jual </th>
                     <th>Alokasi Budget</th>
                     <th>Komparasi</th>
                     <th>Sum Prospek</th>
                     <th>Tgl Pelaksanaan Act</th>
                     <th>Budget Aktual</th>
                     <th style="text-align: center;">Lokasi Act</th>
                     <th style="font-size: 12px;">Sum Unit Entry</th>
                     <th>PIC</th>
                     <th>Sales Jaga</th>
                  </tr>
               </thead>

               <tbody>
                  <?php
                  if ($list) {
                     $no = 0;
                     if (is_array($list->message)) {
                        foreach ($list->message as $key => $value) {
                           $no++;
                           ?>
                           <tr>
                              <td style=" width:3px"><?php echo $no; ?></td>
                              <td style="text-align: center;"><?php echo $value->KD_EVENT;?></td>
                              <td style="text-align: center;"><?php echo $value->NAMA_EVENT;?></td>
                              <td style="text-align: center;"><?php echo number_format($value->UNIT_REVENUE,0);?></td>
                              <td style="text-align: center;"><?php echo number_format($value->ALOKASI_BUDGET,0);?></td>
                              <td style="text-align: center;"><?php echo number_format($value->COMPARASI,0);?></td>
                              <td style="text-align: center;"><?php echo $value->JUMLAH_PORSPECT;?></td>
                              <td style="text-align: center;"><?php echo tglFromSql($value->TGL_EVENT); ?></td>
                              <td style="text-align: center;"><?php echo number_format($value->AKTUAL_BUDGET,0); ?></td>
                              <td style="font-size: 12px;"><?php echo $value->LOKASI_EVENT;?></td>
                              <td style="text-align: center;"><?php echo $value->JUMLAH_UNIT;?></td>
                              <td style="text-align: center;"><?php echo $value->NAMA_SALES;?></td>
                              <td style="text-align: center;"><?php echo $value->SALES_JAGA;?></td>
                            </tr>
                           <?php
                        }
                     } else {
                        belumAdaData(20);
                     }
                  } else {
                     belumAdaData(20);
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
