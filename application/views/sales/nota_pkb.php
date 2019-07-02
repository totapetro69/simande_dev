<style type="text/css">
#desc {
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    width: 100%;
}
.project {
    /* float: left; */
    text-align: left;
    display: table;
    width: 100%;
}
.project div {
    display: table-row;
}

.project .title {
    color: #5D6975;
    width: 90px;
}

.project span {
    text-align: left;
    /* width: 100px; */
    /* margin-right: 15px; */
    padding: 2px 0;
    display: table-cell;
    /* font-size: 0.8em; */
}

.project .content {
    width: 150px;
}

.font-print{
  font-size: 10px;
}
</style>
<?php

  $no_nota="";
  $nopol="";
  $no_pkb="";
  $customer = "";
  $tgl_fak = "";
  $alamat_cs = "";
  $nama_maknik = "";

  $n=0;$total_part=0;$total_jasa=0;  $total=0; $jml=0;$dpp=0;$ppn=0;$hp_pembawa="";
  if(isset($pkb)){ 
    $n=0;$total=0;
    if($pkb->totaldata >0){
      foreach ($pkb->message as $key => $value) {
        $no_nota=str_replace('WO', 'NT', $value->NO_PKB);
        $no_pkb=$value->NO_PKB;
        $nopol = $value->NO_POLISI;
        $customer = $value->NAMA_COMINGCUSTOMER;
        $alamat_cs = $value->ALAMAT_SURAT;
        $tgl_fak = tglfromSql($value->TANGGAL_PKB);
        $hp_pembawa = $value->HP_PEMBAWA;
        $nama_maknik = $value->NAMA_MEKANIK;
      }
    }
  }
  //var_dump($pkb);
  $nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";
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

?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h5 class="modal-title" id="myModalLabel">NOTA Penjualan</h5>
</div>

