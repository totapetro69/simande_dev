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
   $kd_dealer="";$alamat_dealer="";$fincoy="";$telp="";$nama_dealer="";
   $no_trans="";$jumlah=0;$keterangan=""; $no_mesin="";$no_rangka="";$nama_kota;
   $harga_otr=0; $uang_muka=0; $dibuat_oleh=""; $nama_customer="";$telp="";$fax=""; 
   $tampil="hidden";$tgl_trans=""; $kd_maindealer = $this->session->userdata("kd_maindealer");
   $gatampil="";$no_faktur="";
   if(isset($leasing)){
      if($leasing->totaldata >0){
         foreach ($leasing->message as $key => $value) {
            $kd_dealer  = $value->KD_DEALER;
            $fincoy     = $value->KD_FINCOY;
            $no_trans   = $value->NO_TRANS;
            $no_mesin   = $value->NO_MESIN;
            $no_rangka  = $value->NO_RANGKA;
            $harga_otr  = $value->HARGA_OTR;
            $uang_muka  = ($value->JML_DIBAYAR+$value->SUBSIDI);
            $jumlah     = $value->TOTAL_TAGIHAN;
            $no_faktur  = $value->FAKTUR_PENJUALAN;
            $keterangan = str_replace("\/n","",str_replace("\/r"," ",$value->URAIAN_TRANSAKSI));
            $nama_customer = $value->NAMA_CUSTOMER;
            $tgl_trans = tglFromSql($value->TGL_TRANS);
         }
      }
   }
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
      <tr style="height: 15px">
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
         <td align="right"><b><?php echo $no_faktur;?></b></td>
      </tr>
      <tr style="font-size: 10px">
         <td colspan="3"><span class="<?php echo $tampil;?>">Telp: <?php echo $telp;?></span></td>
         <td>&nbsp;</td>
      </tr>
      <tr style="font-size: 10px">
         <td colspan="3"><span class="<?php echo $tampil;?>">Fax:</span> </td>
         <td></td>
      </tr>
      <tr>
         <td colspan="4"><hr></td>
      </tr>
      
      <tr style="height: 20px">
         <td style="width:120px; white-space: nowrap;" align="text-right"><span class="<?php echo $tampil;?>">Telah Terima dari</span></td>
         <td style="width: 20px"><span class="<?php echo $tampil;?>">:</span></td>
         <td colspan="2" style=" white-space: nowrap;"><?php echo $nama_finco." QQ. ".strtoupper($nama_customer);?></td>
      </tr>
      <tr style="height:35px">
         <td align="text-right" style=" white-space: nowrap;"><span class="<?php echo $tampil;?>">Uang Sejumlah</span></td>
         <td><span class="<?php echo $tampil;?>">:</span></td>
         <td colspan="2" style="color:red; white-space: nowrap;"><em><?php echo terbilang($jumlah);?></em></td>
      </tr>
      <tr style="height: 40px">
         <td align="text-right" style=" white-space: nowrap;" valign="top"><span class="<?php echo $tampil;?>">Untuk Pembayaran</span></td>
         <td valign="top"<span class="<?php echo $tampil;?>">:</span></td>
         <td colspan="2" valign="top" style=""><span id="ket"><?php echo $keterangan;?></span></td>
      </tr>
      <tr style="height: 135px">
         <td colspan="4" align="center" style="padding: 10px;">
            <table style="border: 1px solid #000; width: 75%; border-collapse: collapse; padding: 5px">
               <tr style="padding: 5px;">
                  <td style="width:15%">No.Faktur</td>
                  <td style="width:3%">:</td>
                  <td><?php echo $no_trans;?></td>
                  <td>&nbsp;</td>
                  <td style="width:20%">NO.Mesin</td>
                  <td style="width:8%">:</td>
                  <td><?php echo $no_mesin;?></td>
               </tr>
               <tr>
                  <td colspan="4"></td>
                  <td>No.Rangka</td>
                  <td>:</td>
                  <td><?php echo $no_rangka;?></td>
               </tr>
               <tr style="height: 50px"><td colspan="7" style="border-top: 0px solid">&nbsp;</td>
               <tr style="display: none">
                  <td colspan="4">
                  <td style="white-space: nowrap;">Harga OTR</td>
                  <td style="white-space: nowrap;">: Rp.</td>
                  <td align="right" style="padding-right: 10px;white-space: nowrap;"><?php echo number_format($harga_otr,0);?></td>
               <tr style="display: none">
                  <td colspan="4"></td>
                  <td style="white-space: nowrap;">Uang Muka</td>
                  <td style="white-space: nowrap;">: Rp.</td>
                  <td align="right" style="padding-right: 10px;white-space: nowrap;"><?php echo number_format($uang_muka,0);?></td>
               </tr>
               <tr style="height: 5px; display: none;">
                  <td colspan="4">&nbsp;</td>
                  <td colspan="3" style="border-top: 1px solid"></td>
               </tr>
               <tr style="display: none">
                  <td colspan="4"></td>
                  <td style="white-space: nowrap;">Jumlah Tagihan</td>
                  <td style="white-space: nowrap;">: Rp.</td>
                  <td align="right" style="padding-right: 10px;white-space: nowrap;"><?php echo number_format($jumlah,0);?></td>
               </tr>
            </table>
         </td>
      </tr>
      <tr><td colspan="4">&nbsp;</td></tr>
      <tr style="height: 50px">
         <td colspan="2" align="right" style="white-space: nowrap;"><h3><em><span class="<?php echo $tampil;?>">Rp.&nbsp;&nbsp;</span> <?php echo number_format($jumlah,0);?></em></h3></td>
         <td align="right"><em><?php echo ucwords(strtolower($nama_kota));?>,</em></td>
         <td align="left"><em><?php echo date("d F Y");?></em></td>
      <tr style="height: 35px">
         <td colspan="2" valign="top" align="right" style="padding-top: 5px;"><smaller><sup><?php echo date('d/m/Y H:i:s');?></sup></smaller></td> 
         <td>&nbsp</td>
         <td valign="bottom"><u><?php echo $this->session->userdata("user_name");?></u></td>
      </tr>
   </table>
