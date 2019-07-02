<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>LAB</title>

   <style type="text/css">
   /** Define now the real margins of every page in the PDF **/
   
   body {
      margin-top: 3cm;
      margin-bottom: 2cm;
   }

   /** Define the header rules **/
   header {
      position: fixed;
      top: 0cm;
      left: 0cm;
      right: 0cm;

      text-align: center;

      border-top: 0px solid  #5D6975;
      border-bottom: 0px solid  #5D6975;
      color: #5D6975;
      line-height: 1.4em;
      font-weight: normal;
      margin: 5px 0 5px 0;
      /*background: url("../images/dimension.png");*/

      min-height: 150px;
   }

   /** Define the footer rules **/
   footer {
      position: fixed; 
      bottom: 0cm;
      left: 0cm; 
      right: 0cm;
      height: 2cm;

      text-align: center;
      /*line-height: 1.5cm;*/
   }

   #header-left
   {
      width: 100px;
      height: 100px;
      float: left; 
      /*background: url("../images/honda_logo.png");*/
      background-size: 100px;
   }

   #header-right
   {
      width: 100px;
      height: 100px;
      float: right; 
      /*background: url("../images/ahass.png");*/
      background-size: 100px;
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
        font-size: 10px;
    }

    .project span {
        text-align: left;
        padding: 2px 0;
        display: table-cell;
    }

    .project .content {
        width: 200px;
        font-size: 10px;
    }

    .project p{
      margin: 2px 0;
    }

    .page-break-before{
      page-break-before: always;
    }

    .page-break-after{
      page-break-after: always;
    }
    main{
      font-size: 11px;
    }

    

    table.table-border{
      border-collapse: collapse;
    }

    table.table-border td {
       border: 1px solid  #5D6975;
    }

    td{
      vertical-align: top;
      padding: 5px;
    }

    td.content{
      font-size: 15px;
    }

    td.title{
      font-size: 16px;
    }

    input.type1
    {
       border: 1px solid  #5D6975;
       height: 10px;
       width: 20px;

    }
    input.type2{
       border: 1px solid  #5D6975;
       height: 10px;
       width: 10px;

    }
    </style>

  </head>
  <body>

<?php

  $nama_dealer=""; $tlp=""; $nama_kabupaten=""; $alamat=""; $kd_dealerahm="";$nama_user="";

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

  $nama_user = NamaUser($this->session->userdata("user_id"));
?>

<header class="clearfix">
  <div id="header" style="display: flex !important; align-items:center;">
    <table style="border: none;" id="desc" border="0" >
      <tr>
        <td style="text-align: left; width:200px !important;"><span style="font-size: 7px;"><?php echo $nama_dealer;?><br>
          <?php echo $alamat;?> <br>
          <?php echo $nama_kabupaten;?><br>
          <?php echo $tlp;?><br></span>
        </td>
        <td style="text-align: center; width:500px !important;"><h4 style="margin:0 0 5px 0; font-size:22px"><strong>Laporan Akumulasi Bengkel</strong></h4><br><span style="font-size: 8px;">Periode : <?php echo ($this->input->get("tgl_awal")) ? $this->input->get("tgl_awal") : date('d-m-Y', strtotime('first day of this month')); ?> s/d <?php echo($this->input->get("tgl_akhir")) ? $this->input->get("tgl_akhir") : date('d/m/Y'); ?></span></td>
        <td style="width:200px !important;"></td>
      </tr>
    </table>
  </div>
</header>
<main>
  <table border='1' class="table-full table-border" style="border-collapse: collapse; font-size: 20px">
    <thead>
      <tr>
        <th rowspan="3" style="text-align: center!important; width:5px !important;">NO</th>
        <th style="text-align: center; width:8px" rowspan="3">Tanggal</th>
        <th style="text-align: center; width:8px;" colspan="3">Kredit</th>
        <th style="text-align: center; width:8px;" colspan="4">Tunai</th>
        <th style="text-align: center; width:8px;" rowspan="3">Total</th>
      </tr>
      <tr>
        <th style="text-align: center;width:8px;">NJB</th>
        <th style="text-align: center;width:8px;">NSC</th>
        <th style="text-align: center;width:8px;"></th>
        <th style="text-align: center;width:8px;">NJB</th>
        <th style="text-align: center;width:8px;" colspan="2">NSC</th>
        <th style="text-align: center;width:8px;"></th>
      </tr>
      <tr>
        <th style="text-align: center;width:8px;">Jasa</th>
        <th style="text-align: center;width:8px;">Oli</th>
        <th style="text-align: center;width:8px;">Subtotal</th>
        <th style="text-align: center;width:8px;">Jasa</th>
        <th style="text-align: center;width:8px;">Oli</th>
        <th style="text-align: center;width:8px;">Part</th>
        <th style="text-align: center;width:8px;">Subtotal</th>
      </tr>
    </thead>

    <tbody>
      <?php
      $jasa_k=0; $oli_k=""; $subtotal_k=""; $jasa_t=""; $oli_t=""; $part_t=""; $subtotal_t=""; $grandtotal="";
      if (isset($list)) {
        $no = 0;
        if (($list->totaldata >0 )) {
          foreach ($list->message as $key => $value) {
            # code...
            $no++;
            ?>
            <tr>
              <td style="text-align: center !important;width:5px!important;"><?php echo $no; ?></td>
              <td style=" width:8px !important"><?php echo tglFromSql($value->TANGGAL_PKB);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->JASA_K),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->OLI_K),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SUBTOTAL_K),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->JASA_T),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->OLI_T),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->PART_T),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->SUBTOTAL_T),0);?></td>
              <td style="text-align: right;"><?php echo number_format(($value->GRANDTOTAL),0);?></td>
            </tr>
            <?php
            $jasa_k +=(double)$value->JASA_K; $oli_k +=(double)$value->OLI_K; $subtotal_k +=(double)$value->SUBTOTAL_K;
            $jasa_t +=(double)$value->JASA_T; $oli_t +=(double)$value->OLI_T; $part_t +=(double)$value->PART_T;;
            $subtotal_t +=(double)$value->SUBTOTAL_T; $grandtotal +=(double)$value->GRANDTOTAL;
          }
        }
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">Total</td>
        <td style="text-align: right;"><?php echo number_format($jasa_k,0);?></td>
        <td style="text-align: right;"><?php echo number_format($oli_k,0);?></td>
        <td style="text-align: right;"><?php echo number_format($subtotal_k,0);?></td>
        <td style="text-align: right;"><?php echo number_format($jasa_t,0);?></td>
        <td style="text-align: right;"><?php echo number_format($oli_t,0);?></td>
        <td style="text-align: right;"><?php echo number_format($part_t,0);?></td>
        <td style="text-align: right;"><?php echo number_format($subtotal_t,0);?></td>
        <td style="text-align: right;"><?php echo number_format($grandtotal,0);?></td>
      </tr>
    </tfoot>
    
  </table>

  <footer>
    <table>
      <tr style="border-bottom: 0px !important; height: 30px">
      <td colspan="13" align="left" valign="bottom"><h5><?php echo "Dicetak:".$nama_user."-".date('d-F-Y H:i:s');?></h5></td>
    </tr>
    </table>
  </footer>

</main>

<footer>

  <div class="project">
  </footer>
</body>
</html>