<div class="modal-body" id="printarea">

        <!-- <h4 style="text-align: center;">NOTA PENJUALAN</h4><br> -->

        <table style="border-collapse: collapse; width: 100%;" border="0">
          <tr>
            <td colspan="3"><b><?php echo $nama_dealer;?></b></td>
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $alamat;?></td>
            <td colspan="2" align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td colspan="3"><?php echo $nama_kabupaten;?></td>
            <td colspan="2" align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3"><?php echo $tlp;?></td>
            <td colspan="2" align="right">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr style="height: 40px">
            <td colspan="6" valign="middle" align="center"><h4>NOTA PENJUALAN</h4></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right" style="white-space: nowrap;"><b>No. Nota : </b></td>
            <td style="white-space: nowrap;">&nbsp;<?php echo $no_nota;?></td>
            <td colspan="2" align="right" style="white-space: nowrap;"><b>No. Pol : </b></td>
            <td colspan="2" style="white-space: nowrap;">&nbsp;<?php echo $nopol;?></td>
          </tr>
          <tr>
            <td align="right" style="white-space: nowrap;"><b>No. PKB : </b></td>
            <td style="white-space: nowrap;">&nbsp;<?php echo $no_pkb;?></td>
            <td colspan="2" align="right" style="white-space: nowrap;"><b>Nama : </b></td>
            <td colspan="2" style="white-space: nowrap;">&nbsp;<?php echo str_replace("\'","'",$customer);?></td>
          </tr>

          <tr>
            <td align="right" style="white-space: nowrap;"valign="top"><b>Tgl. Faktur : </b></td>
            <td style="white-space: nowrap;" valign="top">&nbsp;<?php echo $tgl_fak;?></td>
            <td colspan="2" align="right" style="white-space: nowrap;" valign="top"><b>Alamat : </b></td>
            <td colspan="2" valign="top">&nbsp;<?php echo str_replace("\'","'",$alamat_cs);?></td>
          </tr>

          <tr><td colspan="6">&nbsp;</td></tr>
          <tr style="border-bottom: 1px solid; border-top: 1px solid; height: 30px" valign="middle" align="center">
            <td style="width:45px;">Item</td>
            <td style="width: 180px">Nama</td>
            <td style="width: 100px">Harga</td>
            <td style="width: 60px">Qty</td>
            <td style="width: 80px">Diskon</td>
            <td style="width: 150px">Jumlah</td>
          </tr>

          <?php
          if(isset($pkbd) && $pkbd->totaldata >0):

          echo '<tr><td colspan="6"><b>NSC</b></td></tr>';

              foreach ($pkbd->message as $key => $value):
              if($value->KATEGORI == 'Part'):
                $n++;
          ?>
              <tr style="height: 26px" valign="middle">
                <td><?php echo $value->PART_NUMBER;?></td>
                <td style="white-space: nowrap;"><?php echo $value->PART_DESKRIPSI;?></td>
                <td align="right"><?php echo number_format($value->HARGA_SATUAN,0);?></td>
                <td align="right" style="padding-right: 5px"><?php echo number_format($value->QTY,0);?></td>
                <td align="right"><?php echo ($value->JENIS_PKB=='KPB')? number_format($value->HARGA_SATUAN,0):0;?></td>
                <td align="right" style="padding-right: 5px"><?php echo ($value->JENIS_PKB=='KPB')?0:number_format(($value->TOTAL_HARGA),0);?></td>
              </tr>
            <?php

            // $total_part +=($value->TOTAL_HARGA);
            // $total_part -=($value->JENIS_PKB=='KPB')?$value->TOTAL_HARGA:0;
            $total_part +=($value->JENIS_PKB=='KPB')?0:$value->TOTAL_HARGA;
            $jml += $value->QTY;

                endif;
                endforeach;

          echo '<tr><td align="right" colspan="5"><b>Total Spare Part</b></td><td align="right" style="padding-right: 5px; border-top: 1px dotted;">'.number_format($total_part,0).'</td></tr>';


          echo '<tr><td colspan="6"><b>NJB</b></td></tr>';

              foreach ($pkbd->message as $key => $value):
              if($value->KATEGORI == 'Jasa'):
                $n++;
          ?>
              <tr style="height: 26px" valign="middle">
                <td><?php echo $value->PART_NUMBER;?></td>
                <td style="white-space: nowrap;"><?php echo $value->PART_DESKRIPSI;?></td>
                <td align="right"><?php echo number_format($value->HARGA_SATUAN,0);?></td>
                <td align="right" style="padding-right: 5px"><?php echo number_format($value->QTY,0);?></td>
                <td align="right"><?php echo ($value->JENIS_PKB=='KPB')? number_format($value->HARGA_SATUAN,0):0;?></td>
                <td align="right" style="padding-right: 5px"><?php echo ($value->JENIS_PKB=='KPB')?0:number_format(($value->TOTAL_HARGA),0);?></td>
              </tr>
            <?php

            // $total_jasa +=($value->TOTAL_HARGA);
            // $total_jasa -=($value->JENIS_PKB=='KPB')?$value->TOTAL_HARGA:0;
            $total_jasa +=($value->JENIS_PKB=='KPB')?0:$value->TOTAL_HARGA;
            $jml += $value->QTY;

                endif;
                endforeach;

          echo '<tr><td align="right" colspan="5"><b>Total Jasa</b></td><td align="right" style="padding-right: 5px; border-top: 1px dotted;">'.number_format($total_jasa,0).'</td></tr>';

            $total = $total_part+$total_jasa;
            $dpp = $total*10/11;
            $ppn=$dpp*0.1;

              endif;
            ?>
          <tr style="border-top:1px solid">
            <td colspan="5" style="padding-right: 10px; border:none;" align="right">Subtotal :</td>
            <td align="right" style="padding-right: 5px"><?php echo number_format($total,0);?></td>
          </tr>
          <tr>
            <td colspan="5" style="padding-right: 10px; border:none;" align="right">DPP :</td>
            <td align="right" style="padding-right: 5px"><?php echo number_format($dpp,0);?></td>
          </tr>
          <tr>
            <td colspan="3" >&nbsp;</td>
            <td colspan="2" style="padding-right: 10px; border:none;" align="right">PPN :</td>
            <td align="right" style="padding-right: 5px"><?php echo number_format($ppn,0);?></td>
          </tr>
          <tr>
            <td colspan="3" style="font-size: 9px">
              <p>Garansi Service max 500 KM atau 1 Minggu dan menunjukan Nota Penjualan ini</p>
              <p>Mekanik : <?php echo $nama_maknik;?></p>
              <?php //echo ($total != 0)?terbilang($total).' Rupiah':'Nol Rupiah';;?>
                
            </td>
            <td colspan="2" style="padding-right: 10px; border:none;" align="right"><h4>TOTAL BAYAR :</h4></td>
            <td align="right" style="padding-right: 5px; border-top: 1px dotted;"><b><?php echo number_format($total,0);?></b></td>
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
      // $("#printarea").css('font-print');
      var no_pkb = "<?php echo $no_pkb;?>";
      var url = "<?php echo base_url('pkb/update_printnota');?>";

      // alert(no_pkb);

      $.getJSON(url,{'no_pkb':no_pkb}, function(data, status){
        if (data.status == true) {
          $("#printarea").css('font-size', '10px');
          printJS('printarea','html');
          $('#keluar').click();
        }
        
      });

    }
</script>