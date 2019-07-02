<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Cetak Laporan Penjualan Service</title>

    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css" > -->

    <!-- <link rel="stylesheet" href="assets/css/pdf-style.css" > -->
    
    <style type="text/css">



    /** Define now the real margins of every page in the PDF **/
    body {
        margin-top: 2.5cm;
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
        line-height: 2px;
        font-size: 8px;
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

    table{
      font-size: 10px;
      width: 100%
    }
    table thead{
      text-align: center;
      border-top: 1px solid #000;
      border-bottom: 1px solid #000;

    }
    table tr th{
      white-space: nowrap;
    }

    .text-center{
      text-align: center;
    }


    .text-right{
      text-align: right;
    }
    </style>

  </head>
  <body>


  
<?php

$tgl_awal   =  $this->input->get('tgl_awal')?$this->input->get('tgl_awal'):date('d/m/Y', strtotime('first day of this month'));
$tgl_akhir  =  $this->input->get('tgl_akhir')?$this->input->get('tgl_akhir'):date('d/m/Y');


?>
    <header class="clearfix">


      <p><?php echo $dealer->message[0]->NAMA_DEALER;?></p>
      <p><?php echo $dealer->message[0]->ALAMAT;?></p>
      <p><?php echo $dealer->message[0]->NAMA_KABUPATEN;?></p>
      <p><?php echo $dealer->message[0]->TLP;?></p>

      <h2 class="text-center"><strong>Laporan <?php echo $list ? $list->message[0]->JENIS_ITEM : '';?> Service</strong></h2> 
      <h4 class="text-center small">Periode : <?php echo $tgl_awal.' s/d '.$tgl_akhir;?></h4>

    </header>

    <main>
      <table class="table" id="content">
        <thead>
          <tr style="border-bottom: 2px solid; border-top: 1px solid;">
            <th style="width:10px;">No.</th>
            <th>Nomor Penjualan</th>
            <th>Tanggal</th>
            <th>No. Part</th>
            <th>Deskripsi Part</th>
            <th>Qty</th>
            <th>% Disc</th>
            <th>Rp Disc</th>
            <th>Harga Jual</th>
            <th>Diskon</th>
            <th>Harga Bersih</th>
          </tr>
        </thead>
        <tbody>

        <?php 
        $no=1;
        if(isset($list) && is_array($list->message)):
        foreach($list->message as $key => $detail): 
        $disc_percent = ($detail->DISKON * 100)/($detail->QTY * $detail->HARGA_SATUAN);
        
        ?>
          <tr>
              <td><?php echo  $no; ?></td>
              <td><?php echo  $detail->NO_PKB; ?></td>
              <td><?php echo  tglfromSql($detail->TANGGAL_PKB); ?></td>
              <td><?php echo  $detail->KD_PEKERJAAN; ?></td>
              <td><?php echo  $detail->PART_DESKRIPSI; ?></td>
              <td class="text-center"><?php echo  number_format($detail->QTY,0); ?></td>
              <td class="text-center"><?php echo  $disc_percent; ?></td>
              <td class="text-right"><?php echo  number_format($detail->DISKON,0); ?></td>
              <td class="text-right"><?php echo  number_format($detail->HARGA_SATUAN,0); ?></td>
              <td class="text-center"><?php echo  0; ?></td>
              <td class="text-right"><?php echo  number_format($detail->TOTAL_HARGA,0); ?></td>
          </tr>

        <?php
        $no ++;
        endforeach; 
        endif;
        ?>
        </tbody>
      </table>

    </main>


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