<style type="text/css">

    @page { size: portrait; }
}
}
</style>
<div class="modal-header">
   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
   <h4 class="modal-title" id="myModalLabel">KWITANSI</h4>
</div>
<?php
   $kd_dealer="";$alamat_dealer="";$fincoy="";$telp="";$nama_dealer="";$ppn=0; $nama_finco="";
   $no_trans="";$jumlah=0;$keterangan=""; $no_mesin="";$no_rangka="";$nama_kota;
   $harga_otr=0; $uang_muka=0; $dibuat_oleh=""; $nama_customer="";$telp="";$fax="";$total=0;
   if(isset($kupon)){
      if($kupon->totaldata >0){
         foreach ($kupon->message as $key => $value) {
            $kd_dealer  = $value->KD_DEALER;
            $fincoy     = $value->KD_FINCOY;
            $no_trans   = $value->NO_TRANS;
            $jumlah     = $value->JML_TAGIHAN;
            $ppn        = round(($value->JML_TAGIHAN*10/100),2);
            $keterangan = $value->URAIAN_TRANSAKSI;
            $nama_customer = $value->NAMA_CUSTOMER;
         }
      }
   }
   $tampil="hidden";
   $gatampil="";
   $total =round(($jumlah+$ppn),0);
   if(isset($dealer)){
      if($dealer->totaldata >0){
         foreach ($dealer->message as $key => $value) {
            if($value->KD_DEALER === $kd_dealer){
               $alamat_dealer = $value->ALAMAT;
               $telp = $value->TLP;
               $nama_dealer = $value->NAMA_DEALER;
               $fax = $value->TLP3;
               $nama_kota = $value->NAMA_KABUPATEN;
               $tampil =($value->KD_JENISDEALER=='Y')?'hidden':'';
               $gatampil=($value->KD_JENISDEALER=='Y')?'':'hidden';
            }
         }
      }
   }
   if(isset($finco)){
      if($finco->totaldata >0){
         foreach ($finco->message as $key => $value) {
            if($value->KD_LEASING === $fincoy){
               $nama_finco = $value->NAMA_LEASING;
            }
         }
      }
   }
   $no_kwt="0";
   if(isset($kwt)){
      if($kwt->totaldata > 0){
         foreach ($kwt->message as $key => $value) {
            $no_kwt = str_pad(($value->LAST_DOCNO),6,"0",STR_PAD_LEFT);
         }
      }
   }
?>
<div class="modal-body" id="printarea">
   <table class="" style="width:100%; border-collapse: collapse; font-size:small; " border="0">
      <tr>
         <td colspan="4" align="center"><h3><strong>KWITANSI</strong></h3></td>
      </tr>
      <tr style="font-size: 12px">
         <td colspan="3"><span class="<?php echo $tampil;?>"><?php echo $nama_dealer;?></span> </td>
         <td align="right" style="width:150px; padding-right: 5px;"><input type='text' class='on-grid text-right <?php echo $gatampil;?>' id="no_kwt" placeholder="Masukan No urut Kwitansi">
            <span class="<?php echo $tampil;?>"><b>No. :<?php echo $no_kwt;?></b>
         </td>
      </tr>
      <tr style="font-size: 11px">
         <td colspan="3"><span class="<?php echo $tampil;?>"><?php echo str_replace("<br>"," ",$alamat_dealer)." ".$nama_kota;?></span></td>
         <td align="right"><b><?php echo $no_trans;?></b></td>
      </tr>
      <tr style="font-size: 10px">
         <td colspan="3">Telp: <?php echo $telp;?></td>
         <td>&nbsp;</td>
      </tr>
      <tr style="font-size: 10px">
         <td colspan="3">Fax: </td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4"><hr></td>
      </tr>
      
      <tr style="min-height: 27px">
         <td style="width:150px" align="text-right"><span class="">Telah Terima dari</span><td style="width: 15px">:</td>
         <td colspan="2"><?php echo $nama_finco." QQ. ".strtoupper($nama_customer);?></td>
      </tr>
      <tr style="min-height:27px">
         <td align="text-right"><span class="">Uang Sejumlah</span><td>:</td>
         <td colspan="2" style="color:red"><em><?php echo terbilang($total)." Rupiah";?></em></td>
      </tr>
      <tr style="min-height: 27px">
         <td align="text-right" style="" valign="top"><span class="">Untuk Pembayaran</span><td>:</td>
         <td colspan="2" valign="top"><?php echo $keterangan;?> a.n <?php echo $nama_customer;?></td>
      </tr>
      <tr><td colspan="4">&nbsp;</td>
      <tr>
         <td colspan="2">&nbsp;</td>
         <td align="" valign="top">
            <table style="width:100%; border-collapse: collapse;">
               <tr>
                  <td style="width: 20%">&nbsp;</td>
                  <td style="width:15%">DPP :</td>
                  <td style="width:25%" align="right"><?php echo number_format($jumlah,0);?></td>
                  <td></td>
               </tr>
               <tr>
                  <td>&nbsp;</td>
                  <td>PPN  :</td>
                  <td align="right"><?php echo number_format(($jumlah*10/100),0);?></td>
                  <td></td>
               </tr>
            </table>
         </td>
         <td></td>
      </tr>
      <tr style="height: 30px"><td colspan="4">&nbsp;</td></tr>
      <tr>
         <td colspan="2" align="right"><h2><em>Rp. <?php echo number_format(($jumlah+$ppn),0);?></em></h2></td>
         <td align="right"><em><?php echo ucwords(strtolower($nama_kota));?>,</em></td>
         <td align="left"><em><?php echo date("d F Y");?></em></td>
      <tr style="height: 50px">
         <td colspan="2" valign="top" align="right"><smaller><sup><?php echo date('d/m/Y H:i:s');?></sup></smaller></td> 
         <td>&nbsp</td>
         <td valign="bottom"><u><?php echo $this->session->userdata("user_name");?></u></td>
      </tr>
      </table>
   </div>
<div class="modal-footer"><span class="pull-left"><em><small><sup>*</sup> Hanya bisa diprint 1 kali</small></em></span>
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