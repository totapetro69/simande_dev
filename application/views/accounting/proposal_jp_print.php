<?php
$no_reff="";$nopol="";$customer="";$no_hp="";$alm_cust="";$tgl="";$nama_user=""; $area=""; $lokasi="";
$defaultDealer =($this->input->get("kd_dealer"))?$this->input->get("kd_dealer"): $this->session->userdata("kd_dealer");
$tgl_trans=date("d/m/Y");
$area_kegiatan="";$lokasi_joinpromo="";$kegiatan="";$no_trans="";$tgl_joinpromo=date('d/m/Y');
$tujuan_joinpromo=""; $target_audiens="";$target_sales="";$target_database="";
$ringkasan_joinpromo="";$approval="";$approval_date="";$approval_by="";
if(isset($list)){
   if($list->totaldata >0){
      foreach($list->message as $key=>$value){
         $defaultDealer = $value->KD_DEALER;
         $tgl_trans = TglFromSql($value->TGL_TRANS);
         $tgl_joinpromo = TglFromSql($value->TGL_JOINPROMO);
         $no_trans = $value->NO_TRANS;
         $area_kegiatan = $value->AREA_JOINPROMO;
         $lokasi_joinpromo = $value->LOKASI_JOINPROMO;
         $kegiatan = $value->KEGIATAN_JOINPROMO;
         $tujuan_joinpromo = $value->TUJUAN_JOINPROMO;
         $target_audiens = $value->TARGET_AUDIENS;
         $target_sales = $value->TARGET_SALES;
         $target_database = $value->TARGET_DATABASE;
         $ringkasan_joinpromo = $value->RINGKASAN_JOINPROMO;
         $approval = $value->STATUS_JOINPROMO;
      }
   }
}
if(isset($dealer)){
   if($dealer->totaldata >0){
      foreach ($dealer->message as $key => $value) {
        $kd_dealerahm = $value->KD_DEALERAHM;
        $alamat = $value->ALAMAT;
        $nama_kabupaten = $value->NAMA_KABUPATEN;
        $tlp = $value->TLP;
        $nama_dealer = $value->NAMA_DEALER;
        $area=$value->AREA;
        $lokasi=$value->LOKASI;
      }
   }
}
$nama_user = NamaUser($this->session->userdata("user_id"));
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h5 class="modal-title" id="myModalLabel">PROPOSAL JOIN PROMO</h5>
</div>

<div class="modal-body" id="printarea">
  <table style="border-collapse: collapse; font-size: 10pt; width: 100%;" border="0">
    <tr style="height: :50px;">
      <td colspan="4" valign="middle" align="center"><h4>PROPOSAL JOIN PROMO</h4></td>
    </tr>

    <tr>
      <td style="white-space: nowrap; width:130px">Kepada</td>
      <td style="width: 25px;" align="center">:</td>
      <td style="width:250px"><?php echo substr($customer,0,50);?></td>
      <td>&nbsp;<?php //echo urlencode(base64_encode('JPT13201901-00009'));?></td>
    </tr>

    <tr>
      <td>Tanggal</td>
      <td align="center">:</td>
      <td><?php echo $tgl_trans;?></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>Area</td>
      <td align="center">:</td>
      <td><?php echo $area_kegiatan;?></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>Kegiatan</td>
      <td align="center">:</td>
      <td><?php echo $kegiatan;?></td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>No</td>
      <td align="center">:</td>
      <td><?php echo $no_trans;?></td>
      <td>&nbsp;</td>
    </tr>
    <tr class="subtotal" style="height:35px; padding-top: 5px; border-top: 1px solid grey">
      <td colspan="4">INFORMASI KEGIATAN</td>
    </tr>

    <tr>
      <td>Tujuan Kegiatan</td>
      <td align="center">:</td>
      <td><?php echo $tujuan_joinpromo;?></td>
      <td rowspan="6"; style="padding: 5px;" valign="top">
         <table style="width:100%; border-collapse: collapse;font-size: 10pt;">
            <thead>
               <tr><td colspan="4"><h5>APPROVAL STATUS</h5></td></tr>
               <tr style="" valign="middle">
                  <td style="border: 1px solid; width:15%; text-align: center;" align="center">No.</td>
                  <td style="border: 1px solid; width:45%; text-align: center;" align="center">Approved By</td>
                  <td style="border: 1px solid; width:25%; text-align: center;" align="center">Approved Date</td>
                  <td style="border: 1px solid; width:15%; text-align: center;" align="center">Status</td>
               </tr>
            </thead>
            <tbody>
               <?php
                  $nx=0;
                  if(isset($apv)){
                     if($apv->totaldata >0){
                        foreach ($apv->message as $key => $value) {
                           $nx++;
                           ?>
                              <tr>
                                 <td style="border:1px solid; text-align: center"><?php echo $nx;?></td>
                                 <td style="border:1px solid; text-align: left"><?php echo $value->APPROVAL_BY;?></td>
                                 <td style="border:1px solid; text-align: center"><?php echo TglFromSql($value->APPROVAL_DATE);?></td>
                                 <td style="border:1px solid; text-align: center"><?php echo $value->APPROVAL_STATUS;?></td>
                           <?php
                        }
                     }
                  }
               ?>
            </tbody>
         </table>
      </td>
    </tr>

    <tr>
      <td>Tanggal Kegiatan</td>
      <td align="center">:</td>
      <td><?php echo $tgl_joinpromo;?></td>
   </tr>

    <tr>
      <td>Lokasi Kegiatan</td>
      <td align="center">:</td>
      <td><?php echo $lokasi_joinpromo;?></td>
   </tr>

    <tr>
      <td>Ringkasan Kegiatan</td>
      <td align="center">:</td>
      <td><?php echo $ringkasan_joinpromo;?></td>
   </tr>

    <tr>
      <td>Target Audiens</td>
      <td align="center">:</td>
      <td><?php echo $target_audiens;?> Orang</td>
   </tr>

    <tr>
      <td>Target Sales</td>
      <td align="center">:</td>
      <td><?php echo $target_sales;?> Unit</td>
   </tr>

    <tr style="height: 30px; border-bottom: 1px solid grey">
      <td>Target Database</td>
      <td align="center">:</td>
      <td><?php echo $target_database;?> Orang</td>
      <td>&nbsp;</td>
   </tr>