</div>
<div class="modal-footer">
   <span class="pull-left"><em><small><sup>*</sup> Hanya bisa diprint 1 kali</small></em></span>
   <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger" id="prnt"><i class='fa fa-print'></i> Print</button>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
   
   $(document).ready(function(){
      if(!$('#no_kwt').hasClass('hidden')){
         $('#prnt').addClass("disabled-action");
      }else{
         $('#prnt').removeClass("disabled-action");
      }
      $('#no_kwt').on('change',function(){
         $('#prnt').removeClass("disabled-action");
      })
      
   })
   function printSj() {
      printJS('printarea', 'html');
      //TODO:update status piutang jadi printed
      __simpan_data();
      // $('#keluar').click();
   }
   function __simpan_data(){
      var datax=[];
      $(".btn i.fa fa-print").removeClass('fa-print');
      $(".btn i.fa fa-print").removeClass('fa-spinner fa-spin');
      datax={
            "kd_maindealer"   : "<?php echo $kd_maindealer;?>",
            "kd_dealer"       : "<?php echo $kd_dealer;?>",
            "no_trans"        : "<?php echo $no_trans;?>",
            "tgl_piutang"     : "<?php echo date('d/m/Y');?>",
            "kd_piutang"      : "PKULS",
            "tgl_trans"       : "<?php echo $tgl_trans;?>",
            "reff_piutang"    : "<?php echo $no_kwt;?>",
            "uraian_piutang"  : $('#ket').html().replace('&#8629;',''),
            "jumlah_piutang"  : "<?php echo $jumlah;?>",
            "cara_bayar"      : "KU",
            "tgl_tempo"       : "<?php echo tglFromSql(getNextDays(date('Ymd'),7));?>",
            "status_piutang"  : "1"
         }
      console.log(datax);
      $.post("<?php echo base_url('report/simpan_tagihan');?>",datax,function(result){
            console.log(result);
            if(result){
               $("#<?php echo $no_trans;?> > td:eq(1) span.fa-stack").removeClass("hidden");
               $("#<?php echo $no_trans;?> > td:eq(1) a#modal-button").addClass("hidden");
               $('#keluar').click();
            }
      })
   }
</script>