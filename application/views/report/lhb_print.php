
<?php

  $nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";

  if(isset($dealer)){
    if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $nama_kabupaten = NamaWilayah("Kabupaten",$value->KD_KABUPATEN);
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER_ASLI;
      }
    }
  }
?>

<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel">Laporan Harian Bengkel</h4>
</div>

<div class="modal-body" id="printarea">
   <div class="table-responsive h350">
   <table border='0' id="desc" class="table ">
      <tr>
         <td colspan="3"><?php echo $nama_dealer;?><br>
            <?php echo $alamat;?> <br>
            <?php echo $nama_kabupaten;?><br>
            <?php echo $tlp;?><br>
         </td>
      </tr>
      <tr align='center'>
         <td></td>
         <td rowspan="2"></td>
         <td><h3><strong>Laporan Harian Bengkel</strong></h3></td>
         <tr>
            <td colspan="5">Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></td>
         </tr>
      </tr>

   </table>

   <table border='1' class="table table-hover table-striped table-bordered">

      <thead>
         <tr>
            <th class="text-center" rowspan="3" style="width:40px;" >NO</th>
            <th class="text-center" rowspan="3">No. PKB</th>
            <th class="text-center" rowspan="3">Tanggal</th>
            <th class="text-center" rowspan="3">Nopol</th>
            <th class="text-center" rowspan="3">Mekanik</th>
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
         $jasa_k=""; $oli_k=""; $subtotal_k=""; $jasa_t=""; $oli_t=""; $part_t=""; $subtotal_t=""; $grandtotal="";
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
                     <td class='table-nowarp'><?php echo ($value->NAMA);?></td>
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
                  $jasa_k +=$value->JASA_K; $oli_k+=$value->OLI_K; $subtotal_k+=$value->SUBTOTAL_K; 
                  $jasa_t+=$value->JASA_T; $oli_t+=$value->OLI_T; $part_t+=$value->PART_T;; 
                  $subtotal_t+=$value->SUBTOTAL_T; $grandtotal+=$value->GRANDTOTAL;
               }
            }
         }
         ?>
      </tbody>
      <tfoot>
         <tr>
            <td colspan="5">Total</td>
            <td><?php echo $jasa_k;?></td>
            <td><?php echo $oli_k;?></td>
            <td><?php echo $subtotal_k;?></td>
            <td><?php echo $jasa_t;?></td>
            <td><?php echo $oli_t;?></td>
            <td><?php echo $part_t;?></td>
            <td><?php echo $subtotal_t;?></td>
            <td><?php echo $grandtotal;?></td>
         </tr>
      </tfoot>
   </table>
</div>
</div>

<div class="modal-footer">
   <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
   <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>
</div>

<script src="<?php echo base_url('assets/dist/print.min.js'); ?>"></script>
<script type="text/javascript">
   function printSj() {
      printJS('printarea', 'html');
      $('#keluar').click();
   }
</script>