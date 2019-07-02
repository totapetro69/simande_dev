<?php

  $no_reff="";$nopol="";$customer="";$no_hp="";$alm_cust="";$tgl="";$nama_user="";
  if(isset($kwt)){ $n=0;$total=0;
    if($kwt->totaldata >0){
      foreach ($kwt->message as $key => $value) {
        $no_reff=$value->NO_REFF;
      }
    }
  }

  if(isset($pkbd)){
    if($pkbd->totaldata >0){
      foreach ($pkbd->message as $key => $value) {
        $nopol = isset($value->NO_POLISI)?$value->NO_POLISI:"";
        $customer = $value->NAMA_CUSTOMER;
        $no_reff = $value->NO_HP;
        $alm_cust = $value->ALAMAT;
        $no_hp = $value->NO_HP;
        $tgl= tglFromSqlLong($value->TGL_TRANS,"");
        
      }
    }
  }
  if(isset($pkb)){
    if($pkb->totaldata >0){
      foreach ($pkb->message as $key => $value) {
        $nopol = $value->NO_POLISI;
        $customer = $value->NAMA_COMINGCUSTOMER;
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
      }
    }
  }
  $nama_user = NamaUser($this->session->userdata("user_id"));
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h5 class="modal-title" id="myModalLabel">NOTA SUKU CADANG</h5>
</div>

