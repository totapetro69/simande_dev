<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Surat Penyerahan</title>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" >

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">



    /** Define now the real margins of every page in the PDF **/
    body {
        margin-top: 3cm;
        /*margin-left: 2cm;*/
        /*margin-right: 2cm;*/
        margin-bottom: 2cm;
    }

    /** Define the header rules **/
    header {
        position: fixed;
        top: 0cm;
        /*left: 0cm;*/
        /*right: 0cm;*/
        height: 2cm;

        /*text-align: center;*/
        line-height: 10px;
    }

    /** Define the footer rules **/
    footer {
        position: fixed; 
        bottom: 0cm; 
        /*left: 0cm; */
        /*right: 0cm;*/
        height: 2cm;

        text-align: center;
        /*line-height: 1.5cm;*/
    }

    #desc {
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        width: 100%;
    }
    .project {
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
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 200px;
    }

    .page-break{
      page-break-before: always;
    }
    </style>

  </head>
  <body>


  
<?php
$NO_TRANS = '';
$TGLMULAI_PENGURUSAN = '';
$NAMA_KABUPATEN = '';
$NAMA_DEALER = '';
$ALAMAT = '';
$KABUPATEN_DEALER='';
$KETERANGAN = '';
$FAKTUR_PENJUALAN = '';

if($list){

  if(is_array($list->message)){

    foreach ($list->message as $key => $value) {
      $NO_TRANS = $value->NO_TRANS;
      $TGLMULAI_PENGURUSAN = tglfromSql($value->TGLMULAI_PENGURUSAN);
      $NAMA_KABUPATEN = $value->NAMA_KABUPATEN;
      $NAMA_DEALER = $value->NAMA_DEALER;
      $ALAMAT = $value->ALAMAT;
      $KABUPATEN_DEALER = $value->KABUPATEN_DEALER;
      $KETERANGAN = $value->REFF_SOURCE == 1 ? 'STNK':'BPKB';
      $FAKTUR_PENJUALAN = $value->FAKTUR_PENJUALAN;

    }

  }

}

?>
    <header class="clearfix">

      <h4><strong>PENGAJUAN BIAYA PENGURUSAN <?php echo $KETERANGAN;?></strong></h4> 

      <div class="project">
        <div style="">
          <span class="title"><p>Nomor</p></span> 
          <span class="content"><p><?php echo $NO_TRANS;?></p></span>
          <span class="title"><p></p></span> 
          <span class="content"><p>PT. TRIOMOTOR <?php echo $NAMA_DEALER;?></p></span>
        </div>
        <div style="">
          <span class="title"><p>Tgl Mohon</p></span> 
          <span class="content"><p><?php echo $TGLMULAI_PENGURUSAN;?></p></span>
          <span class="title"><p></p></span> 
          <span class="content"><p><?php echo $ALAMAT;?></p></span>
        </div>
        <div style="">
          <span class="title"><p>Wilayah</p></span> 
          <span class="content"><p><?php echo $NAMA_KABUPATEN;?></p></span>
          <span class="title"><p></p></span> 
          <span class="content"><p><?php echo $KABUPATEN_DEALER;?></p></span>
        </div>
      </div>

    </header>

    <main>
      <?php if($KETERANGAN == 'STNK'): ?>
      <table class="table" id="content">
        <thead>
          <tr style="border-bottom: 2px solid; border-top: 1px solid;">
            <td style="width: 25px;">No.</td>
            <td>Tgl. Fak</td>
            <td>No. Mesin</td>
            <td>Pemohon</td>
            <td>Type</td>
            <td>No. Faktur</td>
            <td>Biaya</td>
          </tr>
        </thead>
        <tbody>

        <?php 
        $no=1;
        $total = 0;
        foreach($list->message as $key => $detail): 
        $biaya = $detail->REFF_SOURCE == 1?$detail->BIAYA_STNK:$detail->BIAYA_BPKB;
        $total = $total + $biaya;
        ?>
          <tr>
            <td style="text-align: center;"><?php echo $no; ?></td>
            <td><?php echo tglfromSql($detail->TGL_SO);?></td>
            <td><?php echo $detail->KD_MESIN.$detail->NO_MESIN;?></td>
            <td><?php echo $detail->NAMA_PEMILIK;?></td>
            <td><?php echo $detail->NAMA_TYPEMOTOR;?></td>
            <td><?php echo $detail->FAKTUR_PENJUALAN;?></td>
            <td style="text-align: right;"><?php echo number_format($biaya,0);?></td>
          </tr>
        <?php 
        $no++;
        endforeach; 
        ?>
          <tr>
            <td colspan="6"></td>
            <td style="text-align: right;"><?php echo number_format($total,0);?></td>
          </tr>
        </tbody>
      </table>

      <?php else: ?>


      <table class="table" id="content">
        <thead>
          <tr style="border-bottom: 2px solid; border-top: 1px solid;">
            <td style="width: 25px;">No.</td>
            <td>Nama</td>
            <td>Alamat</td>
            <td>No. Mesin</td>
            <td>No. Rangka</td>
            <td>Type</td>
            <td>Warna</td>
            <td>Tgl. Fak</td>
            <td>Ket</td>
            <td>Biaya</td>
          </tr>
        </thead>
        <tbody>

        <?php 
        $no=1;
        $total = 0;
        foreach($list->message as $key => $detail): 
        $biaya = $detail->REFF_SOURCE == 1?$detail->BIAYA_STNK:$detail->BIAYA_BPKB;
        $total = $total + $biaya;
        ?>
          <tr>
            <td style="text-align: center;"><?php echo $no; ?></td>
            <td><?php echo $detail->NAMA_PEMILIK;?></td>
            <td><?php echo $detail->ALAMAT_PEMILIK;?></td>
            <td><?php echo $detail->KD_MESIN.$detail->NO_MESIN;?></td>
            <td><?php echo $detail->NO_RANGKA;?></td>
            <td><?php echo $detail->NAMA_TYPEMOTOR;?></td>
            <td><?php echo $detail->KET_WARNA;?></td>
            <td><?php echo tglfromSql($detail->TGL_SO);?></td>
            <td><?php echo $detail->TYPE_PENJUALAN;?></td>
            <td style="text-align: right;"><?php echo number_format($biaya,0);?></td>
          </tr>
        <?php 
        $no++;
        endforeach; 
        ?>
          <tr>
            <td colspan="9"></td>
            <td style="text-align: right;"><?php echo number_format($total,0);?></td>
          </tr>
        </tbody>
      </table>


      <?php endif; ?>
    </main>

    <footer>


      <div class="project">
        <div style="">
          <span class="content" style="width: 33%; text-align: left; padding: 0 10px 0 0;">Menyetujui</span>

          <span class="content"></span>

          <span class="content" style="width: 33%; text-align: right; padding: 0 0 0 10px;">Pengurus</span>
        </div>
      </div>
    </footer>

  <!-- <script src="<?php echo base_url('assets/js/jquery2.0.3.min.js') ;?>"></script>
  <script src="<?php echo base_url('assets/js/jquery.mask.js'); ?>"></script> -->
  <!-- <script src="assets/js/jquery2.0.3.min.js"></script>
  <script src="assets/js/jquery.mask.js"></script>

  <script type="text/javascript">
    $(document).ready(function(){

      $('.biaya').mask('000.000.000.000,00', {reverse: true});
    });
  </script> -->
  
  </body>
</html>