</table>
<table style="width:100%; border-collapse: collapse; font-size: 10pt; ">
   <tr style="height: 35px">
      <td colspan="7" style="padding-left: 10px; border-bottom: 1px solid grey;"><b><i class='fa fa-list-ul'></i> BUDGETING</b></td>
   </tr>
   <tr style="text-align: center; padding-top: 3px; height: 35px; background-color: #cccc" valign="middle" align="center">
      <th style="width: 30px; border:1px solid; text-align: center;">No.</th>
      <th style="width: 300px; border:1px solid;text-align: center;">URAIAN</th>
      <th style="width: 25px; border:1px solid;text-align: center;">VOLUME</th>
      <th style="width: 100px; border:1px solid;text-align: center;">SATUAN</th>
      <th style="width: 150px; border:1px solid;text-align: center;">HARGA</th>
      <th style="width: 150px; border:1px solid;text-align: center;">JUMLAH</th>
      <th style="width: 300px; border:1px solid;text-align: center;">KETERANGAN</th>
   </tr>
   <tbody>
      <?php
        $no = 0; $t_harga=0;
         if (isset($detail)) {
           if (($detail->totaldata >0 )) {
               foreach ($detail->message as $key => $value) {
                  # code...
                  $no++;
                  ?>
                  <td style="border:1px solid;text-align: center;"><?php echo $no; ?></td>
                  <td style="border:1px solid;text-align: left; padding-left: 5px"><?php echo $value->URAIAN_JOINPROMO;?></td>
                  <td style="border:1px solid;text-align: center;"><?php echo $value->VOLUME_JOINPROMO;?></td>
                  <td style="border:1px solid;text-align: left; padding-left: 5px"><?php echo $value->SATUAN_JOINPROMO;?></td>
                  <td style="border:1px solid;text-align: right; padding-right: 5px;"><?php echo number_format($value->HARGA_JOINPROMO,0);?></td>
                  <td style="border:1px solid;text-align: right; padding-right: 5px;"><?php echo number_format($value->JUMLAH_JOINPROMO,0);?></td>
                  <td style="border:1px solid;text-align: left; padding-left: 5px"><?php echo $value->KETERANGAN_JOINPROMO;?></td>
               </tr>
               <?php
                  $t_harga += $value->JUMLAH_JOINPROMO;
               }
            }
         }
         ?> 
      </tbody>
      <tfoot>
         <tr style="height: 30px;" valign="middle">
            <td colspan="5" class='text-right' style="border:1px solid;text-align: right;padding-right: 10px"><b>TOTAL</b></td>
            <td class="text-right" style="border:1px solid;text-align: right; padding-right: 5px;"><b><?php echo number_format($t_harga,0);?></b></td>
            <td style="border:1px solid;text-align: right; padding-right: 5px;">&nbsp;</td>
         </tr>
         <tr>
            <td colspan="5">
               <table style="border-collapse: collapse; font-size: 10pt;">
                  <tr style="height: 40px; border-bottom: 1px solid;" valign="middle">
                     <td colspan="3"><b>SHARING BUDGET</b></td>
                  </tr>
                  <tr><td colspan="3"> &nbsp;</td></tr>
                  <?php
                     if(isset($sharing)){
                        $n=0;$jml_share=0;
                        if($sharing->totaldata >0){
                           foreach ($sharing->message as $key => $value) {
                              $n++;
                              ?><tr>
                                 <td style="width:30px; text-align: center"><?php echo $n;?>.</td>
                                 <td style="width:70px; text-align: left"><?php echo $value->KD_LEASING;?></td>
                                 <td style="width:30px; text-align: right; padding-right: 5px;"><?php echo number_format($value->JUMLAH_SHARING,0);?></td>
                                 <td>&nbsp;</td>
                              </tr>
                              <?php
                              $jml_share += $value->JUMLAH_SHARING;
                           }
                        }
                        ?>
                           <tr style="height: 30px; border-top: 1px solid;" valign="middle">
                              <td colspan="2"><b>TOTAL </b></td>
                              <td style="text-align: right; padding-right: 5px;"><b><?php echo number_format($jml_share,0);?></b></td>
                           </tr>
                        <?php
                     }
                  ?>
               </table>
            </td>
         </tr>
      </tfoot>

</table>

</div>

<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printKw();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>
<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#print_kwts').hide();
    $('#myModalLg').on("hidden.bs.modal",function(){
      $('#print_kwts').show();
    })
  })
   function printKw() {
      printJS('printarea','html');
       $('#keluar').click();
    }
</script>