<div class="modal-body" id="printarea">

    
        <table style="border-collapse: collapse;" border="0">
          <tr>
            <td colspan="3">AHASS-<?php echo $kd_dealerahm;?> <br> 
              <b><?php echo $nama_dealer;?></b>
            </td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $alamat;?></td>
            <td colspan="2" align="right">NPKP  :</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="3"><?php echo $nama_kabupaten;?></td>
            <td colspan="2" align="right">NPWP  :</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $tlp;?></td>
            <td colspan="2" align="right">Tgl Pengukuhan  :</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr style="height: 50px">
            <td colspan="7" valign="middle" align="center"><h4>NOTA SUKU CADANG</h4></td>
          </tr>
          <tr>
            <td style="white-space: nowrap; text-align: right; width:80px">NSC ID :</td>
            <td style="white-space: nowrap;" colspan="2">&nbsp;<b><?php echo $nomor;?></b></td>
            <td style="white-space: nowrap; text-align: right;" colspan="2" align="right">Pelanggan :</td>
            <td style="white-space: nowrap;" colspan="2">&nbsp;<b><?php echo substr($customer,0,50);?></b></td>
          </tr>
          <tr>
            <td style="white-space: nowrap; text-align: right;" colspan="">Tgl.NSC :</td>
            <td style="white-space: nowrap;" colspan="2">&nbsp;<?php echo $tgl;?></td>
            <td style="white-space: nowrap; text-align: right;" colspan="2" align="right"> Alamat : </td>
            <td style="white-space: nowrap;" colspan="2">&nbsp;<?php echo $alm_cust;?></td>
          </tr>
          <tr>
            <td style="white-space: nowrap; text-align: right;" colspan="">No. PKB :</td>
            <td style="white-space: nowrap;">&nbsp;<?php echo (isset($nom))?$nom:"";?></td>
            <td style="white-space: nowrap; text-align: right;" colspan="4" align=""><?php echo $no_hp;?> </td>
            <!-- <td style="white-space: nowrap;" colspan="2"></td> -->
          </tr>
          <!-- <tr>
            <td colspan="2"></td>
            <td align="right">Nama :</td>
            <td colspan="2">&nbsp;<?php echo substr($customer,0,50);?></td>
          </tr> -->
          <tr><td colspan="7">&nbsp;</td></tr>
          <?php 
            switch($reff){
               case "WO":
               ?>
               <tr style="border-bottom: 1px solid; border-top: 1px solid; height: 30px" valign="middle" align="center">
                  <td style="width:45px;">No</td>
                  <td style="width: 180px">No. Part</td>
                  <td style="width: 300px">Nama Part</td>
                  <td style="width: 60px">Qty</td>
                  <!-- <td style="width: 60px">Nomor Rak</td> -->
                  <td style="width: 100px">Harga</td>
                  <!-- <td style="width: 80px">Diskon/Part</td> -->
                  <td style="width: 150px">Jumlah</td>
             </tr>
             <?php
               break;
               case "SP":
               ?>
               <tr style="border-bottom: 1px solid; border-top: 1px solid; height: 30px" valign="middle" align="center">
                  <td style="width:45px;">No</td>
                  <td style="width: 180px">No. Part</td>
                  <td style="width: 300px">Nama Part</td>
                  <td style="width: 120px">Nomor Rak</td>
                  <td style="width: 100px">Harga</td>
                  <td style="width: 80px">Diskon/Part</td>
                  <td style="width: 60px">Qty</td>
                  <td style="width: 150px">Jumlah</td>
             </tr>
             <?php
               break;
            }
          //var_dump($pkbd);exit();
          $n=0;$total=0; $jml=0;
            if(isset($pkbd)){
              if($pkbd->totaldata >0){
                foreach ($pkbd->message as $key => $value) {
                  $n++;
                  switch($reff){
                    case "WO":
                    ?>
                    <tr style="height: 26px" valign="middle">
                        <td align="center"><?php echo $n;?>.</td>
                        <td><?php echo $value->PART_NUMBER;?></td>
                        <td style="white-space:nowrap;"><?php echo $value->PART_DESKRIPSI;?></td>
                        <td align="right" style="padding-right: 5px"><?php echo number_format($value->QTY,0);?></td>
                        <td align="right"><?php echo number_format($value->HARGA_SATUAN,0);?></td>
                        <td align="right"><?php echo ($value->JENIS_PKB=='KPB')? number_format($value->HARGA_SATUAN,0):0;?></td>
                        <td align="right" style="padding-right: 5px"><?php echo ($value->JENIS_PKB=='KPB')?0:number_format(($value->TOTAL_HARGA),0);?></td>
                      </tr>
                    <?php
                      $total +=($value->TOTAL_HARGA);
                      $total -=($value->JENIS_PKB=='KPB')?$value->TOTAL_HARGA:0;
                      $jml += $value->QTY;
                    break;
                    case "SP":
                    ?>
                    <tr style="height: 26px; border: 1px dotted grey" valign="middle">
                        <td align="center"><?php echo $n;?>.</td>
                        <td><?php echo $value->PART_NUMBER;?></td>
                        <td style="white-space: nowrap;"><?php echo $value->PART_DESKRIPSI;?></td>
                        <td style="white-space: nowrap;"><?php echo $value->KD_RAKBIN;?></td>
                        <td align="right"><?php echo number_format($value->HARGA_JUAL,0);?></td>
                        <td align="right"><?php echo number_format($value->DISKON,0);?></td>
                        <td align="center" style="padding-right: 5px"><?php echo number_format($value->JUMLAH_ORDER,0);?></td>
                        <td align="right" style="padding-right: 5px"><?php echo number_format(($value->JUMLAH_ORDER*$value->HARGA_JUAL)-$value->DISKON,0);?></td>
                      </tr>
                    <?php
                      $total +=($value->JUMLAH_ORDER*$value->HARGA_JUAL)-$value->DISKON;
                      //$total -=$value->DISKON;
                      $jml += $value->JUMLAH_ORDER;
                    break;
                  }
                }
              }
            }
          ?>
         <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
         <tr style="height: 30px;border-top:1px solid">
            <td colspan="3" style="padding-right: 10px; border:none;" align="right"></td>
            <td align="right" style="padding-right: 5px"><?php //echo $jml;?></td>
            <td colspan="2" align="right" style="padding-right: 5px">Subtotal :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;"><?php echo number_format($total,0);?></td>
         </tr>
         <tr style="height: 30px">
            <td colspan="3" rowspan="" valign="top">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="right" style="padding-right: 5px">Total Diskon :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;">0</td>
         </tr>
         <tr style="height: 30px">
            <td colspan="3" rowspan="" valign="top">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="right" style="padding-right: 5px">Uang Muka :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;">0</td>
         </tr>
         <tr style="height: 30px">
            <td colspan="3" rowspan="" valign="top">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="right" style="padding-right: 5px">DPP :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;"><?php echo number_format(($total/1.1),0);?></td>
         </tr>
         <tr style="height: 30px">
            <td colspan="3" rowspan="" valign="top">&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="2" align="right" style="padding-right: 5px">PPN :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;"><?php echo number_format(($total-($total/1.1)),0);?></td>
         </tr>
         <tr style="height: 30px">
            <td colspan="3" align="left" valign="bottom"><h6><?php echo $nomor."-Dicetak:".$nama_user."-".date('d-F-Y H:i:s');?></td>
            <td align="center"></td>
            <td colspan="2" align="right" style="padding-right: 5px">TOTAL BAYAR :</td>
            <td colspan="2"  align="right" style="padding-right: 5px; border-bottom: 1px dotted;"><?php echo number_format($total,0);?></td>
         </tr>
          
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
      var asal="<?php echo (isset($darimana))?$darimana:"";?>"
      if(asal=='kasir'){
        $.post("<?php echo base_url('cashier/updateafterprint/'.$nomor);?>",{'nomor':'<?php echo $nomor;?>','jenis':'nota'},function(result){
          console.log('document <?php echo $nomor;?> printed ');
          if(result){
            $('#myModalLg').on("hidden.bs.modal",function(){
              window.location.reload();
            })
          }
        })
      }
       $('#keluar').click();
    }
</script>