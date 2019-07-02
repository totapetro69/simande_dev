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
    width: 90px;
}

.square-checkbox {
  border: 1px solid; width: 20px; height: 20px; 
}

</style>

<?php
$NAMA_DEALER = '';
$ALAMAT_DEALER = '';
$NAMA_KABUPATEN = '';
$FAKTUR_PENJUALAN = '';
$TGL_SURATJALAN = date('d-m-Y');
$TGL_SO = date('d-m-Y');
$NAMA_PENERIMA = '';

$NAMA_SALESPROGRAM = '';
$VNORANGKA1 = '';
$NO_RANGKA = '';
$NO_MESIN = '';

$JML_SUBSIDI = 0;
$JML_SUBSIDI_TERBILANG = 'NOL';


if($motors){

  if(is_array($motors->message)){

    foreach ($motors->message as $key => $value) {
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT_DEALER = $value->ALAMAT;
      $NAMA_KABUPATEN =  $value->NAMA_KABUPATEN;
      $FAKTUR_PENJUALAN = $value->FAKTUR_PENJUALAN;
      // $TGL_SURATJALAN = tglfromSql($value->TGL_SURATJALAN);
      $NAMA_PENERIMA = $value->NAMA_PENERIMA;

      $NAMA_SALESPROGRAM = $value->NAMA_SALESPROGRAM;
      $NO_RANGKA = $value->VNORANGKA1.$value->NO_RANGKA;
      $NO_MESIN = $value->NO_MESIN;
      $TGL_SO = tglfromSql($value->TGL_SO);


      $JML_SUBSIDI = $subsidi;
      $JML_SUBSIDI_TERBILANG = ($subsidi_terbilang != '')?$subsidi_terbilang.' Rupiah':'Nol Rupiah';

    }

  }

}

?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title" id="myModalLabel">Tanda Terima</h4>
</div>

<div class="modal-body" id="printarea">

    <table id="desc" class="">
      <tr>
        <td colspan="4">
          <h3><strong><?php echo $NAMA_DEALER;?></strong></h3>
          <P><?php echo $ALAMAT_DEALER;?></h3>
          <P></P>
        </td>
      </tr>

      <tr><td colspan="4">&nbsp;</td><td></td></tr></tr>

      <tr>
        <th class="text-center" colspan="4"><h3>TANDA TERIMA</h3></th>
      </tr>

      <tr>
        <td colspan="4" valign="top">
            <div class="project">
              <div><span class="title" style="width: 40%;">Nomor Faktur</span><span class="content"> <?php echo $FAKTUR_PENJUALAN;?></span></div>
              <div><span class="title" style="width: 40%;">Sudah terima dari</span><span class="content"> <?php echo $NAMA_DEALER;?></span></div>
              <div><span class="title" style="width: 40%;">Jumlah Uang</span><span class="content"> <?php echo $JML_SUBSIDI_TERBILANG;?></span></div>
              <div><span class="title" style="width: 40%;">Buat Pembayaran</span><span class="content"> 
                <?php //echo $NAMA_SALESPROGRAM;?>Voucher Belanja</span></div>
            </div>
        </td>
      </tr>

      <tr><td colspan="4">&nbsp;</td></tr>

      <tr>
        <td colspan="4" valign="top">
            <div class="project">
              <div><span class="title" style="width: 40%;">Nomor Rangka</span><span class="content"> <?php echo $NO_RANGKA;?></span></div>
              <div><span class="title" style="width: 40%;">Nomor Mesin</span><span class="content"> <?php echo $NO_MESIN;?></span></div>
            </div>
        </td>
      </tr>

      <tr><td colspan="4">&nbsp;</td></tr>
      <tr><td colspan="4">&nbsp;</td></tr>



      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="title" style="text-align: center;"></span> 
                <span class="title" style="text-align: center;"></span> 
                <span class="title" style="text-align: center;"><?php echo $NAMA_KABUPATEN.', '.$TGL_SO;?></span>
              </div>
            </div>
        </td>
      </tr>

      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="title" style="text-align: center;"><?php echo $NAMA_DEALER;?></span> 
                <span class="title" style="text-align: center;">Yang Membayar,</span> 
                <span class="title" style="text-align: center;">Yang Menerima,</span>
              </div>
            </div>
        </td>
      </tr>

      <tr>
        <td colspan="4">
            <div class="project">
              <div>
                <span class="content" style="text-align: center; padding-top: 80px;">
                  <div>Rp. <span style="padding: 5px; text-align: right; border: 1px solid; width: 150px; height: 25px;"><b><?php echo number_format($JML_SUBSIDI,0);?></b></span></div>
                </span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $NAMA_DEALER;?></u></span>
                <span class="content" style="text-align: center; padding-top: 80px;"><u><?php echo $NAMA_PENERIMA;?></u></span>
              </div>
            </div>
        </td>
      </tr>
    </table>

</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" id="keluar" data-dismiss="modal">Keluar</button>
    <button type="button" onclick="printSj();" class="btn btn-danger"><i class='fa fa-print'></i> Print</button>

</div>

<script src="<?php echo base_url('assets/dist/print.min.js');?>"></script>
<script type="text/javascript">
   function printSj() {
      printJS('printarea','html');
       $('#keluar').click();
    }
</script>